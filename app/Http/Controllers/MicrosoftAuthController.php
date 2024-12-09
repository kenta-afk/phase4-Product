<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use TheNetworg\OAuth2\Client\Provider\Azure;
use GuzzleHttp\Client;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MicrosoftAuthController extends Controller
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new Azure([
            'clientId'                => config('services.outlook.client_id'),
            'clientSecret'            => config('services.outlook.client_secret'),
            'redirectUri'             => config('services.outlook.redirect'),
            'tenant'                  => config('services.outlook.tenant_id'),
            'urlAuthorize'            => 'https://login.microsoftonline.com/' . config('services.outlook.tenant_id') . '/oauth2/v2.0/authorize',
            'urlAccessToken'          => 'https://login.microsoftonline.com/' . config('services.outlook.tenant_id') . '/oauth2/v2.0/token',
            'urlResourceOwnerDetails' => 'https://graph.microsoft.com/v1.0/me',
            'scopes'                  => [
                'openid',
                'profile',
                'offline_access',
                'https://graph.microsoft.com/User.Read',
                'https://graph.microsoft.com/Calendars.Read'
            ],
            'defaultEndPointVersion'  => '2.0' // これを追加
        ]);
    }   
    
    /**
     * ユーザーをMicrosoftの認証ページへリダイレクトします。
     */
    public function redirectToProvider()
    {
        $authorizationUrl = $this->provider->getAuthorizationUrl();
        session(['oauth2state' => $this->provider->getState()]);
        \Log::info("Authorization URL: " . $authorizationUrl); // デバッグ用
        return redirect($authorizationUrl);
    }

    /**
     * Microsoftからのコールバックを処理し、アクセストークンを取得します。
     */
    public function handleProviderCallback(Request $request)
    {
        $state = $request->input('state');
        if (empty($state) || ($state !== session('oauth2state'))) {
            session()->forget('oauth2state');
            \Log::warning("Invalid state detected.");
            return redirect('/')->with('error', '不正なリクエストです。');
        }

        try {
            // トークンを取得
            $token = $this->provider->getAccessToken('authorization_code', [
                'code' => $request->input('code')
            ]);
            // トークン取得後
            \Log::info("Access Token obtained."); 
            // トークン情報をログに記録（デバッグ用）
            \Log::info("Access Token: " . $token->getToken());
            \Log::info("Token Scopes: " . implode(', ', $token->getValues()['scp'] ?? []));

            // トークンのaudを確認（デバッグ用）
            $decodedToken = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token->getToken())[1]))), true);
            \Log::info("Token Audience (aud): " . ($decodedToken['aud'] ?? 'N/A'));

            // ユーザー情報取得前
            \Log::info("Fetching resource owner details from: " . $this->provider->getResourceOwnerDetailsUrl($token));

            // ユーザー情報を取得
            $user = $this->provider->getResourceOwner($token);
            $userData = $user->toArray();

            // ユーザー情報取得後
            \Log::info("Resource owner details fetched successfully.");
            
            // メールアドレスを取得（複数のキーをチェック）
            $email = $userData['userPrincipalName'] ?? $userData['mail'] ?? $userData['email'] ?? $userData['preferred_username'] ?? null;

            if (!$email) {
                \Log::error("ユーザーのメールアドレスが取得できませんでした。");
                return redirect('/')->with('error', 'ユーザー情報の取得に失敗しました。');
            }

            // ユーザー名を取得
            $name = $userData['displayName'] ?? 'No Name';

            // ユーザーをデータベースに登録または取得
            $appUser = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    // 必要に応じて他のフィールドを追加
                ]
            );

            // Laravel の認証セッションにログイン
            Auth::login($appUser, true);

            // アクセストークンをセッションに保存
            session(['access_token' => $token->getToken()]);

            return redirect('/calendar')->with('success', '認証に成功しました。');
        } catch (IdentityProviderException $e) {
            \Log::error("Microsoft Authentication Error: " . $e->getMessage());
            \Log::error("Error Details: " . $e->getTraceAsString()); // デバッグ用
            return redirect('/')->with('error', '認証エラーが発生しました。');
        }
    }

    /**
     * Outlookカレンダーのイベントを取得して表示します。
     */
    public function getCalendar()
    {
        $accessToken = session('access_token');
    //     dd(config('services.outlook.client_id'),
    //     config('services.outlook.client_secret'),
    //     config('services.outlook.redirect'),
    //     config('services.outlook.tenant_id'),
    // $accessToken);
        if (!$accessToken) {
            return redirect('/auth/redirect')->with('error', '認証が必要です。');
        }

        $client = new Client();
        try {
            // リクエスト内容をログに出力（デバッグ用）
            \Log::info("Graph API Request Headers: " . json_encode([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept'        => 'application/json',
            ]));

            // 自分自身のカレンダーイベントを取得
            $response = $client->request('GET', "https://graph.microsoft.com/v1.0/me/events", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept'        => 'application/json',
                ],
            ]);

            $events = json_decode($response->getBody()->getContents(), true);

            return view('calendar', ['events' => $events['value']]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // クライアントエラー（4xx）の場合
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);
            \Log::error("Microsoft Graph API エラー: {$statusCode} - " . json_encode($body));

            return response()->json(['error' => 'カレンダー情報の取得に失敗しました。', 'details' => $body], $statusCode);
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            // サーバーエラー（5xx）の場合
            \Log::error("Microsoft Graph API サーバーエラー: " . $e->getMessage());
            return response()->json(['error' => 'カレンダー情報の取得に失敗しました。'], 500);
        } catch (\Exception $e) {
            // その他のエラー
            \Log::error("予期せぬエラー: " . $e->getMessage());
            return response()->json(['error' => 'カレンダー情報の取得に失敗しました。'], 500);
        }
    }
}

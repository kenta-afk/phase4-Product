<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use TheNetworg\OAuth2\Client\Provider\Azure;
use GuzzleHttp\Client;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
                'https://graph.microsoft.com/Calendars.Read',
                'https://graph.microsoft.com/Calendars.ReadWrite'
            ],
            'defaultEndPointVersion'  => '2.0'
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

            // ユーザー情報を取得
            $user = $this->provider->getResourceOwner($token);
            $userData = $user->toArray();

            // メールアドレスを取得
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

            return redirect('/calendars')->with('success', '認証に成功しました。');
        } catch (IdentityProviderException $e) {
            \Log::error("Microsoft Authentication Error: " . $e->getMessage());
            \Log::error("Error Details: " . $e->getTraceAsString()); // デバッグ用
            return redirect('/')->with('error', '認証エラーが発生しました。');
        }
    }

    /**
     * カレンダー一覧を取得して表示します。
     */
    public function listCalendars()
    {
        $accessToken = session('access_token');

        if (!$accessToken) {
            return redirect('/auth/redirect')->with('error', '認証が必要です。');
        }

        $client = new Client();
        $calendars = [];

        $url = "https://graph.microsoft.com/v1.0/me/calendars?\$top=100";

        try {
            while ($url) {
                \Log::info("Fetching calendars from: " . $url);
                $response = $client->request('GET', $url, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Accept'        => 'application/json',
                    ],
                ]);

                $data = json_decode($response->getBody()->getContents(), true);
                \Log::info("Fetched calendars data: " . json_encode($data));

                if (isset($data['value'])) {
                    $calendars = array_merge($calendars, $data['value']);
                    \Log::info("Fetched " . count($data['value']) . " calendars.");
                }

                // 次のページが存在するか確認
                $url = isset($data['@odata.nextLink']) ? $data['@odata.nextLink'] : null;
                if ($url) {
                    \Log::info("Next calendars page URL: " . $url);
                }
            }

            \Log::info("Total calendars fetched: " . count($calendars));

            return view('calendars', ['calendars' => $calendars]);
        } catch (\Exception $e) {
            \Log::error("Error fetching calendars: " . $e->getMessage());
            return response()->json(['error' => 'カレンダーの取得に失敗しました。'], 500);
        }
    }
}

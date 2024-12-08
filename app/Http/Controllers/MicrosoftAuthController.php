<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use TheNetworg\OAuth2\Client\Provider\Azure;
use GuzzleHttp\Client;

class MicrosoftAuthController extends Controller
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new Azure([
            'clientId'          => config('services.outlook.client_id'),
            'clientSecret'      => config('services.outlook.client_secret'),
            'redirectUri'       => config('services.outlook.redirect'),
            'tenant'            => config('services.outlook.tenant_id'),
            'urlAuthorize'      => 'https://login.microsoftonline.com/' . config('services.outlook.tenant_id') . '/oauth2/v2.0/authorize',
            'urlAccessToken'    => 'https://login.microsoftonline.com/' . config('services.outlook.tenant_id') . '/oauth2/v2.0/token',
            'urlResourceOwnerDetails' => '',
            'scopes'            => ['User.Read', 'Calendars.Read'],
        ]);
    }

    /**
     * ユーザーをMicrosoftの認証ページへリダイレクトします。
     */
    public function redirectToProvider()
    {
        $authorizationUrl = $this->provider->getAuthorizationUrl();
        session(['oauth2state' => $this->provider->getState()]);
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
            exit('Invalid state');
        }

        try {
            // トークンを取得
            $token = $this->provider->getAccessToken('authorization_code', [
                'code' => $request->input('code')
            ]);

            // トークンをセッションに保存
            session(['access_token' => $token->getToken()]);

            return redirect('/calendar');
        } catch (IdentityProviderException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * Outlookカレンダーのイベントを取得して表示します。
     */
    public function getCalendar()
    {
        $accessToken = session('access_token');

        if (!$accessToken) {
            return redirect('/auth/redirect');
        }

        $client = new Client();
        try {
            $response = $client->request('GET', 'https://graph.microsoft.com/v1.0/me/events', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept'        => 'application/json',
                ],
            ]);

            $events = json_decode($response->getBody()->getContents(), true);

            return view('calendar', ['events' => $events['value']]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'カレンダー情報の取得に失敗しました。'], 500);
        }
    }
}

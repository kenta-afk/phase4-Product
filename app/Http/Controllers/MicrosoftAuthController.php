<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\GenericProvider;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

class MicrosoftAuthController extends Controller
{
    public function redirectToMicrosoft()
    {
        $oauthClient = new GenericProvider([
            'clientId'                => env('OUTLOOK_CLIENT_ID'),
            'clientSecret'            => env('OUTLOOK_CLIENT_SECRET_KEY'),
            'redirectUri'             => env('OUTLOOK_REDIRECT_URI'),
            'urlAuthorize'            => 'https://login.microsoftonline.com/' . env('OUTLOOK_TENANT_ID') . '/oauth2/v2.0/authorize',
            'urlAccessToken'          => 'https://login.microsoftonline.com/' . env('OUTLOOK_TENANT_ID') . '/oauth2/v2.0/token',
            'urlResourceOwnerDetails' => '',
            'scopes'                  => 'Calendars.Read'
        ]);

        $authUrl = $oauthClient->getAuthorizationUrl();
        session(['oauth2state' => $oauthClient->getState()]);

        return redirect($authUrl);
    }

    public function handleMicrosoftCallback(Request $request)
    {
        $oauthClient = new GenericProvider([
            'clientId'                => env('OUTLOOK_CLIENT_ID'),
            'clientSecret'            => env('OUTLOOK_CLIENT_SECRET_KEY'),
            'redirectUri'             => env('OUTLOOK_REDIRECT_URI'),
            'urlAuthorize'            => 'https://login.microsoftonline.com/' . env('OUTLOOK_TENANT_ID') . '/oauth2/v2.0/authorize',
            'urlAccessToken'          => 'https://login.microsoftonline.com/' . env('OUTLOOK_TENANT_ID') . '/oauth2/v2.0/token',
            'urlResourceOwnerDetails' => '',
            'scopes'                  => 'Calendars.Read'
        ]);

        if (empty($request->get('state')) || $request->get('state') !== session('oauth2state')) {
            session()->forget('oauth2state');
            return redirect()->route('auth.microsoft')->with('error', 'Invalid state');
        }

        try {
            $accessToken = $oauthClient->getAccessToken('authorization_code', [
                'code' => $request->get('code')
            ]);

            session(['access_token' => $accessToken->getToken()]);

            return redirect('/events');
        } catch (\Exception $e) {
            return redirect()->route('auth.microsoft')->with('error', 'Failed to get access token');
        }
    }

    public function getCalendarEvents()
    {
        $accessToken = session('access_token');

        if (!$accessToken) {
            return redirect()->route('auth.microsoft')->with('error', 'Not authenticated');
        }

        $graph = new Graph();
        $graph->setAccessToken($accessToken);

        try {
            $events = $graph->createRequest('GET', '/me/events')
                            ->setReturnType(Model\Event::class)
                            ->execute();

            return view('events.index', ['events' => $events]);
        } catch (\Exception $e) {
            return redirect()->route('auth.microsoft')->with('error', 'Failed to fetch events');
        }
    }
    public function debugRedirectUrl()
    {
        $oauthClient = new GenericProvider([
            'clientId'                => env('OUTLOOK_CLIENT_ID'),
            'clientSecret'            => env('OUTLOOK_CLIENT_SECRET_KEY'),
            'redirectUri'             => env('OUTLOOK_REDIRECT_URI'),
            'urlAuthorize'            => 'https://login.microsoftonline.com/' . env('OUTLOOK_TENANT_ID') . '/oauth2/v2.0/authorize',
        ]);

        return $oauthClient->getAuthorizationUrl();
    }
}

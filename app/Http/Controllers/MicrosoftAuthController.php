<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MicrosoftAuthController extends Controller
{
    public function redirect()
    {
        $authorizationUrl = "https://login.microsoftonline.com/common/oauth2/v2.0/authorize?"
            . http_build_query([
                'client_id' => env('MICROSOFT_CLIENT_ID'),
                'response_type' => 'code',
                'redirect_uri' => env('MICROSOFT_REDIRECT_URI'),
                'response_mode' => 'query',
                'scope' => 'Calendars.ReadWrite',
                'state' => csrf_token(),
            ]);

        return redirect($authorizationUrl);
    }

    public function callback(Request $request)
    {
        if ($request->input('state') !== csrf_token()) {
            return redirect('/')->withErrors('Invalid state');
        }

        $code = $request->input('code');

        $client = new Client();

        try {
            $response = $client->post('https://login.microsoftonline.com/common/oauth2/v2.0/token', [
                'form_params' => [
                    'client_id' => env('MICROSOFT_CLIENT_ID'),
                    'client_secret' => env('MICROSOFT_CLIENT_SECRET'),
                    'redirect_uri' => env('MICROSOFT_REDIRECT_URI'),
                    'code' => $code,
                    'grant_type' => 'authorization_code',
                ],
            ]);

            $token = json_decode($response->getBody(), true);
            session(['microsoft_access_token' => $token['access_token']]);

            return redirect()->route('calendar.create');
        } catch (\Exception $e) {
            return redirect('/')->withErrors('Failed to get access token: ' . $e->getMessage());
        }
    }
}
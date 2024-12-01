<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function create()
    {
        return view('calendar.create');
    }

    public function store(Request $request)
    {
        $accessToken = session('microsoft_access_token');

        if (!$accessToken) {
            return redirect()->route('auth.redirect');
        }

        $client = new Client();

        $eventData = [
            'Subject' => $request->input('subject'),
            'Start' => [
                'DateTime' => $request->input('start'),
                'TimeZone' => 'UTC',
            ],
            'End' => [
                'DateTime' => $request->input('end'),
                'TimeZone' => 'UTC',
            ],
            'Body' => [
                'ContentType' => 'HTML',
                'Content' => $request->input('description', ''),
            ],
        ];

        try {
            $response = $client->post(env('MICROSOFT_API_ENDPOINT') . '/me/events', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $eventData,
            ]);

            $result = json_decode($response->getBody(), true);
            return redirect()->route('calendar.create')->with('success', 'Event created successfully!');
        } catch (\Exception $e) {
            return back()->withErrors('Failed to create event: ' . $e->getMessage());
        }
    }
}

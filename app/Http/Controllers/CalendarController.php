<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * 共有カレンダーを作成します。
     */
    public function createSharedCalendar()
    {
        $accessToken = session('access_token');

        if (!$accessToken) {
            return redirect('/auth/redirect')->with('error', '認証が必要です。');
        }

        $client = new Client();
        $url = "https://graph.microsoft.com/v1.0/me/calendars";

        $calendarData = [
            "name" => "共有カレンダー",
            "color" => "auto",
            "isDefaultCalendar" => false,
            "canShare" => true,
            "canViewPrivateItems" => true
        ];

        try {
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type'  => 'application/json',
                ],
                'json' => $calendarData,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            Log::info("共有カレンダーが作成されました: " . json_encode($data));

            return redirect('/calendars')->with('success', '共有カレンダーが作成されました。');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);
            Log::error("Graph API エラー (カレンダー作成): {$statusCode} - " . json_encode($body));

            return response()->json(['error' => '共有カレンダーの作成に失敗しました。', 'details' => $body], $statusCode);
        } catch (\Exception $e) {
            Log::error("予期せぬエラー (カレンダー作成): " . $e->getMessage());
            return response()->json(['error' => '共有カレンダーの作成に失敗しました。'], 500);
        }
    }

    /**
     * イベント追加フォームを表示します。
     */
    public function showAddEventForm(Request $request)
    {
        $calendarId = $request->input('calendar_id');

        return view('calendar.add_event', ['calendar_id' => $calendarId]);
    }

    /**
     * 共有カレンダーにイベントを追加します。
     */
    public function addEventToSharedCalendar(Request $request)
    {
        $accessToken = session('access_token');

        if (!$accessToken) {
            return redirect('/auth/redirect')->with('error', '認証が必要です。');
        }

        $request->validate([
            'calendar_id' => 'required|string',
            'subject' => 'required|string',
            'content' => 'required|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'location' => 'required|string',
            'attendee_email' => 'required|email',
            'attendee_name' => 'required|string',
        ]);

        $client = new Client();

        $calendarId = $request->input('calendar_id');
        $url = "https://graph.microsoft.com/v1.0/me/calendars/{$calendarId}/events";

        $eventData = [
            "subject" => $request->input('subject'),
            "body" => [
                "contentType" => "HTML",
                "content" => $request->input('content')
            ],
            "start" => [
                "dateTime" => $request->input('start_datetime'),
                "timeZone" => "Asia/Tokyo"
            ],
            "end" => [
                "dateTime" => $request->input('end_datetime'),
                "timeZone" => "Asia/Tokyo"
            ],
            "location" => [
                "displayName" => $request->input('location')
            ],
            "attendees" => [
                [
                    "emailAddress" => [
                        "address" => $request->input('attendee_email'),
                        "name" => $request->input('attendee_name')
                    ],
                    "type" => "required"
                ]
            ]
        ];

        try {
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type'  => 'application/json',
                ],
                'json' => $eventData,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            Log::info("イベントが追加されました: " . json_encode($data));

            return redirect()->route('calendar.events', ['calendar_id' => $calendarId])->with('success', 'イベントが追加されました。');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);
            Log::error("Graph API エラー (イベント追加): {$statusCode} - " . json_encode($body));

            return redirect()->back()->with('error', 'イベントの追加に失敗しました。');
        } catch (\Exception $e) {
            Log::error("予期せぬエラー (イベント追加): " . $e->getMessage());
            return redirect()->back()->with('error', 'イベントの追加に失敗しました。');
        }
    }

    /**
     * カレンダーを特定のユーザーと共有します。
     */
    public function shareCalendar(Request $request)
    {
        $accessToken = session('access_token');

        if (!$accessToken) {
            return redirect('/auth/redirect')->with('error', '認証が必要です。');
        }

        $request->validate([
            'calendar_id' => 'required|string',
            'user_email' => 'required|email',
            'user_name' => 'required|string',
            'role' => 'required|in:reader,writer',
        ]);

        $client = new Client();

        $calendarId = $request->input('calendar_id');
        $userEmail = $request->input('user_email');
        $userName = $request->input('user_name');
        $role = $request->input('role');

        $url = "https://graph.microsoft.com/v1.0/me/calendars/{$calendarId}/calendarPermissions";

        $permissionData = [
            "emailAddress" => [
                "address" => $userEmail,
                "name" => $userName
            ],
            "role" => $role // "reader" または "writer"
        ];

        try {
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type'  => 'application/json',
                ],
                'json' => $permissionData,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            Log::info("カレンダーが共有されました: " . json_encode($data));

            return redirect()->route('calendar.list')->with('success', 'カレンダーが共有されました。');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);
            Log::error("Graph API エラー (カレンダー共有): {$statusCode} - " . json_encode($body));

            return redirect()->back()->with('error', 'カレンダーの共有に失敗しました。');
        } catch (\Exception $e) {
            Log::error("予期せぬエラー (カレンダー共有): " . $e->getMessage());
            return redirect()->back()->with('error', 'カレンダーの共有に失敗しました。');
        }
    }

    /**
     * 特定のカレンダーのイベント一覧を取得して表示します。
     *
     * @param string $calendar_id
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function getEvents($calendar_id)
    {
        $accessToken = session('access_token');

        if (!$accessToken) {
            return redirect('/auth/redirect')->with('error', '認証が必要です。');
        }

        $client = new Client();
        $events = [];

        // Microsoft Graph APIのエンドポイント
        $url = "https://graph.microsoft.com/v1.0/me/calendars/{$calendar_id}/events?\$top=100";

        try {
            while ($url) {
                Log::info("Fetching events from: " . $url);
                $response = $client->request('GET', $url, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Accept'        => 'application/json',
                    ],
                ]);

                $data = json_decode($response->getBody()->getContents(), true);
                Log::info("Fetched events data: " . json_encode($data));

                if (isset($data['value'])) {
                    $events = array_merge($events, $data['value']);
                    Log::info("Fetched " . count($data['value']) . " events.");
                }

                // ページネーションの確認
                $url = isset($data['@odata.nextLink']) ? $data['@odata.nextLink'] : null;
                if ($url) {
                    Log::info("Next events page URL: " . $url);
                }
            }

            Log::info("Total events fetched: " . count($events));

            // カレンダー詳細ビューにイベント一覧を渡す
            return view('calendar.calendar', ['events' => $events, 'calendar_id' => $calendar_id]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);
            Log::error("Microsoft Graph API エラー (イベント取得): {$statusCode} - " . json_encode($body));

            return response()->json(['error' => 'イベント情報の取得に失敗しました。', 'details' => $body], $statusCode);
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            Log::error("Microsoft Graph API サーバーエラー (イベント取得): " . $e->getMessage());
            return response()->json(['error' => 'イベント情報の取得に失敗しました。'], 500);
        } catch (\Exception $e) {
            Log::error("予期せぬエラー (イベント取得): " . $e->getMessage());
            return response()->json(['error' => 'イベント情報の取得に失敗しました。'], 500);
        }
    }

    /**
     * イベントを追加します。
     */
    public function addEvent(Request $request)
    {
        $accessToken = session('access_token');

        if (!$accessToken) {
            return redirect('/auth/redirect')->with('error', '認証が必要です。');
        }

        // バリデーション
        $request->validate([
            'calendar_id' => 'required|string',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'location' => 'required|string|max:255',
            'attendee_email' => 'required|email',
            'attendee_name' => 'required|string|max:255',
        ]);

        $client = new Client();

        $calendarId = $request->input('calendar_id');
        $url = "https://graph.microsoft.com/v1.0/me/calendars/{$calendarId}/events";

        $eventData = [
            "subject" => $request->input('subject'),
            "body" => [
                "contentType" => "HTML",
                "content" => $request->input('content')
            ],
            "start" => [
                "dateTime" => $request->input('start_datetime'),
                "timeZone" => "Asia/Tokyo"
            ],
            "end" => [
                "dateTime" => $request->input('end_datetime'),
                "timeZone" => "Asia/Tokyo"
            ],
            "location" => [
                "displayName" => $request->input('location')
            ],
            "attendees" => [
                [
                    "emailAddress" => [
                        "address" => $request->input('attendee_email'),
                        "name" => $request->input('attendee_name')
                    ],
                    "type" => "required"
                ]
            ]
        ];

        try {
            \Log::info("Graph API Request Headers: " . json_encode([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ]));

            // イベントを作成
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ],
                'json' => $eventData,
            ]);

            $createdEvent = json_decode($response->getBody()->getContents(), true);

            \Log::info("新しいカレンダーイベントが作成されました。");

            return redirect()->route('calendar.events', ['calendar_id' => $calendarId])->with('success', 'イベントが正常に追加されました。');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);
            \Log::error("Microsoft Graph API エラー: {$statusCode} - " . json_encode($body));

            return redirect()->back()->with('error', 'イベントの追加に失敗しました。');
        } catch (\Exception $e) {
            \Log::error("予期せぬエラー (イベント追加): " . $e->getMessage());
            return redirect()->back()->with('error', 'イベントの追加に失敗しました。');
        }
    }
}

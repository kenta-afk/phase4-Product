<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use TheNetworg\OAuth2\Client\Provider\Azure;
use App\Models\Room;
use App\Models\Appointment;
use League\OAuth2\Client\Token\AccessToken;


class CalendarController extends Controller
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
     * 共有カレンダーを作成します。
     */
    public function createSharedCalendar()
    {
        $accessToken = session('access_token');

        if (!$accessToken) {
            session(['redirect_after_auth' => route('management')]);
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
     * 共有カレンダーにイベントを追加します。
     */
    public function addEventToSharedCalendar($comment, $date, $room_id, $visitor_name, $visitor_company)
    {
        // セッションからアクセストークンを取得
        $accessToken = session('access_token');
        $refreshToken = session('refresh_token');
        $expires = session('expires');
        Log::info("アクセストークン: " . $accessToken);
        if (!$accessToken) {
            session(['redirect_after_auth' => route('appointments.create')]);
            return redirect('/auth/redirect');
        }

        try {
            $client = new Client();

            // ユーザー情報を取得するための Graph API エンドポイント
            $userInfoUrl = "https://graph.microsoft.com/v1.0/me";

            // ユーザー情報を取得
            $userResponse = $client->request('GET', $userInfoUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept'        => 'application/json',
                ],
            ]);

            $userData = json_decode($userResponse->getBody()->getContents(), true);
            Log::info("ユーザーデータ取得成功: " . json_encode($userData));

            // Outlookのメールアドレスを取得
            $email = $userData['userPrincipalName'] ?? $userData['mail'] ?? $userData['email'] ?? $userData['preferredUsername'] ?? null;

            if (!$email) {
                Log::error("ユーザーのメールアドレスが取得できませんでした。ユーザーデータ: " . json_encode($userData));
                return [
                    'success' => false,
                    'message' => 'ユーザー情報の取得に失敗しました。'
                ];
            }

            // Outlookのユーザー名を取得
            $name = $userData['displayName'] ?? 'No Name';

            // イベントの終了日時を設定（1時間後に設定）
            $start_datetime = Carbon::parse($date, 'Asia/Tokyo')->toIso8601String();
            $end_datetime = Carbon::parse($date, 'Asia/Tokyo')->addHours(1)->toIso8601String();
            // 共有カレンダーのID
            $calendarId = env('SHARE_CALENDAR_ID');

            // ルーム名を取得
            $room = Room::find($room_id);
            $room_name = $room->name;

            // イベントのコメントを整形
            $eventComment = "【来客】" . $visitor_company . " " . $visitor_name . "様 " . $comment;

            // イベントデータの構築
            $eventData = [
                "subject" => $eventComment,
                "body" => [
                    "contentType" => "HTML",
                    "content" => $eventComment
                ],
                "start" => [
                    "dateTime" => $start_datetime,
                    "timeZone" => "Asia/Tokyo"
                ],
                "end" => [
                    "dateTime" => $end_datetime,
                    "timeZone" => "Asia/Tokyo"
                ],
                "location" => [
                    "displayName" => $room_name
                ],
                "attendees" => [
                    [
                        "emailAddress" => [
                            "address" => $email,
                            "name" => $name
                        ],
                        "type" => "required"
                    ]
                ]
            ];

            // Graph API を使用してイベントを追加
            $eventUrl = "https://graph.microsoft.com/v1.0/me/calendars/{$calendarId}/events";

            $eventResponse = $client->request('POST', $eventUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type'  => 'application/json',
                ],
                'json' => $eventData,
            ]);

            $eventDataResponse = json_decode($eventResponse->getBody()->getContents(), true);
            Log::info("イベントが追加されました: " . json_encode($eventDataResponse));

            return [
                'success' => true,
                'data' => $eventDataResponse,
                'event_id' => $eventDataResponse['id']
            ];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);
            Log::error("Graph API エラー (イベント追加): {$statusCode} - " . json_encode($body));

            return [
                'success' => false,
                'message' => 'イベントの追加に失敗しました。'
            ];
        } catch (\Exception $e) {
            Log::error("予期せぬエラー (イベント追加): " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'イベントの追加に失敗しました。'
            ];
        }
    }

    /**
     * 共有カレンダーからイベントを削除します。
     */
    public function deleteEventToSharedCalendar($event_id)
    {
        $accessToken = session('access_token');

        if (!$accessToken) {
            session(['redirect_after_auth' => route('appointments.index')]);
            return redirect('/auth/redirect')->with('error', '認証が必要です。');
        }

        $client = new Client();
        $calendar_id = env('SHARE_CALENDAR_ID');    // 共有カレンダーのID
     
        // Microsoft Graph APIのエンドポイント
        $url = "https://graph.microsoft.com/v1.0/me/calendars/{$calendar_id}/events/{$event_id}";

        try {
            $response = $client->request('DELETE', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept'        => 'application/json',
                ],
            ]);

            if ($response->getStatusCode() == 204) {
                Log::info("イベントが正常に削除されました。");
                return response()->json(['success' => 'イベントが正常に削除されました。'], 204);
            } else {
                Log::error("イベントの削除に失敗しました。");
                return response()->json(['error' => 'イベントの削除に失敗しました。'], $response->getStatusCode());
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);
            Log::error("Microsoft Graph API エラー (イベント削除): {$statusCode} - " . json_encode($body));

            return response()->json(['error' => 'イベントの削除に失敗しました。', 'details' => $body], $statusCode);
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            Log::error("Microsoft Graph API サーバーエラー (イベント削除): " . $e->getMessage());
            return response()->json(['error' => 'イベントの削除に失敗しました。'], 500);
        } catch (\Exception $e) {
            Log::error("予期せぬエラー (イベント削除): " . $e->getMessage());
            return response()->json(['error' => 'イベントの削除に失敗しました。'], 500);
        }
    }
    
    /** 
     * 共有カレンダーのイベント一覧を取得して表示します。
     */
    public function getEventsToSharedCalendar()
    {
        $accessToken = session('access_token');

        if (!$accessToken) {
            session(['redirect_after_auth' => route('management')]);
            return redirect('/auth/redirect')->with('error', '認証が必要です。');
        }

        $client = new Client();
        $events = [];
        $calendar_id = env('SHARE_CALENDAR_ID');    // 共有カレンダーのID

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
                foreach ($data['value'] as $event) {
                    $events[] = [
                        'id' => $event['id'],
                        'subject' => $event['subject'],
                        'start' => $event['start'],
                        'end' => $event['end'],
                        'location' => $event['location']['uniqueId'] ?? null,
                    ];
                }
                Log::info("Fetched " . count($data['value']) . " events.");
            }

            // ページネーションの確認
            $url = isset($data['@odata.nextLink']) ? $data['@odata.nextLink'] : null;
            if ($url) {
                Log::info("Next events page URL: " . $url);
            }
            }

            Log::info("Total events fetched: " . count($events));

            // JSON形式でイベント一覧を返す
            return response()->json(['events' => $events, 'calendar_id' => $calendar_id]);
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
     * カレンダーを特定のユーザーと共有します。
     */
    public function shareCalendar(Request $request)
    {
        $accessToken = session('access_token');

        if (!$accessToken) {
            session(['redirect_after_auth' => route('management')]);
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
    public function getSharedEvents()
    {
        $accessToken = session('access_token');

        if (!$accessToken) {
            session(['redirect_after_auth' => route('management')]);
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
    // public function addEvent(Request $request)
    // {
    //     $accessToken = session('access_token');

    //     if (!$accessToken) {
    //         return redirect('/auth/redirect')->with('error', '認証が必要です。');
    //     }

    //     // バリデーション
    //     $request->validate([
    //         'calendar_id' => 'required|string',
    //         'subject' => 'required|string|max:255',
    //         'content' => 'required|string',
    //         'start_datetime' => 'required|date',
    //         'end_datetime' => 'required|date|after:start_datetime',
    //         'location' => 'required|string|max:255',
    //         'attendee_email' => 'required|email',
    //         'attendee_name' => 'required|string|max:255',
    //     ]);

    //     $client = new Client();

    //     $calendarId = $request->input('calendar_id');
    //     $url = "https://graph.microsoft.com/v1.0/me/calendars/{$calendarId}/events";

    //     $eventData = [
    //         "subject" => $request->input('subject'),
    //         "body" => [
    //             "contentType" => "HTML",
    //             "content" => $request->input('content')
    //         ],
    //         "start" => [
    //             "dateTime" => $request->input('start_datetime'),
    //             "timeZone" => "Asia/Tokyo"
    //         ],
    //         "end" => [
    //             "dateTime" => $request->input('end_datetime'),
    //             "timeZone" => "Asia/Tokyo"
    //         ],
    //         "location" => [
    //             "displayName" => $request->input('location')
    //         ],
    //         "attendees" => [
    //             [
    //                 "emailAddress" => [
    //                     "address" => $request->input('attendee_email'),
    //                     "name" => $request->input('attendee_name')
    //                 ],
    //                 "type" => "required"
    //             ]
    //         ]
    //     ];

    //     try {
    //         \Log::info("Graph API Request Headers: " . json_encode([
    //             'Authorization' => 'Bearer ' . $accessToken,
    //             'Accept'        => 'application/json',
    //             'Content-Type'  => 'application/json',
    //         ]));

    //         // イベントを作成
    //         $response = $client->request('POST', $url, [
    //             'headers' => [
    //                 'Authorization' => 'Bearer ' . $accessToken,
    //                 'Accept'        => 'application/json',
    //                 'Content-Type'  => 'application/json',
    //             ],
    //             'json' => $eventData,
    //         ]);

    //         $createdEvent = json_decode($response->getBody()->getContents(), true);

    //         \Log::info("新しいカレンダーイベントが作成されました。");

    //         return redirect()->route('calendar.events', ['calendar_id' => $calendarId])->with('success', 'イベントが正常に追加されました。');
    //     } catch (\GuzzleHttp\Exception\ClientException $e) {
    //         $response = $e->getResponse();
    //         $statusCode = $response->getStatusCode();
    //         $body = json_decode($response->getBody()->getContents(), true);
    //         \Log::error("Microsoft Graph API エラー: {$statusCode} - " . json_encode($body));

    //         return redirect()->back()->with('error', 'イベントの追加に失敗しました。');
    //     } catch (\Exception $e) {
    //         \Log::error("予期せぬエラー (イベント追加): " . $e->getMessage());
    //         return redirect()->back()->with('error', 'イベントの追加に失敗しました。');
    //     }
    // }

}

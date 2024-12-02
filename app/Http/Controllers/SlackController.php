<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Host;

class SlackController extends Controller
{
    public function __construct()
    {
        $this->slackToken = env('SLACK_BOT_TOKEN'); // .envからBot Tokenを取得
    }
    public function sendMessage(Request $request)
    {
        $slackId = $request->input('slackId');
        $message = $request->input('message');
        $channelId = env('CHANNEL_ID'); // 呼び出し用のチャンネルIDを指定
        $token = $this->slackToken;

        $response = Http::withToken($token)->post('https://slack.com/api/chat.postMessage', [
            'channel' => $channelId,
            'text' => '<@'.$slackId.'> さん '.$message
        ]);

        if ($response->successful()) {
            return response()->json(['status' => '担当者を呼び出しました'], 200);
        } else {
            return response()->json(['status' => 'Failed to send message', 'error' => $response->json()], 500);
        }
    }
    public function getWorkspaceMembers()
    {
        $token = $this->slackToken;

        $response = Http::withToken($token)->get('https://slack.com/api/users.list');

        // dd($response->json());
        if ($response->successful()) {
            $members = $response->json()['members'];
            $memberIds = array_filter(array_map(function($member) {
                if (!$member['is_bot']) {
                    $hostData = [
                        'host_name' => $member['real_name'],
                        'slack_id' => $member['id'],
                        // 'host_email' => $member['profile']['email'] ?? null, //DBの定義になかったためいったんコメントアウト
                    ];

                    // DBに保存する処理を追加する
                    Host::updateOrCreate(
                        ['slack_id' => $hostData['slack_id']],
                        $hostData
                    );

                    return $hostData;
                }
                return null;
            }, $members));
            return response()->json(['members' => $memberIds], 200, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json(['status' => 'Failed to retrieve members', 'error' => $response->json()], 500);
        }
    }
}
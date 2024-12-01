<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SlackController extends Controller
{
    public function __construct()
    {
        $this->slackToken = env('SLACK_BOT_TOKEN'); // .envからBot Tokenを取得
    }

    public function sendMessage(Request $request, $slackId)
    {
        // $message = $request->input('message');
        $message = 'Hello from Laravel!';
        $token = env('SLACK_BOT_TOKEN');

        $response = Http::withToken($token)->post('https://slack.com/api/chat.postMessage', [
            'channel' => $slackId,
            'text' => $message
        ]);

        if ($response->successful()) {
            return response()->json(['status' => 'Message sent successfully']);
        } else {
            return response()->json(['status' => 'Failed to send message', 'error' => $response->json()], 500);
        }
    }
    public function getWorkspaceMembers()
    {
        $token = $this->slackToken;

        $response = Http::withToken($token)->get('https://slack.com/api/users.list');

        dd($response->json());
        if ($response->successful()) {
            $members = $response->json()['members'];
            $memberIds = array_filter(array_map(function($member) {
            if (!$member['is_bot']) {
                return ['id' => $member['id'],
                 'real_name' => $member['real_name']
                ]; // スラックIDと名前を取得
            }
            return null;
            }, $members));
            return response()->json(['members' => $memberIds], 200, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json(['status' => 'Failed to retrieve members', 'error' => $response->json()], 500);
        }
    }
}
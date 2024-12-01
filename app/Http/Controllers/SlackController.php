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
            return response()->json(['status' => 'Message sent successfully']);
        } else {
            return response()->json(['status' => 'Failed to send message', 'error' => $response->json()], 500);
        }
    }
}
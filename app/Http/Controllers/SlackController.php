<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;

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
            return response()->json(['status' => 'call host'], 200);
        } else {
            return response()->json(['status' => 'Failed to send message', 'error' => $response->json()], 500);
        }
    }

    public function getWorkspaceMembers()
    {
        $token = $this->slackToken;
        $response = Http::withToken($token)->get('https://slack.com/api/users.list');

        //emailが登録されているユーザーのみslack_idを登録
        if ($response->successful()) {
            $members = $response->json()['members'];
            $updatedMembers = array_filter(array_map(function($member) {
                if (!$member['is_bot'] && isset($member['profile']['email'])) {
                    $email = $member['profile']['email'];
                    $user = User::where('email', $email)->first();
                    if ($user) {
                        $user->slack_id = $member['id'];
                        $user->save();
                        return $user;
                    }
                }
                return null;
            }, $members));
            return response()->json(['members' => $updatedMembers], 200, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json(['status' => 'Failed to fetch members', 'error' => $response->json()], 500);
        }
    }

    public function showMessageForm()
    {
        $members = User::all();
        return view('messagetest', compact('members'));
    }
}
<?php

namespace App\Http\Controllers\Api\Mobile\Chat;

use App\Events\MessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ConversationController extends Controller
{
    public function show($user_two)
    {
        $user_one = auth()->user()->id;

        $message = 'Detail Conversation';
        $conversation = Conversation::where(function ($query) use ($user_one, $user_two) {
            $query->where(['user_one' => $user_one, 'user_two' => $user_two]);
        })->orWhere(function ($query) use ($user_one, $user_two) {
            $query->where(['user_one' => $user_two, 'user_two' => $user_one]);
        })->with('chats')->first();
        if ($conversation == null && $user_two != $user_one) {
            Conversation::create([
                'user_one' => $user_one,
                'user_two' => $user_two,
            ]);
        } else if ($user_two == $user_one) {
            return response()->json([
                'data' => 'Chat make conversation with himself.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $conversation = Conversation::where(function ($query) use ($user_one, $user_two) {
            $query->where(['user_one' => $user_one, 'user_two' => $user_two]);
        })->orWhere(function ($query) use ($user_one, $user_two) {
            $query->where(['user_one' => $user_two, 'user_two' => $user_one]);
        })->with('chats')->first();


        $chat = Chat::where([['conversation_id', $conversation->id], ['user_id', '!=', $user_one]])->get();

        if ($chat->first() != null) {
            Chat::where([['conversation_id', $conversation->id], ['user_id', '!=', $user_one]])->update(['is_read' => 1]);
        }
        $conversation = Conversation::where(function ($query) use ($user_one, $user_two) {
            $query->where(['user_one' => $user_one, 'user_two' => $user_two]);
        })->orWhere(function ($query) use ($user_one, $user_two) {
            $query->where(['user_one' => $user_two, 'user_two' => $user_one]);
        })->with('chats')->first();

        return response()->json([
            'message' => $message,
            'data' => $conversation
        ], Response::HTTP_OK);
    }

    public function store(Request $request, $conversation_id)
    {
        try {
            DB::beginTransaction();
            $message = Chat::create([
                'conversation_id' => $conversation_id,
                'body' => $request->body,
                'user_id' => auth()->user()->id,
                'tim_relawan_id' => auth()->user()->current_team_id,
            ]);

            $findUser2 = Conversation::find($conversation_id);
            $userFix = $findUser2->user_two;
            if ($userFix == auth()->user()->id) {
                $userFix = $findUser2->user_one;
            }
            $findDetailMessage = Chat::with('user:id,name')->find($message->id);
            MessageEvent::dispatch($findDetailMessage, $userFix);
            DB::commit();
            return response()->json([
                'message' => 'Chat has been sent.',
                'data' => $message
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'data' => 'Chat cannot be send.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
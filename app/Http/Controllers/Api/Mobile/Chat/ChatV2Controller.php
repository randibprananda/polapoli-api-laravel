<?php

namespace App\Http\Controllers\Api\Mobile\Chat;

use App\Events\MessageEvent;
use App\Events\TriggerShowMessage;
use App\Http\Controllers\Controller;
use App\Models\ChatV2;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ChatV2Controller extends Controller
{
    public function show($user_two)
    {
        try {
            DB::beginTransaction();
            $user_one = auth()->user()->id;

            $message = 'Detail Conversation';
            $conversation = ChatV2::where([
                [function ($query) use ($user_one, $user_two) {
                    $query->where(
                        ['user_one' => $user_one, 'user_two' => $user_two]
                    );
                }],
                ['tim_relawan_id', Auth::user()->current_team_id]
            ])->orWhere([
                [function ($query) use ($user_one, $user_two) {
                    $query->where(
                        ['user_one' => $user_two, 'user_two' => $user_one]);
                }],
                ['tim_relawan_id', Auth::user()->current_team_id]
            ])->get();

            for ($i=0; $i < count($conversation); $i++) {
                if ($conversation[$i]->user_one == $user_two) {
                    ChatV2::where('id', $conversation[$i]->id)->update(['is_read' => 1]);
                }
            }

            $conversation = ChatV2::where([
                [function ($query) use ($user_one, $user_two) {
                    $query->where(
                        ['user_one' => $user_one, 'user_two' => $user_two]
                    );
                }],
                ['tim_relawan_id', Auth::user()->current_team_id]
            ])->orWhere([
                [function ($query) use ($user_one, $user_two) {
                    $query->where(
                        ['user_one' => $user_two, 'user_two' => $user_one]);
                }],
                ['tim_relawan_id', Auth::user()->current_team_id]
            ])->get();

            DB::commit();

            $userTwo = User::find($user_two);
            return response()->json([
                'message' => $message,
                'user_two' => $userTwo,
                'chats' => $conversation
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function store(Request $request, $userTwo)
    {
        try {
            DB::beginTransaction();
            $message = ChatV2::create([
                'tim_relawan_id' => auth()->user()->current_team_id,
                'user_one' => Auth::user()->id,
                'user_two' => $userTwo,
                'body' => $request->body,
            ]);

            $findDetailMessage = ChatV2::with('userTwo:id,name')->find($message->id);
            MessageEvent::dispatch($findDetailMessage, $userTwo);

            DB::commit();
            return response()->json([
                'message' => 'Chat has been sent.',
                'data' => $message
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'data' => 'Chat cannot be send.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function triggerShow($user_two)
    {
        TriggerShowMessage::dispatch($user_two);
        $userTwo = User::find($user_two);
        return response()->json([
            'message' => 'Triggered show message.',
            'user_two' => $userTwo,
        ], Response::HTTP_OK);
    }
}

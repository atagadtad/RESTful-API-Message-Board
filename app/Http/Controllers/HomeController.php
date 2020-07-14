<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class HomeController extends Controller
{
    public function retrieveAllMessages() {
        $messages = Message::all();
        return response()->json([
            "message" => "All messages retrieved",
            "data" => $messages
        ], 201);
    }

    public function retrieveMessage($id) {
        $message = Message::where('id', $id)->get();

        $arr = $message->toArray();

        if (empty($arr)) {
            return response()->json([
                "message" => "Message not found"
            ], 404);
        }

        return response()->json([
            "message" => "Message retrieved",
            "data" => $message
        ], 200);
    }

    public function createMessage(Request $request) {
        $message = new Message;
        $message->title = $request->title;
        $message->content = $request->content;

        if ($message->save()) {

            return response()->json([
                "message" => "Message succesfully saved"
            ], 200);

        } else {

            return response()->json([
                "message" => "Message failed to save"
            ]);
        }
    }

    public function deleteMessage($id) {
        $message = Message::find($id);

        if ( isset($message) ) {
            $message->delete();

            return response()->json([
                "message" => "Message succesfully deleted"
            ]);

        } else {

            return response()->json([
                "message" => "Message not found"
            ]);
        }
    }

    public function updateMessage(Request $request, $id) {
        if ( Message::where('id', $id)->exists() ) {
            $message = Message::find($id);
            $message->title = is_null($request->title) ? $message->title : $request->title;
            $message->content = is_null($request->content) ? $message->content : $request->content;

            if ( $message->save() ) {
                return response()->json([
                    "message" => "Message updated succesfully"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Error occured while saving. Please try again."
                ], 400);
            }

        } else {
            return response()->json([
                "message" => "Message not found"
            ], 404);
        }
    }
}

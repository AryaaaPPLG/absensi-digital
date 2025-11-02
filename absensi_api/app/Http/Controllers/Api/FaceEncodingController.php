<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserFace;
use Illuminate\Support\Facades\Validator;

class FaceEncodingController extends Controller
{
    // POST /api/face-encoding/save
    public function save(Request $request)
    {
        $v = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'encoding' => 'required|string',
        ]);
        if ($v->fails()) {
            return response()->json(['error' => 'validation', 'messages' => $v->errors()], 422);
        }

        $userId = $request->input('user_id');
        $encoding = $request->input('encoding');

        $userFace = UserFace::updateOrCreate(
            ['user_id' => $userId],
            ['encoding' => $encoding]
        );

        return response()->json(['success' => true, 'message' => 'encoding saved', 'id' => $userFace->id], 200);
    }

    // GET /api/face-encoding/{user_id}
    public function get($user_id)
    {
        $user = User::find($user_id);
        if (!$user) {
            return response()->json(['error' => 'user not found'], 404);
        }

        $face = UserFace::where('user_id', $user_id)->first();
        if (!$face || !$face->encoding) {
            return response()->json(['error' => 'encoding not found'], 404);
        }

        return response()->json(['encoding' => $face->encoding], 200);
    }
}

<?php


namespace App\Http\Controllers;

use App\Models\Invite;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InviteController
{
    use ValidatesRequests;

    public function create(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $inviteKey = Str::random(32);
        Invite::create(
            [
                'user_id' => $request->user()->id,
                'email' => $request->input('email'),
                'invite_key' => $inviteKey,
            ]
        );

        return response()->json(
            ['key' => $inviteKey],
        201
        );
    }
}

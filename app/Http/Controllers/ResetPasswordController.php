<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{

    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|exists:users,email'
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json([
                'message' => trans('passwords.user')
            ], 404);
        }

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(60)
            ]
        );

        if ($passwordReset) {
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );
        }

        return response()->json(['message' => trans('passwords.sent')], 200);
    }

    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => trans('passwords.token')
            ], 404);
        }

        if (Carbon::parse($passwordReset->created_at)->addMinutes(240)->isPast()) {
            $passwordReset->delete();

            return response()->json([
                'message' => trans('passwords.token')
            ], 404);
        }

        $user = User::where('email', $passwordReset->email)->first();

        $response = [
            'token' => $token,
            'email' => $passwordReset->email,
            'userName' => $user->getFullName(),
        ];

        return response()->json(['success' => $response], 200);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
            'token' => 'required|string'
        ]);

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();

        $user = User::where('email', $passwordReset->email)->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => trans('passwords.token')
            ], 404);
        }

        if (!$user) {
            return response()->json([
                'message' => trans('validation.user_not_found')
            ], 404);
        }

        $user->password = bcrypt($request->input('password'));
        $user->save();

        $passwordReset->delete();

        $user->notify(new PasswordResetSuccess($passwordReset));

        return response()->json(['success' => trans('passwords.reset')], 200);
    }
}

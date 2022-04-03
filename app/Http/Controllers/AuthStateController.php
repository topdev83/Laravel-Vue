<?php

namespace App\Http\Controllers;

use App\AuthState;
use Illuminate\Http\Request;

class AuthStateController extends Controller
{
    public function get()
    {
        $authState = AuthState::where('user_id', auth()->id())->first();

        if (!$authState) {
            $authState = new AuthState();
            $authState->user_id = auth()->id();
            $authState->tenant_id = tenant()->id;
        }

        $authState->generateToken();
        $authState->save();

        return ['token' => $authState->token];
    }
}

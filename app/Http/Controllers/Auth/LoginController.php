<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

class LoginController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return redirect()->route('keycloak.login');
    }

    public function logout(): RedirectResponse
    {
        auth()->logout();

        return redirect()->route('home');
    }
}

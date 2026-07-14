<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LoginController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        return redirect('/dashboard');
    }
}

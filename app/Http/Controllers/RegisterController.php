<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    public function register(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validateData = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $validateData['password'] = Hash::make($validateData['password']);
        $user = User::create($validateData);
        return redirect()->route('login')->with('success', 'You are registered and can login!');
    }
}

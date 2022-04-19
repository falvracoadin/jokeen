<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        return view('auth.login');
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $credential = $request->only(['email', 'password']);
        if(Auth::attempt($credential)){
            $user = User::where('email', $credential['email'])
                ->first();
            Auth::login($user);
            return redirect('/admin/artikel');
        }
        return redirect()->back()->with("credential", "Credential Invalid");
    }
}

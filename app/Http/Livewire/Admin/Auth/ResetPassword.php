<?php

namespace App\Http\Livewire\Admin\Auth;

use App\Mail\ResetPasswordMail;
use App\Models\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ResetPassword extends Component
{
    public $token;
    public $status;
    public $showReset;
    public $msg;
    public $email;
    public $pass;
    public $notLoading;
    public $statusReset = false;
    public function mount($token = null){
        $this->pass = array();
        $lastRequest = ResetPasswordRequest::getLastRequest($token);
        if(empty($lastRequest) and $token === null){ 
            $this->email = Auth::user() == null ? env('MAIL_FROM_ADDRESS') : Auth::user()->email;
            return;
        }elseif(empty($lastRequest) and $token !== null){
            $this->status = true;
            $this->msg = 'Token autentikasi reset password invalid!!';
        }
        elseif((strtotime(now()) - strtotime($lastRequest->created_at))/60 > 30){
            $this->email = $lastRequest->email;
            $this->msg = 'Token expired!';
            return;
        }
        else{
            $this->status = true;
            $this->email = $lastRequest->email;
            $this->msg = 'Token autentikasi diterima!';
            $this->showReset = true;
            $this->token = $token;
        }
    }

    public function updated($name, $value){
        if($name === 'email'){
            $this->validate([
                'email' => 'email|required'
            ]);
        }
    }

    public function resetPass(){
        $this->validate([
            'pass.password' => 'required|string|min:8',
        ]);

        if(! $this->status or ! $this->showReset) return;

        if($this->pass['password'] !== $this->pass['confirm_password']){
            $this->msg = 'Password tidak sama!';
            return;
        }
        $user = User::where('email', $this->email)
            ->first();
        $user->password = Hash::make($this->pass['password']);
        $lastRequest = ResetPasswordRequest::getLastRequest($this->token);
        $lastRequest->status_reset = true;
        $lastRequest->save(['status_reset']);
        $user->save(['password']);
        $this->statusReset = true;
        $this->msg = 'Berhasil mereset password';
    }

    public function sendResetLink(){
        //cek apakah ada request 30 menit terakhir
        $lastRequest = ResetPasswordRequest::getLastRequest($this->token);

        //jika tidak ada last request, maka kirim reset link baru
        if(empty($lastRequest)){
            //buat request baru
            $lastRequest = ResetPasswordRequest::makeRequest(request()->ip(), $this->email);
            $this->sendMail($lastRequest);
            return;
        }
        $time = 30 - (strtotime(now()) - strtotime($lastRequest->created_at))/60;
        
        if($time < 0){
            $lastRequest = ResetPasswordRequest::makeRequest(request()->ip(), $this->email);
            $this->sendMail($lastRequest);
            return;
        }

        $this->status = true;
        $this->msg('Silahkan cek email anda! Atau tunggu '.ceil($time). ' menit!');

    }

    public function sendMail($lastRequest){
        $mail = new ResetPasswordMail($lastRequest['token']);
        Mail::to($this->email)->send($mail);
        $this->status = true;
        $this->msg = 'Reset Password link telah dikirim melalui email.';
    }

    public function render()
    {
        return view('livewire.admin.auth.reset-password')
            ->extends('layouts.empty')
            ->slot('slot');
    }
}

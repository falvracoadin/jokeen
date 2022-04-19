<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ResetPasswordRequest extends Model
{
    use HasFactory;
    public $fillable = [
        'ip_address',
        'token',
        'status_reset',
        'email'
    ];

    public static function makeRequest($ip, $email){
        $token = Str::random(255);
        return self::create([
            'ip_address' => $ip,
            'token' => $token,
            'email' => $email
        ]);
    }

    public static function getLastRequest($token = null){
        $req = self::where('created_at', '>', now()->subMinutes(30))
            ->where('status_reset', false)
            ->where('token', $token);
        return $req->first() ?? array();
    }
}

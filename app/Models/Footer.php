<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    use HasFactory;
    protected $fillable  = [
      'konten'
    ];

    public static function getContent(){
      return self::where('id', '>', 0)
        ->first()
        ->konten;
    }
}


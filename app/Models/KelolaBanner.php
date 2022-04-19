<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelolaBanner extends Model
{
    use HasFactory;

    public $fillable = [
        'nama_app',
        'deskripsi',
        'file'
    ];

    public static function getBannerByApp($name){
        return self::where('nama_app', $name)
            ->first()
            ->toArray();
    }


}

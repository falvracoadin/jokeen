<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelolaServis extends Model
{
    use HasFactory;

    public $fillable = [
        'nama_servis',
        'deskripsi'
    ];
}

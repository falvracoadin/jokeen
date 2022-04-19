<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelolaStaff extends Model
{
    use HasFactory;

    public $fillable = [
        'nama_staff',
        'deskripsi',
        'posisi',
        'file'
    ];

    public static function showStaff($limit = null, $offset = null, $keyword = null, $show = true){
        $q = self::where('show', $show);
        if($limit) $q = $q->limit($limit);
        if($offset) $q = $q->offset($offset);
        if($keyword) $q = $q->where('nama_staff', 'like', "%$keyword%");
        return $q->get()
            ->toArray();
    }

    public static function countShowable($keyword = null){
        $q = self::where('show', true);
        if($keyword !== null) $q = $q->where('nama_staff', "like", "%$keyword%");
        return $q->count();
    }
}

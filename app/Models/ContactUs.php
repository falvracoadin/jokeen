<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;
    public $fillable = [
        'nama',
        'email',
        'subjek',
        'deskripsi',
        'status_terjawab',
        'jawaban'
    ];

    public static function countUnAnswered(){
        return self::where('status_terjawab', false)
            ->count();
    }

    public static function showContact($limit, $offset = null, $status = false, $keyword = null){
        $q = self::limit($limit);
        if($offset) $q = $q->offset($offset);
        if($status === 0) $q = $q->where('status_terjawab', false);
        elseif($status === 1) $q = $q->where('status_terjawab', true);
        if($keyword)$q = $q->where('nama', 'like', "%$keyword%")
            ->orWhere('email', 'like', "%$keyword%")
            ->orWhere('subjek', 'like', "%$keyword%");
        return $q->get()->toArray();
    }
}

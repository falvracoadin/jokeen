<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portofolio extends Model
{
    use HasFactory;

    public $fillable = [
        'nama_portofolio',
        'deskripsi',
        'date',
        'kategori',
        'file',
        'tipe'
    ];

    public static function showPortofolio($limit = null, $offset = null, $category = 'all', $tipe = null, $keyword = null){
        $q = self::where('show', true);
        if($limit) $q = $q->limit($limit);
        if($offset) $q = $q->offset($offset);
        if($tipe) $q = $q->where('tipe', $tipe);
        if($keyword) $q = $q->where('nama_portofolio', 'like', "%$keyword%");
        if($category !== 'all') $q = $q->where('kategori', $category);
        return $q->get()
            ->toArray();
    }

    public static function getRelatedPortofolio($kategori, $tipe, $limit){
        return self::where('kategori', $kategori)
            ->where('tipe', $tipe)
            ->limit($limit)
            ->get()->toArray();
    }

}

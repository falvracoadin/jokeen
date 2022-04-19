<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_artikel',
        'deskripsi',
        'tanggal_terbit',
        'id_staff',
        'kategori',
        'gambar',
        'pdf'
    ];

    public static function getNewArtikel($size){
        return self::orderBy('created_at')
            ->limit($size)
            ->get()
            ->toArray();
    }

    public static function showArtikel($limit, $offset = null, $category = 'all', $keyword = null){
        $q = self::limit($limit)
            ->orderBy('tanggal_terbit')
            ->where('hapus', false);
        if($offset) $q = $q->offset($offset);
        if($category !== 'all') $q = $q->where('kategori', $category);
        if($keyword) $q = $q->where('nama_artikel', 'like', "%$keyword%");
        return $q->get()
            ->toArray();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ArtikelComment extends Model
{
    use HasFactory;

    public static function countComment($ids){
        return self::selectRaw('id_artikel, count(id) as jumlah')
            ->where('status', true)
            ->whereIn('id_artikel', $ids)
            ->groupBy('id_artikel')
            ->get()
            ->toArray();
    }

    public static function getCommentsByIdArtikel($idArtikel){
        return self::where('id_artikel', $idArtikel)
            ->get()
            ->toArray();
    }

    public static function comment($data){  
        DB::beginTransaction();
        $status = DB::table('artikel_comments')
            ->insert($data);
        DB::commit();
        return $status;
    }
}

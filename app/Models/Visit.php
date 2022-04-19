<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Stevebauman\Location\Facades\Location;

class Visit extends Model
{
    use HasFactory;

    public $fillable = [
        'ip_address',
        'tahun',
        'bulan',
        'tanggal'
    ];

    public $timestamps = false;

    public static function visit($ip){
        DB::beginTransaction();
        $visit = self::where('ip_address', $ip)->first();
        $info = Location::get($ip);
        $countryCode = 'ID';
        if($info) $countryCode = $info->countryCode;
        if(empty($visit)){
            $visit = new Visit([
                'ip_address' => $ip,
                'tahun' => now()->year,
                'bulan' => now()->month,
                'tanggal' => now()->day,
                'kode' => $countryCode
            ]);
        }else{
            $visit->tahun = now()->year;
            $visit->bulan = now()->month;
            $visit->tanggal = now()->day;
            $visit->kode = $countryCode;
        }
        $status = $visit->save();
        DB::commit();
        return $status;
    }

    public static function countVisitor(){
        $data = array();
        $query = self::where('bulan', now()->month)
            ->where('tahun', now()->year);
        $data['day'] = $query->where('tanggal', now()->day)
            ->count();
        $data['month'] = $query->count();
        return $data;
    }

    public static function countVisitorByCountry($limit = 10){
        return self::selectRaw('kode, count(kode) as jumlah')
            ->groupBy('kode')
            ->orderBy('jumlah', 'desc')
            ->where('bulan', now()->month)
            ->where('tahun', now()->year)
            ->limit($limit)
            ->get()
            ->toArray();
    }

}

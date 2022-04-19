<?php

namespace App\Http\Livewire;

use App\Models\Artikel;
use App\Models\KelolaBanner;
use App\Models\KelolaServis;
use App\Models\KelolaStaff;
use App\Models\Portofolio;
use App\Models\Visit;
use Livewire\Component;

class HomeController extends Component
{
    public $banner,
            $servis,
            $portofolio,
            $staff,
            $artikel,
            $visitor,
            $countryVisitor;
    public function mount(){
        $this->banner = KelolaBanner::getBannerByApp('home');
        $this->servis = KelolaServis::all()->toArray();
        $this->portofolio = Portofolio::showPortofolio(6);
        $this->staff = KelolaStaff::showStaff(3);
        $this->artikel = Artikel::getNewArtikel(3);
        $this->visitor = Visit::countVisitor();
        $this->countryVisitor = Visit::countVisitorByCountry();
    }

    public function render()
    {
        return view('livewire.home-controller')
            ->extends('layouts.app',[
                'home' => 'active'
            ])
            ->section('slot');
    }
}

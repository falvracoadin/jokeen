<?php

namespace App\Http\Livewire;

use App\Models\Artikel;
use App\Models\KategoriArtikel;
use App\Models\KelolaStaff;
use Livewire\Component;

class StaffController extends Component
{
    public $staffs;
    public $recentArticles;
    public $allCategories;

    public function mount(){
        $this->staffs = KelolaStaff::all()->toArray();
        $this->recentArticles = Artikel::getNewArtikel(3);
        $this->allCategories = KategoriArtikel::all()->toArray();
    }

    public function render()
    {
        return view('livewire.staff-controller')
            ->extends('layouts.app',[
                'about' => 'active'
            ])
            ->section('slot');
    }
}

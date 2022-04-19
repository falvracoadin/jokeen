<?php

namespace App\Http\Livewire;

use App\Models\Artikel;
use App\Models\KategoriArtikel;
use App\Models\KelolaStaff;
use Livewire\Component;

class DetailStaffController extends Component
{
    public $staff;
    public $recentArticles;
    public $allCategories;

    public function mount(KelolaStaff $staff){
        if(empty($staff)) abort(404);
        $this->staff = $staff;
        $this->recentArticle = Artikel::getNewArtikel(3);
        $this->allCategories = KategoriArtikel::all()->toArray();
    }

    public function render()
    {
        return view('livewire.detail-staff-controller')
            ->extends('layouts.app', [
                'about' => 'active'
            ])
            ->section('slot');
    }
}

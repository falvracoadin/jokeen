<?php

namespace App\Http\Livewire;

use App\Models\KategoriPortofolio;
use App\Models\Portofolio;
use Livewire\Component;

class LatestReportController extends Component
{
    public $allCategories;
    public $currentPage;
    public $maxPage;
    public $currentCategory;
    public $portofolios;

    public function mount(){
        $this->allCategories = KategoriPortofolio::all()->toArray();
        $this->currentPage = 0;
        $this->maxPage = ceil(Portofolio::where('tipe','latest-report')->count()/9);
        $this->currentCategory = 'all';
        $this->portofolios = Portofolio::showPortofolio(9, $this->currentPage * 9, $this->currentCategory, 'latest-report');
    }

    public function changeCategory($category){
        if($category === $this->currentCategory)return;
        elseif(in_array($category, $this->allCategories) or $category === 'all'){
            $this->currentPage = 0;
            $this->maxPage = ceil(Portofolio::where('kategori', $category)->where('tipe', 'latest-report')->count()/9);
            $this->currentCategory = $category;
            $this->portofolios = Portofolio::showPortofolio(9, $this->currentPage * 9, $category, 'latest-report');
        }
    }

    public function show($page){
        if($page == $this->currentPage) return;
        elseif($page >= 0 and $page < $this->maxPage){
            $this->currentPage = $page;
            $this->portofolios = Portofolio::showPortofolio(9, $this->currentPage * 9, $this->currentCategory, 'latest-report');
        }
    }
    public function render()
    {
        return view('livewire.latest-report-controller')
            ->extends('layouts.app', [
                'portofolio' => 'active'
            ])
            ->section('slot');
    }
}

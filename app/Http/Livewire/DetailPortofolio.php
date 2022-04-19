<?php

namespace App\Http\Livewire;

use App\Models\Portofolio;
use Livewire\Component;

class DetailPortofolio extends Component
{
    public $portofolio;
    Public $related;

    public function mount(Portofolio $portofolio){
        if($portofolio === null) abort(404);
        $this->portofolio = $portofolio->toArray();
        $this->related = Portofolio::getRelatedPortofolio(
            $portofolio->kategori,
            $portofolio->tipe,
            3
        );
    }
    public function render()
    {
        return view('livewire.detail-portofolio')
            ->extends('layouts.app',[
                'portofolio' => 'active'
            ])
            ->slot('slot');
    }
}

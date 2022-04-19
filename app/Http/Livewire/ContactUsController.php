<?php

namespace App\Http\Livewire;

use App\Models\Artikel;
use App\Models\ContactUs;
use App\Models\KategoriArtikel;
use Livewire\Component;

class ContactUsController extends Component
{
    public $recentArticles;
    public $allCategories;
    public $message;

    public $nama,
        $email,
        $subjek,
        $deskripsi;
    
    public $status;

    public $rules = [
        'nama' => 'required|string|max:50',
        'email' => 'required|email',
        'subjek' => 'required|string|max:20',
        'deskripsi' => 'required|string|max:255'
    ];

    public function mount(){
        $this->recentArticles = Artikel::getNewArtikel(3);
        $this->allCategories = KategoriArtikel::all()->toArray();
        $this->status = false;
    }

    public function updated($name, $value){
        $this->validateOnly($name);
    }

    public function send(){
        $this->validate($this->rules);
        $cont = ContactUs::create([
            'nama' => $this->nama,
            'email' => $this->email,
            'subjek' => $this->subjek,
            'deskripsi' => $this->deskripsi
        ]);
        if($cont){
            $this->status = true;
        }
    }

    public function render()
    {
        return view('livewire.contact-us-controller')
            ->extends('layouts.app')
            ->section('slot');
    }
}

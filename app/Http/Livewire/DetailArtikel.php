<?php

namespace App\Http\Livewire;

use App\Models\Artikel;
use App\Models\ArtikelComment;
use App\Models\KategoriArtikel;
use App\Models\KelolaStaff;
use Livewire\Component;

class DetailArtikel extends Component
{
    public $article;
    public $comments;
    public $staff;
    public $recentArticle;
    public $allCategories;
    public $newComment;

    protected $rules = [
        'newComment.nama' => 'string|required|max:50',
        'newComment.email' => 'email|required|max:50',
        'newComment.komentar' => 'required|string|max:255'
    ];

    public function mount(Artikel $artikel){
        $this->article = $artikel->toArray();
        $this->comments = ArtikelComment::getCommentsByIdArtikel($this->article['id']);
        $this->staff = KelolaStaff::find($this->article['id_staff']);
        $this->recentArticle = Artikel::getNewArtikel(3);
        $this->allCategories = KategoriArtikel::all()->toArray();
        $this->newComment = array();
    }

    public function updated($el, $val){
        $this->validateOnly($el, $this->rules);
    }

    public function comment(){
        $this->validate($this->rules);
        if($this->article and $this->staff){
            $this->newComment['id_artikel'] = $this->article['id'];
            $this->newComment['waktu'] = now();
            $status = ArtikelComment::comment($this->newComment);
            if($status){
                $this->comments = ArtikelComment::getCommentsByIdArtikel($this->article['id']);
                $this->newComment = array();
            }
        }
    }

    public function render()
    {
        return view('livewire.detail-artikel')
            ->extends('layouts.app',[
                'artikel' => 'active'
            ])
            ->slot('slot');
    }
}

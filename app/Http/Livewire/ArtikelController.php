<?php

namespace App\Http\Livewire;

use App\Models\Artikel;
use App\Models\ArtikelComment;
use App\Models\KategoriArtikel;
use App\Models\KelolaStaff;
use App\Models\Portofolio;
use Livewire\Component;

class ArtikelController extends Component
{
    public $articles;
    public $recentArticles;
    public $currentPage;
    public $maxPage;
    public $allCategories;
    public $currentCategory;
    public $numComment;
    public $penulis;


    public function mount($category = 'all'){
        $this->currentPage = 0;
        $this->maxPage = ceil(Artikel::count()/9);
        $this->currentCategory = $category;
        $this->articles = Artikel::showArtikel(9,$this->currentPage * 9, $this->currentCategory);
        $this->recentArticles = Artikel::getNewArtikel(3);
        $this->allCategories = KategoriArtikel::all()->toArray();
        $idArtikels = $this->getIdArtikel();
        $this->numComment = ArtikelComment::countComment($idArtikels);
        $this->orderNumComment();

        $this->penulis = KelolaStaff::find($this->getIdPenulis())->toArray();
        $this->orderPenulisArtikels();
    }

    public function getIdArtikel(){
        $id = array();
        foreach($this->articles as $arc){
            array_push($id, $arc['id']);
        }
        return $id;
    }

    public function getIdPenulis(){
        $id = array();
        foreach($this->articles as $arc){
            array_push($id, $arc['id_staff']);
        }
        return $id;
    }

    public function orderNumComment(){
        $nC = array();
        foreach($this->articles as $arc){
            foreach($this->numComment as $id => $nc){
                if($nc['id_artikel'] == $arc['id']){
                    array_push(
                        $nC,
                        array_splice($this->numComment, $id, 1)[0]
                    );
                    break;
                }
            }
        }
        $this->numComment = $nC;
    }

    public function orderPenulisArtikels(){
        $staff = array();
        foreach($this->articles as $arc){
            $status = false;
            foreach($this->penulis as $id => $pn){
                if($pn['id'] === $arc['id_staff']){
                    array_push(
                        $staff,
                        array_splice($this->penulis, $id, 1)[0]
                    );
                    $status = true;
                    break;
                }
            }
            if(!$status){
                // dd($staff);
                foreach($staff as $st){
                    if($arc['id_staff'] === $st['id']){
                        array_push(
                            $staff,
                            [
                                'nama_staff' => $st['nama_staff'],
                                'id' => $st['id']
                            ]
                        );
                    }
                }
            }

        }
        $this->penulis = $staff;
    }

    public function changeCategory($category){
        if($category == $this->currentCategory)return;
        elseif($this->checkCategory($category) or $category === 'all'){
            $this->currentCategory = $category;
            $this->currentPage = 0;
            $this->maxPage = ceil(Artikel::where('kategori', $category)->count()/9);
            $this->articles = Artikel::showArtikel(9, 0, $category);
            $this->numComment = ArtikelComment::countComment($this->getIdArtikel());
            $this->orderNumComment();
        }
    }

    public function checkCategory($category){
        foreach($this->allCategories as $ctg){
            if($ctg['kategori'] === $category) return true;
        }
        return false;
    }

    public function render()
    {
        return view('livewire.artikel-controller')
            ->extends('layouts.app',[
                'artikel' => 'active'
            ])
            ->section('slot');
    }
}

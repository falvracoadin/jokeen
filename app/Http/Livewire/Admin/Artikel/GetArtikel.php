<?php

namespace App\Http\Livewire\Admin\Artikel;
use App\Models\Artikel;
use App\Models\ArtikelComment;
use App\Models\KelolaStaff;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class GetArtikel extends Component
{
    public $artikels;

    public $kategori = 'all';
    public $currentPage;
    public $maxPage;

    public $updateId;

    public $keyword;
    public $numComment;
    public $penulis;

    public $listeners = [
        'refresh'
    ];

    public function mount()
    {
        $this->currentPage = 0;
        $this->keyword = '';
        $this->delete = null;
        $this->updateId = null;

        //get artikels
        $this->maxPage = ceil(Artikel::count()/9);
        $this->artikels = Artikel::showArtikel(9,$this->currentPage * 9, $this->kategori);
        $idArtikels = $this->getIdArtikel();

        //get num comment
        $this->numComment = ArtikelComment::countComment($idArtikels);
        $this->orderNumComment();

        //geet penulis artikel
        $this->penulis = KelolaStaff::find($this->getIdStaff())->toArray();
        $this->orderPenulisArtikels();
    }

    public function updated($name, $value){
        if($name === 'keyword'){
            $this->currentPage = 0;
            $this->maxPage = ceil(Artikel::where('nama_artikel', 'like', "%$this->keyword%")->count()/9);
            $this->artikels = Artikel::showArtikel(9, $this->currentPage * 9, $this->kategori, $this->keyword);
            $idArtikels = $this->getIdArtikel();

            $this->numComment = ArtikelComment::countComment($idArtikels);
            $this->orderNumComment();

            //geet penulis artikel
            $this->penulis = KelolaStaff::find($this->getIdStaff())->toArray();
            $this->orderPenulisArtikels();
        }
    }

    public function getIdArtikel(){
        $id = array();
        foreach($this->artikels as $arc){
            array_push($id, $arc['id']);
        }
        return $id;
    }

    public function getIdStaff(){
        $staff = array();
        foreach($this->artikels as $arc){
            array_push($staff, $arc['id_staff']);
        }
        return $staff;
    }

    public function orderNumComment(){
        $nC = array();
        foreach($this->artikels as $arc){
            $status = false;
            foreach($this->numComment as $id => $nc){
                if($nc['id_artikel'] == $arc['id']){
                    $status = true;
                    array_push(
                        $nC,
                        array_splice($this->numComment, $id, 1)[0]
                    );
                    break;
                }
            }
            if(! $status){
                array_push($nC, ['jumlah' => 0]);
            }
        }
        $this->numComment = $nC;
    }

    public function orderPenulisArtikels(){
        $staff = array();
        foreach($this->artikels as $arc){
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

    public function confirmDelete()
    {
        $deleted_artikel = Artikel::find($this->delete);
        if(!empty($deleted_artikel)){
            $deleted_artikel->hapus = true;
            $deleted_artikel->save(['hapus']);
        }

        $this->delete = null;
        //get artikels
        $this->artikels = Artikel::showArtikel(9,$this->currentPage * 9, $this->kategori);
        $idArtikels = $this->getIdArtikel();

        //get num comment
        $this->numComment = ArtikelComment::countComment($idArtikels);
        $this->orderNumComment();

        //geet penulis artikel
        $this->penulis = KelolaStaff::find($this->getIdStaff())->toArray();
        $this->orderPenulisArtikels();
    }

    public function delete($id)
    {
        if($id < 0) $this->delete = null;
        else $this->delete = $id;
    }

    public function getPage($page){
        if($page < 0 or $page > $this->maxPage) return;
        $this->currentPage = $page;

        $this->artikels = Artikel::showArtikel(9,$this->currentPage * 9, $this->kategori);
        $idArtikels = $this->getIdArtikel();

        //get num comment
        $this->numComment = ArtikelComment::countComment($idArtikels);
        $this->orderNumComment();

        //geet penulis artikel
        $this->penulis = KelolaStaff::find($this->getIdStaff())->toArray();
        $this->orderPenulisArtikels();        
    }

    public function showUpdateArtikel($id){
        $this->updateId = $id;
        $this->emitTo('admin.artikel.update-artikel', 'showUpdateArtikel', ['id' => $id]);
    }

    public function refresh(){
         //get artikels
         $this->artikels = Artikel::showArtikel(9,$this->currentPage * 9, $this->kategori, $this->keyword);
         //get num comment
         $idArtikels = $this->getIdArtikel();
        $this->numComment = ArtikelComment::countComment($idArtikels);
        $this->orderNumComment();

        //geet penulis artikel
        $this->penulis = KelolaStaff::find($this->getIdStaff())->toArray();
        $this->orderPenulisArtikels();
    }

    public function addArtikel(){
        $this->emitTo('admin.artikel.create-artikel', 'showCreatePanel', ['show' => true]);
    }

    public function addKategori(){
        $this->emitTo('admin.artikel.create-kategori-artikel', 'showCreateKategoriPanel');
    }

    public function render()
    {
        return view('livewire.admin.artikel.get-artikel')
            ->extends('layouts.admin', [
                'artikel'=> 'active'
            ])
            ->section('slot');
    }
}

<?php

namespace App\Http\Livewire\Admin\Portofolio;
use App\Models\KategoriPortofolio;
use Livewire\Component;

class CreateKategoriPortofolio extends Component
{
    public $kategori;

    public $kategoris;
    public $show;
    public $idDelete;

    public $listeners = [
        'showCreateKategoriPanel'
    ];
    public function mount()
    {
        $this->show = false;
        $this->kategoris = KategoriPortofolio::all()->toArray();
    }

    public function showCreateKategoriPanel(){
        $this->show = true;
    }

    public function create()
    {
        //validasi
        $this->validate([
            'kategori' => 'required|string|max:50'
        ]);

        $kategori = KategoriPortofolio::create(
            [
                'kategori' => $this->kategori
            ]
        );

        if($kategori){
            $this->kategoris = KategoriPortofolio::all()->toArray();
            $this->kategori = '';
        }
    }

    public function close(){
        $this->kategori = null;
        $this->show = false;
    }

    public function cancel(){
        $this->idDelete = null;
    }

    public function delete($id){
        if($id < 0 or $id > sizeof($this->kategoris)) return;
        $this->idDelete = $id;
    }

    public function confirmDelete(){
        $id = $this->kategoris[$this->idDelete]['id'];
        $status = KategoriPortofolio::find($id)->delete();
        if($status){
            $this->kategoris = KategoriPortofolio::all()->toArray();
        }
        $this->idDelete = null;
        $this->kategoris = KategoriPortofolio::all()->toArray();
    }

    public function render()
    {
        return view('livewire.admin.portofolio.create-kategori-portofolio');
    }
}

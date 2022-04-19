<?php

namespace App\Http\Livewire\Admin\Artikel;
use App\Models\KategoriArtikel;
use Livewire\Component;

class GetKategoriArtikel extends Component
{
    public $kategoris;
    public $delete = 0;
    
    public function mount()
    {
        //auth

        //get kategoris
        $this->kategoris = KategoriArtikel::all();
    }

    public function delete($id)
    {
        $this->delete = $id;
    }

    public function confirmDelete()
    {
        $deleted_kategori = KategoriArtikel::find($this->delete);
        if(!empty($deleted_kategori)){
            $deleted_kategori->delete();
        }
        $this->delete = 0;
    }

    public function render()
    {
        return view('livewire.admin.artikel.get-kategori-artikel');
    }
}

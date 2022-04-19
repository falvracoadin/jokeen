<?php

namespace App\Http\Livewire\Admin\Portofolio;
use App\Models\KategoriPortofolio;
use App\Models\Portofolio;
use Livewire\Component;

class GetKategoriPortofolio extends Component
{
    public $kategoris;
    public $delete = 0;

    public function mount()
    {
        //auth

        //get kategoris
        $this->kategoris = KategoriPortofolio::all();
    }

    public function delete($id)
    {
        $this->delete = $id;
    }

    public function confirmDelete()
    {
        $deleted_kategori = KategoriPortofolio::find($this->delete);
        if(!empty($deleted_kategori)){
            $deleted_kategori->delete();
        }
        $this->delete = 0;
    }
    
    public function render()
    {
        return view('livewire.admin.portofolio.get-kategori-portofolio');
    }
}
 
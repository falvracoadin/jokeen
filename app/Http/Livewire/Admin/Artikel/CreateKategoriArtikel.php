<?php

namespace App\Http\Livewire\Admin\Artikel;

use App\Models\KategoriArtikel;
use Livewire\Component;

class CreateKategoriArtikel extends Component
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
        $this->kategoris = KategoriArtikel::all()->toArray();
    }

    public function showCreateKategoriPanel()
    {
        $this->show = true;
        $this->kategoris = KategoriArtikel::all()->toArray();
    }

    public function create()
    {
        //validasi
        $this->validate([
            'kategori' => 'required|string|max:50'
        ]);

        KategoriArtikel::create(
            [
                'kategori' => $this->kategori
            ]
        );
        $this->kategoris = KategoriArtikel::all()->toArray();
        $this->kategori = '';
    }

    public function close()
    {
        $this->kategori = null;
        $this->show = false;
    }

    public function cancel()
    {
        $this->idDelete = null;
    }

    public function delete($id)
    {
        if ($id < 0 or $id > sizeof($this->kategoris)) return;
        $this->idDelete = $id;
    }

    public function confirmDelete()
    {
        $id = $this->kategoris[$this->idDelete]['id'];
        $status = KategoriArtikel::find($id)->delete();
        if ($status) {
            $this->kategoris = KategoriArtikel::all()->toArray();
        }
        $this->idDelete = null;
        $this->kategoris = KategoriArtikel::all()->toArray();
    }

    public function render()
    {
        return view('livewire.admin.artikel.create-kategori-artikel');
    }
}

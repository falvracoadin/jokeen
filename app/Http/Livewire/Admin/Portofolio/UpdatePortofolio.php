<?php

namespace App\Http\Livewire\Admin\Portofolio;
use App\Models\Portofolio;
use App\Models\KategoriPortofolio;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdatePortofolio extends Component
{
    use WithFileUploads;

    public $updated_portofolio;
    public $nama_portofolio, $deskripsi, $date, $kategori, $file, $tipe;
    public $kategoris;
    public $tipes = [
        'working-paper',
        'latest-brief',
        'latest-report'
    ];

    public $listeners = [
        'showUpdatePortofolio',
        'updateDeskripsi',
        'update'
    ];

    public function mount($id)
    {
        //get portofolio
        $portofolio = Portofolio::find($id);
        if(!empty($portofolio)){
            $this->updated_portofolio = $portofolio;
            $this->nama_portofolio = $portofolio->nama_portofolio;
            $this->deskripsi = $portofolio->deskripsi;
            $this->date = $portofolio->date;
            $this->kategori = $portofolio->kategori;
            $this->file = $portofolio->file;
            $this->tipe = $portofolio->tipe;
        }
 
        $this->kategoris = KategoriPortofolio::all()->toArray();
    }

    public function close(){
        $this->updated_portofolio = null;
        $this->nama_portofolio = null;
        $this->deskripsi = null;
        $this->date = null;
        $this->kategori = null;
        $this->file = null;
        $this->tipe = null;
    }

    public function showUpdatePortofolio($id){
        if($id == -1) return;

        $portofolio = Portofolio::find($id)[0];
        if(empty($portofolio)) return;
        else{
            $this->updated_portofolio = $portofolio;
            $this->nama_portofolio = $portofolio->nama_portofolio;
            $this->deskripsi = $portofolio->deskripsi;
            $this->date = $portofolio->date;
            $this->kategori = $portofolio->kategori;
            $this->file = $portofolio->file;
            $this->tipe = $portofolio->tipe;
        }
        $this->kategoris = KategoriPortofolio::all()->toArray();
    }

    public function updateDeskripsi($deskripsi){
        $this->deskripsi = $deskripsi;
    }

    public function update()
    {
        //validasi
        $this->validate([
            'nama_portofolio' => 'required|string|max:50',
            'deskripsi' => 'required|string',
            'date' => 'required|date',
            'kategori' => 'required|string',
            'tipe' => 'required|string|max:50'
        ]);
        //upload file section
        $path = null;
        if(gettype($this->file) !== 'string'){
            $this->validate([
                'file' => 'required|image'
            ]);
            $path = 'portofolio/'. $this->file->getFileName();
            Storage::move('livewire-tmp/'.$this->file->getFileName(), 'public/'.$path);
        }

        if($path == null) $path = $this->file;

        $this->updated_portofolio->update(
            [
                'nama_portofolio' => $this->nama_portofolio,
                'deskripsi' => $this->deskripsi,
                'date' => $this->date,
                'kategori' => $this->kategori,
                'file' => $path, // ganti,
                'tipe' => $this->tipe
            ]
        );

        $this->close();
        $this->emitTo('admin.portofolio.get-portofolio', 'refresh');

    }

    public function render()
    {
        return view('livewire.admin.portofolio.update-portofolio');
    }
}

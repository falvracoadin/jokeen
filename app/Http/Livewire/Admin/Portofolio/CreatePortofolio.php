<?php

namespace App\Http\Livewire\Admin\Portofolio;
use App\Models\KategoriPortofolio;
use App\Models\Portofolio;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePortofolio extends Component
{
    use WithFileUploads;

    public $nama_portofolio_new, 
            $deskripsi_new, 
            $date_new, 
            $kategori_new, 
            $file_new, 
            $tipe_new;

    public $kategoris;

    public $show;

    public $newFile;

    public $tipes = [
        'working-paper',
        'latest-brief',
        'latest-report'
    ];

    public $listeners = [
        'showCreatePanel',
        'create'
    ];

    public function mount()
    {
        $this->kategoris = KategoriPortofolio::all()->toArray();
    }

    public function create()
    {
        //validasi
        $this->validate([
            'nama_portofolio_new' => 'required|string|max:50',
            'deskripsi_new' => 'required|string',
            'date_new' => 'required|date',
            'kategori_new' => 'required|string',
            'tipe_new' => 'required|string',
            'newFile' => 'mimes:jpeg,jpg|max:2048|nullable'
        ]);
        //upload file section
        $path = null;
        if($this->newFile){
            $path = 'portofolio/'.$this->newFile->getFileName();
            Storage::move('livewire-tmp/'.$this->newFile->getFileName(), 'public/'.$path);
            $this->newFile = null;
        }

        $portofolio = Portofolio::create(
            [
                'nama_portofolio' => $this->nama_portofolio_new,
                'deskripsi' => $this->deskripsi_new,
                'date' => $this->date_new,
                'kategori' => $this->kategori_new,
                'file' => $path, // ganti,
                'tipe' => $this->tipe_new
            ]
        );

        if($portofolio){
            $this->close();
            $this->emitTo('admin.portofolio.get-portofolio', 'refresh');
        }
    }

    public function showCreatePanel($show){
        $this->show = $show;
        $this->kategoris = KategoriPortofolio::all()->toArray();
    }

    public function close(){
        $this->nama_portofolio_new = null;
        $this->deskripsi_new = null;
        $this->date_new = null;
        $this->kategori_new = null;
        $this->file_new = null;
        $this->tipe_new = null;

        if($this->newFile){
            Storage::delete('livewire-tmp/'.$this->newFile->getFileName());
            $this->newFile = null;
        }
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.admin.portofolio.create-portofolio');
    }
}

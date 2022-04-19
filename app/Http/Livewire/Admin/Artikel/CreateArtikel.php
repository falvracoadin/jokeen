<?php

namespace App\Http\Livewire\Admin\Artikel;
use App\Models\Artikel;
use App\Models\KategoriArtikel;
use App\Models\KelolaStaff;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateArtikel extends Component
{
    use WithFileUploads;

    public $nama_artikel_new,
            $deskripsi_new,
            $tanggal_terbit_new, 
            $id_staff_new, 
            $kategori_new, 
            $gambar_new,
            $pdf_new;

    public $kategoris,$staff;

    public $show;

    public $newFile, $newPdfFile;

    public $listeners = [
        'showCreatePanel',
        'create'
    ];

    public function mount()
    {
        //get kategori
        $this->kategoris = KategoriArtikel::all()->toArray();

        //get staff
        $this->staff = KelolaStaff::all()->toArray();
    }

    public function create()
    {
        //validasi
        $this->validate([
            'nama_artikel_new' => 'required|string|max:50',
            'deskripsi_new' => 'required|string',
            'tanggal_terbit_new' => 'required|date',
            'id_staff_new' => 'required|numeric|min:1',
            'kategori_new' => 'required|string|max:50',
            'newFile' => 'mimes:jpeg,jpg|max:2048|nullable',
            'pdfFile' => 'max:2048|nullable'
        ]);
        //upload file section
        $path = null;
        $pdfPath = null;
        if($this->newFile){
            $path = "artikel/". $this->newFile->getFileName();
            Storage::move('livewire-tmp/'.$this->newFile->getFileName(), 'public/'.$path);
            $this->newFile = null;
        }
        if($this->newPdfFile){
            $pdfPath = 'artikel/pdf/'. $this->newPdfFile->getFileName();
            Storage::move('livewire-tmp/'.$this->newPdfFile->getFileName(), 'public/'.$path);
            $this->newPdfFile = null;

        }
        
        $artikel = Artikel::create(
            [
                'nama_artikel' => $this->nama_artikel_new,
                'deskripsi' => $this->deskripsi_new,
                'tanggal_terbit' => $this->tanggal_terbit_new,
                'id_staff' => $this->id_staff_new,
                'kategori' => $this->kategori_new,
                'gambar' => $path ,
                'pdf' => $pdfPath
            ]
        );

        if($artikel){
            $this->close();
            $this->emitTo('admin.artikel.get-artikel', 'refresh');
        }
    }

    public function showCreatePanel($show){
        $this->show = $show;
        $this->kategoris = KategoriArtikel::all()->toArray();
    }

    public function close(){
        $this->nama_artikel_new = null;
        $this->deskripsi_new = null;
        $this->tanggal_terbit_new = null;
        $this->id_staff_new = null;
        $this->kategori_new = null;
        $this->gambar_new = null;
        if($this->newFile){
            Storage::delete('livewire-tmp/'.$this->newFile->getFIleName());
            $this->newFile = null;
        }
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.admin.artikel.create-artikel');
    }
}

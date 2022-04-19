<?php

namespace App\Http\Livewire\Admin\Artikel;
use App\Models\Artikel;
use App\Models\KategoriArtikel;
use App\Models\KelolaStaff;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateArtikel extends Component
{
    use WithFileUploads;

    public $updated_artikel;
    public $nama_artikel, $deskripsi, $tanggal_terbit, $id_staff, $kategori, $gambar, $pdf; 
    public $kategoris,$staff;
    public $file, $pdfFile;

    public $listeners = [
        'showUpdateArtikel',
        'updateDeskripsi'
    ];

    public function mount($id)
    {
        //get data artikel
        $artikel = Artikel::find($id);
        if(!empty($artikel)){
            $this->updated_artikel = $artikel;
            $this->nama_artikel = $artikel->nama_artikel;
            $this->deskripsi = $artikel->deskripsi;
            $this->tanggal_terbit = $artikel->tanggal_terbit;
            $this->id_staff = $artikel->id_staff;
            $this->kategori = $artikel->kategori;
            $this->gambar = $artikel->gambar;
            $this->pdf = $artikel->pdf;
        }

        //get kategori
        $this->kategoris = KategoriArtikel::all()->toArray();

        //get staff
        $this->staff = KelolaStaff::all()->toArray();
    }

    public function close(){
        $this->updated_artikel = null;
        $this->nama_artikel = null;
        $this->deskripsi = null;
        $this->tanggal_terbit  = null;
        $this->id_staff = null;
        $this->kategori = null;
        $this->gambar = null;
        if($this->file){
            Storage::delete('livewire-tmp/'.$this->file->getFileName());
            $this->file = null;
        }
        if($this->pdfFile){
            Storage::delete('livewire-tmp/'.$this->pdfFile->getFileName());
        }
    }

    public function showUpdateArtikel($id){
        if($id <= 0) return;

        $artikel = Artikel::find($id);
        if(empty($artikel)){
            return;
        }
        else{
            $artikel = $artikel[0];
            $this->updated_artikel = $artikel;
            $this->nama_artikel = $artikel->nama_artikel;
            $this->deskripsi = $artikel->deskripsi;
            $this->tanggal_terbit = $artikel->tanggal_terbit;
            $this->id_staff = $artikel->id_staff;
            $this->kategori = $artikel->kategori;
            $this->gambar = $artikel->gambar;
            $this->pdf = $artikel->pdf;
        }
        
        //get kategori
        $this->kategoris = KategoriArtikel::all()->toArray();

        //get staff
        $this->staff = KelolaStaff::all()->toArray();
    }

    public function updateDeskripsi($deskripsi){
        $this->deskripsi = $deskripsi;
    }

    public function update()
    {
        //validasi
        $this->validate([
            'nama_artikel' => 'required|string|max:50',
            'deskripsi' => 'required|string',
            'tanggal_terbit' => 'required',
            'id_staff' => 'required|numeric',
            'kategori' => 'required|string',
            'file' => 'mimes:jpeg,jpg|max:2048|nullable',
            'pdfFile' => 'max:2048|nullable'
        ]);
        //upload file section
        $path = $this->gambar;
        $pdfPath = $this->pdf;
        if($this->file){
            $path = "artikel/". $this->file->getFileName();
            Storage::move('livewire-tmp/'.$this->file->getFileName(), 'public/'.$path);
            if($this->gambar){
                Storage::delete('public/'. $this->gambar);
            }
        }
        if($this->pdfFile){
            $pdfPath = 'artikel/pdf/'.$this->pdfFile->getFileName();
            Storage::move('livewire-tmp/'.$this->pdfFile->getFileName(), 'public/'.$pdfPath);
            if($this->pdf){
                Storage::delete('public/'.$this->pdf);
            }
        }

        $this->updated_artikel->update(
            [
                'nama_artikel' => $this->nama_artikel,
                'deskripsi' => $this->deskripsi,
                'tanggal_terbit' => $this->tanggal_terbit,
                'id_staff' => $this->id_staff,
                'kategori' => $this->kategori,
                'gambar' => $path,
                'pdf' => $pdfPath
            ]
        );

        $this->close();
        $this->emitTo('admin.artikel.get-artikel', 'refresh');
    }

    public function render()
    {
        return view('livewire.admin.artikel.update-artikel');
    }
}

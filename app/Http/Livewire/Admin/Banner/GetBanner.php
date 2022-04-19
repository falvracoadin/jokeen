<?php

namespace App\Http\Livewire\Admin\Banner;
use App\Models\KelolaBanner;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class GetBanner extends Component
{
    use WithFileUploads;
    public $banners;
    public $nama_app,
        $deskripsi,
        $file,
        $gambar,
        $idApp,
        $section;

    public function mount()
    {
        $this->banners = KelolaBanner::all()->toArray();
        $this->section = 0;
        $this->nama_app = $this->banners[0]['nama_app'];
        $this->deskripsi = $this->banners[0]['deskripsi'];
        $this->gambar = $this->banners[0]['file'];
        $this->idApp = $this->banners[0]['id'];
    }

    public function updated($name, $value){
        if($name === 'section'){
            $this->nama_app = $this->banners[$value]['nama_app'];
            $this->deskripsi = $this->banners[$value]['deskripsi'];
            $this->gambar = $this->banners[$value]['file'];
            $this->idApp = $this->banners[$value]['id'];
        }
    }

    public function save(){
        $this->validate([
            'nama_app' => 'string|max:50|required',
            'deskripsi' => 'string|required|max:255',
            'file' => 'mimes:jpeg,jpg|max:2048|nullable',
            'idApp' => 'required|numeric'
        ]);

        $app = KelolaBanner::find($this->idApp);
        if(!in_array($this->nama_app, config('app.nama_app')) or empty($app)){
            session()->flash('err', 'Nama App tidak ditemukan dalam sistem.');
        }
        $path = $this->gambar;
        if($this->file){
            $path = 'banner/'.$this->file->getFileName();
            Storage::move('livewire-tmp/'.$this->file->getFileName(), 'public/'.$path);
            if($this->gambar){
                Storage::delete('public/'.$this->gambar);
            }
            $this->file = null;
            $this->gambar = $path;
        }

        $app->update([
            'nama_app' => $this->nama_app,
            'deskripsi' => $this->deskripsi,
            'file' => $path
        ]);
        session()->flash('success', 'Berhasil mengubah informasi banner '.$this->nama_app);
    }

    public function render()
    {
        return view('livewire.admin.banner.get-banner')
            ->extends('layouts.admin',[
                'banner' => 'active'
            ])
            ->slot('slot');
    }
}

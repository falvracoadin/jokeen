<?php

namespace App\Http\Livewire\Admin\Banner;
use App\Models\KelolaBanner;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
 
class CreateBanner extends Component
{
    public $nama_app_new, $deskripsi_new, $file_new;

    public $show;
    public $namaApp;
    public $newFile;

    public $listeners = [
        'showCreatePanel',
        'create'
    ];

    public function mount()
    {
        $this->namaApp = config('app.nama_app');
    } 

    public function create()
    {
        //validasi
        $this->validate([
            'nama_app_new' => 'required|string|max:20',
            'deskripsi' => 'required|string|max:255',
            'newFile' => 'mimes:jpeg,jpg|max:2048|nullable'
        ]);

        //upload file section
        $path = null;
        if($this->newFile){
            $path = "banner/".$this->newFile->getFileName();
            Storage::move('livewire-tmp/'.$this->newFile->getFileName(), 'public/'.$path);
            $this->newFile = null;
        }

        $banner = KelolaBanner::create(
            [
                'nama_app' => $this->nama_app,
                'deskripsi' => $this->deskripsi,
                'file' => $path,
            ]
        );
 
        if($banner){
            $this->close();
            $this->emitTo('admin.banner.get-banner', 'refresh');
        }
    }

    public function render()
    {
        return view('livewire.admin.banner.create-banner');
    }
}

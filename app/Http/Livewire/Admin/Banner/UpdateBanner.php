<?php

namespace App\Http\Livewire\Admin\Banner;
use App\Models\KelolaBanner;
use Livewire\Component;
 
class UpdateBanner extends Component
{
    public $updated_banner;
    public $nama_app, $deskripsi, $file;

    public function mount($id)
    {
        //auth

        //get banner
        $banner = KelolaBanner::find($id);
        if(empty($banner)){
            return;
        }
        else{
            $this->updated_banner = $banner;
            $this->nama_app = $banner->nama_app;
            $this->deskripsi = $banner->deskripsi;
            $this->file = $banner->file;
        }

    }

    public function update()
    {
        //validasi 

        //upload file section

        $this->updated_banner->update(
            [
                'nama_app' => $this->nama_app,
                'deskripsi' => $this->deskripsi,
                'file' => $this->file, // ganti
            ]
        );

        //redirect
    }

    public function render()
    {
        return view('livewire.admin.banner.update-banner');
    }
}

<?php

namespace App\Http\Livewire\Admin\Staff;
use App\Models\KelolaStaff;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateStaff extends Component
{
    use WithFileUploads;
    public $updated_staff;
    public $nama_staff,
        $deskripsi,
        $posisi,
        $photo,
        $file;
    public $listeners = [
        'showUpdateStaff',
        'updateDeskripsi'
    ];

    public function mount()
    {
    }

    public function close(){
        $this->updated_staff = null;
        $this->nama_staff = null;
        $this->deskripsi = null;
        $this->posisi = null;
        if($this->file){
            Storage::delete('livewire-tmp/'.$this->file->getFileName());
            $this->fille = null;
        }
    }

    public function showUpdateStaff($id){
        if($id <= 0) return;

        $staff = KelolaStaff::find($id);
        if(empty($staff)) return;
        else{
            $staff = $staff[0];
            $this->updated_staff = $staff;
            $this->nama_staff = $staff->nama_staff;
            $this->deskripsi = $staff->deskripsi;
            $this->posisi = $staff->posisi;
            $this->photo = $staff->file;
        }
    }

    public function update()
    {
        //validasi
        $this->validate([
            'nama_staff' => 'required|string|max:50',
            'deskripsi' => 'required|string|max:255',
            'posisi' => 'required|string|max:50',
            'file' => 'mimes:jpeg,jpg|max:2048|nullable' 
        ]);
        //upload file section
        $path = null;
        if($this->file){
            $path = 'staff/'.$this->file->getFileName();
            Storage::move('livewire-tmp/'.$this->file->getFileName(), 'public/'.$path);
            if($this->photo){
                Storage::delete('public/'.$this->photo);
            }
            $this->photo = $path;
        }
        if($path == null) $path = $this->photo;

        $this->updated_staff->update([
            'nama_staff' => $this->nama_staff,
            'deskripsi' => $this->deskripsi,
            'posisi' => $this->posisi,
            'file' => $path
        ]);
        $this->close();
        $this->emitTo('admin.staff.get-staff', 'refresh');

    }

    public function render()
    {
        return view('livewire.admin.staff.update-staff');
    }
}

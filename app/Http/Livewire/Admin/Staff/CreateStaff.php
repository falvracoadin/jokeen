<?php

namespace App\Http\Livewire\Admin\Staff;
use App\Models\KelolaStaff;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateStaff extends Component
{
    use WithFileUploads;
    public $nama_staff_new,
        $deskripsi_new,
        $posisi_new,
        $file_new;

    public $newFile;
    public $show;

    public $listeners = [
        'showCreatePanel',
        'create'
    ];

    public function mount()
    {

    }

    public function create()
    {
        $this->validate([
            'nama_staff_new' => 'required|string|max:100',
            'deskripsi_new' => 'required|string|max:255',
            'posisi_new' => 'required|string|max:50',
            'newFile' => 'mimes:jpeg,jpg|max:2048|nullable'
        ]);

        $path = null;
        if($this->newFile){
            $path = 'staff/'.$this->newFile->getFileName();
            Storage::move('livewire-tmp/'.$this->newFile->getFileName(), 'public/'.$path);
            $this->newFile = null;
        }

        $staff = KelolaStaff::create([
            'nama_staff' => $this->nama_staff_new,
            'deskripsi' => $this->deskripsi_new,
            'posisi' => $this->posisi_new,
            'file' => $path
        ]);

        if($staff){
            $this->close();
            $this->emitTo('admin.staff.get-staff', 'refresh');
        }
    }

    public function showCreatePanel($show){
        $this->show = $show;
    }

    public function close(){
        $this->nama_staff_new = null;
        $this->deskripsi_new = null;
        $this->posisi_new = null;
        $this->file_new = null;
        if($this->newFile){
            Storage::delete('livewire-tmp/'.$this->newFile->getFileName());
            $this->newFile = null;
        }
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.admin.staff.create-staff');
    }
}

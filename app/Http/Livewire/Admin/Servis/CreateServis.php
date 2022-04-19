<?php

namespace App\Http\Livewire\Admin\Servis;
use App\Models\KelolaServis;
use Livewire\Component;

class CreateServis extends Component
{
    public $nama_servis_new, $deskripsi_new;

    public $show;

    public $listeners = [
        'showCreatePanel',
        'create'
    ];

    public $rules = [
        'nama_servis_new' => 'required|string|max:50',
        'deskripsi_new' => 'required|string|max:255'
    ];

    public function mount()
    {
    }

    public function updated($name, $value){
        $this->validateOnly($name);
    }
    
    public function create()
    {
        //validasi
        $this->validate([
            'nama_servis_new' => 'required|string|max:50',
            'deskripsi_new' => 'required|string|max:255'
        ]);
       
        $servis = KelolaServis::create(
            [
                'nama_servis' => $this->nama_servis_new,
                'deskripsi' => $this->deskripsi_new
            ]
        );
 
        if($servis){
            $this->close();
            $this->emitTo('admin.servis.get-servis', 'refresh');
        }
    }

    public function showCreatePanel($show){
        $this->show = $show;
    }

    public function close(){
        $this->nama_servis_new = null;
        $this->deskripsi_new = null;
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.admin.servis.create-servis');
    }
}

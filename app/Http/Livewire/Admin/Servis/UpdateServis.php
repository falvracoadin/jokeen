<?php

namespace App\Http\Livewire\Admin\Servis;
use App\Models\KelolaServis;
use Livewire\Component;

class UpdateServis extends Component
{
    public $updated_servis;
    public $nama_servis, $deskripsi;

    public $listeners = [
        'showUpdateServis'
    ];
    public $rules = [
        'nama_servis' => 'string|required|max:50',
        'deskripsi' => 'string|required|max:255'
    ];

    public function mount()
    {
    }
    public function updated($name, $value){
        $this->validateOnly($name);
    }

    public function close(){
        $this->updated_servis = null;
        $this->nama_servis = null;
        $this->deskripsi = null;
    }

    public function showUpdateServis($id){
        if($id <= 0) return;
        $servis = KelolaServis::find($id);

        if(empty($servis)) return;
        else{
            $servis =$servis[0];
            $this->updated_servis = $servis;
            $this->nama_servis = $servis->nama_servis;
            $this->deskripsi = $servis->deskripsi;
        }
    }

    public function updateDeskripsi($deskripsi){
        $this->deskripsi = $deskripsi;
    }

    public function update()
    {
        //validasi 
        $this->validate([
            'nama_servis' => 'required|string|max:50',
            'deskripsi' => 'required|string|max:255'
        ]);

        $this->updated_servis->update(
            [
                'nama_servis' => $this->nama_servis,
                'deskripsi' => $this->deskripsi
            ]
        );

        $this->close();
        $this->emitTo('admin.servis.get-servis', 'refresh');
    }


    public function render()
    {
        return view('livewire.admin.servis.update-servis');
    }
}
 
<?php

namespace App\Http\Livewire\Admin\Servis;
use App\Models\KelolaServis;
use Livewire\Component;

class GetServis extends Component
{ 
    public $servis;
    public $delete;
    public $keyword;
    public $updateId;

    public $listeners = [
        'refresh'
    ];

    public function mount()
    {
        $this->servis = KelolaServis::all()->toArray();
        $this->keyword = null;
        $this->updateId = null;
    }

    public function updated($name, $value){
        if($name === 'keyword'){
            $this->refresh();
        }
    }

    public function refresh(){
        if($this->keyword !== null) $this->servis = KelolaServis::where('nama_servis', 'like',"%$this->keyword%")
            ->get()
            ->toArray();
        else $this->servis = KelolaServis::all()->toArray();
    }

    public function confirmDelete()
    {
        $deleted = KelolaServis::find($this->delete);
        if(!empty($deleted)){
            $deleted = $deleted[0];
            $deleted->hapus = true;
            $deleted->save(['hapus']);
        }

        $this->delete = null;

        $this->refresh();
    }

    public function delete($id)
    {
        if($id <= 0) return;
        $this->delete = $id;
    }

    public function showUpdateServis($id){
        if($id <= 0) return;
        $this->updateId = $id;
        $this->emitTo('admin.servis.update-servis', 'showUpdateServis', ['id' => $id]);
    }

    public function addServis(){
        $this->emitTo('admin.servis.create-servis','showCreatePanel', ['show' => true]);
    }

    public function render()
    {
        return view('livewire.admin.servis.get-servis')
            ->extends('layouts.admin', [
                'servis' => 'active'
            ])
            ->slot('slot');
    }
}

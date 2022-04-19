<?php

namespace App\Http\Livewire\Admin\Staff;

use App\Models\KelolaServis;
use App\Models\KelolaStaff;
use Livewire\Component;

class GetStaff extends Component
{
    public $staffs;
    public $delete;
    public $keyword;
    public $updateId;

    public $maxPage;
    public $currentPage;

    public $listeners = [
        'refresh'
    ];

    public function mount()
    {
        $this->staffs = KelolaStaff::showStaff(9, $this->currentPage * 9, $this->keyword);
        $this->maxPage = ceil(KelolaStaff::countShowable($this->keyword)/9);
        $this->currentPage = 0;
    }

    public function updated($name, $value){
        if($name === 'keyword'){
            $this->refresh();
        }
    }

    public function refresh(){
        $this->currentPage = 0;
        $this->staffs = KelolaStaff::showStaff(9, $this->currentPage * 9, $this->keyword);
        $this->maxPage = ceil(KelolaStaff::countShowable($this->keyword)/9);
    }

    public function delete($id)
    {
        if($id <= 0) return;
        $this->delete = $id;
    }

    public function confirmDelete()
    {
        $deleted = KelolaStaff::find($this->delete);
        if(!empty($deleted)){
            $deleted = $deleted[0];
            $deleted->show = false;
            $deleted->save('show');
        }
        $this->delete = null;
        $this->refresh();
    }

    public function showUpdateStaff($id){
        if($id <= 0) return;
        $this->updateId = $id;
        $this->emitTo('admin.staff.update-staff', 'showUpdateStaff', ['id' => $id]);
    }

    public function addStaff(){
        $this->emitTo('admin.staff.create-staff', 'showCreatePanel', ['show' => true]);
    }

    public function getPage($page){
        if($page < 0 or $page > $this->maxPage) return;
        $this->currentPage = $page;

        $this->staffs = KelolaStaff::showStaff(9, $this->currentPage * 9, $this->keyword);
    }

    public function render()
    {
        return view('livewire.admin.staff.get-staff')
            ->extends('layouts.admin', [
                'staff' => 'active'
            ])
            ->slot('slot');
    }
}

<?php

namespace App\Http\Livewire\Admin\Portofolio;
use App\Models\Portofolio;
use Livewire\Component;
 
class GetPortofolio extends Component
{
    public $portofolios;
    public $delete;
    public $kategori = 'all'; 
    public $currentPage;

    public $maxPage;

    public $updateId;
    public $keyword;

    public $listeners = [
        'refresh'
    ];

    public function mount()
    {
        $this->currentPage = 0;
        $this->keyword = '';
        $this->delete = null;
        $this->updateId = null;
        //get artikels
        $this->maxPage = ceil(Portofolio::count()/9);
        $this->portofolios = Portofolio::showPortofolio(9,$this->currentPage * 9, $this->kategori,null, $this->keyword);
        
    } 

    public function updated($name, $value){
        if($name === 'keyword'){
            $this->currentPage = 0;
            $this->maxPage = ceil(Portofolio::where('nama_portofolio', 'like', "%$this->keyword%")->count()/9);
            $this->portofolios = Portofolio::showPortofolio(9, $this->currentPage * 9, $this->kategori, null, $this->keyword);
        }
    }

    public function confirmDelete()
    {
        $deleted = Portofolio::find($this->delete);
        if(!empty($deleted)){
            $deleted->show = false;
            $deleted->save(['show']);
        }

        $this->delete = null;
        $this->refresh(); 
    }

    public function delete($id){
        if($id < 0) $this->delete = null;
        else $this->delete = $id;
    }

    public function getPage($page){
        if($page < 0 or $page > $this->maxPage) return;
        $this->currentPage = $page;

        $this->portofolios = Portofolio::showPortofolio(9, $this->currentPage * 9, $this->kategori, null, $this->keyword);
    }

    public function showUpdatePortofolio($id){
        $this->updateId = $id;
        $this->emitTo('admin.portofolio.update-portofolio', 'showUpdatePortofolio', ['id' => $id]);
    }

    public function refresh(){
        $this->portofolios = Portofolio::showPortofolio(9, $this->currentPage * 9, $this->kategori, null, $this->keyword);
    }

    public function addPortofolio(){
        $this->emitTo('admin.portofolio.create-portofolio', 'showCreatePanel', ['show' => true]);
    }

    public function addKategori(){
        $this->emitTo('admin.portofolio.create-kategori-portofolio', 'showCreateKategoriPanel');
    }

    public function render()
    {
        return view('livewire.admin.portofolio.get-portofolio')
            ->extends('layouts.admin',[
                'portofolio' => 'active'
            ])
            ->slot('slot');
    }
}
  
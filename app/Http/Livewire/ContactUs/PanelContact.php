<?php

namespace App\Http\Livewire\ContactUs;

use App\Models\ContactPanel;
use Livewire\Component;

class PanelContact extends Component
{
    public $title;
    public $subTitle;
    public $description;
    public $panel;

    public function mount(){
        $this->panel = ContactPanel::first();
        $this->title = $this->panel->judul;
        $this->subTitle = $this->panel->sub_judul;
        $this->description = $this->panel->description;
    }

    public function update(){
        $this->validate([
            'title' => 'string',
            'subTitle' => 'string',
            'description' => 'string'
        ]);

        $this->panel->judul = $this->title;
        $this->panel->sub_judul = $this->subTitle;
        $this->panel->description = $this->description;

        $status  = $this->panel->save();
        if($status) session()->flash('succes', 'Successfully store data!');
        else session()->flash('err', 'Failed to store data!');
    }


    public function render()
    {
        return view('livewire.contact-us.panel-contact')
            ->extends('layouts.admin',[
                'panelContact' => 'active'
            ])
            ->slot('slot');
    }
}

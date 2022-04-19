<?php

namespace App\Http\Livewire\Admin\Footer;

use App\Models\Footer;
use Livewire\Component;

class UpdateFooter extends Component
{
    public $updated_footer;
    public $konten;
    public $listeners = [];

    public function mount()
    {
        $this->updated_footer = Footer::where("id", ">", 0)->first();
        if($this->updated_footer) 
            $this->konten = $this->updated_footer->konten;
    }

    public function update()
    {
        $this->validate([
            'konten' => 'required|string|max:255',
        ]);

        $status = $this->updated_footer->update([
            'konten' => $this->konten
        ]);

        if ($status)
            session()->flash('success', 'Berhasil update footer!');
        else session()->flash('err', 'Gagal update footer!');
    }

    public function render()
    {
        return view('livewire.admin.footer.update-footer')
            ->extends('layouts.admin', ['footer' => 'active'])
            ->slot('slot');
    }
}

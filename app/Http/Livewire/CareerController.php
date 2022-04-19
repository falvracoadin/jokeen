<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CareerController extends Component
{
    public function render()
    {
        return view('livewire.career-controller')
            ->extends('layouts.app')
            ->section('slot');
    }
}

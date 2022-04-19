<?php

namespace App\Http\Livewire\ContactUs;

use App\Mail\ContactUsMail;
use App\Models\ContactUs;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class GetContatctUs extends Component
{
    public $currentPage;
    public $maxPage;

    public $contacts;

    public $limit;
    public $status;
    public $keyword;
    public $tampilkan;

    public $contact;

    public function mount(){
        $this->maxPage = ceil(ContactUs::countUnAnswered()/20);
        $this->currentPage = 0;
        $this->limit = 20;
        $this->status = false;
        $this->answerPanel = false;
        $this->refresh();
    }

    public function updated($name, $value){
        if($name === 'tampilkan' and $value >=0 or $value <=2){
            switch($this->tampilkan){
                case 0 : {
                    $this->status = 0;
                    $this->refresh();
                    break;
                }
                case 1 : {
                    $this->status = 1;
                    $this->refresh();
                    break;
                }
                case 2 : {
                    $this->status = 2;
                    $this->refresh();
                    break;
                }
            }
        }
    }

    public function getPage($page){
        if($page < 0 or $page >= $this->maxPage) return;
        $this->currentPage = $page;
        $this->refresh();
    }

    public function showContact($id){
        $this->contact = ContactUs::find($id);
        if($this->contact) $this->contact = $this->contact->toArray();
        else $this->contact = null;
    }

    public function answer(){
        $this->validate([
            'contact.id' => 'numeric|required',
            'contact.nama' => 'required|string|max:50',
            'contact.email' => 'required|email',
            'contact.subjek' => 'required|string',
            'contact.deskripsi' => 'required|string|max:255',
            'contact.jawaban' => 'required|string|max:255'
        ]);

        $cont = ContactUs::find($this->contact['id']);
        if($cont == null){
            session()->flash('err', 'Data tidak ditemukan!');
            return;
        }

        $status = $cont->update([
            'jawaban' => $this->contact['jawaban'],
            'status_terjawab' => true
        ]);

        if($status){
            $mail = new ContactUsMail([
                'deskripsi' => $this->contact['deskripsi'],
                'jawaban' => $this->contact['jawaban']
            ]);
            Mail::to($this->contact['email'])->send($mail);
            session()->flash('success', 'Berhasil mengirim jawaban!');
            $this->contact = null;
            $this->refresh();       
            $this->close();
        }else{
            session()->flash('err', 'Fatal err');
        }
    }

    public function close(){
        $this->contact = null;
    }

    public function refresh(){
        $this->contacts = ContactUs::showContact($this->limit, $this->limit * $this->currentPage, $this->status, $this->keyword);
    }

    public function render()
    {
        return view('livewire.contact-us.get-contatct-us')
            ->extends('layouts.admin',[
                'contactUs' => 'active'
            ])
            ->slot('slot');
    }
}

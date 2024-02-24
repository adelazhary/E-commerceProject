<?php

namespace App\Livewire;

use App\Models\image as ModelsImage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Image extends Component
{
    use WithFileUploads;
    public $image;
    public  function mount() {
        $this->image = url('images/default.png');
    }
    public function render()
    {
        return view('livewire.image');
    }
    public function save() {

        $this->validate([
            'image' => 'required|image|max:1024',
        ]);

        dd($this->image->storeAs('images'));
        ModelsImage::create([
            'image' => $this->image->hashName()
        ]);
        $this->image = '';
        session()->flash('message', 'Image successfully uploaded.');
    }

}

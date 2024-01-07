<?php

namespace App\Livewire;

use App\Models\category as ModelsCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule as ValidationRule;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;

class Category extends Component
{
    use WithPagination;
    public bool $showModal = false;
    public string $name;
    public string $slug;
    public string $description;
    public ModelsCategory $category;

    public function mount()
    {
        $this->name = '';
        $this->slug = '';
        $this->description = '';
    }
    public function openModal()
    {
        $this->showModal = true;

        $this->category = new ModelsCategory();
    }
    #[Title('Product Form')]
    public function render()
    {
        $categories = ModelsCategory::paginate(10);
        return view('livewire.category', compact('categories'));
    }
}

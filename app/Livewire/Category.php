<?php

namespace App\Livewire;

use App\Livewire\Forms\categoryform;
use App\Models\category as ModelsCategory;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class Category extends Component
{
    use WithPagination;
    public categoryform $form;
    public ModelsCategory $categories;
    public Category $category;
    public bool $showModal = true;
    protected function rules(): array
    {
        return [
            'categories.name' => ['required' , 'string', 'min:3', 'max:255', 'unique:categories,name,' . $this->category->id],
            'categories.description' => ['nullable', 'text', 'min:3'],
        ];
    }
    public function updatedCategoryName()
    {
        $this->categories->slug = str::slug($this->category->name);
    }

    public function openModal()
    {
        $this->showModal = true;

        $this->category = new Category();
    }

    public function save()
    {
        $this->validate();

        $this->categories->save();

        $this->reset('showModal',false ,'categoryform');
    }
    public function render()
    {
        $categories = ModelsCategory::paginate(10);

        return view('livewire.category',compact('categories'));
    }
}

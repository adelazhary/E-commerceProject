<?php

namespace App\Livewire\Forms;

use App\Models\category;
use Livewire\Attributes\Validate;
use Livewire\Form;

class categoryform extends Form
{
    public $name;
    public $slug;
    public $description;
    public int $editedCategoryId;
    public array $active = [];
    public bool $showModal = false;
    protected function rules(): array
    {
        return [
            'category.name' => ['required' , 'string', 'min:3', 'max:255', 'unique:categories,name,' . $this->editedCategoryId],
            'category.description' => ['nullable', 'text', 'min:3'],
            'category.slug' => ['nullable', 'string', 'min:3', 'max:255', 'unique:categories,slug,'],
        ];
    }
}

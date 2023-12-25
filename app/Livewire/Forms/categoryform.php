<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class categoryform extends Form
{
    protected function rules(): array
    {
        return [
            'category.name' => ['required' , 'string', 'min:3', 'max:255', 'unique:categories,name,' . $this->category->id],
            'category.description' => ['nullable', 'text', 'min:3'],
        ];
    }


}

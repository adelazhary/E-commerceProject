<?php

namespace App\Livewire;

use App\Models\category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class CategoriesList extends Component
{
    use WithPagination;
    public $name;
    public $slug;
    public $description;
    public int $editedCategoryId = 0;
    public array $active = [];

    public bool $showModal = false;
    public function openModal()
    {
        $this->showModal = true;
    }
    public function editCategory($categoryId)
    {
        $this->editedCategoryId = $categoryId;
        $category = category::find($categoryId);
        // $this->name = $category->name;
        // $this->slug = $category->slug;
        // $this->description = $category->description;
        // $this->showModal = true;
    }

    public function editedCategory()
    {
        $this->validate();

        category::where('id', $this->editedCategoryId)->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
        ]);

        $this->reset('showModal');
    }
    public function updateCategory()
    {
        $this->validate();

        category::where('id', $this->editedCategoryId)->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
        ]);

        $this->reset('showModal');
    }
    public function mount()
    {
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->editedCategoryId = 0;
    }
    public function toggleIsActive($categoryId)
    {
        Category::where('id', $categoryId)->update([
            'is_active' => $this->active[$categoryId],
        ]);
    }
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:categories,name'],
            'slug' => ['nullable', 'string', 'min:3', 'max:255', 'unique:categories,slug'],
            'description' => ['required', 'string'], // Add any additional rules for description
        ];
    }
    public function updatedCategoryName()
    {
        $this->slug = Str::slug($this->name);
    }
    public function save()
    {
        $this->validate();

        category::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
        ]);

        $this->reset('showModal');
    }
    public function delete($id)
    {
        category::where('id', $id)->delete();
    }
    public function render()
    {
        $categories = category::paginate(10);

        $this->active = $categories->mapWithKeys(
            fn (Category $item) => [$item['id'] => (bool) $item['is_active']]
        )->toArray();

        return view('livewire.categories-list', compact('categories'));
    }
}

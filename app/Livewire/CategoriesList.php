<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;

class CategoriesList extends Component
{
    use WithPagination;

    #[Validate('required|string|min:3|max:255|unique:categories,name')]
    public $name;

    #[Validate('required|string|min:3|max:255|unique:categories,slug')]
    public $slug;

    #[Validate('required|string|min:10|max:255')]
    public $description;
    protected $listeners = ['delete'];
    public int $editedCategoryId = 0;
    public array $active = [];
    public bool $showModal = false;

    public function openModal()
    {
        $this->showModal = true;
    }

    public function startEdit($categoryId)
    {
        $this->editedCategoryId = $categoryId;
    }

    public function editCategory($categoryId)
    {
        $this->editedCategoryId = $categoryId;
        $category = Category::find($categoryId);
        $this->name = $category->name;
        $this->slug = Str::slug($this->name);
        $this->description = $category->description;
    }

    public function closeModal()
    {
        $this->showModal = false;
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
            'name' => [Rule::unique('categories', 'name')->ignore($this->editedCategoryId)],
            'slug' => [Rule::unique('categories', 'slug')->ignore($this->editedCategoryId)],
        ];
    }

    public function updatedCategoryName()
    {
        $this->slug = Str::slug($this->name);
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->editedCategoryId) {
            $category = Category::find($this->editedCategoryId);
            $category->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
            ]);
            // $this->resetValidation();
            $this->reset('showModal', 'editedCategoryId');
        } else {
            $this->create($validated);
            $this->resetValidation();
            $this->reset('showModal', 'editedCategoryId');
        }
    }
    public function delete($categoryId)
    {
        $category = Category::find($categoryId);
        $category->delete();
    }

    public function cancelCategoryEdit()
    {
        $this->reset('editedCategoryId');
    }

    public function render()
    {
        $categories = Category::paginate(10);

        $this->active = $categories->mapWithKeys(
            fn (Category $item) => [$item['id'] => (bool) $item['is_active']]
        )->toArray();

        return view('livewire.categories-list', compact('categories'));
    }
}

<?php

namespace App\Livewire;

use App\Jobs\imageUplaod;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

class CategoriesList extends Component
{
    use WithPagination;

    #[Validate('required|string|min:3|max:255')]
    public string $name;
    #[Validate('required|string|min:3|max:255')]
    public string $slug;
    #[Validate('required|string|min:10|max:255')]
    public string $description;
    public int $editedCategoryId = 0;
    #[Validate('boolean')]
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
        $this->active = [];
        $this->showModal = false;
        $this->resetPage();
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
            'name' => 'required|string|min:3|max:255|unique:categories,name,' . $this->editedCategoryId,
            'slug' => 'required|string|min:3|max:255|unique:categories,slug,' . $this->editedCategoryId,
            'description' => 'required|string|min:10|max:255',
            'active.*' => 'boolean',
        ];
    }

    public function updatedCategoryName()
    {
        $this->slug = Str::slug($this->name);
    }
    public function save()
    {
        if($this->editedCategoryId){
            $category = Category::findOrFail($this->editedCategoryId);
            $category->update([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'description' => $this->description,
                'is_active' => $this->active[$this->editedCategoryId] ?? '0',
            ]);
            $this->reset('showModal', 'editedCategoryId');
        }else{
            category::updateOrCreate([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'description' => $this->description,
                'is_active' => $this->active[$this->editedCategoryId] ?? '0',
            ]);
            imageUplaod::dispatch('high')->onQueue('high');
            imageUplaod::dispatch('low')->onQueue('low');
            $this->reset('showModal', 'editedCategoryId');
        }
    }
    public function delete($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $category->delete();
    }

    public function cancelCategoryEdit()
    {
        $this->reset('editedCategoryId');
    }
    #[Title('Categories List')]
    public function render()
    {
        $categories = Category::paginate(10);

        $this->active = $categories->mapWithKeys(
            fn (Category $item) => [$item['id'] => (bool) $item['is_active']]
        )->toArray();

        return view('livewire.categories-list', compact('categories'));
    }
}

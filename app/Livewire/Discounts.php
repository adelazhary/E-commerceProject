<?php

namespace App\Livewire;

use App\Models\discount;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Discounts extends Component
{
    use WithPagination;
    public $name;
    public $discount_percent;
    public array $active = [];
    public $discription;
    public $modified_at;
    public $showModal = false;
    public $start_date;
    public $end_date;
    public function mount()
    {
        $this->name = ' ';
        $this->discount_percent = 0;
        $this->discription = '';
        $this->modified_at = now();
        $this->showModal = false;
        $this->start_date = now();
        $this->end_date = date('Y-m-d', strtotime($this->start_date . ' + 1 day'));
    }

    #[Title('Discounts')]
    public function render()
    {
        $discounts = discount::paginate(10);
        $this->active = $discounts->mapWithKeys(
            fn (discount $item) => [$item['id'] => (bool) $item['active']]
        )->toArray();

        return view('livewire.discounts', [
            'discounts' => $discounts,
        ]);
    }
    public function rules(): array
    {
        return [
            'name' => 'required',
            'discount_percent' => 'required|numeric',
            'active.*' => 'boolean',
            'discription' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];
    }
    public function save()
    {

        $this->validate();
        $activeJson = json_encode($this->active);
        // dd($this->DiscActive);
        discount::create([
            'name' => $this->name,
            'discount_percent' => $this->discount_percent,
            'active' => 1,
            'discription' => $this->discription,
            'start_date' => $this->start_date,
            'end_date' => date('Y-m-d', strtotime($this->start_date . ' + 1 day'))
        ]);
        $this->reset();
    }
    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function edit($id)
    {
        $discount = discount::findorfail($id);
        if ($discount) {
            $this->name = $discount->name;
            $this->discount_percent = $discount->discount_percent;
            $this->active = $discount->active;
            $this->discription = $discount->discription;
            $this->modified_at = $discount->modified_at;

            // $this->showModal = true;
        }
    }
    public function toggleIsActive(int $discountID): void
    {
        discount::where('id', $discountID)->update([
            'active' => $this->active[$discountID],
        ]);
    }
    public function delete($id)
    {
        discount::where('id', $id)->delete();
    }
    public function create()
    {

        $this->reset();
    }
}

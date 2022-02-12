<?php

namespace App\Http\Livewire\Admin;

use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProducts2 extends Component
{
    use WithPagination;
    public $colors;
    public $sizes;
    public $search;

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function mount()
    {
        $this->colors = Color::all();
        $this->sizes = Size::all();
    }

    public function render()
    {
        $products = Product::where('name', 'LIKE', "%{$this->search}%")->paginate(10);

        return view('livewire.admin.show-products2', compact('products'))->layout('layouts.admin');
    }
}

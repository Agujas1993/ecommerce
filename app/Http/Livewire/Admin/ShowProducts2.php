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
    public $per_page = 15;
    public $columns = ['Id','Nombre', 'Slug', 'Descripción','Categoría','Estado','Stock','Precio','Subcategoría','Marca','Fecha creación','Colores', 'Tallas'];
    public $selectedColumns = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function mount()
    {
        $this->colors = Color::all();
        $this->sizes = Size::all();
        $this->selectedColumns = $this->columns;
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }

    public function render()
    {
        $products = Product::where('name', 'LIKE', "%{$this->search}%")->paginate($this->per_page);

        return view('livewire.admin.show-products2', compact('products'))->layout('layouts.admin');
    }
}

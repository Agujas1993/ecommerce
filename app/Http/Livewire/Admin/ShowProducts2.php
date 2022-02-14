<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Models\Sortable;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProducts2 extends Component
{
    use WithPagination;
    public $colors;
    public $sizes;
    public $originalUrl;
    public $search;
    public $per_page = 15;
    public $columns = ['Id','Nombre', 'Slug', 'Descripción','Categoría','Estado','Stock','Precio','Subcategoría','Marca','Fecha creación','Colores', 'Tallas'];
    public $selectedColumns = [];
    public $selectedCategories = '';
    public $categories;
    public $subcategories;
    public $selectedSubcategories = '';
    public $order;

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function mount(Request $request)
    {
        $this->colors = Color::all();
        $this->sizes = Size::all();
        $this->selectedColumns = $this->columns;
        $this->categories = Category::all();
        $this->subcategories = SubCategory::all();
        $this->originalUrl = $request->url();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function changeOrder($order)
    {
        $this->order = $order;
    }

    public function updatingSelectedSubcategories()
    {
        $this->resetPage();
    }

    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }

    public function render()
    {
        $sortable = new Sortable($this->originalUrl);

        $products = Product::where('name', 'LIKE', "%{$this->search}%")->when($this->selectedSubcategories, function($query) {
            return $query->where('subcategory_id', $this->selectedSubcategories);
        })->paginate($this->per_page);

        return view('livewire.admin.show-products2', compact('products', 'sortable'))->layout('layouts.admin');
    }
}

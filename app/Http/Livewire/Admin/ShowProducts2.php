<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
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
    public $search;
    public $per_page = 15;
    public $columns = ['Id','Nombre', 'Slug', 'Descripción','Categoría','Estado','Stock','Precio','Subcategoría','Marca','Fecha creación','Colores', 'Tallas'];
    public $selectedColumns = [];
    public $selectedCategories = '';
    public $categories;
    public $selectedBrands = '';
    public $brands;
    public $subcategories;
    public $selectedSubcategories = '';
    public $sortColumn = "id";
    public $sortDirection = "asc";
    public $selectedFromDate = "";
    public $selectedToDate = "";
    public $selectedMinPrice = "";
    public $selectedMaxPrice = "";
    public $quantities = [0,10,20,50];
    public $selectedStock = "";

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount(Request $request)
    {
        $this->colors = Color::all();
        $this->sizes = Size::all();
        $this->selectedColumns = $this->columns;
        $this->categories = Category::orderBy('name')->get();
        $this->subcategories = SubCategory::orderBy('name')->get();
        $this->brands = Brand::orderBy('name')->get();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sort($column)
    {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function updatingSelectedSubcategories()
    {
        $this->resetPage();
    }

    public function updatingSelectedFromDate()
    {
        $this->resetPage();
    }

    public function updatingSelectedToDate()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategories()
    {
        $this->resetPage();
    }

    public function updatingSelectedMinPrice()
    {
        $this->resetPage();
    }

    public function updatingSelectedMaxPrice()
    {
        $this->resetPage();
    }

    public function updatingSelectedStock()
    {
        $this->resetPage();
    }

    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }

    public function render()
    {
        $products = Product::orderBy($this->sortColumn, $this->sortDirection)->where('name', 'LIKE', "%{$this->search}%")
            ->when($this->selectedSubcategories, function($query) {
            return $query->where('subcategory_id', $this->selectedSubcategories);
        })->when($this->selectedBrands, function($query) {
                return $query->where('brand_id', $this->selectedBrands);
            })->when($this->selectedFromDate, function($query) {
                return $query->where('created_at', '>=', $this->selectedFromDate);
            })->when($this->selectedToDate, function($query) {
                return $query->where('created_at', '<=', $this->selectedToDate);
            })->when($this->selectedMinPrice, function($query) {
                return $query->where('price', '>=', $this->selectedMinPrice);
            })->when($this->selectedMaxPrice, function($query) {
                return $query->where('price', '<=', $this->selectedMaxPrice);
            })->when($this->selectedStock, function($query) {
                return $query->where('quantity', '>=', $this->selectedStock);
            })->paginate($this->per_page);

        return view('livewire.admin.show-products2', compact('products'))->layout('layouts.admin');
    }
}

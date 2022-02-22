<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Models\Sortable;
use App\Models\Subcategory;
use App\ProductFilter;
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
    public $category = 'all';
    public $categories;
    public $brand = 'all';
    public $brands;
    public $subcategories;
    public $subcategory = "all";
    public $sortColumn = "id";
    public $sortDirection = "desc";
    public $from;
    public $to;
    public $minPrice;
    public $maxPrice;
    public $quantities = [0,10,20,50];
    public $stock;
    public $colorsf = "";
    public $selectedColors = [];
    public $searchSize = "";
    public $originalUrl;
    public $order;


    protected $queryString = [
        'search' => ['except' => ''],
        'from' => ['except' => ''],
        'to' => ['except' => ''],
        'searchSize' => ['except' => ''],
        'subcategory' => ['except' => 'all'],
        'category' => ['except' => 'all'],
        'brand' => ['except' => 'all'],
        'minPrice' => ['except' => ''],
        'maxPrice' => ['except' => ''],
        'stock' => ['except' => ''],
        'selectedColors' => [],
        'sortColumn' => [],
        'sortDirection' => [],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFrom()
    {
        $this->resetPage();
    }

    public function updatingBrand()
    {
        $this->resetPage();
    }

    public function updatingTo()
    {
        $this->resetPage();
    }

    public function updatingSearchSize()
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
        $this->colorsf = Color::pluck('name', 'id')->toArray();
        $this->originalUrl = $request->url();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingSubcategory()
    {
        $this->resetPage();
    }

    public function updatingSelectedColors()
    {
        $this->resetPage();
    }

    public function updatingSortColumn()
    {
        $this->resetPage();
    }

    public function updatingSortDirection()
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

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingMinPrice()
    {
        $this->resetPage();
    }

    public function updatingMaxPrice()
    {
        $this->resetPage();
    }

    public function updatingOrder()
    {
        $this->resetPage();
    }

    public function updatingStock()
    {
        $this->resetPage();
    }

    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }

    public function changeOrder($order)
    {
        $this->order = $order;
    }

    protected function getProducts(ProductFilter $productFilter)
    {
        $products = Product::query()->filterBy($productFilter, array_merge(
                ['search' => $this->search,
                    'from' =>  $this->from,
                    'to' =>  $this->to,
                    'searchSize' => $this->searchSize,
                    'subcategory' => $this->subcategory,
                    'category' => $this->category,
                    'brand' => $this->brand,
                    'minPrice' => $this->minPrice,
                    'maxPrice' => $this->maxPrice,
                    'stock' => $this->stock,
                    'selectedColors' => $this->selectedColors,

                ]
            ))->join('subcategories','subcategories.id','products.subcategory_id')
                ->join('categories', 'subcategories.category_id', 'categories.id')
            ->select('products.*')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->per_page);

        $products->appends($productFilter->valid());

        return $products;
    }

    public function render(ProductFilter $productFilter)
    {

        $sortable = new Sortable($this->originalUrl);
        return view('livewire.admin.show-products2', ['products' => $this->getProducts($productFilter), 'sortable' => $sortable,'selectedColors' => collect(request('selectedColors'))])->layout('layouts.admin');
    }
}

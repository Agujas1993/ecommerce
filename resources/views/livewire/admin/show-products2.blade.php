<div>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-semibold text-xl text-gray-600 leading-tight">
                Lista de productos
            </h2>
            <x-button-link class="ml-auto" href="{{route('admin.products.create')}}">
                Agregar producto
            </x-button-link>
        </div>
    </x-slot>
    <x-table-responsive>
        <div x-data="{ open: false }">
        <select wire:model="per_page" class="bg-gray-200">
            <option value="10">Mostrar 10</option>
            <option value="15">Mostrar 15</option>
            <option value="20">Mostrar 20</option>
            <option value="50">Mostrar 50</option>
        </select>

        <b><button @click="open = !open" class="mt-4 mb-2 form-control bg-blue-400 p-2">Mostrar/Ocultar columnas</button></b>
        <div x-show="open">
        @foreach($columns as $column)
            <input type="checkbox" wire:model="selectedColumns" value="{{$column}}" class="form-control inline-block ml-4">
            <label>{{$column}}</label>
        @endforeach
        </div>
        </div>
        <div x-data="{ open: false }">
            <b><button @click="open = !open" class="mt-4 mb-2 form-control bg-blue-400 p-2">Filtros</button></b>
            <div x-show="open">
                <div class="px-6 py-4">
                    <label class="ml-4"><b>
                            Producto:
                        </b>
                    </label>
                    <x-jet-input class="w-96"
                                 wire:model="search"
                                 type="text"
                                 placeholder="Introduzca el nombre del producto a buscar" />
                    <label class="ml-2"><b>
                            Categoría:
                        </b>
                    </label>
                    <select wire:model="selectedCategories">
                        <option value="" selected disabled>Seleccionar una Categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>

                    <label class="ml-2"><b>
                            Subcategorías:
                        </b>
                    </label>
                    <select wire:model="subcategory">
                        <option value="all" selected disabled>Seleccionar una subcategoría</option>
                        @foreach($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="ml-2"><b>
                            Marcas:
                        </b>
                    </label>
                <select wire:model="selectedBrands">
                    <option value="" selected disabled>Seleccionar una marca</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>

                    <label class="ml-2"><b>
                            Desde:
                        </b>
                    </label>
                <input type="text" id="from" wire:model="from"/>
                    <label class="ml-2"><b>
                            Hasta:
                        </b>
                    </label>
                <input type="text" id="to" wire:model="to"/>
                    <label class="ml-2"><b>
                            Precio:
                        </b>
                    </label>
                    <input type="text" size="10" placeholder="Precio mínimo" wire:model="selectedMinPrice"/>
                    <input type="text" size="10" placeholder="Precio máximo" wire:model="selectedMaxPrice"/>
                </div>
                <div class="mt-2">
                <span class="ml-2"><b>Stock: </b></span>
                @foreach($quantities as $stock)
                    <label for="">{{ $stock . "+" }}</label>
                <input type="radio" name="stock" class="mr-2" wire:model="selectedStock" value="{{ $stock }}">
                @endforeach
                    <span x-data="{ open: false }" @click.away="open = false">
                        <input type="radio" name="stock"  @click="open = !open" >Otro</button>
                        <span x-show="open">
                    <input type="text" class="mr-2" wire:model="selectedStock" value="{{ $stock }}">
                        </span>
                        </span>
                    <label class="ml-4"><b>
                            Colores:
                        </b>
                    </label>
                    @foreach($colorsf as $color_id => $color_name)
                        <label for="">{{ __(ucfirst($color_name)) }}</label>
                        <input type="checkbox" name="color" class="mr-2" wire:model="selectedColors" value="{{ $color_id }}">
                    @endforeach
                    <label class="ml-4"><b>
                            Tallas:
                        </b>
                    </label>
                    <x-jet-input class="w-96"
                                 wire:model="searchSize"
                                 type="text"
                                 placeholder="Introduzca el nombre de la talla a buscar" />

                </div>
        </div>
        @if($products->count())
            <table class="min-w-full divide-y divide-gray-200 overflow-x-auto block whitespace-nowrap">
                <thead class="bg-gray-50">
                <tr>
                    @if($this->showColumn('Id'))
                        <th scope="col" wire:click="sort('products.id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                           <button>ID</button>
                        </th>
                    @endif

                    @if($this->showColumn('Nombre'))
                    <th scope="col" wire:click="sort('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button>NOMBRE</button>
                    </th>
                    @endif
                        @if($this->showColumn('Slug'))
                            <th scope="col" wire:click="sort('slug')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <button>SLUG</button>
                            </th>
                        @endif
                        @if($this->showColumn('Descripción'))
                            <th scope="col"  wire:click="sort('description')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <button>DESCRIPCIÓN</button>
                            </th>
                        @endif
                        @if($this->showColumn('Categoría'))
                    <th scope="col"  wire:click="sort('')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button>CATEGORÍA</button>
                    </th>
                        @endif
                        @if($this->showColumn('Estado'))
                    <th scope="col" wire:click="sort('status')"  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button>ESTADO</button>
                    </th>
                        @endif
                        @if($this->showColumn('Stock'))
                            <th scope="col" wire:click="sort('quantity')"  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <button>STOCK</button>
                            </th>
                        @endif
                        @if($this->showColumn('Precio'))
                    <th scope="col" wire:click="sort('price')"  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button>PRECIO</button>
                    </th>
                        @endif
                        @if($this->showColumn('Subcategoría'))
                    <th scope="col" wire:click="sort('subcategory_id')"  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button>SUBCATEGORÍA</button>
                    </th>
                        @endif
                        @if($this->showColumn('Marca'))
                    <th scope="col" wire:click="sort('brand_id')"  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button>MARCA</button>
                    </th>
                        @endif
                        @if($this->showColumn('Fecha creación'))
                    <th scope="col" wire:click="sort('created_at')"  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button>FECHA CREACIÓN</button>
                    </th>
                        @endif
                        @if($this->showColumn('Colores'))
                    <th scope="col" wire:click="sort('colors')"  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button>COLORES</button>
                    </th>
                        @endif
                        @if($this->showColumn('Tallas'))
                    <th scope="col" wire:click="sort('sizes.id')"  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button>TALLAS</button>
                    </th>
                        @endif
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($products as $product)
                    <tr>
                        @if($this->showColumn('Id'))
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $product->id }}
                            </td>
                        @endif
                        @if($this->showColumn('Nombre'))
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 object-cover">
                                    <img class="h-10 w-10 rounded-full" src="{{ $product->images->count() ? Storage::url($product->images->first()->url) : 'img/default.jpg' }}" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $product->name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        @endif
                            @if($this->showColumn('Slug'))
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $product->slug }}</div>
                                </td>
                            @endif
                            @if($this->showColumn('Descripción'))
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $product->description }}</div>
                                </td>
                            @endif
                            @if($this->showColumn('Categoría'))
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $product->subcategory->category->name }}</div>
                        </td>
                            @endif
                            @if($this->showColumn('Estado'))
                        <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $product->status == 1 ? 'red' : 'green'
}}-100 text-{{ $product->status == 1 ? 'red' : 'green' }}-800">
                                {{ $product->status == 1 ? 'Borrador' : 'Publicado' }}
                                </span>
                        </td>
                            @endif
                            @if($this->showColumn('Stock'))

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($product->quantity !== null)
                                    {{ $product->quantity }}
                                    @else
                                        Tiene color y/o talla
                                    @endif
                                </td>
                            @endif
                            @if($this->showColumn('Precio'))
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $product->price }} &euro;
                        </td>
                            @endif
                            @if($this->showColumn('Subcategoría'))
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $product->subcategory->name }}
                        </td>
                            @endif
                            @if($this->showColumn('Marca'))
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ ucfirst($product->brand->name) }}
                        </td>
                            @endif
                            @if($this->showColumn('Fecha creación'))
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $product->created_at }}
                        </td>
                            @endif
                            @if($this->showColumn('Colores'))
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($product->colors->count())
                            @foreach($product->colors as $color)
                            <span>{{ __(ucfirst($colors->find($color)->name)). ': ' . $color->pivot->quantity . ', '}}</span>
                            @endforeach
                            @else
                            <span>Sin color</span>
                                @endif
                        </td>
                            @endif
                            @if($this->showColumn('Tallas'))
                        <td class="px-6 py-4 text-left text-sm font-medium">
                            @if($product->sizes->count())
                                @foreach($product->sizes as $size)
                                <span><b>{{ $size->name . ': '}}</b></span>
                                    @foreach($size->colors as $color)
                                    <span>{{__(ucfirst($color->name)) . '(' . $color->pivot->quantity . ')' }}</span>
                                @endforeach
                            @endforeach
                            @else
                                <span>Sin talla</span>
                            @endif
                        </td>
                            @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="px-6 py-4">
                No existen productos coincidentes
            </div>
        @endif
        @if($products->hasPages())
            <div class="px-6 py-4">
                {{ $products->links() }}
            </div>
        @endif
    </x-table-responsive>
</div>
</div>
</div>
</div>

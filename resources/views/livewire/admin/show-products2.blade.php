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
    <div class="p-4">
        <div x-data="{ open: false }">
        <select wire:model="per_page" class="bg-gray-200" name="paginate">
            <option value="10">Mostrar 10</option>
            <option value="15">Mostrar 15</option>
            <option value="20">Mostrar 20</option>
            <option value="50">Mostrar 50</option>
        </select>

        <b><button @click="open = !open" class="mt-4 mb-2 form-control bg-blue-400 p-2">Mostrar/Ocultar columnas</button></b>
        <div x-show="open" x-cloak>
        @foreach($columns as $column)
            <input type="checkbox" wire:model="selectedColumns" value="{{$column}}" class="form-control inline-block ml-4">
            <label>{{$column}}</label>
        @endforeach
        </div>
        </div>
        <div x-data="{ open: false }" >
            <b><button @click="open = !open" class="mt-4 mb-2 form-control bg-blue-400 p-2">Filtros</button></b>
            <div x-show="open" x-cloak>
        @include('shared._filters')
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
                    <th scope="col" wire:click="sort('products.name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button>NOMBRE</button>
                    </th>
                    @endif
                        @if($this->showColumn('Slug'))
                            <th scope="col" wire:click="sort('products.slug')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <button>SLUG</button>
                            </th>
                        @endif
                        @if($this->showColumn('Descripción'))
                            <th scope="col"  wire:click="sort('description')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <button>DESCRIPCIÓN</button>
                            </th>
                        @endif
                        @if($this->showColumn('Categoría'))
                    <th scope="col"  wire:click="sort('categories.name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                    <th scope="col" wire:click="sort('subcategories.name')"  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button>SUBCATEGORÍA</button>
                    </th>
                        @endif
                        @if($this->showColumn('Marca'))
                    <th scope="col" wire:click="sort('brands.name')"  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button>MARCA</button>
                    </th>
                        @endif
                        @if($this->showColumn('Fecha creación'))
                    <th scope="col" wire:click="sort('created_at')"  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button>FECHA CREACIÓN</button>
                    </th>
                        @endif
                        @if($this->showColumn('Colores'))
                    <th scope="col" wire:click="sort('colors.name')"  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button>COLORES</button>
                    </th>
                        @endif
                        @if($this->showColumn('Tallas'))
                    <th scope="col" wire:click="sort('sizes.name')"  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                                <td class=" px-6 py-4  text-sm text-gray-500">
                            <span>
                                <div x-data="{ open: false }">
                     <button class="whitespace-pre-wrap text-left"  @click="open = ! open">
                         {{ strlen($product->description) >= 30 ? (substr($product->description,0,20)):
                                ($product->description)}}</button >
                                    <span x-show="open" x-transition x-cloak>
                            {{ (substr($product->description,20)) }}
                                            </span>
                                            </div>
                            </span>
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
                                    @if($product->getStockAttribute() !== 0)
                                    {{ $product->getStockAttribute() }}
                                    @else
                                        Sin stock
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
            <div class="px-4 py-2">
                No existen productos coincidentes
            </div>
        @endif
        @if($products->hasPages())
            <div class="px-6 py-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                flatpickr('.dateFlatpicker', {
                    enableTime: false,
                    dateFormat: 'd/m/Y',
                    altInput: true,
                    altFormat: 'd/m/Y',
                    time_24hr: true,
                    allowInput: true,
                });
            });
        </script>
    @endpush
</div>






<?php

namespace Tests;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Support\Str;

trait TestHelpers
{

    public function withData(array $custom = [])
    {
        return array_merge($this->defaultData(), $custom);
    }

    protected function defaultData()
    {
        return $this->defaultData;
    }

    public function createCategory()
    {
        return Category::factory()->create(['name' => 'Celulares y tablets',
            'slug' => Str::slug('Celulares y tablets'),
            'icon' => '<i class="fas fa-mobile-alt"></i>']);
    }

    public function createSubcategory()
    {
        return Subcategory::create([
                'category_id' => 1,'name' => 'Celulares y smartphones',
                'slug' => Str::slug('Celulares y smartphones'),
            ]
        );
    }

    public function createColorSubcategory()
    {
        return Subcategory::create([
                'category_id' => 1,'name' => 'Celulares y smartphones',
                'slug' => Str::slug('Celulares y smartphones'),
                'color' => true
            ]
        );
    }

    public function createColorSizeSubcategory()
    {
        return Subcategory::create([
                'category_id' => 1,'name' => 'Celulares y smartphones',
                'slug' => Str::slug('Celulares y smartphones'),
                'color' => true, 'size'=> true
            ]
        );
    }

    public function createBrand()
    {
        $category = $this->createCategory();
        return $category->brands()->create(['name' => 'LG']);
    }

    public function createUser()
    {
        return User::factory()->create([
            'name' => 'Samuel Garcia',
            'email' => 'samuel@test.com',
            'password' => bcrypt('123'),
        ]);
    }

    public function createProduct()
    {
        $category = $this->createCategory();

        $subcategory = $this->createSubcategory();

        $brand = $category->brands()->create(['name' => 'LG']);
        return Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '20',
            'status' => 2
        ]);
    }

    public function createProducts($products)
    {
        return Product::factory($products)->create()->each(function (Product $product) {
            Image::factory(1)->create(['imageable_id' => $product->id, 'imageable_type' => Product::class]);
        });
    }

    public function createColorProduct()
    {
        $category = $this->createCategory();

        $subcategory = $this->createColorSubcategory();

        $brand = $category->brands()->create(['name' => 'LG']);
        return Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '20',
            'status' => 2
        ]);
    }

    public function createColorSizeProduct()
    {
        $category = $this->createCategory();

        $subcategory = $this->createColorSizeSubcategory();

        $brand = $category->brands()->create(['name' => 'LG']);
        return Product::factory()->create([
            'name' => 'Tablet LG2080',
            'slug' => Str::slug('Tablet LG2080'),
            'description' => 'Tablet LG2080' . 'moderno año 2022',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '118.99',
            'quantity' => '20',
            'status' => 2
        ]);
    }
}

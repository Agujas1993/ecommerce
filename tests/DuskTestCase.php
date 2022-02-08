<?php

namespace Tests;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Str;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        if (! static::runningInSail()) {
            static::startChromeDriver();
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
{

    $options = (new ChromeOptions)->addArguments(collect([
        '—window-size=1920,1080',
        ])->unless($this->hasHeadlessDisabled(), function ($items) {
            return $items->merge([
                "start-maximized",
                'headless',
                'disable-gpu',
                '-no-sandbox',
                '--disable-dev-shm-usage',
                '--window-size=1920,1080',
            ]);
    })->all());

    return RemoteWebDriver::create(
        $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(
            ChromeOptions::CAPABILITY, $options
        )
    );
}

    /**
     * Determine whether the Dusk command has disabled headless mode.
     *
     * @return bool
     */
    protected function hasHeadlessDisabled()
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
               isset($_ENV['DUSK_HEADLESS_DISABLED']);
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

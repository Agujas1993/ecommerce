<?php

namespace Tests;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
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

    public function createProduct()
    {
        $this->createCategory();

        $subcategory = Subcategory::factory()->create(['category_id' => 1,
            'name' => 'Tablets',
            'slug' => Str::slug('Tablets'),
        ]);

        $brand = Brand::factory()->create(['name' => 'LG']);

        return Product::factory()->create([
            'name' => 'Tablet LG',
            'slug' => 'tablet-lg',
            'description' => 'tablet lg 4/64',
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'price' => '262.99',
            'quantity' => '20',
            'status' => 2
        ]);
    }
}

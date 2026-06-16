<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Occasion;
use App\Models\Fabric;

class GenerateSlugsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:generate-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate missing slugs for Products, Occasions, and Fabrics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating slugs for Products...');
        $products = Product::whereNull('slug')->get();
        foreach ($products as $product) {
            $product->slug = Str::slug($product->name);
            $product->save();
        }
        $this->info('Products updated: ' . $products->count());

        $this->info('Generating slugs for Occasions...');
        $occasions = Occasion::whereNull('slug')->get();
        foreach ($occasions as $occasion) {
            $occasion->slug = Str::slug($occasion->name);
            $occasion->save();
        }
        $this->info('Occasions updated: ' . $occasions->count());

        $this->info('Generating slugs for Fabrics...');
        $fabrics = Fabric::whereNull('slug')->get();
        foreach ($fabrics as $fabric) {
            $fabric->slug = Str::slug($fabric->name);
            $fabric->save();
        }
        $this->info('Fabrics updated: ' . $fabrics->count());

        $this->info('All missing slugs generated successfully.');
    }
}

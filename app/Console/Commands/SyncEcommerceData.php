<?php

namespace App\Console\Commands;

use App\Models\EcommerceDeptConfiguratorConfig;
use App\Models\EcommerceDeptPrebuiltConfig;
use App\Models\EcommerceDeptProduct;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncEcommerceData extends Command
{
    protected $signature = 'sync:ecommerce';

    protected $description = 'Sync Ecommerce department data';

    public function handle(): int
    {
        $this->info('Syncing ecommerce_dept -> local tables...');

        $products = DB::connection('ecommerce_dept')
            ->table('products')
            ->get();

        foreach ($products as $row) {
            EcommerceDeptProduct::updateOrCreate(
                [
                    'source_id' => $row->id,
                ],
                [
                    'name' => $row->name,
                    'category' => $row->category,
                    'brand' => $row->brand,
                    'processor' => $row->processor,
                    'specs' => $row->specs,
                    'price' => $row->price,
                    'original_price' => $row->original_price,
                    'rating' => $row->rating,
                    'image_url' => $row->image_url,
                    'badge' => $row->badge,
                    'tag' => $row->tag,
                    'os' => $row->os,
                    'cpu' => $row->cpu,
                    'gpu' => $row->gpu,
                    'ram' => $row->ram,
                    'storage' => $row->storage,
                    'motherboard' => $row->motherboard,
                    'psu' => $row->psu,
                    'case' => $row->case,
                    'cooler' => $row->cooler,
                    'is_sold_out' => $row->is_sold_out,
                    'forge_points' => $row->forge_points,
                    'shipping_status' => $row->shipping_status,
                    'promo_tag' => $row->promo_tag,
                    'source_created_at' => $row->created_at,
                    'source_updated_at' => $row->updated_at,
                ]
            );
        }

        $this->info("Synced {$products->count()} products.");

        $prebuilt = DB::connection('ecommerce_dept')
            ->table('prebuilt_configs')
            ->get();

        foreach ($prebuilt as $row) {
            EcommerceDeptPrebuiltConfig::updateOrCreate(
                [
                    'source_id' => $row->id,
                ],
                [
                    'name' => $row->name,
                    'description' => $row->description,
                    'price' => $row->price,
                    'image_url' => $row->image_url,
                    'cpu_id' => $row->cpu_id,
                    'gpu_id' => $row->gpu_id,
                    'motherboard_id' => $row->motherboard_id,
                    'ram_id' => $row->ram_id,
                    'storage_id' => $row->storage_id,
                    'power_supply_id' => $row->power_supply_id,
                    'pc_case_id' => $row->pc_case_id,
                    'cooler_id' => $row->cooler_id,
                    'source_created_at' => $row->created_at,
                    'source_updated_at' => $row->updated_at,
                ]
            );
        }

        $this->info("Synced {$prebuilt->count()} prebuilt configs.");

        $configs = DB::connection('ecommerce_dept')
            ->table('configurator_configs')
            ->get();

        foreach ($configs as $row) {
            EcommerceDeptConfiguratorConfig::updateOrCreate(
                [
                    'source_id' => $row->id,
                ],
                [
                    'name' => $row->name,
                    'description' => $row->description,
                    'price' => $row->price,
                    'image_url' => $row->image_url,
                    'platform' => $row->platform,
                    'tier' => $row->tier,
                    'cpu_id' => $row->cpu_id,
                    'gpu_id' => $row->gpu_id,
                    'motherboard_id' => $row->motherboard_id,
                    'ram_id' => $row->ram_id,
                    'storage_id' => $row->storage_id,
                    'power_supply_id' => $row->power_supply_id,
                    'pc_case_id' => $row->pc_case_id,
                    'cooler_id' => $row->cooler_id,
                    'rating' => $row->rating,
                    'review_count' => $row->review_count,
                    'source_created_at' => $row->created_at,
                    'source_updated_at' => $row->updated_at,
                ]
            );
        }

        $this->info("Synced {$configs->count()} configurator configs.");

        $this->info('Ecommerce sync complete.');

        return self::SUCCESS;
    }
}
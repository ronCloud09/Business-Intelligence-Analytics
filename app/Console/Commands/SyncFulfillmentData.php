<?php

namespace App\Console\Commands;

use App\Models\FulfillmentDeliveryMan;
use App\Models\FulfillmentOrder;
use App\Models\FulfillmentPackingError;
use App\Models\FulfillmentPackingMaterial;
use App\Models\FulfillmentShipment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncFulfillmentData extends Command
{
    protected $signature = 'sync:fulfillment';

    protected $description = 'Pull orders, shipments, delivery men, packing errors, and packing materials from the Order Fulfillment department database';

    public function handle(): int
    {
        $this->info('Syncing fulfillment_dept -> local tables...');

        $orders = DB::connection('fulfillment_dept')->table('orders')->get();
        foreach ($orders as $row) {
            FulfillmentOrder::updateOrCreate(
                ['source_id' => $row->id],
                [
                    'customer_name' => $row->customer_name,
                    'product_name' => $row->product_name,
                    'qty' => $row->qty,
                    'status' => $row->status,
                    'due_date' => $row->due_date,
                    'address' => $row->address,
                    'amount' => $row->amount,
                    'source_created_at' => $row->created_at,
                    'source_updated_at' => $row->updated_at,
                ]
            );
        }
        $this->info("Synced {$orders->count()} orders.");

        $shipments = DB::connection('fulfillment_dept')->table('shipments')->get();
        foreach ($shipments as $row) {
            FulfillmentShipment::updateOrCreate(
                ['source_id' => $row->id],
                [
                    'shipment_id' => $row->shipment_id,
                    'order_id' => $row->order_id,
                    'customer_name' => $row->customer_name,
                    'product_name' => $row->product_name,
                    'qty' => $row->qty,
                    'courier' => $row->courier,
                    'box_used' => $row->box_used,
                    'tracking_number' => $row->tracking_number,
                    'status' => $row->status,
                    'address' => $row->address,
                    'due_date' => $row->due_date,
                    'amount' => $row->amount,
                    'source_created_at' => $row->created_at,
                    'source_updated_at' => $row->updated_at,
                ]
            );
        }
        $this->info("Synced {$shipments->count()} shipments.");

        $deliveryMen = DB::connection('fulfillment_dept')->table('delivery_men')->get();
        foreach ($deliveryMen as $row) {
            FulfillmentDeliveryMan::updateOrCreate(
                ['source_id' => $row->id],
                [
                    'age' => $row->age,
                    'license_num' => $row->license_num,
                    'vehicle_type' => $row->vehicle_type,
                    'shipping_provider_id' => $row->shipping_provider_id,
                ]
            );
        }
        $this->info("Synced {$deliveryMen->count()} delivery men.");

        $packingErrors = DB::connection('fulfillment_dept')->table('packing_errors')->get();
        foreach ($packingErrors as $row) {
            FulfillmentPackingError::updateOrCreate(
                ['source_id' => $row->id],
                [
                    'order_id' => $row->order_id,
                    'material' => $row->material,
                    'reason' => $row->reason,
                    'source_created_at' => $row->created_at,
                    'source_updated_at' => $row->updated_at,
                ]
            );
        }
        $this->info("Synced {$packingErrors->count()} packing errors.");

        $packingMaterials = DB::connection('fulfillment_dept')->table('packing_materials')->get();
        foreach ($packingMaterials as $row) {
            FulfillmentPackingMaterial::updateOrCreate(
                ['source_id' => $row->id],
                [
                    'name' => $row->name,
                    'stock_qty' => $row->stock_qty,
                    'low_stock_threshold' => $row->low_stock_threshold,
                    'is_box' => $row->is_box,
                    'box_size' => $row->box_size,
                ]
            );
        }
        $this->info("Synced {$packingMaterials->count()} packing materials.");

        $this->info('Fulfillment sync complete.');

        return self::SUCCESS;
    }
}

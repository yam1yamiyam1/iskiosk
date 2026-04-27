<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Shop;
use App\Models\ActivityLog;
use App\Models\ActivityLogProductShop;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToCollection, WithHeadingRow
{
    protected $shopId;
    public $updated = 0;

    public function __construct($shopId = null)
    {
        $this->shopId = $shopId;
    }

    public function collection(Collection $rows)
    {
        $user = Auth::user();

        $activity = null;

        foreach ($rows as $row) {
            $productId = $row['productid'];
            $product = Product::find($productId);

            if (!$product) {
                continue;
            }

            foreach (Shop::all() as $shop) {
                $normalizedSlug = str_replace(['-', '_'], '', $shop->slug . 'quantity');

                foreach ($row as $key => $value) {
                    $normalizedKey = str_replace(['-', '_'], '', $key);

                    if ($normalizedSlug === $normalizedKey) {
                        $existingPivot = $product->shops()->find($shop->id)?->pivot;
                        $existingQuantity = $product->shops()->find($shop->id)?->pivot->quantity ?? 0;
                        $newQuantity = $value ?? 0;

                        if ($existingQuantity != $newQuantity) {

                            if ($existingPivot) {
                                $product->shops()->updateExistingPivot($shop->id, [
                                    'quantity' => $newQuantity
                                ]);
                            } else {
                                $product->shops()->attach($shop->id, [
                                    'quantity' => $newQuantity
                                ]);
                            }

                            $product->shops()->updateExistingPivot($shop->id, [
                                'quantity' => $newQuantity
                            ]);

                            if ($this->updated == 0) {
                                $activity = ActivityLog::create([
                                    'user_id' => $user?->id,
                                    'user_full_name' => $user ? "{$user->fname} {$user->lname} ({$user->email})" : 'System',
                                    'shop_id' => null,
                                    'module' => 5,
                                    'action' => 'Imported Product Quantity',
                                    'ip_address' => request()->ip(),
                                    'device' => request()->userAgent(),
                                    'description' => "Updated product quantity via Excel.",
                                ]);
                            }

                            $this->updated++;

                            ActivityLogProductShop::create([
                                'activity_log_id' => $activity?->id,
                                'product_id' => $product->id,
                                'shop_id' => $shop->id,
                                'new_quantity' => $newQuantity,
                                'old_quantity' => $existingQuantity,
                                'change_quantity' => $newQuantity - $existingQuantity,
                                'description' => 'Imported updated quantity.',
                            ]);
                        }

                        break;
                    }
                }
            }
        }
    }

}


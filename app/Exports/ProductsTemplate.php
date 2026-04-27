<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Shop;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsTemplate implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $shopId;
    protected $sortedShops;

    public function __construct($shopId = null)
    {
        $this->shopId = $shopId;

        if ($this->shopId && $this->shopId !== 'all') {
            $this->sortedShops = Shop::where('id', $this->shopId)->get();
        } else {
            $this->sortedShops = Shop::orderBy('slug')->get();
        }
    }

    public function collection()
    {
        $products = Product::with(['brand', 'shops'])->get();

        return $products->map(function ($product) {
            $data = [
                'ProductId' => $product->id,
                'ProductName' => $product->name,
                'Brand' => $product->brand->name ?? 'N/A',
                'SellingPrice' => $product->selling_price,
            ];

            foreach ($this->sortedShops as $shop) {
                $slug = 'restock-' . $shop->slug . '-quantity';
                $quantity = 0;
                $data[$slug] = $quantity;
            }

            return collect($data);
        });
    }

    public function headings(): array
    {
        $base = ['ProductId', 'ProductName', 'Brand', 'SellingPrice'];
        $shopHeaders = $this->sortedShops->map(fn($shop) => 'restock-' . $shop->slug . '-quantity')->toArray();
        return array_merge($base, $shopHeaders);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:Z1')->getAlignment()->setWrapText(false);
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

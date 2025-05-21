<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\BookFormat;
use Illuminate\Database\Seeder;

class InvoiceItemSeeder extends Seeder
{   
     public function run()
    {
        $invoices = Invoice::all();
        
        foreach ($invoices as $invoice) {
            // Mỗi hóa đơn có 1-5 sản phẩm
            $bookFormats = BookFormat::inRandomOrder()->limit(rand(1, 5))->get();
            
            foreach ($bookFormats as $bookFormat) {
                InvoiceItem::factory()->create([
                    'id' => fake()->uuid(),
                    'invoice_id' => $invoice->id,
                    'quantity' => rand(1, 3),
                    'price' => $bookFormat->price,
                ]);
            }
        }
    }
}

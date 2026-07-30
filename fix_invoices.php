<?php
App\Models\StockOut::whereNull('invoice_number')->get()->each(function($so) {
    $so->update(['invoice_number' => 'INV-OLD-' . str_pad($so->id, 4, '0', STR_PAD_LEFT)]);
});
echo "Fixed.\n";

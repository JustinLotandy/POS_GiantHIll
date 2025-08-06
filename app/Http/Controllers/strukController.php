<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class strukController extends Controller
{
    public function struk($id)
    {
       
        $trx = \App\Models\Transaction::with('items')->findOrFail($id);

        
        $items = collect($trx->items)->map(function ($item) {
            return [
                'item' => $item->product->name ?? '-', 
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
            ];
        });

        // Kirim ke view struk
        return view('pos.struk', [
            'transaction' => $trx,
            'items' => $items
        ]);
    }
}

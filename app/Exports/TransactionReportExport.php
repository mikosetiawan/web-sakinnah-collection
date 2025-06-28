<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionReportExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Transaction::with(['user', 'items.barang', 'items.jasa'])->get();
    }

    public function headings(): array
    {
        return [
            'Transaction ID',
            'User',
            'Items',
            'Total Price',
            'Status',
            'Date',
        ];
    }

    public function map($transaction): array
    {
        $items = $transaction->items->map(function ($item) {
            $name = $item->barang ? $item->barang->name : ($item->jasa ? $item->jasa->name : 'N/A');
            return $name . ' (Qty: ' . $item->quantity . ')';
        })->implode(', ');

        return [
            $transaction->id,
            $transaction->user ? $transaction->user->name : 'N/A',
            $items,
            number_format($transaction->total_price, 2),
            $transaction->status,
            $transaction->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
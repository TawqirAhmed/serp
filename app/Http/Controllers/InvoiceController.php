<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ApprovedSell;
use App\Models\Ledger;
use PDF;

class InvoiceController extends Controller
{
    public function invoice($bill_no)
    {
        $invoice = ApprovedSell::where('bill_no',$bill_no)->with('customer','invoiceBy','checkedBy','approvedBy','paymentMethod','soldItem')->first();

        $ledger = Ledger::where('bill_code',$invoice->bill_no)->with('paymentMethod')->first();

        $products = array();

        foreach ($invoice->soldItem as $key => $value) {
            $data = [
                'description'=> $value->product->item_description,
                'unit'=> $value->product->unit->name,
                'quantity'=> $value->quantity,
                'sell_price' => $value->sell_price,
                'total'=> $value->quantity * $value->sell_price,
            ];

            array_push($products, $data);
        }


        // dd($ledger);

        $data = [
            'invoice' => $invoice,
            'products' => $products,
            'customer' => $invoice->customer,
            'ledger' => [
                            'previous_balance' => ($ledger->balance - $ledger->credit) + $ledger->debit,
                            'paid'=> $ledger->debit,
                            'new_balance'=> $ledger->balance,
                        ],
            'payment' => [
                            'name' => $ledger->paymentMethod->name,
                            'note' => $ledger->note,
                         ],
        ];

        $pdf = PDF::loadView('livewire.sell.invoice', $data);


        return $pdf->stream('Invoice_no_'.$invoice->bill_no.'.pdf');
    }

    public function challan($bill_no)
    {
        $invoice = ApprovedSell::where('bill_no',$bill_no)->with('customer','invoiceBy','checkedBy','approvedBy','paymentMethod','soldItem')->first();

        $ledger = Ledger::where('bill_code',$invoice->bill_no)->with('paymentMethod')->first();

        $products = array();

        foreach ($invoice->soldItem as $key => $value) {
            $data = [
                'description'=> $value->product->item_description,
                'unit'=> $value->product->unit->name,
                'quantity'=> $value->quantity,
            ];

            array_push($products, $data);
        }


        // dd($ledger);

        $data = [
            'invoice' => $invoice,
            'products' => $products,
            'customer' => $invoice->customer,
        ];

        $pdf = PDF::loadView('livewire.sell.challan', $data);


        return $pdf->stream('Challan_no_'.$invoice->bill_no.'.pdf');
    }
}

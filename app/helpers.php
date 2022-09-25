<?php 

use App\Models\ApprovedSell;


if (! function_exists('stock_info')) {
    function stock_info($data)
    {
        $info = [
        	'stock_total' => 0,
        	'sold_total' => 0,
        ];

        foreach ($data as $key => $value) {
        	$info['stock_total'] = $info['stock_total'] + $value->stock;
        	$info['sold_total'] = $info['sold_total'] + $value->sold;
        }
 
        return $info;
    }
}



if (! function_exists('customer_profit')) {
    function customer_profit($id)
    {
        $data = ApprovedSell::where('customer_id',$id)->with('soldItem')->get();
        // dd($data);
        $profit = 0;

        foreach ($data as $data_key => $sold_item) {
            
            foreach ($sold_item->soldItem as $key => $value) {
                $profit = $profit + $value->profit;
            }

        }

        return $profit;
    }
}
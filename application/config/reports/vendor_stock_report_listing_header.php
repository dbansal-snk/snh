<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['report_columns']['vendor_stock_report_listing_header'] = array(
   'name' 				=> 	array(
       'name'           => 'Medicine',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	FALSE
	),
    
    'total_stock' 		=> 	array(
        'name'           => 'Quantity',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	FALSE
	),
    
    'free_item' => 	array(
        'name'           => 'Free Item Quantity',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	TRUE
	),
    
    'loose_item_quantity' 	=> 	array(
        'name'           => 'Loose Item',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	TRUE
	),
    
    'sold_quantity' 			=> 	array(
        'name'           => 'Sold Stock',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	FALSE
	),
    
    'price' 			=> 	array(
        'name'           => 'Purchased Amount',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	TRUE
	),
    
    'revenue' 			=> 	array(
        'name'           => 'Revenue',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	FALSE
	)
);
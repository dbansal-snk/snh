<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['report_columns']['medicine_stock_report_listing_header'] = array(
   'name' 				=> 	array(
       'name'           => 'Name',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	TRUE
	),
    
    'total_stock' 		=> 	array(
        'name'           => 'Total Stock',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	FALSE
	),
    
    'mrp' 				=> 	array(
        'name'           => 'M.R.P',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	TRUE
	),
    
    'remaining_stock' 	=> 	array(
        'name'           => 'Remaining Stock',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	FALSE
	),
    
    'revenue' 			=> 	array(
        'name'           => 'Revenue',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	FALSE
	),
);
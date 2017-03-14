<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['report_columns']['medicine_stock_report_listing_header'] = array(
   'name' 				=> 	array(
       'name'           => 'Medicine',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	TRUE,
        'defaultSort'       => TRUE,
        'defaultSortOrder'  => 'asc',
	),
    
    'batch' 			=> 	array(
        'name'           => 'Batch',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	TRUE
	),
    
    
    'mrp' 				=> 	array(
        'name'           => 'M.R.P',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	TRUE
	),
    
    'total_stock' 		=> 	array(
        'name'           => 'Total Stock',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	false,
        'isSortable'	=>	false
	),
    
    'remaining_stock' 	=> 	array(
        'name'           => 'Remaining Stock',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	FALSE,
        'isSortable'	=>	false
	),
    
    'revenue' 			=> 	array(
        'name'           => 'Revenue',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	False,
        'isSortable'	=>	false
	)
);
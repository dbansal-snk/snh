<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['report_columns']['medicine_listing_header'] = array(
   'name' 				=> 	array(
       'name'           => 'Name',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	TRUE
	),
    
    'description' 		=> 	array(
        'name'           => 'description',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	FALSE
	),
    
    'status' 				=> 	array(
        'name'           => 'status',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	FALSE
	)
);
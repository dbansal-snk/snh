<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['report_columns']['medicine_listing_header'] = array(
    'name'                  => 	array(
       'name'               => 'Name',
        'isColumn'          => TRUE,
        'isFilter'          =>	TRUE,
        'defaultSort'       => TRUE,
        'defaultSortOrder'  => 'asc',
        'isLink'            => TRUE,
        'linkUrl'           => 'index.php?pharmacist/manage_medicine_category/edit/',
        'additionDetails'   => 'medicine_category_id'
	),
    
    'description' 		=> 	array(
        'name'      => 'Description',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	FALSE
	),
    
    'status' 				=> 	array(
        'name'      => 'Status',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	FALSE
	),
     'medicine_category_id' => 	array(
        'name'      => 'id',
        'isColumn' 	=> FALSE,
        'isFilter'	=>	FALSE,
	)
);
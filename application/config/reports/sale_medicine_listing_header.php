<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['report_columns']['sale_medicine_listing_header'] = array(
    'patient_name'                  => 	array(
        'name'              => 'Patient',
        'isColumn'          => TRUE,
        'isFilter'          =>	TRUE,
        'defaultSort'       => TRUE,
        'defaultSortOrder'  => 'asc',
        'isLink'            => TRUE,
        'linkUrl'           => 'index.php?pharmacist/edit_sell/',
        'additionDetails'   => 'id'
	),
    
    'name' 		=> 	array(
        'name'      => 'Medicine',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	TRUE
	),
    
    'quantity' 				=> 	array(
        'name'      => 'Quantity',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	TRUE
	),
    
    'total_amount' => 	array(
        'name'      => 'Total Amount',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	TRUE,
	),
    
    'medicine_sold_date' => 	array(
        'name'      => 'Selling Date',
        'isColumn' 	=> TRUE,
        'isFilter'	=>	TRUE,
	),
    
    'id' => 	array(
        'name'      => 'Id',
        'isColumn' 	=> FALSE,
        'isFilter'	=>	FALSE,
	)
);
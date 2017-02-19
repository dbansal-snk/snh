<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class My_Controller extends CI_Controller
{

    const RECORDS_OFFSET = 50;
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * @mthod: get_pagination
     * @desc This fucntion fetched the table pagination with ordering
     * @return {Array}
     */
    public function get_pagination_details()
    {
        $result               = array();
        $result['start']      = 0;
        $result['sort_name']  = null;
        $result['sort_order'] = null;
        
        // get record's length
        $start                = $this->input->post('start');
        if (!empty($start)) {
            $result['start'] = $start;
        }
        
        $result['offset'] = self::RECORDS_OFFSET;
        
        // get sorting details
        $order_details    = $this->input->post('order');
        $columns_details  = $this->input->post('columns');
        if (is_array($order_details) && count($order_details) > 0) {
            if (isset($order_details[0]['column'])) {
                $result['sort_name']  = $columns_details[$order_details[0]['column']]['data'];
                $result['sort_order'] = $order_details[0]['dir'];
            }
        }
        
        return $result;
    }
}
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
        $post_data            = $this->input->post();
        
        // get record's length
        $result['start']      = $this->input->post('iDisplayStart');
        $result['offset']     = self::RECORDS_OFFSET;
        
        // get sorting details
        $result['sort_order']  = $this->input->post('sSortDir_0');
        $sort_column_index     = $this->input->post('iSortCol_0');
        $columns_count         = $this->input->post('columns');

        if (isset($post_data['mDataProp_' . $sort_column_index])) {
            $result['sort_name']  = $post_data['mDataProp_' . $sort_column_index];
        }
        
        return $result;
    }
}
<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class My_Controller extends CI_Controller
{

    const RECORDS_OFFSET = 50;
    public function __construct() {
        parent::__construct();
    }
    
    public function get_pagination()
    {
        $result          = array();
        $result['start'] = 0;
        $start           = $this->input->post('start');
        if (!empty($start)) {
            $result['start'] = $start;
        }
        
        $result['offset']   = self::RECORDS_OFFSET;
        
        return $result;
    }
}
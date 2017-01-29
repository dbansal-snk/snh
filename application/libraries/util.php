<?php

class Util
{
	private $CI;
    
    function __construct()
    {
        $this->CI = & get_instance();
    }
    
    function snh_date_format($date, $format = 'Y-m-d h:i:s')
    {
        $output = null;
        if (!empty($date)) {
            $output = date($format, strtotime($date));
        }
        
        return $output;
    }

}
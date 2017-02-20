<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_medicine_mrp')) {

	function get_medicine_mrp() {

		$CI	=&	get_instance();
		$CI->load->database();
        $CI->db->select('IF(is_loose_item = 1, mrp/loose_item_quantity, null ) as loose_item_mrp', false);
        $CI->db->select(array('mrp', 'medicine_category_id', 'is_loose_item'));
        $CI->db->where('is_latest_stock', 1);
        $data = $CI->db->get('medicine_stock')->result_array();
        log_message('error', $CI->db->last_query());
        return $data;
	}

    function get_medicine_list() {

		$CI	=&	get_instance();
		$CI->load->database();

        $CI->db->select(array('name', 'medicine_category_id'));
        $CI->db->where('status', 'IN_STOCK');
		$data = $CI->db->get('medicine_category')->result_array();
        return $data;
	}
    
    function get_vendors_list() {

		$CI	=&	get_instance();
		$CI->load->database();

        $CI->db->select(array('name', 'id'));
        $CI->db->where('status', 'ACTIVE');
		$data = $CI->db->get('vendors')->result_array();
        return $data;
	}
    
    function get_company_list() {

		$CI	=&	get_instance();
		$CI->load->database();

        $CI->db->select(array('name', 'id'));
        $CI->db->where('status', 'ACTIVE');
		$data = $CI->db->get('manufacture_company')->result_array();
        return $data;
	}
}
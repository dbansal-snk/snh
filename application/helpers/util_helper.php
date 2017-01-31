<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_medicine_mrp')) {

	function get_medicine_mrp() {

		$CI	=&	get_instance();
		$CI->load->database();
        $CI->db->select('IF(is_loose_item = 1, mrp/loose_item_quantity, null ) as loose_item_mrp', false);
        $CI->db->select(array('mrp', 'medicine_category_id'));
        $CI->db->where('is_latest_stock', 0);
        $data = $CI->db->get('medicine')->result_array();
        return $data;
	}

    function get_medicine_list() {

		$CI	=&	get_instance();
		$CI->load->database();

        $CI->db->select(array('name', 'medicine_category_id'));
		$data = $CI->db->get('medicine_category')->result_array();
        return $data;
	}
}
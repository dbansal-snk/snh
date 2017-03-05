<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	function get_medicine_mrp() {

		$CI	=&	get_instance();
		$CI->load->database();
        $CI->db->select('IF(is_loose_item = 1, mrp/loose_item_quantity, null ) as loose_item_mrp', false);
        $CI->db->select(array('mrp', 'medicine_category_id', 'is_loose_item', 'batch'));
        $CI->db->where('is_latest_stock', 1);
        $CI->db->where('batch' . ' IS NOT NULL');
        $CI->db->where('batch' . ' != ""');
        $CI->db->group_by('medicine_category_id');
        $CI->db->group_by('batch');
        $data = $CI->db->get('medicine_stock')->result_array();
        
        return $data;
	}

    function get_medicine_list() {

		$CI	=&	get_instance();
		$CI->load->database();

        $CI->db->select(array('name', 'medicine_category_id'));
        $CI->db->where('status', 'IN_STOCK');
        $CI->db->order_by('name', 'asc');
		$data = $CI->db->get('medicine_category')->result_array();
        return $data;
	}

    function get_vendors_list() {

        $CI	=&	get_instance();
        $CI->load->database();

        $CI->db->select(array('name', 'id'));
        $CI->db->where('status', 'ACTIVE');
        $CI->db->order_by('name', 'asc');

		$data = $CI->db->get('vendors')->result_array();
        return $data;
	}

    function get_company_list() {

		$CI	=&	get_instance();
		$CI->load->database();

        $CI->db->select(array('name', 'id'));
        $CI->db->where('status', 'ACTIVE');
								$CI->db->order_by("name", "ASC");

		$data = $CI->db->get('manufacture_company')->result_array();
        return $data;
	}
    
    function get_all_medicine_list() {

		$CI	=&	get_instance();
		$CI->load->database();

        $CI->db->select(array('name', 'medicine_category_id'));
        $CI->db->order_by('name', 'asc');
		$data = $CI->db->get('medicine_category')->result_array();
        return $data;
	}


<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pharmacist_model extends CI_Model
{

    private $_medicine_sale;
    private $_medicine_sale_details;
    private $_medicine_category;
    private $_medicine;
            
	function __construct()
    {
		parent::__construct();
        $this->_medicine_sale           = 'medicine_sale';
        $this->_medicine_sale_details   = 'medicine_sale_details';
        $this->_medicine_category       = 'medicine_category';
        $this->_medicine                = 'medicine';
	}
    
    public function get_sold_medicine_details()
    {
        $this->db->select('GROUP_CONCAT(' . $this->_medicine_category . '.name) as name');
        $this->db->select('GROUP_CONCAT(' . $this->_medicine_sale_details . '.quantity) as quantity');
        $this->db->select($this->_medicine_sale . '.*');
        $this->db->join($this->_medicine_sale_details, $this->_medicine_sale_details . '.medicine_sale_id = ' . $this->_medicine_sale . '.id', 'INNER');
        $this->db->join($this->_medicine_category, $this->_medicine_category . '.medicine_category_id = ' . $this->_medicine_sale_details . ' .medicine_id', 'INNER');
        $this->db->group_by($this->_medicine_sale_details . '.medicine_sale_id');
        $this->db->order_by($this->_medicine_sale . '.create_date', 'DESC');
        $data = $this->db->get($this->_medicine_sale)->result_array();
        
        return $data;
    }
    
    public function med_sold_to_patient($data)
    {
        $this->db->insert($this->_medicine_sale, $data);
        return $this->db->insert_id();
    }
    
    public function med_details_sold_to_patient($data)
    {
        $this->db->insert_batch($this->_medicine_sale_details, $data);
    }
    
    public function mark_obsolete_to_old_med_stock($medicine_cat_id)
    {
        if (!empty($medicine_id)) {
            $data = array('is_latest_stock' => 1);
            $this->db->where('medicine_category_id', $medicine_cat_id);
            $this->db->update($this->_medicine, $data);
        }
    }
    
    public function get_medicine_revenue()
    {
        $this->db->select(array($this->_medicine_category . '.name', $this->_medicine . '.loose_item_quantity'));
        $this->db->select('SUM(' . $this->_medicine . '.quantity) as total_stock');
        $this->db->select('SUM(' . $this->_medicine_sale_details . '.amount) as total_amount');
        $this->db->select('SUM(IF(' . $this->_medicine_sale_details . '.is_loose_sale = 0,' . $this->_medicine_sale_details . '.quantity, "0" )) as sold_stock', false);
        $this->db->select('SUM(IF(' . $this->_medicine_sale_details . '.is_loose_sale = 1,' . $this->_medicine_sale_details . '.quantity, "0" )) as loose_sold_stock', false);
        $this->db->join($this->_medicine, $this->_medicine . '.medicine_id = ' . $this->_medicine_category . '.medicine_category_id', 'LEFT');
        $this->db->join($this->_medicine_sale_details, $this->_medicine_sale_details . '.medicine_id = ' . $this->_medicine_category . ' .medicine_category_id', 'LEFT');
        $this->db->group_by($this->_medicine_sale_details . '.medicine_id');
        $data = $this->db->get($this->_medicine_category)->result_array();
        
        return $data;
    }
}
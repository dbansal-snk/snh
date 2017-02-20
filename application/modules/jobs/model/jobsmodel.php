<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class JobsModel extends CI_Model
{
    private $_table_medicine_stock;
    private $_table_medicine_category;
    private $_table_medicine_sale_details;
    
    public function __construct() {
        parent::__construct();
        $this->_table_medicine_stock    = 'medicine_stock';
        $this->_table_medicine_category = 'medicine_category';
        $this->_table_medicine_sale_details = 'medicine_sale_details';
    }
    
    /**
     * @desc: This function fetches the quantity of all the medicines purchased so far
     */
    public function get_medicines_purchased_stock()
    {
        $this->db->select('SUM(' . $this->_table_medicine_stock . '.quantity) as quantity');
        $this->db->select(array('medicine_category_id'));
        $this->db->join($this->_table_medicine_stock, $this->_table_medicine_stock . '.medicine_category_id = ' . $this->_table_medicine_category . '.medicine_category_id', 'INNER');
        $this->db->group_by($this->_table_medicine_category . '.medicine_category_id');
        $data = $this->db->get($this->_table_medicine_category)->result_array();
        
        return $data;
    }
    
    /**
     * @desc: This function fetches the quantity of all the medicines sold so far
     */
    public function get_medicines_sold_stock()
    {
        $this->db->select('SUM(quantity) as quantity');
        $this->db->select(array('medicine_id'));
        $this->db->group_by($this->_table_medicine_sale_details . '.medicine_id');
        $data = $this->db->get($this->_table_medicine_sale_details)->result_array();
        
        return $data;
    }
}
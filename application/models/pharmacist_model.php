<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pharmacist_model extends CI_Model
{

    private $_medicine_sale;
    private $_medicine_sale_details;
    private $_medicine_category;
    private $_medicine_stock;
    private $_table_vendors;
    private $_table_manufacture_company;
    const STATUS_ACTIVE = 'ACTIVE';
            
	function __construct()
    {
		parent::__construct();
        $this->_medicine_sale               = 'medicine_sale';
        $this->_medicine_sale_details       = 'medicine_sale_details';
        $this->_medicine_category           = 'medicine_category';
        $this->_medicine_stock              = 'medicine_stock';
        $this->_table_vendors               = 'vendors';
        $this->_table_manufacture_company   = 'manufacture_company';
	}
    
    public function get_sold_medicine_details($medicine_sale_id = null, $columns = array())
    {
        if (is_array($columns) && count($columns) > 0) {
            $this->db->select($columns);
        } else {
            $this->db->select('GROUP_CONCAT(' . $this->_medicine_category . '.name) as name');
            $this->db->select('GROUP_CONCAT(' . $this->_medicine_sale_details . '.quantity) as quantity');
            $this->db->select($this->_medicine_sale . '.*');
            $this->db->group_by($this->_medicine_sale_details . '.medicine_sale_id');
        }
        
        $this->db->join($this->_medicine_sale_details, $this->_medicine_sale_details . '.medicine_sale_id = ' . $this->_medicine_sale . '.id', 'INNER');
        $this->db->join($this->_medicine_category, $this->_medicine_category . '.medicine_category_id = ' . $this->_medicine_sale_details . ' .medicine_id', 'INNER');
        
        if (!empty($medicine_sale_id)) {
            $this->db->where($this->_medicine_sale . '.id', $medicine_sale_id);
            $this->db->join($this->_medicine_stock, $this->_medicine_stock . '.medicine_category_id = ' . $this->_medicine_sale_details . ' .medicine_id AND '
                . '' . $this->_medicine_stock . '.is_latest_stock = 1', 'INNER');
        
            $this->db->group_by($this->_medicine_sale_details . '.id');
        }
        
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
            $data = array('is_latest_stock' => 0);
            $this->db->where('medicine_category_id', $medicine_cat_id);
            $this->db->update($this->_medicine_stock, $data);
        }
    }
    
    public function get_medicine_revenue($medicine_ids = array())
    {
        $this->db->select(array($this->_medicine_category . '.name', $this->_medicine_stock . '.loose_item_quantity'));
        $this->db->select('SUM(' . $this->_medicine_stock . '.quantity) as total_stock');
        $this->db->select('SUM(' . $this->_medicine_stock . '.free_item) as total_free_item_stock');
        $this->db->select(array($this->_medicine_category . '.medicine_category_id'));
        $this->db->select('IF(' . $this->_medicine_stock . '.is_latest_stock = 1, ' . $this->_medicine_stock . '.mrp, 0) as mrp', false);
        $this->db->join($this->_medicine_stock, $this->_medicine_stock . '.medicine_category_id = ' . $this->_medicine_category . '.medicine_category_id', 'LEFT');
        if (is_array($medicine_ids) && count($medicine_ids) > 0) {
            $this->db->where_in($this->_medicine_stock . '.medicine_category_id', $medicine_ids);
        }
        
        $this->db->group_by($this->_medicine_category . '.medicine_category_id');
        $data = $this->db->get($this->_medicine_category)->result_array();
        
        return $data;
    }
    
    public function get_sold_stock_of_medicine($medicine_ids = array())
    {
        $this->db->select('SUM(quantity) as sold_stock'); // this is a sold stock
        $this->db->select(array('medicine_id'));
        if (is_array($medicine_ids) && count($medicine_ids) > 0) {
            $this->db->select($this->_medicine_category . '.name as name');
            $this->db->join($this->_medicine_category, $this->_medicine_category . '.medicine_category_id = ' . $this->_medicine_sale_details . '.medicine_id', 'INNER');
            $this->db->where_in($this->_medicine_sale_details . '.medicine_id', $medicine_ids);
        }
        
        $this->db->group_by($this->_medicine_sale_details . '.medicine_id');
        $data = $this->db->get($this->_medicine_sale_details)->result_array();
        
        return $data;
    }
    
    public function get_all_medicine_revenue()
    {
        $this->db->select('SUM(' . $this->_medicine_sale_details . '.amount) as total_amount');
        $data = $this->db->get($this->_medicine_sale_details)->result_array();
        
        return $data;
    }
    
    public function get_medicine_stock_details($medicine_id)
    {
        $data = array();
        if (!empty($medicine_id)) {
            $this->db->select(array('loose_item_quantity', 'is_loose_item'));
            $this->db->where('medicine_category_id', $medicine_id);
            $this->db->where('is_loose_item', 1);
            $this->db->where('is_latest_stock', 1);
            $data = $this->db->get($this->_medicine_stock)->result_array();
        }
    
        return $data;
    }
    
    public function get_vendors_list($id = null, $show_active_list = false) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        
        if (!empty($show_active_list)) {
            $this->db->where('status', self::STATUS_ACTIVE);
        }
        $this->db->order_by('name', 'asc');
        $data = $this->db->get($this->_table_vendors)->result_array();
        
        return $data;
    
    }
    
    public function add_vendor($data)
    {
        $this->db->insert($this->_table_vendors, $data);
    }
    
    public function delete_vendor($id)
    {
        if (!empty($id)) {
            $this->db->where('id', $id);
            $this->db->delete($this->_table_vendors);
        }
    }
    
    public function update_vendor_details($id, $data)
    {
        if (!empty($id)) {
            $this->db->where('id', $id);
            $this->db->update($this->_table_vendors, $data);
        }
    }
    
    public function delete_med_sold_to_patient($id)
    {
        if (!empty($id)) {
            $this->db->where('id', $id);
            $this->db->delete($this->_medicine_sale);
            
            $this->db->where('medicine_sale_id', $id);
            $this->db->delete($this->_medicine_sale_details);
        }
    }
    
    
     public function get_manufacture_company_list($id = null, $show_active_companies = false) {
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        
        if (!empty($show_active_companies)) {
            $this->db->where('status', self::STATUS_ACTIVE);
        }
        
        $this->db->order_by('name', 'asc');
        $data = $this->db->get($this->_table_manufacture_company)->result_array();
        
        return $data;
    
    }
    
    public function add_manufacture_company($data)
    {
        $this->db->insert($this->_table_manufacture_company, $data);
    }
    
    public function delete_manufacture_company($id)
    {
        if (!empty($id)) {
            $this->db->where('id', $id);
            $this->db->delete($this->_table_manufacture_company);
        }
    }
    
    public function update_manufacture_company($id, $data)
    {
        if (!empty($id)) {
            $this->db->where('id', $id);
            $this->db->update($this->_table_manufacture_company, $data);
        }
    }
    
    public function get_medicine_list()
    {
        $this->db->select('IF(status = "IN_STOCK", "In Stock", IF(status = "OUT_STOCK", "Out of Stock", "Not in Stock")) as status', false);
        $this->db->select(array('name', 'description', 'medicine_category_id'));
        $data = $this->db->get($this->_medicine_category)->result_array();
        
        return $data;
    }
    
    public function get_medicine_batch_list($medicine_id)
    {
        $data = array();
        if (!empty($medicine_id)) {
            $this->db->select('DISTINCT(batch) as batch');
            $this->db->where('medicine_category_id', $medicine_id);
            $data = $this->db->get($this->_medicine_stock)->result_array();
        }
        return $data;
    }
    
    public function get_med_manufacture_details($medicine_id)
    {
        $data = array();
        if (!empty($medicine_id)) {
            $this->db->select('DISTINCT(' . $this->_table_manufacture_company . '.id) as id');
            $this->db->select($this->_table_manufacture_company . '.name');
            $this->db->join($this->_table_manufacture_company, $this->_table_manufacture_company . '.id = ' . $this->_medicine_stock . '.manufacture_company_id', 'INNER');
            $this->db->where('medicine_category_id', $medicine_id);
            $this->db->where($this->_table_manufacture_company . '.status', self::STATUS_ACTIVE);
            $data = $this->db->get($this->_medicine_stock)->result_array();
        }

        return $data;
    }
    
    public function get_med_vendors_details($medicine_id)
    {
        $data = array();
        if (!empty($medicine_id)) {
            $this->db->select('DISTINCT(' . $this->_table_vendors . '.id) as id');
            $this->db->select($this->_table_vendors . '.name');
            $this->db->join($this->_table_vendors, $this->_table_vendors . '.id = ' . $this->_medicine_stock . '.vendor_id', 'INNER');
            $this->db->where('medicine_category_id', $medicine_id);
            $this->db->where($this->_table_vendors. '.status', self::STATUS_ACTIVE);
            $data = $this->db->get($this->_medicine_stock)->result_array();
        }
        
        return $data;
    }
}
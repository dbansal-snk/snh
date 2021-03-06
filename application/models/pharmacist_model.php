<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pharmacist_model extends MY_Model
{

    private $_medicine_sale;
    private $_medicine_sale_details;
    private $_medicine_category;
    private $_medicine_stock;
    private $_table_vendors;
    private $_table_manufacture_company;
    private $_table_doctor;
    
    const STATUS_ACTIVE                 = 'ACTIVE';
    const DUPLICATE_RECORDS_TIME_SPAN   = 30;
            
	function __construct()
    {
		parent::__construct();
        $this->_medicine_sale               = 'medicine_sale';
        $this->_medicine_sale_details       = 'medicine_sale_details';
        $this->_medicine_category           = 'medicine_category';
        $this->_medicine_stock              = 'medicine_stock';
        $this->_table_vendors               = 'vendors';
        $this->_table_manufacture_company   = 'manufacture_company';
        $this->_table_doctor                = 'doctor';
	}

    public function get_sold_medicine_details($medicine_sale_id = null, $columns = array(), $pagination = array(), $filters = array())
    {
        if (is_array($columns) && count($columns) > 0) {
            $this->db->select($columns);
        } else {
            $this->db->select('GROUP_CONCAT(' . $this->_medicine_category . '.name) as name');
            $this->db->select('GROUP_CONCAT(' . $this->_medicine_sale_details . '.quantity) as quantity');
            $this->db->select($this->_medicine_sale . '.*');
            $this->db->where_in($this->_medicine_sale_details . '.is_deleted','0');
            $this->db->group_by($this->_medicine_sale_details . '.medicine_sale_id');
        }
        
        $this->db->select('doctor.name as doctor_name');

        $this->db->join($this->_medicine_sale_details, $this->_medicine_sale_details . '.medicine_sale_id = ' . $this->_medicine_sale . '.id', 'INNER');
        $this->db->join($this->_medicine_category, $this->_medicine_category . '.medicine_category_id = ' . $this->_medicine_sale_details . ' .medicine_id', 'LEFT');
        $this->db->join($this->_table_doctor, $this->_table_doctor . '.doctor_id = ' . $this->_medicine_sale . ' .doctor_id', 'LEFT');

        $this->_get_medicine_sale_filters($filters);
        
        if (!empty($pagination)) {
            $this->db->limit($pagination['offset'], $pagination['start']);
        }
        
        if (!empty($pagination['sort_name']) && !empty($pagination['sort_order'])) {
            $this->db->order_by($pagination['sort_name'], $pagination['sort_order']);
        }
        
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
    
    public function get_sold_medicine_details_count($filters = array())
    {
        $this->db->select($this->_medicine_sale . '.id');
        $this->db->join($this->_medicine_sale_details, $this->_medicine_sale_details . '.medicine_sale_id = ' . $this->_medicine_sale . ' .id', 'INNER');
        $this->db->group_by($this->_medicine_sale . '.id');
        $this->db->where('is_deleted', 0);
        $this->_get_medicine_sale_filters($filters);
        $query  = $this->db->get($this->_medicine_sale);
        $result = $query->num_rows();
        
        return $result;
    }
    
    private function _get_medicine_sale_filters($filters)
    {
        if (is_array($filters) && count($filters) > 0) {
            foreach ($filters as $filter_array) {
                $filter_obj             = new stdClass();
                $filter_obj->colName    = $filter_array['fieldName'];
                $filter_obj->operator   = $filter_array['operator'];
                $filter_obj->colValue   = $filter_array['value'];
                $filter_obj->colType    = $filter_array['type'];
                $this->applyFilterExpression($filter_obj);
            }
            
            //put this at the end in if condition bracket
            $this->compileFilters();
            
        }
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
    

    public function get_medicine_revenue($medicine_ids = array(), $pagination = array(), $filters = array(), $batch_data = false)

    {
        $this->db->select(array($this->_medicine_category . '.name', $this->_medicine_stock . '.loose_item_quantity'));
        $this->db->select('GROUP_CONCAT( DISTINCT ' . $this->_medicine_stock . '.batch  ORDER BY batch ASC) as batch');
        $this->db->select('SUM(' . $this->_medicine_stock . '.quantity) as total_stock');
        $this->db->select('SUM(' . $this->_medicine_stock . '.free_item) as total_free_item_stock');
        $this->db->select(array($this->_medicine_category . '.medicine_category_id'));
        $this->db->select('IF(' . $this->_medicine_stock . '.is_latest_stock = 1, ' . $this->_medicine_stock . '.mrp, 0) as mrp', false);
//        $this->db->select('SUM(' . $this->_medicine_stock . '.quantity) as sold_stock'); // this is a sold stock
//        $this->db->select('SUM(amount) as revenue');
        $this->db->join($this->_medicine_stock, $this->_medicine_stock . '.medicine_category_id = ' . $this->_medicine_category . '.medicine_category_id', 'LEFT');
//        $this->db->join($this->_medicine_sale_details, $this->_medicine_sale_details . '.medicine_id = ' . $this->_medicine_category . '.medicine_category_id', 'LEFT');
        if (is_array($medicine_ids) && count($medicine_ids) > 0) {
            $this->db->where_in($this->_medicine_stock . '.medicine_category_id', $medicine_ids);
        }
        
        $this->_get_medicine_report_filters($filters);
        
        $this->db->group_by($this->_medicine_category . '.medicine_category_id');
        
        if (!empty($batch_data)) {
            $this->db->group_by($this->_medicine_stock . '.batch');
        }
        
        if (!empty($pagination)) {
            $this->db->limit($pagination['offset'], $pagination['start']);
        }
        
        if (!empty($pagination['sort_name']) && !empty($pagination['sort_order'])) {
            $this->db->order_by($pagination['sort_name'], $pagination['sort_order']);
        } else {
            $this->db->order_by('name', 'ASC');
        }
        
        $data = $this->db->get($this->_medicine_category)->result_array();
        return $data;
    }

    public function get_sold_stock_of_medicine($medicine_ids = array(), $batch_data = false, $filters = array())
    {
        $this->db->select('SUM(quantity) as sold_stock'); // this is a sold stock
        $this->db->select('SUM(amount) as revenue'); // this is a sold stock
        $this->db->select(array('medicine_id'));
        $this->db->select('GROUP_CONCAT(distinct batch  ORDER BY batch ASC) as batch');
        if (is_array($medicine_ids) && count($medicine_ids) > 0) {
            $this->db->select($this->_medicine_category . '.name as name');
            $this->db->join($this->_medicine_sale_details, $this->_medicine_category . '.medicine_category_id = ' . $this->_medicine_sale_details . '.medicine_id', 'INNER');
            $this->db->where_in($this->_medicine_sale_details . '.medicine_id', $medicine_ids);
        }
        
        $this->db->where('is_deleted', 0);
        
        if (is_array($filters) && count($filters) > 0) {
            foreach ($filters as $filter_array) {
                $filter_obj             = new stdClass();
                $filter_obj->colName    = $filter_array['fieldName'];
                $filter_obj->operator   = $filter_array['operator'];
                $filter_obj->colValue   = $filter_array['value'];
                $filter_obj->colType    = $filter_array['type'];
                
                switch ($filter_array['fieldName']) {
                    case 'mrp' :
                    case 'total_stock' :
                        break;
                    
                    default:
                        $this->applyFilterExpression($filter_obj);
                }
            }
            
            //put this at the end in if condition bracket
            $this->compileFilters();
            
        }

        $this->db->group_by($this->_medicine_sale_details . '.medicine_id');
        
        if (!empty($batch_data)) {
            $this->db->group_by($this->_medicine_sale_details . '.batch');
        }
        
        $data = $this->db->get($this->_medicine_category)->result_array();
//        log_message('error', $this->db->last_query());
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
            $this->db->where('medicine_sale_id', $id);
            $this->db->update($this->_medicine_sale_details, array('is_deleted' => 1));
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
    
    public function get_medicine_list_count($filters)
    {
        $this->db->select('IF(status = "IN_STOCK", "In Stock", IF(status = "OUT_STOCK", "Out of Stock", "Not in Stock")) as status', false);
        $this->db->select(array('name', 'description', 'medicine_category_id'));
        $this->_get_medicine_report_filters($filters);
        $query = $this->db->get($this->_medicine_category);
        $result = $query->num_rows();
        return $result;
    }
    
    public function get_medicine_list($pagination, $filters)
    {
        $this->db->select('IF(status = "IN_STOCK", "In Stock", IF(status = "OUT_STOCK", "Out of Stock", "Not in Stock")) as status', false);
        $this->db->select(array('name', 'description', 'medicine_category_id'));
        
        $this->db->limit($pagination['offset'], $pagination['start']);

        if (!empty($pagination['sort_name']) && !empty($pagination['sort_order'])) {
            $this->db->order_by($pagination['sort_name'], $pagination['sort_order']);
        }
        
        $this->_get_medicine_report_filters($filters);
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

    public function update_medicine_sale_cash_memo_no($sale_id, $cash_memo_no)
    {
        if (!empty($sale_id) && !empty($cash_memo_no)) {
            $data = array('cash_memo_no' => $cash_memo_no);
            $this->db->where('id', $sale_id);
            $this->db->update($this->_medicine_sale, $data);
        }
    }

    public function get_sell_medicine_details($medicine_sale_id = null, $columns = array())
    {
        if (is_array($columns) && count($columns) > 0) {
            $this->db->select($columns);
        } else {
            $this->db->select('*');
        }
        
        $this->db->from($this->_medicine_sale);
        $this->db->join($this->_medicine_sale_details , $this->_medicine_sale.'.id ='.$this->_medicine_sale_details.'.medicine_sale_id');
        $this->db->where($this->_medicine_sale . '.id', $medicine_sale_id);
        $this->db->where($this->_medicine_sale_details.'.is_deleted','0');
        $data = $this->db->get()->result_array();

        return $data;
    }
    
    public function update_sell_medicine($id, $data)
    {
        if (!empty($id)) {
            $this->db->where('id', $id);
            $this->db->update($this->_medicine_sale, $data);
        }
    }
    
    public function update_sell_medicine_details($id, $data)
    {
        if (!empty($id)) {
            $this->db->where('id', $id);
            $this->db->update($this->_medicine_sale_details, $data);
        }
    }
    
    public function update_med_sold_to_patient($data)
    {
        $this->db->insert($this->_medicine_sale_details, $data);
        return $this->db->insert_id();
    }
     
    public function vendor_stock_report($vendor_id, $pagination, $filters)
    {
        $this->db->select(array($this->_medicine_category . '.name' ));
        $this->db->select('SUM(' . $this->_medicine_stock . '.quantity' . ') as total_stock');
        $this->db->select('SUM(' . $this->_medicine_stock . '.free_item' . ') as free_item');
        $this->db->select('IF(' . $this->_medicine_stock . '.is_latest_stock = 1, ' . $this->_medicine_stock . '.loose_item_quantity, 0 ' . ') as loose_item_quantity', false);
        $this->db->select('SUM(' . $this->_medicine_sale_details . '.quantity' . ') as sold_quantity');
        $this->db->select(array($this->_medicine_stock . '.price'));
        $this->db->select('SUM(' . $this->_medicine_sale_details . '.amount' . ') as revenue');
        
        $this->db->join($this->_table_vendors, $this->_medicine_stock . '.vendor_id = ' . $this->_table_vendors . '.id', 'INNER');
        $this->db->join($this->_medicine_category, $this->_medicine_stock . '.medicine_category_id = ' . $this->_medicine_category . '.medicine_category_id', 'INNER');
        $this->db->join($this->_medicine_sale_details, $this->_medicine_stock . '.batch = ' . $this->_medicine_sale_details . '.batch', 'LEFT');
        $this->db->where($this->_table_vendors . '.id', $vendor_id);
        
        $this->_get_vendor_report_filters($filters);
        
        $this->db->group_by($this->_medicine_stock . '.medicine_category_id');
        $this->db->limit($pagination['offset'], $pagination['start']);

        if (!empty($pagination['sort_name']) && !empty($pagination['sort_order'])) {
            $this->db->order_by($pagination['sort_name'], $pagination['sort_order']);
        }
        
        $data = $this->db->get($this->_medicine_stock)->result_array();
//        log_message('error', $this->db->last_query());
        return $data;
    }
    
    public function vendor_stock_report_count($vendor_id, $filters)
    {
        $this->db->select($this->_medicine_category . '.name');
        $this->db->join($this->_table_vendors, $this->_medicine_stock . '.vendor_id = ' . $this->_table_vendors . '.id', 'INNER');
        $this->db->join($this->_medicine_category, $this->_medicine_stock . '.medicine_category_id = ' . $this->_medicine_category . '.medicine_category_id', 'INNER');
        $this->db->join($this->_medicine_sale_details, $this->_medicine_stock . '.batch = ' . $this->_medicine_sale_details . '.batch', 'LEFT');
        $this->db->where($this->_table_vendors . '.id', $vendor_id);
        $this->db->group_by($this->_medicine_stock . '.medicine_category_id');
       
        $this->_get_vendor_report_filters($filters);
        $query = $this->db->get($this->_medicine_stock);
        $result = $query->num_rows();
        return $result;
    }
    
    private function _get_vendor_report_filters($filters)
    {
        if (is_array($filters) && count($filters) > 0) {
            foreach ($filters as $filter_array) {
                $filter_obj             = new stdClass();
                $filter_obj->colName    = $filter_array['fieldName'];
                $filter_obj->operator   = $filter_array['operator'];
                $filter_obj->colValue   = $filter_array['value'];
                $filter_obj->colType    = $filter_array['type'];
                $this->applyFilterExpression($filter_obj);
            }
            
            //put this at the end in if condition bracket
            $this->compileFilters();
            
        }
    }
    
    public function get_med_stock_list()
    {
        $result = array();
        $this->db->select(array($this->_medicine_stock . '.quantity', $this->_medicine_stock . '.price', $this->_medicine_stock . '.free_item',
            $this->_medicine_stock . '.id', $this->_table_manufacture_company . '.name as company_name',
            $this->_table_vendors . '.name as vendor_name', $this->_medicine_category . '.name as medicine_name',
            $this->_medicine_stock . '.status'));
        $this->db->join($this->_table_vendors, $this->_table_vendors . '.id = ' . $this->_medicine_stock . '.vendor_id', 'LEFT');
        $this->db->join($this->_table_manufacture_company, $this->_table_manufacture_company . '.id = ' . $this->_medicine_stock . '.manufacture_company_id', 'LEFT');
        $this->db->join($this->_medicine_category, $this->_medicine_category . '.medicine_category_id = ' . $this->_medicine_stock . '.medicine_category_id', 'LEFT');
        $query = $this->db->get($this->_medicine_stock);
        $result = $query->result_array();
        return $result;
    }
    
    /**
     * @method check_dupliate_medicine_sale
     * @desc This function checks the sale of medicine already done or not
     */
    public function check_dupliate_medicine_sale()
    {
        $result = array();
        $this->db->select('GROUP_CONCAT(' . $this->_medicine_sale_details . '.medicine_id) as purchased_medicine');
        $this->db->select($this->_medicine_sale . '.patient_name');
        $this->db->join($this->_medicine_sale_details, $this->_medicine_sale . '.id = ' . $this->_medicine_sale_details . '.medicine_sale_id');
        $this->db->where($this->_medicine_sale . '.create_date >= DATE_SUB(NOW(), INTERVAL ' . self::DUPLICATE_RECORDS_TIME_SPAN . ' MINUTE)');
        
        $query  = $this->db->get($this->_medicine_sale);
        $result = $query->result_array();
        return $result;
    }

    public function get_total_medicine_count($filters, $batch_data = false)
    {
        $this->db->select($this->_medicine_category . '.medicine_category_id');
        $this->db->join($this->_medicine_stock, $this->_medicine_stock . '.medicine_category_id = ' . $this->_medicine_category . '.medicine_category_id', 'LEFT');
        $this->_get_medicine_report_filters($filters);
        
        $this->db->group_by($this->_medicine_category . '.medicine_category_id');
        if (!empty($batch_data)) {
            $this->db->group_by($this->_medicine_stock . '.batch');
        }
        $query = $this->db->get($this->_medicine_category);
        $result = $query->num_rows();
        return $result;
    }
    
    private function _get_medicine_report_filters($filters)
    {
        if (is_array($filters) && count($filters) > 0) {
            foreach ($filters as $filter_array) {
                $filter_obj             = new stdClass();
                $filter_obj->colName    = $filter_array['fieldName'];
                $filter_obj->operator   = $filter_array['operator'];
                $filter_obj->colValue   = $filter_array['value'];
                $filter_obj->colType    = $filter_array['type'];
                $this->applyFilterExpression($filter_obj);
            }
            
            //put this at the end in if condition bracket
            $this->compileFilters();
            
        }
    }
    
    public function delete_medicine($id)
    {
        if (!empty($id)) {
            $this->db->where('medicine_category_id', $id);
            $this->db->delete('medicine_category');
        }
    }
    
    public function delete_already_sold_medicines(array $medicine_ids, $med_sale_id)
    {
         if (is_array($medicine_ids) && count($medicine_ids) > 0 && !empty($med_sale_id)) {
            $data = array('is_deleted' => 1);
            $this->db->where('medicine_sale_id', $med_sale_id);
            $this->db->where_in('medicine_id', $medicine_ids);
            $this->db->update($this->_medicine_sale_details, $data);
        }
    }
    
    public function update_med_details_sold_to_patient($data, $med_sale_id)
    {
        $this->db->update_batch($this->_medicine_sale_details, $data, 'id');
    }
    
    public function get_doctors_list()
    {
        $this->db->select(array('name', 'doctor_id'));
        $query = $this->db->get($this->_table_doctor);
        $result = $query->result_array();
        return $result;
    }
    
//    public function get_medicine_stock_report($pagination, $filters, $batch_data= false)
//    {
//        $this->db->select(array($this->_medicine_category . '.name', $this->_medicine_stock . '.loose_item_quantity'));
//        $this->db->select('GROUP_CONCAT( DISTINCT ' . $this->_medicine_stock . '.batch) as batch');
//        $this->db->select('SUM(' . $this->_medicine_stock . '.quantity) as total_stock');
//        $this->db->select('SUM(' . $this->_medicine_stock . '.free_item) as total_free_item_stock');
//        $this->db->select(array($this->_medicine_category . '.medicine_category_id'));
//        $this->db->select('IF(' . $this->_medicine_stock . '.is_latest_stock = 1, ' . $this->_medicine_stock . '.mrp, 0) as mrp', false);
//        $this->db->select('null as sold_stock', false); // this is a sold stock
//        $this->db->select('null as revenue', false);
//        $this->db->from($this->_medicine_category);
//        $this->db->join($this->_medicine_stock, $this->_medicine_stock . '.medicine_category_id = ' . $this->_medicine_category . '.medicine_category_id', 'LEFT');
//        $this->db->group_by($this->_medicine_category . '.medicine_category_id');
//        
//        if (!empty($batch_data)) {
//            $this->db->group_by($this->_medicine_stock . '.batch'); 
//        }
//        
//        $query1 = $this->db->_compile_select();
//        
//        $this->db->_reset_select();
//       
//        $this->db->select(array($this->_medicine_category . '.name'));
//        $this->db->select('null as loose_item_quantity', false);
//        $this->db->select('null as batch', false);
//        $this->db->select('null as total_stock', false);
//        $this->db->select('null as  total_free_item_stock', false);
//        $this->db->select('null as medicine_category_id', false);
//        $this->db->select('null as mrp', false);
//        $this->db->select('SUM(' . $this->_medicine_sale_details . '.quantity) as sold_stock'); // this is a sold stock
//        $this->db->select('SUM(amount) as revenue');
//
//        $this->db->from($this->_medicine_category);
//        $this->db->join($this->_medicine_sale_details, $this->_medicine_sale_details . '.medicine_id = ' . $this->_medicine_category . '.medicine_category_id', 'LEFT');
//        $this->db->where('is_deleted', 0);
//        $this->db->group_by($this->_medicine_category . '.medicine_category_id');
//        
//        $name_group_by = ' GROUP BY name';
//        if (!empty($batch_data)) {
//            $this->db->group_by($this->_medicine_sale_details . '.batch');
//            $name_group_by .= ', batch';
//        }
//        
//        $query2 = $this->db->_compile_select();
//        $this->db->_reset_select();
//        
//        $sql =  "SELECT name, loose_item_quantity, batch, total_stock, total_free_item_stock, medicine_category_id, mrp, sum(sold_stock) as sold_stock, sum(revenue) as revenue
//            FROM (".$query1." UNION ALL ".$query2.") tmp1" .
//                $name_group_by
//                . " ORDER BY " . $pagination['sort_name'] . " " . $pagination['sort_order'];
//
//        $sql .= ' LIMIT ' . $pagination['start'] . ',' . $pagination['offset'];
//        $query = $this->db->query($sql);
//
//        $data = $query->result_array();
//                log_message('error', $this->db->last_query());
//
//        return $data;
//    }
}
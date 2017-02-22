<?phpif (!defined('BASEPATH'))	exit('No direct script access allowed');/* *	@author : Joyonto Roy *	date	: 1 August, 2013 *	University Of Dhaka, Bangladesh *	Hospital Management system *	http://codecanyon.net/user/joyontaroy */class Pharmacist extends CI_Controller{	const MED_STATUS_IN_STOCK = 'IN_STOCK';    const MED_STATUS_OUT_STOCK = 'OUT_STOCK';	function __construct()	{		parent::__construct();        if ($this->session->userdata('pharmacist_login') != 1) {            redirect(base_url() . 'index.php?login', 'refresh');        }		$this->load->database();        $this->load->model('pharmacist_model');		/*cache control*/		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');		$this->output->set_header('Pragma: no-cache');		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");	}	/***Default function, redirects to login page if no admin logged in yet***/	public function index()	{		if ($this->session->userdata('pharmacist_login') != 1)			redirect(base_url() . 'index.php?login', 'refresh');		if ($this->session->userdata('pharmacist_login') == 1)			redirect(base_url() . 'index.php?pharmacist/dashboard', 'refresh');	}	/***pharmacist DASHBOARD***/	function dashboard()	{		if ($this->session->userdata('pharmacist_login') != 1)			redirect(base_url() . 'index.php?login', 'refresh');		$page_data['page_name']  = 'dashboard';		$page_data['page_title'] = get_phrase('pharmacist_dashboard');		$this->load->view('index', $page_data);	}	/****MANAGE MEDICINE CATEGORIES*********/	function manage_medicine_category($param1 = '', $param2 = '', $param3 = '')	{        if ($param1 == 'create') {            $result              = array();			$data['name']        = $this->input->post('name');			$data['description'] = $this->input->post('description');            if (!empty($data['name'])) {                $this->db->insert('medicine_category', $data);            } else {                $result['error'] = true;                $result['message'] = 'Please type the medicine name.';            }            $this->output->set_content_type('application/json');            $this->output->set_output(json_encode(                array('sucesss' => 1,                      'content' => $result)            ));		}else if ($param1 == 'edit' && $param2 == 'do_update') {            $result              = array();			$data['name']        = $this->input->post('name');			$data['description'] = $this->input->post('description');            $medicine_id         = $this->input->post('medicine_id');            if (!empty($data['name']) && !empty($medicine_id)) {                $this->db->where('medicine_category_id', $medicine_id);                $this->db->update('medicine_category', $data);            } else {                $result['error'] = true;                $result['message'] = 'Please type the medicine name.';            }			$this->output->set_content_type('application/json');            $this->output->set_output(json_encode(                array('sucesss' => 1,                      'content' => $result)            ));		} else {             if ($param1 == 'edit') {                $page_data['edit_profile'] = $this->db->get_where('medicine_category', array(                    'medicine_category_id' => $param2                ))->result_array();            }            if ($param1 == 'delete') {                $this->db->where('medicine_category_id', $param2);                $this->db->delete('medicine_category');                $this->session->set_flashdata('flash_message', get_phrase('medicine_category_deleted'));                redirect(base_url() . 'index.php?pharmacist/manage_medicine_category', 'refresh');            }            $page_data['page_name']             = 'manage_medicine_category';            $page_data['page_title']            = 'Manage Medicines';            $page_data['medicine_categories']   = $this->pharmacist_model->get_medicine_list();            $this->load->view('index', $page_data);        }    }	/****MANAGE MEDICINES CATEGORY WISE*********/	function manage_medicine($param1 = '', $param2 = '', $param3 = '')	{		if ($param1 == 'create') {			  //$cat_id=$this->input->post('medicine_category_id');//$medicine_data=explode("_",$cat_id);			// 	$this->db->select('name');			//			//			// $query = $this->db->get_where('medicine_category',array('medicine_category_id' => $cat_id));			// print_r($query);			// exit;			//$data['name']   =$medicine_data['1'];			$data['medicine_category_id']   =$this->input->post('medicine_category_id');			//$medicine_data['0'];			$data['description']            = $this->input->post('description');			$data['price']                  = $this->input->post('price');			$data['manufacture_company_id']  = $this->input->post('manufacturing_company');            $data['vendor_id']              = $this->input->post('vendor_name');            $data['quantity']               = $this->input->post('quantity');            $data['mrp']                    = $this->input->post('mrp');            $data['medicine_purchase_date'] = $this->input->post('purchase_date');            $data['medicine_purchase_date'] = Util::snh_date_format($data['medicine_purchase_date']);            $data['medicine_purchase_date'] = Util::snh_date_format($data['medicine_purchase_date']);            $data['medicine_expiry_date']   = $this->input->post('expiry_date');            $data['medicine_expiry_date']   = Util::snh_date_format($data['medicine_expiry_date']);            $data['batch']                  = $this->input->post('batch');            $data['discount']               = $this->input->post('discount');            $data['vat']                    = $this->input->post('vat');            $data['free_item']              = $this->input->post('free_item');            if (isset($_POST['is_loose_item'])) {                $data['is_loose_item']       = 1;                $data['loose_item_quantity'] = $this->input->post('loose_item_quantity');            } else {                $data['is_loose_item']       = 0;                $data['loose_item_quantity'] = 0;            }            $this->pharmacist_model->mark_obsolete_to_old_med_stock($data['medicine_category_id']);			$this->db->insert('medicine_stock', $data);            $medicine_id = $this->db->insert_id();            $this->_check_medicines_stock($data['medicine_category_id']);			$this->session->set_flashdata('flash_message', get_phrase('medicine_created'));			redirect(base_url() . 'index.php?pharmacist/manage_medicine', 'refresh');		}		if ($param1 == 'edit' && $param2 == 'do_update') {			$data['medicine_category_id']   = $this->input->post('medicine_category_id');			$data['description']            = $this->input->post('description');			$data['price']                  = $this->input->post('price');			$data['manufacture_company_id']  = $this->input->post('manufacturing_company');            $data['vendor_id']          = $this->input->post('vendor_name');            $data['quantity']               = $this->input->post('quantity');            $data['mrp']                    = $this->input->post('mrp');            $data['medicine_purchase_date'] = $this->input->post('purchase_date');            $data['medicine_purchase_date'] = Util::snh_date_format($data['medicine_purchase_date']);            $data['medicine_expiry_date']   = $this->input->post('expiry_date');            $data['medicine_expiry_date']   = Util::snh_date_format($data['medicine_expiry_date']);            $data['batch']                  = $this->input->post('batch');            $data['discount']               = $this->input->post('discount');            $data['vat']                    = $this->input->post('vat');            $data['free_item']              = $this->input->post('free_item');            if (isset($_POST['is_loose_item'])) {                $data['is_loose_item']       = 1;                $data['loose_item_quantity'] = $this->input->post('loose_item_quantity');            } else {                $data['is_loose_item']       = 0;                $data['loose_item_quantity'] = 0;            }			$this->db->where('id', $param3);            $this->db->update('medicine_stock', $data);            log_message('error', $this->db->last_query());            $this->_check_medicines_stock($data['medicine_category_id']);			$this->session->set_flashdata('flash_message', get_phrase('medicine_updated'));			redirect(base_url() . 'index.php?pharmacist/manage_medicine', 'refresh');		} else if ($param1 == 'edit') {			$page_data['edit_profile'] = $this->db->get_where('medicine_stock', array(				'id' => $param2			))->result_array();            if (!empty($page_data['edit_profile'][0]['manufacture_company_id'])) {                $page_data['manufacture_list'] = $this->pharmacist_model->get_med_manufacture_details($page_data['edit_profile'][0]['manufacture_company_id']);                $page_data['vendor_list']      = $this->pharmacist_model->get_med_vendors_details($page_data['edit_profile'][0]['manufacture_company_id']);            }		}		if ($param1 == 'delete') {			$this->db->where('id', $param2);			$this->db->delete('medicine_stock');			$this->session->set_flashdata('flash_message', get_phrase('medicine_deleted'));			redirect(base_url() . 'index.php?pharmacist/manage_medicine', 'refresh');		}        $page_data['company_list'] = $this->pharmacist_model->get_manufacture_company_list(null, true);		$page_data['page_name']  = 'manage_medicine';		$page_data['page_title'] = get_phrase('manage_medicine');		$page_data['medicines']  = $this->db->get('medicine_stock')->result_array();		$this->load->view('index', $page_data);	}/***UPDATE Sell MEDICINE******/public function edit_sell($id){			log_message('error', '>>>>>>>>>>>>>>>>>>>>>>>>>>>');   $page_data['page_name']    = 'update_sell';			$page_data['page_title']   = 'update sell';   $data                      = $page_data['edit_profile']=			$this->pharmacist_model->get_sell_medicine_details($id);        $page_data['update_sell'] = !empty($data[0]) ? $data[0] : null;								$this->load->view('index', $page_data);}public function update(){	//$mdid = $this->input->post('md_id');	$data                          = $this->input->post();		$update_data                = array();			$update_data1                = array();			$update_data['patient_name'] = $data['patient_name'];			$update_data['discount'] = $data['discount'];			$update_data['total_amount'] = $data['total_amount'];			$update_data['medicine_sold_date'] = $data['selling_date'];			$this->pharmacist_model->update_sell_medicine($data['mid'], $update_data);for($i=1;$i<=15;$i++){		if(isset($data['md_id'.$i]) &&  $data['md_id'.$i]>0)		{			if($data['quantity' . $i]!="")			{						$update_data1['medicine_id'] = $data['medicine_id' . $i];						$update_data1['batch'] = $data['batch_id'.$i];						$update_data1['is_loose_sale'] = !empty($data['selling_loose_quantity'.$i]) ? '1' : '0'; //$data['selling_loose_quantity'.$i];						$update_data1['quantity'] = $data['quantity'.$i];						$update_data1['amount'] = $data['amount'.$i];							$this->pharmacist_model->update_sell_medicine_details($data['md_id'.$i], $update_data1);			}		}		else {			if($data['quantity' . $i]!="")			{						$update_data1['medicine_sale_id'] = $data['mid'];						$update_data1['medicine_id'] = $data['medicine_id' . $i];						$update_data1['batch'] = $data['batch_id'.$i];						$update_data1['is_loose_sale'] = !empty($data['selling_loose_quantity'.$i]) ? '1' : '0'; //$data['selling_loose_quantity'.$i];						$update_data1['quantity'] = $data['quantity'.$i];						$update_data1['amount'] = $data['amount'.$i];						if (!empty($update_data1['is_loose_sale'])) {           $med_stock_details = $this->pharmacist_model->get_medicine_stock_details($insert_med_details[$i]['medicine_id']);           if (!empty($med_stock_details[0]['loose_item_quantity']) && !empty($med_stock_details[0]['is_loose_item'])) {               $update_data1['quantity'] = number_format($update_data1['quantity']/$med_stock_details[0]['loose_item_quantity'], 1);           }       }       $update_data1['amount'] = $data['amount' . $i];       if (!empty($update_data1['medicine_id'])) {           $this->_check_medicines_stock($update_data1['medicine_id']);       }				//$this->pharmacist_model->update_sell_medicine_details($data['md_id'.$i], $update_data1);	//			echo "Update";			//$last_id=$this->db->insert_id();
		 $this->pharmacist_model->update_med_sold_to_patient($update_data1);			}		}}				$this->session->set_flashdata('flash_message', get_phrase('prescription_updated'));			redirect(base_url() . 'index.php?pharmacist/manage_prescription', 'refresh'); $this->load->view('index', $page_data);}	/***MANAGE PRESCRIPTIONS******/	function manage_prescription($param1 = '', $param2 = '', $param3 = '')	{		$this->load->helper('util');		if ($param1 == 'create') {			$this->session->set_flashdata('flash_message', get_phrase('prescription_created'));			redirect(base_url() . 'index.php?pharmacist/manage_prescription', 'refresh');		}		if ($param1 == 'edit' && $param2 == 'do_update') {			// echo $param3;			// echo "test";			// exit; //$mdid = $this->input->post('md_id');//print_r($data['id']);//exit;			$data['patient_name'] = $this->input->post('patient_name');			$data['discount'] = $this->input->post('discount');			$data['total_amount'] = $this->input->post('total_amount');			$data['medicine_sold_date'] = $this->input->post('selling_date');			$mdid = $this->input->post('md_id'); //print_r($mdid);// exit;			$this->db->where('id', $param3);			$this->db->update('medicine_sale', $data);			$data1['medicine_id'] = $this->input->post('medicine_id');			$data1['batch'] = $this->input->post('batch_id');			$data1['is_loose_sale'] = $this->input->post('selling_loose_quantity');			$data1['quantity'] = $this->input->post('quantity');			$data1['amount'] = $this->input->post('amount'); // print_r($data1['is_loose_sale']); // EXIT;			for($i=0;$i<count($mdid);$i++)			{				if($data1['is_loose_sale'][$i]=="on")				{					$data1['is_loose_sale'][$i]=1;				}					$this->db->where('id', $mdid[$i]);					$this->db->update('medicine_sale_details', $data1[$i]);			}			$this->session->set_flashdata('flash_message', get_phrase('prescription_updated'));			redirect(base_url() . 'index.php?pharmacist/manage_prescription', 'refresh');		} else if ($param1 == 'edit') {//print_r($param2);			// $page_data['edit_profile'] = $this->db->get_where('medicine_sale', array(			//			// 	'id' => $param2			//			// ))->result_array();			$page_data['edit_profile']=$this->db->query("select medicine_sale.*,medicine_sale_details.* from medicine_sale INNER JOIN medicine_sale_details on medicine_sale.id=medicine_sale_details.medicine_sale_id where medicine_sale.id='".$param2	."'")->result();			//redirect(base_url() . 'index.php?pharmacist/manage_prescriptions', 'refresh');		}		if ($param1 == 'delete' && !empty($param2)) {			$this->pharmacist_model->delete_med_sold_to_patient($param2);			$this->session->set_flashdata('flash_message', get_phrase('prescription_deleted'));			redirect(base_url() . 'index.php?pharmacist/manage_prescription', 'refresh');		}		$page_data['page_name']     = 'manage_prescription';		$page_data['page_title']    = 'Sold Medicine';		$page_data['prescriptions'] = $this->pharmacist_model->get_sold_medicine_details();		//$this->load->view('index', $page_data);$this->load->view('index', $page_data);	}	/******MANAGE OWN PROFILE AND CHANGE PASSWORD***/	function manage_profile($param1 = '', $param2 = '', $param3 = '')	{		if ($this->session->userdata('pharmacist_login') != 1)			redirect(base_url() . 'index.php?login', 'refresh');		if ($param1 == 'update_profile_info') {			$data['name']    = $this->input->post('name');			$data['email']   = $this->input->post('email');			$data['address'] = $this->input->post('address');			$data['phone']   = $this->input->post('phone');			$this->db->where('pharmacist_id', $this->session->userdata('pharmacist_id'));			$this->db->update('pharmacist', $data);			$this->session->set_flashdata('flash_message', get_phrase('profile_updated'));			redirect(base_url() . 'index.php?pharmacist/manage_profile/', 'refresh');		}		if ($param1 == 'change_password') {			$data['password']             = $this->input->post('password');			$data['new_password']         = $this->input->post('new_password');			$data['confirm_new_password'] = $this->input->post('confirm_new_password');			$current_password = $this->db->get_where('pharmacist', array(				'pharmacist_id' => $this->session->userdata('pharmacist_id')			))->row()->password;			if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {				$this->db->where('pharmacist_id', $this->session->userdata('pharmacist_id'));				$this->db->update('pharmacist', array(					'password' => $data['new_password']				));				$this->session->set_flashdata('flash_message', get_phrase('password_updated'));			} else {				$this->session->set_flashdata('flash_message', get_phrase('password_mismatch'));			}			redirect(base_url() . 'index.php?pharmacist/manage_profile/', 'refresh');		}		$page_data['page_name']    = 'manage_profile';		$page_data['page_title']   = get_phrase('manage_profile');		$page_data['edit_profile'] = $this->db->get_where('pharmacist', array(			'pharmacist_id' => $this->session->userdata('pharmacist_id')		))->result_array();		$this->load->view('index', $page_data);	}    public function sold_to_patient()    {        $result                        = array();        $data                          = $this->input->post();        // check the medicine quantity is available in stock or not        $check_med_remaining_stock     = $this->_check_medicine_stock($data);        if (empty($check_med_remaining_stock)) {            $insert_data                        = array();            $insert_data['patient_name']        = $this->input->post('patient_name');            $insert_data['total_amount']        = $this->input->post('total_amount');            $insert_data['discount']            = $this->input->post('discount');            $insert_data['medicine_sold_date']  = $this->input->post('selling_date');            $insert_data['medicine_sold_date']  = Util::snh_date_format($insert_data['medicine_sold_date']);            $last_insert_id                     = $this->pharmacist_model->med_sold_to_patient($insert_data);            // update the cash memo no in databse            $cash_memo_prefix                   = $this->config->item('cash_memo_no_prefix');            $cash_memo_no                       = $cash_memo_prefix . $last_insert_id;            $this->pharmacist_model->update_medicine_sale_cash_memo_no($last_insert_id, $cash_memo_no);            $total_medicine_count               = $data['total_med_details'];            $insert_med_details                 = array();            if ($total_medicine_count > 0) {                $counter = 1;                for ($i=1; $i<=15; $i++) {                    if (!empty($data['medicine_id' . $i])) {                        $insert_med_details[$counter]['medicine_sale_id']   = $last_insert_id;                        $insert_med_details[$counter]['medicine_id']        = $data['medicine_id' . $i];                        $insert_med_details[$counter]['is_loose_sale']      = !empty($data['selling_loose_quantity' . $i]) ? 1 : 0;                        $insert_med_details[$counter]['quantity']           = $data['quantity' . $i];                        $insert_med_details[$counter]['batch']              = $data['batch_id' . $i];                        if (!empty($insert_med_details[$counter]['is_loose_sale'])) {                            $med_stock_details = $this->pharmacist_model->get_medicine_stock_details($insert_med_details[$counter]['medicine_id']);                            if (!empty($med_stock_details[0]['loose_item_quantity']) && !empty($med_stock_details[0]['is_loose_item'])) {                                $insert_med_details[$counter]['quantity'] = number_format($insert_med_details[$counter]['quantity']/$med_stock_details[0]['loose_item_quantity'], 1);                            }                        }                        $insert_med_details[$counter]['amount'] = $data['amount' . $i];                        if (!empty($insert_med_details[$counter]['medicine_id'])) {                            $this->_check_medicines_stock($insert_med_details[$counter]['medicine_id']);                        }                        $counter++;                    }                }                if(is_array($insert_med_details) && count($insert_med_details) > 0) {                    $this->pharmacist_model->med_details_sold_to_patient($insert_med_details);                }            }        } else {            $result['error']   = true;            $result['message'] = $check_med_remaining_stock;        }        $this->output->set_content_type('application/json');        $this->output->set_output(json_encode(            array( 'sucesss' => 1, 'content' => $result)        ));    }    private function _check_medicine_stock($data)    {        $medicine_list       = array();        $medicine_other_list = array();        for ($i=1; $i<=15; $i++) {            if (!empty($data['medicine_id' . $i])) {                $medicine_id                                          = $data['medicine_id' . $i];                $medicine_list[]                                      = $medicine_id;                $medicine_other_list[$medicine_id]['total_stock']     = 0;                $medicine_other_list[$medicine_id]['sold_stock']      = 0;                if (isset($data['selling_loose_quantity' . $i])) {                    $medicine_other_list[$medicine_id]['loose_item'] = true;                }                $medicine_other_list[$medicine_id]['going_to_sell'] = $data['quantity' . $i];            }        }        $total_stock   = $this->pharmacist_model->get_medicine_revenue($medicine_list);        if (is_array($total_stock) && count($total_stock) > 0) {            foreach ($total_stock as $stock) {                if (!empty($medicine_other_list[$stock['medicine_category_id']])) {                    $medicine_other_list[$stock['medicine_category_id']]['total_stock'] = $stock['total_free_item_stock'] + $stock['total_stock'];                    if (!empty($medicine_id[$medicine_id]['loose_item'])) {                        $medicine_other_list[$medicine_id]['going_to_sell'] = $medicine_other_list[$medicine_id]['going_to_sell']/$stock['loose_item_quantity'];                        $medicine_other_list[$medicine_id]['going_to_sell'] = number_format($medicine_other_list[$medicine_id]['going_to_sell'], 2);                    }                }            }        }        $sold_stock   = $this->pharmacist_model->get_sold_stock_of_medicine($medicine_list);        if (is_array($sold_stock) && count($sold_stock) > 0) {            foreach ($sold_stock as $rem_stock) {                if (!empty($medicine_other_list[$rem_stock['medicine_id']])) {                    $medicine_other_list[$rem_stock['medicine_id']]['sold_stock']  = $rem_stock['sold_stock'];                    $medicine_other_list[$rem_stock['medicine_id']]['name']        = $rem_stock['name'];                }            }        }        $error_message = null;        if (is_array($medicine_other_list) && count($medicine_other_list) > 0) {            foreach ($medicine_other_list as $list) {                $remaining_stock = 0;                $remaining_stock = $list['total_stock'] - $list['sold_stock'];                if ($remaining_stock < $list['going_to_sell']) {                    $error_message .= $remaining_stock .' medicine remaining for medicine ' . $list['name'] . '\n';                }            }        }        return $error_message;    }    public function medicine_stock_report()    {        $page_data                      = array();        $page_data['page_name']         = 'medicine_stock_report';		$page_data['page_title']        = get_phrase('medicine_stock_report');        $page_data['data']              = $this->pharmacist_model->get_medicine_revenue();        $sold_stock   = $this->pharmacist_model->get_sold_stock_of_medicine();        $med_sold_stock = array();        if (is_array($sold_stock) && count($sold_stock) > 0) {            foreach ($sold_stock as $value) {                $med_sold_stock[$value['medicine_id']] = $value['sold_stock'];            }        }        $page_data['med_sold_stock'] = $med_sold_stock;        $page_data['all_med_revenue']   = $this->pharmacist_model->get_all_medicine_revenue();		$this->load->view('index', $page_data);    }	private function _check_medicines_stock($medicine_id)	{        $this->load->model('jobs_model');        $purchased_stock = $this->jobs_model->get_medicines_purchased_stock($medicine_id);        $sold_stock = $this->jobs_model->get_medicines_sold_stock($medicine_id);        $update_med_stock_status = array();        $sold_stock_details = array();        if (is_array($sold_stock) && count($sold_stock) > 0) {            foreach ($sold_stock as $row) {                $sold_stock_details[$row['medicine_id']] = $row['quantity'];            }        }        if (is_array($purchased_stock) && count($purchased_stock) > 0) {            foreach ($purchased_stock as $row) {                if ((!empty($sold_stock_details[$medicine_id])                    && $row['quantity'] > $sold_stock_details[$medicine_id]) || (!isset($sold_stock_details[$medicine_id]))) {                    $update_med_stock_status['status'] = self::MED_STATUS_IN_STOCK;                } else if(!empty ($medicine_id)) {                    $update_med_stock_status['status'] = self::MED_STATUS_OUT_STOCK;                }            }        }        if (is_array($update_med_stock_status) && count($update_med_stock_status) > 0) {            $this->jobs_model->update_medicine_stock_status($update_med_stock_status, $medicine_id);        }    }    public function vendors_list()    {		$page_data['page_name']    = 'manage_vendors';		$page_data['page_title']   = get_phrase('manage_vendors');        $page_data['vendors_list'] = $this->pharmacist_model->get_vendors_list();		$this->load->view('index', $page_data);    }    public function edit_vendors_details($id)    {		$page_data['page_name']    = 'manage_vendors';		$page_data['page_title']   = get_phrase('manage_vendors');        $page_data['edit_profile'] = true;        $data                      = $this->pharmacist_model->get_vendors_list($id);        $page_data['edit_vendors_list'] = !empty($data[0]) ? $data[0] : null;        $page_data['vendors_list'] = $this->pharmacist_model->get_vendors_list();		$this->load->view('index', $page_data);    }    public function add_vendor()    {            $data = $this->input->post();            $insert_data = array();			$insert_data['name']   = $this->input->post('name');			$insert_data['description']   = $this->input->post('description');            $insert_data['status']       = $this->input->post('status');            $this->pharmacist_model->add_vendor($insert_data);            $this->session->set_flashdata('flash_message', get_phrase('Saved_successfully'));			redirect(base_url() . 'index.php?pharmacist/vendors_list', 'refresh');            $this->load->view('index', $page_data);    }    public function update_vendor($id)    {        $update_data                = array();        $update_data['name']        = $this->input->post('name');        $update_data['description'] = $this->input->post('description');        $update_data['status']      = $this->input->post('status');        $this->pharmacist_model->update_vendor_details($id, $update_data);        $this->session->set_flashdata('flash_message', get_phrase('vendoe_details_updated'));        redirect(base_url() . 'index.php?pharmacist/vendors_list', 'refresh');        $page_data['edit_profile'] = $this->db->get_where('medicine_category', array(            'medicine_category_id' => $param2        ))->result_array();		$page_data['page_name']           = 'vendors_list';		$page_data['page_title']          = 'Manage Vendors';		$page_data['medicine_categories'] = $this->db->get('medicine_category')->result_array();		$this->load->view('index', $page_data);    }    public function delete_vendor($id)    {        $page_data['vendors_list'] = $this->pharmacist_model->delete_vendor($id);        $this->session->set_flashdata('flash_message', get_phrase('Saved_successfully'));        redirect(base_url() . 'index.php?pharmacist/vendors_list', 'refresh');        $this->load->view('index', $page_data);    }    public function get_manufacture_company_list()    {        $page_data['page_name']    = 'manage_company';		$page_data['page_title']   = get_phrase('manage_company');        $page_data['company_list'] = $this->pharmacist_model->get_manufacture_company_list();		$this->load->view('index', $page_data);    }    public function edit_manufacture_company_details($id)    {		$page_data['page_name']    = 'manage_company';		$page_data['page_title']   = get_phrase('manage_company');        $page_data['edit_profile'] = true;        $data                      = $this->pharmacist_model->get_manufacture_company_list($id);        $page_data['edit_company_list'] = !empty($data[0]) ? $data[0] : null;        $page_data['company_list'] = $this->pharmacist_model->get_vendors_list();		$this->load->view('index', $page_data);    }    public function add_manufacture_company()    {            $data = $this->input->post();            $insert_data = array();			$insert_data['name']   = $this->input->post('name');			$insert_data['description']   = $this->input->post('description');            $insert_data['status']       = $this->input->post('status');            $this->pharmacist_model->add_manufacture_company($insert_data);            $this->session->set_flashdata('flash_message', get_phrase('Saved_successfully'));			redirect(base_url() . 'index.php?pharmacist/get_manufacture_company_list', 'refresh');            $this->load->view('index', $page_data);    }    public function update_manufacture_company($id)    {        $update_data                = array();        $update_data['name']        = $this->input->post('name');        $update_data['description'] = $this->input->post('description');        $update_data['status']      = $this->input->post('status');        $this->pharmacist_model->update_manufacture_company($id, $update_data);        $this->session->set_flashdata('flash_message', get_phrase('company_details_updated'));        redirect(base_url() . 'index.php?pharmacist/get_manufacture_company_list', 'refresh');		$page_data['page_name']           = 'manage_company';		$page_data['page_title']          = get_phrase('manage_company');        redirect(base_url() . 'index.php?pharmacist/get_manufacture_company_list', 'refresh');        $this->load->view('index', $page_data);    }    public function delete_manufacture_company($id)    {        $page_data['vendors_list'] = $this->pharmacist_model->delete_manufacture_company($id);        $this->session->set_flashdata('flash_message', get_phrase('Saved_successfully'));        redirect(base_url() . 'index.php?pharmacist/get_manufacture_company_list', 'refresh');        $this->load->view('index', $page_data);    }    public function get_medicine_batch_list()    {        $medicine_id = $this->input->post('medicine_id');        $data        = $this->pharmacist_model->get_medicine_batch_list($medicine_id);        $this->output->set_content_type('application/json');        $this->output->set_output(json_encode(            array( 'sucesss'=>1, 'content'=> $data)        ));    }    public function get_med_distributor_details()    {        $medicine_id              = $this->input->post('medicine_id');        $data['manufacture_list'] = $this->pharmacist_model->get_med_manufacture_details($medicine_id);        $data['vendor_list']      = $this->pharmacist_model->get_med_vendors_details($medicine_id);        $this->output->set_content_type('application/json');        $this->output->set_output(json_encode(            array( 'sucesss'=>1, 'content'=> $data)        ));    }    public function generate_medicine_bill()    {        $data             = array();        $medicine_sale_id = $this->input->post('medicine_sale_id');        $columns          = array('medicine_sale_details.quantity', 'amount', 'total_amount',            'medicine_sale_details.batch', 'medicine_category.name', 'mrp', 'patient_name', 'cash_memo_no'        );        $data['content']  = $this->pharmacist_model->get_sold_medicine_details($medicine_sale_id, $columns);        $template         = $this->load->view('pharmacist/medicine_bill', $data, true);        $this->output->set_content_type('application/json');        $this->output->set_output(json_encode(            array('sucesss' => 1,                  'content' => $template)        ));    }}
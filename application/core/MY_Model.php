<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Base model for the application
 *
 */
class MY_Model extends CI_Model {

    private $_privateKey       = "";
    public $dataMappingArray   = array();
    private $_foundRows        = 0;
    protected $_filterExpArray = array();
    protected $_havingExpArray = array();
    protected $_unionQueries   = array();
    protected $_unionSQL       = NULL;
    // Komal : CPSRV-7711
    const NMEQ                 = 'NMEQ';
    const CONTAINS             = 'CONTAINS';
    const MEQ                  = 'MEQ';
    const EQ                   = 'EQ';
    const NEQ                  = 'NEQ';
    const NOTCONTAINS          = 'NOTCONTAINS';
    const GT                   = 'GT';
    const LTEQ                 = 'LTEQ';
    const IS                   = 'IS';

    // Davinder: CPSRV-7937 [Prod] Manage Stations > Usable By and Visibility filters with Match All not working, returns no data
    const OP_AND               = 'AND';
    const OP_OR                = 'OR';
    const JOINTYPE_ALL         = 'All';
    const JOINTYPE_ANY         = 'Any';

    
     /**
     * Constructor.
     * @return
     */
    public function __construct() {
        parent::__construct();

//        $this->_privateKey = db_pass_decrypt(DB_ENCRYPTION_KEY);
    }

    public function applyFilterExpression($filterObj, $filterJoinType = "All", $applyFilter = true, $portalTZ = false, $escape = true, $offset = 0, $apply_having = false)
    {
        $filterExp = '';

        $colName    = isset($filterObj->colName) ? $filterObj->colName : "";
        $operator   = isset($filterObj->operator) ? $filterObj->operator : "";
        $colValue   = isset($filterObj->colValue) ? $filterObj->colValue : "";
        $colType    = isset($filterObj->colType) ? $filterObj->colType : "";

        $operatorArray = $this->config->item('Coulomb_filter_operators');
        $colValue = trim($colValue);

        if(!empty($colName) && $colValue != '' && isset($operatorArray[$operator]))
        {
            $oprt = $operatorArray[$operator];
            switch ($operator)
            {
                case 'BTW':
                    $arrVal = explode('|',$colValue);
                    log_message('debug','column type = '.$colType);
					if(count($arrVal)==2)
					{
						if($colType == 'number' || $colType == 'floating')
                        {
                            $filterExp = "(".$colName.$oprt.$arrVal[0]." AND ".$arrVal[1].")";
                        }
                        else if($colType == 'dateTime')
                        {
                        	if($arrVal[0] == 'start' || $arrVal[0] == ''){
                        		$date1 = '0000-00-00 00:00:00';
                        	}else{
                        		$date1 = $arrVal[0].' 00:00:00';
                        	}
                        	if($arrVal[1] == 'end' || $arrVal[1] == ''){
                        		$date2 = gmdate('Y-m-d H:i:s');
                        	}else{
                        		$date2 = $arrVal[1].' 23:59:59';
                        	}
                        
                        	$filterExp = "(".$colName.$oprt.$this->convertTZ($date1,$escape,$portalTZ,$offset)." AND ".$this->convertTZ($date2,$escape,$portalTZ,$offset).")";
                        }
                        else if($colType == 'Time')
                        {
                            if($arrVal[0] == 'start' || $arrVal[0] == ''){
                                    $time1 = '00:00:00';
                            }else{
                                    $time1 = $arrVal[0];
                            }
                            if($arrVal[1] == 'end' || $arrVal[1] == ''){
                                    $time2 = gmdate('H:i:s');
                            }else{
                                    $time2 = $arrVal[1];
                            }

                            $filterExp = "(".$colName.$oprt.$this->db->escape($time1)." AND ".$this->db->escape($time2).")";
                        }
                    }
					break;

				case 'CONTAINS':
                    $filterExp = $colName.$oprt."'%".str_replace('_','\\_',addslashes($colValue))."%'";
                    break;

				case 'NOTCONTAINS':
                    $filterExp = "(".$colName.$oprt."'%".str_replace('_','\\_',addslashes($colValue))."%' or ".$colName." IS NULL)";
                    break;

                // Adding support for multiselect
                // UI sends a PIPE separated list of options to filter on.
				case 'MEQ':
                    $filterExp = $colName . $oprt . "('" . str_replace("|", "','", $colValue)  . "')";
                    break;
				case self::NMEQ:
					$filterExp = $colName . $oprt . "('" . str_replace("|", "','", $colValue)  . "')";
				break;
            
                // Adding support for NULL values
                case 'IS':
                    $filterExp = $colName.$oprt.$colValue;
                    break;

				default:
                    if(in_array($colType, array('number','floating','dbcolumn')))
                    {
                        $filterExp = $colName.$oprt.$colValue;
                    }
                    else if($colType == 'dateTime')
                    {
                        //Liu Pan: performance enhancement for reports with datetime filter to leverage existing DB index
                        $date1 = $colValue.' 00:00:00';
                        $date2 = $colValue.' 23:59:59';
                        if($operator == 'EQ')
                        {
                            $filterExp = "(".$colName.$operatorArray['BTW'].$this->convertTZ($date1,$escape,$portalTZ,$offset)." AND ".$this->convertTZ($date2,$escape,$portalTZ,$offset).")";
                        }
                        else if($operator == 'NEQ')
                        {
                            $filterExp = "(".$colName.' NOT '.$operatorArray['BTW'].$this->convertTZ($date1,$escape,$portalTZ,$offset)." AND ".$this->convertTZ($date2,$escape,$portalTZ,$offset).")";
                        }
                        else
                        {
                            if($operator == 'LT' || $operator == 'GTEQ')
                            {
                                $filterExp = $colName.$oprt.$this->convertTZ($date1,$escape,$portalTZ,$offset);
                            }
                            else if($operator == 'GT' || $operator == 'LTEQ')
                            {
                                $filterExp = $colName.$oprt.$this->convertTZ($date2,$escape,$portalTZ,$offset);
                            }
                        }
                    }
                    else
                    {
                        $filterExp = $colName.$oprt.$this->db->escape($colValue);
                    }
                    break;
            }
        }

		if (!empty($filterExp) && (true == $apply_having)) {
			$this->_havingExpArray[] = $filterExp;
		} elseif (!empty($filterExp) && (true == $applyFilter)) {
			$this->_filterExpArray[] = $filterExp;
		}
		else if($applyFilter == false)
		{
			return $filterExp;
		}
    }

     /**
     * Integrates filter expressions in DB query
     *
     * @author deepak
     * @method compileFilters
     * @access public
     *
     * @param type $filterJoinType
     */
    public function compileFilters($filterJoinType = "All",$applyFilter = true)
    {
        if (is_array($this->_filterExpArray) && count($this->_filterExpArray) > 0) {
            if ($filterJoinType == "Any") {
                $joinType = ' OR ';
            } else {
                $joinType = ' AND ';
            }
            
            $filterExp = ' ( ' . join(' ' . $joinType . ' ', $this->_filterExpArray) . ' ) ';
            $this->_filterExpArray = array();
            
            if ($applyFilter == true) {
                $this->db->where($filterExp, NULL, FALSE);
            } else {
                return $filterExp;
            }
        }
    }
    
}
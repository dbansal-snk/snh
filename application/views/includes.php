<link rel="stylesheet" href="<?php echo base_url();?>template/css/font.css">

<!--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800">-->
<link href="<?php echo base_url();?>template/css/bayanno.css" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>template/css/jquery.dataTables.css" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>template/css/snh.css" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>template/css/jquery-ui.css" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>template/bootstrap-sweetalert-master/dist/sweetalert.css" media="screen" rel="stylesheet" type="text/css" />

<!--[if lt IE 9]>
<script src="<?php echo base_url();?>template/js/html5shiv.js" type="text/javascript"></script>

<script src="<?php echo base_url();?>template/js/excanvas.js" type="text/javascript"></script>
<![endif]-->
<script src="<?php echo base_url();?>template/bootstrap-sweetalert-master/dist/sweetalert.min.js" type="text/javascript"></script>

<script src="<?php echo base_url();?>template/js/jquery-3.1.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>template/js/bayanno.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>template/js/jquery-ui.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>template/js/snh_common.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>template/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>template/js/datatable/ReportTable.js" type="text/javascript"></script>

<?php

//////////LOADING SYSTEM SETTINGS FOR ALL PAGES AND ACCOUNTS/////////

$system_name	=	$this->db->get_where('settings' , array('type'=>'system_name'))->row()->description;

$system_title	=	$this->db->get_where('settings' , array('type'=>'system_title'))->row()->description;
<script src="<?php echo base_url();?>template/js/manage_categories/manage_categories.js" type="text/javascript"></script><div class="box">	<div class="box-header">    	<!------CONTROL TABS START------->		<ul class="nav nav-tabs nav-tabs-left">        	<?php if(isset($edit_profile)):?>			<li class="active">            	<a href="#edit" data-toggle="tab"><i class="icon-wrench"></i>					Edit Medicine                </a>            </li>            <?php endif;?>			<li class="<?php if(!isset($edit_profile))echo 'active';?>">            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i>					Medicine List                </a>            </li>			<li>            	<a href="#add" data-toggle="tab"><i class="icon-plus"></i>                    Add Medicine                </a>            </li>		</ul>    	<!------CONTROL TABS END------->	</div>	<div class="box-content padded">		<div class="tab-content">        	<!----EDITING FORM STARTS---->        	<?php if(isset($edit_profile)):?>			<div class="tab-pane box active" id="edit" style="padding: 5px">                <div class="box-content">                	<?php foreach($edit_profile as $row):?>                <form id="update_medicine" onsubmit="return false;" class="form-horizontal validatable">                        <div class="padded">                            <div class="control-group">                                <label class="control-label">Name</label>                                <div class="controls">                                    <input type="text" class="validate[required]" name="name" value="<?php echo $row['name'];?>"/>                                </div>                            </div>                            <div class="control-group">                                <label class="control-label"><?php echo get_phrase('medicine_category_description');?></label>                                <div class="controls">                                    <input type="text" class="" name="description" value="<?php echo $row['description'];?>"/>                                </div>                            </div>                            <input type="hidden" id="medicine_id" name="medicine_id" value="<?php echo $row['medicine_category_id']; ?>">                        </div>                        <div class="form-actions">                            <button type="submit" id="update_medicine_button" class="btn btn-blue">Update</button>                        </div>                </form>                    <?php endforeach;?>                </div>			</div>            <?php endif;?>            <!----EDITING FORM ENDS--->            <!----TABLE LISTING STARTS--->                        <div class="box-content padded tab-pane box <?php if(!isset($edit_profile))echo 'active';?>" id="list">                <div class="tab-content">                       <div id="filter_containers">                        <button id="apply_filter">Apply Filter</button>                    </div>                    <div id="report">                        <table id="medicine_list" class="display" cellspacing="0" width="100%"  class="dTable responsive">                            <thead>                                <tr>                                    <th><div>Name</div></th>                                    <th><div>Description</div></th>                                    <th><div>Status</div></th>                                </tr>                            </thead>                        </table>                    </div>                </div>            </div>            <!----TABLE LISTING ENDS--->			<!----CREATION FORM STARTS---->			<div class="tab-pane box" id="add">                <div class="box-content">                    <form id="add_medicine" onsubmit="return false;" class="form-horizontal validatable">                        <div class="padded">                            <div class="control-group">                                <label class="control-label">Name</label>                                <div class="controls">                                    <input type="text" class="validate[required]" name="name"/>                                </div>                            </div>                            <div class="control-group">                                <label class="control-label">Description</label>                                <div class="controls">                                    <input type="text" class="" name="description"/>                                </div>                            </div>                        </div>                        <div class="form-actions">                            <button type="submit" id="add_medicine_button" class="btn btn-blue"><?php echo get_phrase('add_medicine_category');?></button>                        </div>                    </form>                </div>			</div>			<!----CREATION FORM ENDS--->  		</div>	</div></div><script>$(document).ready( function () {    $('#medicine_list').DataTable({        "scrollY":        "800px",        "scrollCollapse": true,        "paging":         false    });        var configUrl = 'index.php?pharmacist/get_medicine_list_config';    var listUrl   = 'index.php?pharmacist/get_medicine_listing';    var tableId   = 'medicine_list';    ReportTable.getReportConfig(configUrl, listUrl, tableId);});$("#add_medicine_button").on("click", function() {    manage_category.addMedicine();});$("#update_medicine_button").on("click", function() {    manage_category.updateMedicine();});jQuery('body').on('click','.delete',function(eve){	eve.preventDefault();	var deleteLink = jQuery(this).attr('data-delete');	swal({  	title: 'Are you sure?',   text: "You won't be able to Delete this!",  	type: 'warning',			showCancelButton:true,			showConfirmButton:true,  },  function(){			window.location.href = deleteLink;});});</script>
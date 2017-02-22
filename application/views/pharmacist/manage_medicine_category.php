<script src="<?php echo base_url();?>template/js/manage_categories/manage_categories.js" type="text/javascript"></script><div class="box">	<div class="box-header">    	<!------CONTROL TABS START------->		<ul class="nav nav-tabs nav-tabs-left">        	<?php if(isset($edit_profile)):?>			<li class="active">            	<a href="#edit" data-toggle="tab"><i class="icon-wrench"></i>					Edit Medicine                </a>            </li>            <?php endif;?>			<li class="<?php if(!isset($edit_profile))echo 'active';?>">            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i>					Medicine List                </a>            </li>			<li>            	<a href="#add" data-toggle="tab"><i class="icon-plus"></i>                    Add Medicine                </a>            </li>		</ul>    	<!------CONTROL TABS END------->	</div>	<div class="box-content padded">		<div class="tab-content">        	<!----EDITING FORM STARTS---->        	<?php if(isset($edit_profile)):?>			<div class="tab-pane box active" id="edit" style="padding: 5px">                <div class="box-content">                	<?php foreach($edit_profile as $row):?>                <form id="update_medicine" onsubmit="return false;" class="form-horizontal validatable">                        <div class="padded">                            <div class="control-group">                                <label class="control-label">Name</label>                                <div class="controls">                                    <input type="text" class="validate[required]" name="name" value="<?php echo $row['name'];?>"/>                                </div>                            </div>                            <div class="control-group">                                <label class="control-label"><?php echo get_phrase('medicine_category_description');?></label>                                <div class="controls">                                    <input type="text" class="" name="description" value="<?php echo $row['description'];?>"/>                                </div>                            </div>                            <input type="hidden" id="medicine_id" name="medicine_id" value="<?php echo $row['medicine_category_id']; ?>">                        </div>                        <div class="form-actions">                            <button type="submit" id="update_medicine_button" class="btn btn-blue">Update</button>                        </div>                </form>                    <?php endforeach;?>                </div>			</div>            <?php endif;?>            <!----EDITING FORM ENDS--->            <!----TABLE LISTING STARTS--->            <div class="tab-pane box <?php if(!isset($edit_profile))echo 'active';?>" id="list">                <table id="medicine_list" class="display" cellspacing="0" width="100%"  class="dTable responsive">                	<thead>                		<tr>                    		<th><div>Name</div></th>                    		<th><div><?php echo get_phrase('description');?></div></th>                            <th><div><?php echo get_phrase('status');?></div></th>                    		<th><div><?php echo get_phrase('options');?></div></th>						</tr>					</thead>                    <tbody>                    	<?php foreach($medicine_categories as $row):?>                        <tr>							<td><?php echo $row['name'];?></td>							<td><?php echo $row['description'];?></td>                            <td><?php echo $row['status'];?></td>							<td align="center">            	<a href="<?php echo base_url();?>index.php?pharmacist/manage_medicine_category/edit/<?php echo $row['medicine_category_id'];?>"                	rel="tooltip" data-placement="top" data-original-title="<?php echo get_phrase('edit');?>" class="btn btn-blue">                		<i class="icon-wrench"></i>                </a>            	<!-- <a href="<?php echo base_url();?>index.php?pharmacist/manage_medicine_category/delete/<?php echo $row['medicine_category_id'];?>"                	rel="tooltip" data-placement="top" data-original-title="<?php echo get_phrase('delete');?>" class="btn btn-red ">                		<i class="icon-trash"></i>                </a> -->            	<a href="javascript:;"													data-delete = "<?php echo base_url();?>index.php?pharmacist/manage_medicine_category/delete/<?php echo $row['medicine_category_id'];?>"                	rel="tooltip" data-placement="top" data-original-title="<?php echo get_phrase('delete');?>" class="btn btn-red delete">                		<i class="icon-trash"></i>                </a>        					</td>                        </tr>                        <?php endforeach;?>                    </tbody>                </table>			</div>            <!----TABLE LISTING ENDS--->			<!----CREATION FORM STARTS---->			<div class="tab-pane box" id="add">                <div class="box-content">                    <form id="add_medicine" onsubmit="return false;" class="form-horizontal validatable">                        <div class="padded">                            <div class="control-group">                                <label class="control-label">Name</label>                                <div class="controls">                                    <input type="text" class="validate[required]" name="name" id="name"/>                                </div>                            </div>                            <div class="control-group">                                <label class="control-label">Description</label>                                <div class="controls">                                    <input type="text" class="" name="description"/>                                </div>                            </div>                        </div>                        <div class="form-actions">                            <button type="submit" id="add_medicine_button" class="btn btn-blue"><?php echo get_phrase('add_medicine_category');?></button>                        </div>                    </form>                </div>			</div>			<!----CREATION FORM ENDS--->  		</div>	</div></div><script>

$(document).ready(function () {// $("#add_medicine").validate({//// 	rules: {//     name: {//       required: true,////     }//   },//   messages: {//     name: {//       required: "We need your name",////     }//   }//   submitHandler: function(form) {//     // do other things for a valid form//     form.submit();//   }// });    $('#medicine_list').DataTable({        "scrollY":        "800px",        "scrollCollapse": true,        "paging":         false    });} );$("#add_medicine_button").on("click", function() {    manage_category.addMedicine();});$("#update_medicine_button").on("click", function() {    manage_category.updateMedicine();});jQuery('body').on('click','.delete',function(eve){	eve.preventDefault();	var deleteLink = jQuery(this).attr('data-delete');	swal({  	title: 'Are you sure?',   text: "You won't be able to revert this!",  	type: 'warning',			showCancelButton:true,			showConfirmButton:true,  },  function(){			window.location.href = deleteLink;});});</script>
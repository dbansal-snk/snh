<script src="<?php echo base_url();?>template/js/medicine.js" type="text/javascript"></script>
<div class="box">

	<div class="box-header">

    

    	<!------CONTROL TABS START------->

		<ul class="nav nav-tabs nav-tabs-left">

        	<?php if(isset($edit_profile)):?>

			<li class="active">

            	<a href="#edit" data-toggle="tab"><i class="icon-wrench"></i>

					<?php echo get_phrase('edit_stock');?>

                    	</a></li>

            <?php endif;?>

			<li class="<?php if(!isset($edit_profile))echo 'active';?>">

            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i>

					<?php echo get_phrase('stock_list');?>

                    	</a></li>

			<li>

            	<a href="#add" data-toggle="tab"><i class="icon-plus"></i>

					<?php echo get_phrase('New_stock');?>

                    	</a></li>

		</ul>

    	<!------CONTROL TABS END------->

        

	</div>

	<div class="box-content padded">

		<div class="tab-content">

        	<!----EDITING FORM STARTS---->

        	<?php if(isset($edit_profile)):?>

			<div class="tab-pane box active" id="edit" style="padding: 5px">

                <div class="box-content">

                	<?php foreach($edit_profile as $row):?>

                    <?php echo form_open('pharmacist/manage_medicine/edit/do_update/'.$row['id'] , array('class' => 'form-horizontal validatable', 'onsubmit' => 'return medicine.validate_medicine_stock_form();'));?>

                        <div class="padded">
                            
                            <div class="control-group">
                                <label class="control-label">Invoice Number</label>
                                <div class="controls">
                                    <input type="text" class="validate[required]" name="invoice" value="<?php echo !empty($row['invoice_no']) ? $row['invoice_no'] : null; ?>"/>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('medicine_name');?></label>
                                <div class="controls">
                                    <select name="medicine_category_id" id="medicine_id" class="uniform" style="width:100%;" onchange="medicine.get_med_distributor_details();">
                                    	<?php 
										$medicine_categories = get_all_medicine_list();
                                        
										foreach($medicine_categories as $row2):
										?>
                                    		<option value="<?php echo $row2['medicine_category_id'];?>" <?php if($row2['medicine_category_id']==$row['medicine_category_id'])echo 'selected';?>>
												<?php echo $row2['name'];?>
                                                	</option>
                                        <?php
										endforeach;
										?>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('description');?></label>
                                <div class="controls">
                                    <textarea name="description"><?php echo $row['description'];?></textarea>
                                </div>
                            </div>
                            
                             <div class="control-group">
                                <label class="control-label">Manufacturing Company</label>
                                <div class="controls">
                                    <select name="manufacturing_company" id="manufacture_company_id" class="uniform" style="width:100%;">
                                    	<option value="0">--Select Manufacturer--</option>
                                        <?php 
                                        if (!empty($manufacture_list) && is_array($manufacture_list) && count($manufacture_list) > 0) {
                                            foreach($manufacture_list as $company) {
                                                $selected = '';
                                                if (!empty($company['id'])) {
                                                    if ($company['id'] == $row['manufacture_company_id']) {
                                                        $selected = 'selected';
                                                    }
                                                    ?>
                                                        <option value="<?php echo $company['id'];?>" <?php echo $selected; ?>><?php echo $company['name'];?></option>
                                                    <?php
                                                }
                                            }
                                        }
										?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('vendor_name');?></label>
                                <div class="controls">
                                    <select name="vendor_name" id="vendor_id" class="uniform" style="width:100%;">
                                        <option value="0">--Select Vendor--</option>
                                    	<?php
                                        if (!empty($vendor_list) && is_array($vendor_list) && count($vendor_list) > 0) {
                                            foreach($vendor_list as $row2) {
                                            ?>
                                                <option value="<?php echo $row2['id'];?>" <?php if($row2['id'] == $row['vendor_id'])echo 'selected';?>>
                                                    <?php echo $row2['name'];?>
                                                        </option>
                                            <?php
                                            }
                                        }
										?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('quantity');?></label>
                                <div class="controls">
                                    <input type="number" min="0" step="0.1" class="validate[required]" name="quantity" value="<?php echo $row['quantity'];?>"/>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('No._of_free_item');?></label>
                                <div class="controls">
                                    <input type="number" min="0" step="0.1" class="validate[required]" name="free_item" value="<?php echo $row['free_item'];?>"/>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('is_loose_item');?></label>
                                <div class="controls">
                                    <input type="checkbox" id="is_loose_item" name="is_loose_item" <?php echo !empty($row['is_loose_item']) ? 'checked' : ''; ?> onchange="medicine.get_loose_item_derails();"/>
                                </div>
                            </div>
                            
                            <div id="loose_item_details" class="control-group" style="display: <?php echo !empty($row['is_loose_item']) ? '' : 'none'; ?>;">
                                <label class="control-label"><?php echo get_phrase('packing');?></label>
                                <div class="controls">
                                    <input type="number" id="loose_item_quantity" name="loose_item_quantity" value="<?php echo !empty($row['loose_item_quantity']) ? $row['loose_item_quantity'] : 0;?>"/>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label">Rate</label>
                                <div class="controls">
                                    <input type="text" class="validate[required]" name="price" value="<?php echo $row['price'];?>"/> per strip/ml
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('M.R.P');?></label>
                                <div class="controls">
                                    <input type="text" class="validate[required]" name="mrp" value="<?php echo $row['mrp'];?>"/> per strip/ml
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('purchase_date');?></label>
                                <div class="controls">
                                    <input type="datetime" class="datepicker fill-up validate[required]" name="purchase_date" value="<?php echo $row['medicine_purchase_date'];?>"/>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('expiry_date');?></label>
                                <div class="controls">
                                    <input type="text" class="datepicker fill-up validate[required]" name="expiry_date" value="<?php echo $row['medicine_expiry_date'];?>"/>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('batch');?></label>
                                <div class="controls">
                                    <input type="text" class="validate[required]" name="batch" value="<?php echo $row['batch'];?>"/>
                                </div>
                            </div>
                           
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('discount');?></label>
                                <div class="controls">
                                    <input type="number" min="0" step="0.01" class="validate[required]" name="discount" value="<?php echo $row['discount'];?>"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('vat');?></label>
                                <div class="controls">
                                    <input type="number" min="0" step="0.01" class="validate[required]" name="vat" value="<?php echo $row['vat'];?>"/>
                                </div>
                            </div>

<!--                            <div class="control-group">
                                <label class="control-label"><?php // echo get_phrase('status');?></label>
                                <div class="controls">
                                    <select name="status" class="uniform" style="width:100%;">
                                    		<option value="In Stock" <?php //if($row['status'] == 'In Stock')echo 'selected';?>><?php //echo get_phrase('In_Stock');?></option>
                                            <option value="Stock Out" <?php //if($row['status'] == 'Stock Out')echo 'selected';?>><?php //echo get_phrase('Out_Of_Stock');?></option>
                                    </select>
                                </div>
                            </div>-->



                        </div>

                        <div class="form-actions">

                            <button type="submit" class="btn btn-blue"><?php echo get_phrase('update');?></button>

                        </div>

                    <?php echo form_close();?>

                    <?php endforeach;?>

                </div>

			</div>

            <?php endif;?>

            <!----EDITING FORM ENDS--->

            

            <!----TABLE LISTING STARTS--->

            <div class="tab-pane box <?php if(!isset($edit_profile))echo 'active';?>" id="list">

				

                <table id="stock_list" class="display" cellspacing="0" width="100%" class="dTable responsive">

                	<thead>

                		<tr>


                    		<th><div>Medicine Name</div></th>

                    		<th><div><?php echo get_phrase('quantity');?></div></th>

                    		<th><div>Rate</div></th>
                            <th><div>Vendor</div></th>
                    		<th><div><?php echo get_phrase('manufacturing_company');?></div></th>

                    		<th><div><?php echo get_phrase('status');?></div></th>

                    		<th><div><?php echo get_phrase('options');?></div></th>

						</tr>

					</thead>

                    <tbody>

                    	<?php foreach($medicines as $row):?>

                        <tr>


							<td><?php echo $row['medicine_name'];?></td>
                            <?php        
                            $quantity = 0;
                            $free_item_quantity = !empty($row['free_item']) ? $row['free_item'] : 0;
                            $quantity = $row['quantity'] + $free_item_quantity;
                            ?>

							<td><?php echo $quantity;?></td>

							<td><?php echo $row['price'];?></td>
                            <td><?php echo $row['vendor_name'];?></td>
							<td><?php echo $row['company_name'];?></td>

							<td><?php echo $row['status'];?></td>

							<td align="center">

                            	<a href="<?php echo base_url();?>index.php?pharmacist/manage_medicine/edit/<?php echo $row['id'];?>"

                                	rel="tooltip" data-placement="top" data-original-title="<?php echo get_phrase('edit');?>" class="btn btn-blue">

                                		<i class="icon-wrench"></i>

                                </a>

                            	<a href="javascript:;"
													data-delete = "<?php echo base_url();?>index.php?pharmacist/manage_medicine/delete/<?php echo $row['id'];?>"
                                	rel="tooltip" data-placement="top" data-original-title="<?php echo get_phrase('delete');?>" class="btn btn-red delete">

                                		<i class="icon-trash"></i>

                                </a>
                            	<!-- <a href="<?php echo base_url();?>index.php?pharmacist/manage_medicine/delete/<?php echo $row['id'];?>" onclick="return confirm('delete?')"

                                	rel="tooltip" data-placement="top" data-original-title="<?php echo get_phrase('delete');?>" class="btn btn-red">

                                		<i class="icon-trash"></i>

                                </a> -->

        					</td>

                        </tr>

                        <?php endforeach;?>

                    </tbody>

                </table>

			</div>

            <!----TABLE LISTING ENDS--->

            

            

			<!----CREATION FORM STARTS---->

			<div class="tab-pane box" id="add" style="padding: 5px">

                <div class="box-content">

                    <?php echo form_open('pharmacist/manage_medicine/create/' , array('class' => 'form-horizontal validatable', 'onsubmit' => 'return medicine.validate_medicine_stock_form();'));?>

                        <div class="padded">

                          <div class="control-group">
                                <label class="control-label">Invoice Number</label>
                                <div class="controls">
                                    <input type="text" class="validate[required]" name="invoice"/>
                                </div>
                            </div>

                            <div class="control-group">

                                <label class="control-label"><?php echo get_phrase('medicine_name');?></label>

                                <div class="controls">

                                    <select name="medicine_category_id" id="medicine_id" class="uniform validate[required]" style="width:100%;">

                                    	<?php 

										$medicine_categories = get_all_medicine_list(); ?>
                                        <option value="0">--Select Medicine--</option>
                                        <?php
										foreach($medicine_categories as $row):

										?>

                                    		<option value="<?php echo $row['medicine_category_id'];?>"><?php echo $row['name'];?></option>

                                        <?php

										endforeach;

										?>

                                    </select>

                                </div>

                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('description');?></label>
                                <div class="controls">
                                    <!--<input type="textarea" class="" name="description" />-->
                                    <textarea name="description"></textarea>
                                </div>
                            </div>
                            
                             <div class="control-group">
                                <label class="control-label">Manufacturing Company</label>
                                <div class="controls">
                                    <select name="manufacturing_company" id="manufacture_company_id" class="uniform validate[required]" style="width:100%;">
                                    	<option value="0">--Select Manufacturer--</option>
                                        <?php 
                                            $company_list = get_company_list();
                                            if (is_array($company_list) && count($company_list) > 0) { 
                                                foreach ($company_list as $company) {
                                            ?>
                                              <option value="<?php echo $company['id']; ?>"><?php echo $company['name']; ?></option>  
                                        <?php 
                                            }}
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                             <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('vendor_name');?></label>
                                <div class="controls">
                                    <select name="vendor_name" id="vendor_id" class="vendor_id" >
                                    	<option value="0">--Select Vendor--</option>
                                        <?php
                                            $vendor_list = get_vendors_list();
                                            if (is_array($vendor_list) && count($vendor_list) > 0) {
                                                foreach ($vendor_list as $vendor) {
                                            ?>
                                              <option value="<?php echo $vendor['id']; ?>"><?php echo $vendor['name']; ?></option>
                                        <?php
                                            }}
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('quantity');?></label>
                                <div class="controls">
                                    <input type="number" min="0" step="0.1" class="validate[required]" name="quantity"/>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('No._of_free_item');?></label>
                                <div class="controls">
                                    <input type="number" min="0" step="0.1" class="validate[required]" name="free_item"/>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('is_loose_item');?></label>
                                <div class="controls">
                                    <input type="checkbox" id="is_loose_item" name="is_loose_item" onchange="medicine.get_loose_item_derails();"/>
                                </div>
                            </div>
                            
                            <div id="loose_item_details" class="control-group" style="display: none;">
                                <label class="control-label"><?php echo get_phrase('packing');?></label>
                                <div class="controls">
                                    <input type="number" name="loose_item_quantity" id="loose_item_quantity"/>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label">Rate</label>
                                <div class="controls">
                                    <input type="text" class="validate[required]" name="price"/> per strip/ml
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('M.R.P');?></label>
                                <div class="controls">
                                    <input type="text" class="validate[required]" name="mrp"/> per strip/ml
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('purchase_date');?></label>
                                <div class="controls">
                                    <input type="datetime" class="datepicker fill-up validate[required]" name="purchase_date"/>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('expiry_date');?></label>
                                <div class="controls">
                                    <input type="text" class="datepicker fill-up validate[required]" name="expiry_date"/>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('batch');?></label>
                                <div class="controls">
                                    <input type="text" class="validate[required]" name="batch"/>
                                </div>
                            </div>
                           
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('discount');?></label>
                                <div class="controls">
                                    <input type="number" min="0" step="0.01" class="validate[required]" name="discount"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('vat');?></label>
                                <div class="controls">
                                    <input type="number" min="0" step="0.01" class="validate[required]" name="vat"/>
                                </div>
                            </div>
                            

<!--                            <div class="control-group">
                                <label class="control-label"><?php //echo get_phrase('status');?></label>
                                <div class="controls">
                                     <select name="status" class="uniform" style="width:100%;">
                                    		<option value="In Stock"><?php //echo get_phrase('In_Stock');?></option>
                                            <option value="Stock Out"><?php //echo get_phrase('Out_Of_Stock');?></option>
                                    </select>
                                </div>
                            </div>-->



                        </div>

                        <div class="form-actions">

                            <button type="submit" class="btn btn-blue">Add Stock</button>

                        </div>
.
                    <?php echo form_close();?>

                </div>

			</div>

			<!----CREATION FORM ENDS--->

            

		</div>

	</div>
<div id="datepicker"></div>
 
</div>
<script>
 
    $( ".datepicker" ).datepicker({
        changeYear: true,
        changeMonth: true,
        dateFormat: "dd-mm-yy"
    });
 

$(document).ready( function () {
    $('#stock_list').DataTable({
        "scrollY":        "800px",
        "scrollCollapse": true,
        "paging":         false
    });
				    
    $(".medicine_id").select2();
    $(".manufacture_company_id").select2();
    $(".vendor_id").select2();
});

    jQuery('body').on('click','.delete',function(eve){
        var currentRow=$(this).closest("tr");
        var cmpname=currentRow.find(".cname").html();
        eve.preventDefault();
        var deleteLink = jQuery(this).attr('data-delete');
        swal({
        title: '',
            text: "Are you sure want to delete medicine stock " +cmpname+" ?",
        type: 'warning',
            showCancelButton:true,
            showConfirmButton:true,
      },
      function(){
        window.location.href = deleteLink;
    });

});


</script>
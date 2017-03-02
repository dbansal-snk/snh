<script src="<?php echo base_url();?>template/js/medicine.js" type="text/javascript"></script>
<style>
.form-horizontal .controls {
    width: 265px !important;
}
</style>
<div class="box">

	<div class="box-header">



    	<!------CONTROL TABS START------->

		<ul class="nav nav-tabs nav-tabs-left">

        	<?php if(isset($edit_profile)):?>

			<li class="active">

            	<a href="#edit" data-toggle="tab"><i class="icon-wrench"></i>

					<?php echo get_phrase('edit_prescription');?>

                    	</a></li>

            <?php endif;?>
<?php if(!isset($edit_profile)):?>
			<li class="<?php if(!isset($edit_profile))echo 'active';?>">

            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i>

					<?php echo get_phrase('prescription_list');?>

                    	</a>
            </li>

            <li>
            	<a href="#add" data-toggle="tab"><i class="icon-plus"></i><?php echo get_phrase('sold_to_patient');?></a>
            </li>
  <?php endif;?>
		</ul>

    	<!------CONTROL TABS END------->



	</div>

	<div class="box-content padded">

		<div class="tab-content">

        	<!----EDITING FORM STARTS---->

        	<?php if(isset($edit_profile)):
            ?>

			<div class="tab-pane box active" id="edit" style="padding: 5px">
                <div class="box-content">

                    <form method="post" action="<?php echo base_url();?>index.php?pharmacist/manage_prescription/edit/do_update/<?php echo $edit_profile[0]->medicine_sale_id;?>" class="form-horizontal validatable">
                      <!-- <input type="hidden" id="mid" name="mid" value="<?php //echo $edit_profile[0]->medicine_sale_id;?>"> -->

  <?php
                            $medicine_price_details = get_medicine_mrp();
                    ?>

                        <div class="padded">

                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('Patient_Name');?></label>

                                <div class="controls">
                                    <input type="text" id="patient_name" name="patient_name" value="<?php echo $edit_profile['0']->patient_name;?>"/>
                                </div>
                            </div>
                            <?php
                             $i=1;
                            foreach ($edit_profile as $key => $value) {

                             ?>
                             <input type="hidden" id="md_id" name="md_id[]" value="<?php echo $value->id;?>">
                            <div id="med_details_container">
                                <div class="control-group"id="selling_med_detais">
                                    <label class="control-label"><?php echo get_phrase('Medicine'.$i );?></label>
                                    <div class="controls" id="medicine_list">
                                        <select name="medicine_id" id="medicine_id<?= $i ?>" class="medicine_id" onchange="medicine.calculate_med_price(), medicine.can_sell_loose_item(1), medicine.getMedicineBatchList(1);">
                                            <option value="0">--Select Medicine--</option>
                                            <?php
                                            $medicine	= get_medicine_list();
                                            foreach($medicine as $row):
                                            ?>
                                                <option <?php  if($value->medicine_id==$row['medicine_category_id']){echo "selected";} ?> value="<?php echo $row['medicine_category_id'];?>"><?php echo $row['name'];?></option>
                                            <?php
                                            endforeach;
                                            ?>

                                        </select>
                                </div>
                            </div>

                                <div id="med_details_container">
                            <div class="control-group">
                                    <label class="control-label">Batch<?= $i ?></label>
                                    <div class="controls" id="batch_list">
                                        <select   name="batch_id[]" id="batch_id<?= $i ?>">
                                        <option value=""> <?php echo $value->batch; ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="control-group" id="loose_quantity_element1">
                                    <label class="control-label"><?php echo get_phrase('selling_loose_quantity'.$i);?></label>
                                <div class="controls">
                                        <input type="checkbox" <?php if($value->is_loose_sale==1){echo "checked";} ?> id="selling_loose_quantity<?= $i ?>" name="selling_loose_quantity[]" onchange="medicine.calculate_med_price();"/>
                                </div>
                            </div>

                            <div class="control-group">
                                    <label class="control-label"><?php echo get_phrase('Quantity'.$i);?></label>
                                <div class="controls">
                                        <input value="<?php echo $value->quantity;?>" type="number" id="med_quantity<?= $i ?>" class="validate[required]" name="quantity[]" onkeyup="medicine.calculate_med_price();"/>
                                </div>

                            </div>

                            <div class="control-group">
                                    <label class="control-label"><?php echo get_phrase('Amount'.$i);?></label>
                                <div class="controls">
                                        <input value="<?php echo $value->amount;?>" type="text" id="med_amount1" readonly="readonly" name="amount1"/>
                                </div>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="addMedicine" onclick="medicine.add_medicine_container();"></div>
                                <div class="addMedicineText" onclick="medicine.add_medicine_container();"><?php echo get_phrase('Add_Medicine');?></div>
                            </div>

                            <input type="hidden" id="total_med_details" name="total_med_details" value="1">

                        </div>
                        <?php
                        $i++;
                            } ?>
                        <div class="control-group">
                               <label class="control-label"><?php echo get_phrase('Discount');?></label>
                                <div class="controls">
                                   <input type="number" value="<?php echo $value->discount;?>" id="discount" min="0" step="0.1" name="discount" onkeyup="medicine.calculate_med_price();"/>
                                                </div>
                                        </div>


                            <div class="control-group">
                           <label class="control-label"><?php echo get_phrase('total_amount');?></label>
                                <div class="controls">
                               <input type="text" value="<?php echo $edit_profile['0']->total_amount;?>"  id="total_amount" readonly="readonly"  name="total_amount"/>
                                </div>
                            </div>

                            <div class="control-group">
                            <label class="control-label">Selling Date</label>
                                <div class="controls">
                                <input type="text" id="selling_date"  class="datepicker" value="<?php echo $edit_profile['0']->amount;?>"  name="selling_date" />
                                </div>
                        </div>

                        <div class="form-actions">

                        <button type="submit" class="btn btn-blue" id="save_prescribed_details"><?php echo get_phrase('Update');?></button>

                        </div>

                </div>
                    </form>
                </div>

			</div>

            <?php endif;?>

            <!----EDITING FORM ENDS--->



            <!----TABLE LISTING STARTS--->

            <div class="tab-pane box <?php if(!isset($edit_profile))echo 'active';?>" id="list">



                <table id="sold_to_patient_list" class="display" cellspacing="0" width="100%"  class="dTable responsive">

                	<thead>
                		<tr>
                    		<th><div><?php echo get_phrase('Patient');?></div></th>
                    		<th><div><?php echo get_phrase('Medicine');?></div></th>
                    		<th><div><?php echo get_phrase('quantity');?></div></th>
                    		<th><div><?php echo get_phrase('total_amount');?></div></th>
                            <th><div><?php echo get_phrase('selling_date');?></div></th>
                            <th><div><?php echo get_phrase('options');?></div></th>
						</tr>
					</thead>

                    <tbody>

                    	<?php $count = 1;foreach($prescriptions as $row):?>

                        <tr>
							<td class="cname"><?php echo $row['patient_name'];?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['quantity'];?></td>
                            <td><?php echo number_format($row['total_amount'], 2);?></td>
                            <td><?php echo $row['medicine_sold_date'];?></td>
							<td align="center">
                            	<a href="<?php echo base_url();?>index.php?pharmacist/edit_sell/<?php echo $row['id'];?>"
                                	rel="tooltip" data-placement="top" data-original-title="<?php echo get_phrase('edit');?>" class="btn btn-blue">
                                		<i class="icon-wrench"></i>
                                </a>

                            	<a href="javascript:;"
													data-delete = "<?php echo base_url();?>index.php?pharmacist/manage_prescription/delete/<?php echo $row['id'];?>"
                                	rel="tooltip" data-placement="top" data-original-title="<?php echo get_phrase('delete');?>" class="btn btn-red delete">
                                    <i class="icon-trash"></i>
                                </a>
                            	<!-- <a href="<?php echo base_url();?>index.php?pharmacist/manage_prescription/delete/<?php echo $row['id'];?>" onclick="return confirm('delete?')"
                                	rel="tooltip" data-placement="top" data-original-title="<?php echo get_phrase('delete');?>" class="btn btn-red">
                                    <i class="icon-trash"></i>
                                </a> -->

                                <a href="javascript: void(0);" onclick="medicine.generate_medicine_bill(<?php echo $row['id'];?>);"
                                	rel="tooltip" data-placement="top" data-original-title="Print Bill" class="btn btn-blue">
                                		Print Bill
                                </a>
        					</td>

                        </tr>

                        <?php endforeach;?>

                    </tbody>

                </table>

			</div>

            <!----TABLE LISTING ENDS--->

            <!----CREATION FORM STARTS---->

            <div class="tab-pane box" id="add" style="padding: 5px" onsubmit="return false;">
                <div class="loader" style="display: none;"></div>
                <div class="box-content">

                    <form id="med_prescribed_form" class = "form-horizontal validatable">
                    <?php
                            $medicine_price_details = get_medicine_mrp();
                    ?>

                        <div class="padded">

                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('Patient_Name');?></label>
                                <div class="controls">
                                    <input type="text" id="patient_name" name="patient_name"/>
                                </div>
                            </div>
                            <div id="med_details_container">
                                <div class="control-group"id="selling_med_detais">
                                    <label class="control-label"><?php echo get_phrase('Medicine1');?></label>
                                    <div class="controls" id="medicine_list">
                                        <select name="medicine_id1" id="medicine_id1" class="medicine_id" onchange="medicine.calculate_med_price(), medicine.can_sell_loose_item(1), medicine.getMedicineBatchList(1);">
                                            <option value="0">--Select Medicine--</option>
                                            <?php
                                            $medicine	= get_medicine_list();
                                            foreach($medicine as $row):
                                            ?>
                                                <option value="<?php echo $row['medicine_category_id'];?>"><?php echo $row['name'];?></option>
                                            <?php
                                            endforeach;
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div id="med_details_container">
                                <div class="control-group">
                                    <label class="control-label">Batch1</label>
                                    <div class="controls" id="batch_list">
                                        <select name="batch_id1" id="batch_id1"></select>
                                    </div>
                                </div>

                                <div class="control-group" id="loose_quantity_element1">
                                    <label class="control-label"><?php echo get_phrase('selling_loose_quantity1');?></label>
                                    <div class="controls">
                                        <input type="checkbox" id="selling_loose_quantity1" name="selling_loose_quantity1" onchange="medicine.calculate_med_price();"/>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label"><?php echo get_phrase('Quantity1');?></label>
                                    <div class="controls">
                                        <input type="number" id="med_quantity1" class="validate[required]" name="quantity1" onkeyup="medicine.calculate_med_price();"/>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label"><?php echo get_phrase('Amount1');?></label>
                                    <div class="controls">
                                        <input type="text" id="med_amount1" readonly="readonly" name="amount1"/>
                                    </div>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="addMedicine" onclick="medicine.add_medicine_container();"></div>
                                <div class="addMedicineText" onclick="medicine.add_medicine_container();"><?php echo get_phrase('Add_Medicine');?></div>
                            </div>

                            <input type="hidden" id="total_med_details" name="total_med_details" value="1">

                        </div>

                        <div class="control-group">
                               <label class="control-label"><?php echo get_phrase('Discount');?></label>
                               <div class="controls">
                                   <input type="number" id="discount" min="0" step="0.01" name="discount" onkeyup="medicine.calculate_med_price();"/>
                               </div>
                        </div>


                        <div class="control-group">
                           <label class="control-label"><?php echo get_phrase('total_amount');?></label>
                           <div class="controls">
                               <input type="text" id="total_amount" readonly="readonly" name="total_amount"/>
                           </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Selling Date</label>
                            <div class="controls">
                                <input type="text" id="selling_date"  class="datepicker"  name="selling_date" />
                            </div>
                        </div>

                        <div class="form-actions">

                        <button type="submit" class="btn btn-blue" id="save_prescribed_details"><?php echo get_phrase('Submit');?></button>

                        </div>

                </div>
                    </form>
			</div>

		</div>

	</div>

</div>

<div id="datepicker"></div>
<script>
    var medicine_price_details = [];
    var loose_item_mrp = [];
    var medicineList = [];
    var medicineListOPtion = [];

    var medicine_loose_item_info = [];
    <?php if (is_array($medicine_price_details) && count($medicine_price_details) > 0) {
     foreach ($medicine_price_details as $med_row) {
        ?>
        medicine_price_details[<?php echo $med_row['medicine_category_id'] ?>] = <?php echo !empty($med_row['mrp']) ? $med_row['mrp'] : 0; ?>;
        loose_item_mrp[<?php echo $med_row['medicine_category_id'] ?>] = <?php echo !empty($med_row['loose_item_mrp']) ? $med_row['loose_item_mrp'] : 0; ?>;
        medicine_loose_item_info[<?php echo $med_row['medicine_category_id'] ?>] = <?php echo !empty($med_row['is_loose_item']) ? 1 : 0; ?>;
     <?php }} ?>

    <?php
     $medicine	= get_medicine_list();
    if (is_array($medicine) && count($medicine) > 0) {
         foreach ($medicine as $key =>$medecine_row) {
           ?>
           medicineListOPtion[<?php echo $key; ?>] = "<?php echo $medecine_row['medicine_category_id']; ?>";
            medicineList[<?php echo $key; ?>] = "<?php echo $medecine_row['name']; ?>";
    <?php
        }
    } ?>

$(document).ready( function () {
    $('#sold_to_patient_list').DataTable({
        "scrollY":        "800px",
        "scrollCollapse": true,
        "paging":         false,
        "columnDefs": [
        { "width": "10%", "targets": 0 },
        { "width": "20%", "targets": 1 }
        ]
    });
});
$(".medicine_id").select2();
$( "#save_prescribed_details" ).click(function() {
  medicine.validate_medicine_sale_form();
});

 $( ".datepicker" ).datepicker({
        changeYear: true,
        changeMonth: true,
        dateFormat: "dd-mm-yy"
    });

// set the curent date in seeling_date field
$("#selling_date").datepicker('setDate', new Date());

jQuery('body').on('click','.delete',function(eve){
 var currentRow=$(this).closest("tr");
var cmpname=currentRow.find(".cname").html();
	eve.preventDefault();
	var deleteLink = jQuery(this).attr('data-delete');
	swal({
  	title: '',
			text: "Are you sure want to delete sell medicine patient "+ cmpname +" ?",
  	type: 'warning',
			showCancelButton:true,

			showConfirmButton:true,
  },
  function(){

			window.location.href = deleteLink;
});

});

</script>

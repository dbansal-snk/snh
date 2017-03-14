<script src="<?php echo base_url();?>template/js/medicine.js" type="text/javascript"></script>
<style>
.form-horizontal .controls {
    width: 265px !important;
}
</style>

<div class="box">
	<div class="box-header">
</div>

	<div class="box-content padded">
		<div class="tab-content">
            <div class="tab-pane box active" id="edit" style="padding: 5px">
                <div class="box-content">

                <form method="post" id="update_medicine"  class="form-horizontal validatable" onsubmit="return false;">
                <input type="hidden" id="mid" name="mid" value="<?php echo $edit_profile['0']['medicine_sale_id'];?>">
            
          <?php
                $medicine_price_details = get_medicine_mrp();
           ?>
            <div class="padded">

                <div class="control-group">
                    <label class="control-label">Doctor</label>
                    <div class="controls">
                        <select name="doctor_name" id="doctor_name">
                            <option value="0">--Select Doctor--</option>
                            <?php
                            foreach($doctor_list as $row) {
                                $selected = '';
                                if (!empty($update_sell['doctor_id']) && $row['doctor_id'] == $update_sell['doctor_id']) {
                                    $selected = 'selected';
                                }
                            ?>
                                <option value="<?php echo $row['doctor_id'];?>" <?php echo $selected; ?>><?php echo $row['name'];?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">Patient Name</label>

                    <div class="controls">
                        <input type="text" id="patient_name" class="validate[required]" name="patient_name" value="<?php echo !empty($edit_profile['0']['patient_name']) ? $edit_profile['0']['patient_name'] : ''; ?>"/>
                    </div>
                </div>

                <div id="med_details_container">
                <?php
                $i=1;
                for($j=0;$j<count($edit_profile);$j++) { ?>
                <input type="hidden" id="md_id<?php echo $i;?>" name="md_id<?php echo $i;?>" value="<?php echo $edit_profile[$j]['id'];?>">
                <div class="control-group" id="sold_med_details<?php echo $i;?>">
                    <div id="med_details_container">
                        <div class="control-group"id="selling_med_detais<?php echo $i;?>">
                            <label class="control-label"><?php echo get_phrase('Medicine'.$i );?></label>
                            <div class="controls" id="medicine_list">
                                <select name="medicine_id<?php echo $i;?>" id="medicine_id<?php echo $i; ?>" class="medicine_id" onchange="medicine.calculate_med_price(),  medicine.can_sell_loose_item(<?php echo $i; ?>), medicine.getMedicineBatchList(<?php echo $i; ?>);">
                                    <option value="0">--Select Medicine--</option>
                                    <?php
                                    $medicine	= get_medicine_list();
                                    foreach($medicine as $row):
                                    ?>
                                        <option <?php  if($edit_profile[$j]['medicine_id']==$row['medicine_category_id']){echo "selected";} ?> value="<?php echo $row['medicine_category_id'];?>"><?php echo $row['name'];?></option>
                                    <?php
                                    endforeach;
                                    ?>

                                </select>
                                
                                <?php if ($i > 1) {?>
                                    <div id="<?php echo $i;?>" class="removeMedicine" onclick="medicine.remove_medicine_container(<?php echo $i;?>);"></div>
                                <?php } ?>
                            </div>
                        </div>

                        <div id="med_details_container">
                            <div class="control-group">
                                <label class="control-label">Batch<?php echo $i; ?></label>
                                <div class="controls" id="batch_list">
                                    <select class="batch_id"  name="batch_id<?php echo $i;?>" id="batch_id<?php echo $i; ?>">
                                    <option value="<?php echo $edit_profile[$j]['batch']; ?>"> <?php echo $edit_profile[$j]['batch']; ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group loose_med_option<?php echo $i; ?>" id="loose_quantity_element<?php echo $i; ?>">
                                <label class="control-label"><?php echo get_phrase('selling_loose_quantity'.$i);?></label>
                                <div class="controls">
                                    <input type="checkbox" <?php if($edit_profile[$j]['is_loose_sale']==1){echo "checked";} ?> id="selling_loose_quantity<?php echo $i; ?>" name="selling_loose_quantity<?php echo $i;?>" onchange="medicine.calculate_med_price();"/>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('Quantity'.$i);?></label>
                                <div class="controls">
                                    <input value="<?php echo $edit_profile[$j]['quantity'];?>" type="number" id="med_quantity<?php echo $i; ?>" class="validate[required]" name="quantity<?php echo $i;?>" onkeyup="medicine.calculate_med_price();"/>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('Amount'.$i);?></label>
                                <div class="controls">
                                    <input value="<?php echo $edit_profile[$j]['amount'];?>" type="text" id="med_amount<?php echo $i;?>" readonly="readonly" name="amount<?php echo $i;?>"/>
                                </div>
                            </div>
                        </div>


                <input type="hidden" id="total_med_details" name="total_med_details" value="<?php echo $i;?>">

                </div>
                <?php
                if ($i != count($edit_profile)) {
                ?>
                    <div class="seprator-dooted-lines"></div>

                <?php } ?>
                </div>
                <?php
                $i++;
                    } ?>
                </div>
                <div class="control-group">
                    <div class="addMedicine" onclick="medicine.add_medicine_container();"></div>
                    <div class="addMedicineText" onclick="medicine.add_medicine_container();"><?php echo get_phrase('Add_Medicine');?></div>
                </div>

                <div class="control-group">
                       <label class="control-label"><?php echo get_phrase('Discount');?></label>
                       <div class="controls">
                           <input type="number" value="<?php echo $edit_profile['0']['discount'];?>" id="discount" min="0" step="0.1" name="discount" onkeyup="medicine.calculate_med_price();"/>
                       </div>
                </div>


                <div class="control-group">
                   <label class="control-label"><?php echo get_phrase('total_amount');?></label>
                   <div class="controls">
                       <input type="text" value="<?php echo $edit_profile['0']['total_amount'];?>"  id="total_amount" readonly="readonly"  name="total_amount"/>
                   </div>
                </div>

                <div class="control-group">
                    <label class="control-label">Selling Date</label>
                    <div class="controls">
                        <input type="text" id="selling_date"  class="datepicker" value="<?php echo date("d-m-Y", strtotime($edit_profile['0']['medicine_sold_date']));
                        ?>"  name="selling_date" />
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-blue" id="update_prescribed_details"><?php echo get_phrase('Update');?></button>
                </div>

            </div>
        </form>
  </div>

			</div>

            

            <!----EDITING FORM ENDS--->

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
        medicine_price_details["<?php echo $med_row['batch'] . '__' . $med_row['medicine_category_id'] ?>"] = <?php echo !empty($med_row['mrp']) ? $med_row['mrp'] : 0; ?>;
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
        "paging":         false
    });
    $(".medicine_id").select2();
});

$( "#update_prescribed_details" ).click(function(e) {
 e.preventDefault();
  medicine.validat_medicine_sale_form();
});

// $( "#update_prescribed_details" ).click(function() {
//   medicine.updateMedicine();
// });

 $( ".datepicker" ).datepicker({
        changeYear: true,
        changeMonth: true,
        dateFormat: "dd-mm-yy"
    });

// set the curent date in seeling_date field
//$("#selling_date").datepicker('setDate', new Date());
</script>

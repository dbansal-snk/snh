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

            <!----TABLE LISTING STARTS--->

            <div class="tab-pane box <?php if(!isset($edit_profile))echo 'active';?>" id="list">
                
                <div id="filter_containers">
                    <button id="apply_filter">Apply Filter</button>
                </div>
                
                <table id="sold_to_patient_list" class="display" cellspacing="0" width="100%"  class="dTable responsive">
                	<thead>
                		<tr></tr>
					</thead>
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
                                <label class="control-label">Doctor</label>
                                <div class="controls">
                                    <select name="doctor_name" id="doctor_name">
                                        <option value="0">--Select Doctor--</option>
                                        <?php
                                        foreach($doctor_list as $row) {
                                        ?>
                                            <option value="<?php echo $row['doctor_id'];?>"><?php echo $row['name'];?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label">Patient Name</label>
                                <div class="controls">
                                    <input type="text" id="patient_name" name="patient_name" class="validate[required]"/>
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
                                        <select name="batch_id1" id="batch_id1" onchange="medicine.calculate_med_price(); return false;"></select>
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
//         if (!empty())
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
    var configUrl       = 'index.php?pharmacist/get_medicine_sale_report_config';
    var listUrl         = 'index.php?pharmacist/medicine_sale_report_list';
    var tableId         = 'sold_to_patient_list';
    ReportTable.getReportConfig(configUrl, listUrl, tableId);
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
          }, function(){
            window.location.href = deleteLink;
    });

});

$('body').delegate('#sold_to_patient_list tbody tr', 'click', function () {
    var nTr             = this;
    var currTd          = $(nTr);
    var patientName     = $(this).children("td:first-child").find('a').html();
    var url             = $(this).children("td:first-child").find('a').attr("href");
    var saleId          = url.replace("index.php?pharmacist/edit_sell/","");
    var actionHtml      = '';
    var actionHtml      = "<span class='actionEdit'></span><a href ='" + url + "' style='font-weight:normal' >Edit '" + patientName + "'</a> <br />";
    actionHtml         += "<span class='actionDelete'></span> <a href='#' onclick='deleteSaleDetails(" + '"' + saleId + '","' + patientName + '")' + "'); return false;'  style='font-weight:normal'>Delete '" + patientName + "' Details</a><br />";
    actionHtml         += "<span class='actionDelete'></span> <a href='#' onclick='medicine.generate_medicine_bill("+ saleId +"); return false;'  style='font-weight:normal'>Print Bill</a>";    
    TableRowMenu.showMenu(currTd,actionHtml);
    $(nTr).append(TableRowMenu.getElement());
});


function deleteSaleDetails(saleId, patientName) {
    var deleteLink = 'index.php?pharmacist/delete_patient_med_sale_details';
    swal({
        title: '',
        text: "Are you sure want to Delete " + patientName + ' details?',
        type: 'warning',
        showCancelButton:true,
        showConfirmButton:true,
    }, function(){
        $('.loader').show();
        $.ajax({
            type: 'POST',
            url: deleteLink,
            data: {id: saleId},
            success: function() {
                $('.loader').hide();
                ReportTable.drawTable();
            }
        });
    });
}


</script>
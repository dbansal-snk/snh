<div class="box">
	<div class="box-header">
    	<!------CONTROL TABS START------->
		<ul class="nav nav-tabs nav-tabs-left">
        	<?php if(isset($edit_profile)):?>
			<li class="active">
            	<a href="#edit" data-toggle="tab"><i class="icon-wrench"></i>
					<?php echo get_phrase('Edit Company');?>
                    	</a>
            </li>
            <?php endif;?>

            <li class="<?php if(!isset($edit_profile))echo 'active';?>">
            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i>
					<?php echo get_phrase('Company List');?>
                </a>
            </li>

			<li>
            	<a href="#add" data-toggle="tab"><i class="icon-plus"></i>
					<?php echo get_phrase('Add Company');?>
                </a>
            </li>
		</ul>

    	<!------CONTROL TABS END------->



	</div>

	<div class="box-content padded">

		<div class="tab-content">

        	<!----EDITING FORM STARTS---->

        	<?php if(isset($edit_profile)):?>
			<div class="tab-pane box active" id="edit" style="padding: 5px">
                <div class="box-content">
                    <?php echo form_open('pharmacist/update_manufacture_company/'.$edit_company_list['id'] , array('class' => 'form-horizontal validatable'));?>
                        <div class="padded">
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('name');?></label>
                                <div class="controls">
                                    <input type="text" class="validate[required]" name="name" value="<?php echo $edit_company_list['name'];?>"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('description');?></label>
                                <div class="controls">
                                    <textarea  class="" name="description"><?php echo $edit_company_list['description'];?></textarea>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('Status');?></label>
                                <div class="controls">
                                    <select name="status" class="status" >
                                        <?php
                                        $active_selected = '';
                                        $inactive_selected = '';
                                        if (!empty($edit_company_list['status']) && $edit_company_list['status'] == 'ACTIVE') {
                                            $active_selected = 'selected';
                                        } else {
                                            $inactive_selected = 'selected';
                                        } ?>
                                        <option value="ACTIVE" <?php echo $active_selected; ?>>ACTIVE</option>
                                        <option value="INACTIVE" <?php echo $inactive_selected; ?>>IN ACTIVE</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-blue"><?php echo get_phrase('Update');?></button>
                        </div>
                    <?php echo form_close();?>
                </div>
			</div>
            <?php endif;?>

            <!----EDITING FORM ENDS--->



            <!----TABLE LISTING STARTS--->

            <div class="tab-pane box <?php if(!isset($edit_profile))echo 'active';?>" id="list">
                <table id="company_list" class="display" cellspacing="0" width="100%" class="dTable responsive">
                	<thead>
                		<tr>
                    		<th><div><?php echo get_phrase('Name');?></div></th>
                    		<th><div><?php echo get_phrase('description');?></div></th>
                            <th><div><?php echo get_phrase('status');?></div></th>
                    		<th><div><?php echo get_phrase('options');?></div></th>
						</tr>
					</thead>

                    <tbody>

                    	<?php
                        if (is_array($company_list) && count($company_list) > 0) {
                        foreach($company_list as $row) { ?>

                        <tr>
							<td class="cname"><?php echo $row['name'];?></td>
							<td><?php echo $row['description'];?></td>
                            <td><?php echo $row['status'];?></td>
							<td align="center">
                            	<a href="<?php echo base_url();?>index.php?pharmacist/edit_manufacture_company_details/<?php echo $row['id'];?>"
                                	rel="tooltip" data-placement="top" data-original-title="<?php echo get_phrase('edit');?>" class="btn btn-blue">
                                		<i class="icon-wrench"></i>
                                </a>
                            	<a href="javascript:;"
													data-delete = "<?php echo base_url();?>index.php?pharmacist/delete_manufacture_company/<?php echo $row['id'];?>"
                                	rel="tooltip" data-placement="top" data-original-title="<?php echo get_phrase('delete');?>" class="btn btn-red delete">
                                		<i class="icon-trash"></i>
                                </a>
                            	<!-- <a href="<?php echo base_url();?>index.php?pharmacist/delete_manufacture_company/<?php echo $row['id'];?>" onclick="return confirm('delete?')"
                                	rel="tooltip" data-placement="top" data-original-title="<?php echo get_phrase('delete');?>" class="btn btn-red">
                                		<i class="icon-trash"></i>
                                </a> -->
        					</td>
                        </tr>

                        <?php }} ?>

                    </tbody>

                </table>

			</div>

            <!----TABLE LISTING ENDS--->





			<!----CREATION FORM STARTS---->

			<div class="tab-pane box" id="add" style="padding: 5px">
                <div class="box-content">

                    <?php echo form_open('pharmacist/add_manufacture_company/' , array('class' => 'form-horizontal validatable'));?>
                        <div class="padded">
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('Name');?></label>
                                <div class="controls">
                                    <input type="text" class="validate[required]" name="name"/>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('Description');?></label>
                                <div class="controls">
                                    <input type="text" class="" name="description"/>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('Status');?></label>
                                <div class="controls">
                                    <select name="status" class="status" >
                                        <option value="ACTIVE">ACTIVE</option>
                                        <option value="INACTIVE">IN ACTIVE</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-blue"><?php echo get_phrase('Submit');?></button>
                        </div>

                    <?php echo form_close();?>
                </div>
			</div>

			<!----CREATION FORM ENDS--->



		</div>

	</div>

</div>

<script>
$(document).ready( function () {
    $('#company_list').DataTable({
        "scrollY":        "800px",
        "scrollCollapse": true,
        "paging":         false
    });
				$(".status").select2();
} );

jQuery('body').on('click','.delete',function(eve){
	var currentRow=$(this).closest("tr");
	var cmpname=currentRow.find(".cname").html();

	eve.preventDefault();
	var deleteLink = jQuery(this).attr('data-delete');

	swal({
  	title: '',
   text: "Are you sure want to delete company ! "+cmpname,
  	type: 'warning',
			showCancelButton:true,

			showConfirmButton:true,
  },
  function(){

			window.location.href = deleteLink;
});

});
</script>

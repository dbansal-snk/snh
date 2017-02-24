<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs nav-tabs-left">
			<li class="active">
            	<a href="#report"><i class="icon-wrench"></i> 
					<?php echo get_phrase('Medicine_stock_report'); ?>
                </a>
            </li>
		</ul>
        
        <ul class="nav nav-tabs nav-tabs-left">
			<li class="active">
            	<a href="index.php?pharmacist/vendor_stock_report"><i class="icon-wrench"></i>Vendor Stock Report</a>
            </li>
		</ul>
	</div>
    
		

	<div class="box-content padded">
    	<div class="tab-content">   
            <div id="filter_containers">
                <button id="apply_filter">Apply Filter</button>
            </div>
            <div id="report">
                <div style="margin-left: 400px;"><?php echo get_phrase('Total_Medicine_Revenue');?>: <?php echo !empty($all_med_revenue[0]['total_amount']) ? $all_med_revenue[0]['total_amount'] : 0; ?></div>
                <table id="medicine_stock_report" class="display" cellspacing="0" width="100%"  class="dTable responsive">
                    <thead>
                        <tr>
                            <th><div><?php echo get_phrase('Medicine');?></div></th>
                            <th><div>M.R.P</div></th>
                            <th><div><?php echo get_phrase('Total_Stock');?></div></th>
                            <th><div><?php echo get_phrase('Remaining_Stock');?></div></th>
                            <th><div><?php echo get_phrase('Total_Revenue');?></div></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready( function () {
    $('#stock_report_list').DataTable({
        "scrollY":        "800px",
        "scrollCollapse": true,
        "paging":         false
    });
    
    var data = [];
    var columns = ['name', 'mrp', 'total_stock', 'remaining_stock', 'revenue'];
    data.vendor_id = $('#stock_report_list').val();
    var url = 'index.php?pharmacist/medicine_stock_report_list';
    ReportTable.init('medicine_stock_report', url, data, columns);
} );
</script>
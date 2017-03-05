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
<div class="loader" style="display: none;"></div>

<script>
$(document).ready( function () {
    var configUrl = 'index.php?pharmacist/get_med_report_config';
    var listUrl   = 'index.php?pharmacist/medicine_stock_report_list';
    var tableId   = 'medicine_stock_report';
    ReportTable.getReportConfig(configUrl, listUrl, tableId);
  
} );
</script>
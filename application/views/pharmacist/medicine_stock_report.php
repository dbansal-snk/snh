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
            <div><span>Batch One Row</span><span><input type="checkbox" id="batch_row_med"></span></div>
            <div id="report">
                <div style="margin-left: 400px;"><?php echo get_phrase('Total_Medicine_Revenue');?>: <?php echo !empty($all_med_revenue[0]['total_amount']) ? $all_med_revenue[0]['total_amount'] : 0; ?></div>
                <table id="medicine_stock_report" class="display" cellspacing="0" width="100%"  class="dTable responsive">
                    <thead>
                        <tr></tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="loader" style="display: none;"></div>

<script>
$(document).ready( function () {
    var configUrl       = 'index.php?pharmacist/get_med_report_config';
    var listUrl         = 'index.php?pharmacist/medicine_stock_report_list';
    var tableId         = 'medicine_stock_report';
    var data  = {};
    data['batch_row_med'] = 0;
    if ($('#batch_row_med').is(':checked')) {
        data['batch_row_med'] = 1;
    }
    ReportTable.getReportConfig(configUrl, listUrl, tableId, data);
  
    $("#batch_row_med").click(function() {
        if ($('#batch_row_med').is(':checked')) {
            data['batch_row_med'] = 1;
        } else {
            data['batch_row_med'] = 0;
        }
        ReportTable.getReportConfig(configUrl, listUrl, tableId, data);
    });

});
</script>
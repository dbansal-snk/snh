<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs nav-tabs-left">
			<li class="active">
            	<a href="index.php?pharmacist/medicine_stock_report"><i class="icon-wrench"></i> 
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
            <div id="report">
                <div>
                    <select id="vendor_listing">
                        <option val="0">--Select Vendor--</option>
                        <?php $vendor_list = get_vendors_list(); 
                              if (is_array($vendor_list) && count($vendor_list) > 0) {
                                  foreach ($vendor_list as $vendor) {
                                      echo "<option value='" . $vendor['id'] ."'>" . $vendor['name'] . "</option>";
                                  }
                              }
                        ?>
                    </select>
                </div>
                <div id="filter_containers">
                    <button id="apply_filter">Apply Filter</button>
                </div>
                <table id="vendor_stock_report" class="display" cellspacing="0" width="100%"  class="dTable responsive">
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
    
$(document).ready(function() {
    $("#vendor_listing").change(function() {
        var configUrl       = 'index.php?pharmacist/get_vendor_stock_report_config';
        var listUrl         = 'index.php?pharmacist/vendor_stock_report_list';
        var tableId         = 'vendor_stock_report';
        var data            = {};
        data['vendor_id']   = $(this).val();
        ReportTable.getReportConfig(configUrl, listUrl, tableId, data);
    });   
});
</script>
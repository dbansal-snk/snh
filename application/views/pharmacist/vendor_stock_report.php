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
                <table id="vendor_stock_report" class="display" cellspacing="0" width="100%"  class="dTable responsive">
                    <thead>
                        <tr>
                            <th><div>Medicine</div></th>
                            <th><div>Quantity</div></th>
                            <th><div>Free Item Quantity</div></th>
                            <th><div>Loose Item</div></th>
                            <th><div>Sold Stock</div></th>
                            <th><div>Purchased Amount</div></th>
                            <th><div>Revenue</div></th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
</div>

<script>

$(document).ready(function() {
    $("#vendor_listing").change(function() {
        var vendorId = $(this).val();
        $('#vendor_stock_report').DataTable().destroy();
        $('#vendor_stock_report').DataTable( {
            processing : true,
            bServerSide: true,
            bLengthChange: false,
            serverSide: true,
            ordering: true,
            pageLength: 50,
            searching: false,
            scrollY:        '600px',
            scrollCollapse: true,
            paging:         true,

             ajax: {
                "url": "/index.php?pharmacist/vendor_stock_report_list",
                "type": "POST",
                data: {vendor_id: vendorId}
            },
            "columns": [
                { "data": "name" },
                { "data": "total_stock" },
                { "data": "free_item" },
                { "data": "loose_item_quantity" },
                { "data": "sold_quantity" },
                { "data": "price" },
                { "data": "revenue" }
            ],
            scroller: {
                loadingIndicator: true
            }
        });
    });   
});
</script>
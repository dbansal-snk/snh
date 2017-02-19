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
                <div style="margin-left: 400px;"><?php echo get_phrase('Total_Medicine_Revenue');?>: <?php echo !empty($all_med_revenue[0]['total_amount']) ? $all_med_revenue[0]['total_amount'] : 0; ?></div>
                <table id="vendor_stock_report" class="display" cellspacing="0" width="100%"  class="dTable responsive">
                    <thead>
                        <tr>
                            <th><div>Medicine</div></th>
                            <th><div>Total Stock</div></th>
                            <th><div>Loose Item Stock</div></th>
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
    $('#vendor_stock_report').DataTable( {
        "processing" : true,
        "bServerSide": true,
        serverSide: true,
        ordering: true,
        
        searching: false,
        "scrollY":        '600px',
        "scrollCollapse": true,
        "paging":         true,
        
         ajax: {
            "url": "/index.php?pharmacist/vendor_stock_report_list",
            "type": "POST"
        },
        "columns": [
            { "data": "name" },
            { "data": "total_stock" },
            { "data": "loose_item_quantity" },
            { "data": "sold_quantity" },
            { "data": "price" },
            { "data": "revenue" }
        ],
        scroller: {
            loadingIndicator: true
        }
    } );
} );
</script>
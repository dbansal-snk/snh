<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs nav-tabs-left">
			<li class="active">
            	<a href="#report"><i class="icon-wrench"></i> 
					<?php echo get_phrase('Medicine_stock_report'); ?>
                </a>
            </li>
		</ul>
	</div>

	<div class="box-content padded">
    	<div class="tab-content">            
            <div id="report">
                <div style="margin-left: 400px;"><?php echo get_phrase('Total_Medicine_Revenue');?>: <?php echo !empty($all_med_revenue[0]['total_amount']) ? $all_med_revenue[0]['total_amount'] : 0; ?></div>
                <table cellpadding="0" cellspacing="0" border="0" class="dTable responsive">
                    <thead>
                        <tr>
                            <th><div><?php echo get_phrase('Medicine');?></div></th>
                            <th><div><?php echo get_phrase('Total_Stock');?></div></th>
                            <th><div><?php echo get_phrase('Remaining_Stock');?></div></th>
                            <th><div><?php echo get_phrase('Total_Revenue');?></div></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        foreach ($data as $row) {?>
                        <tr>
                            <td><?php echo $row['name'];?></td>
                            <td><?php 
                            $total_stock = !empty($row['total_stock']) ? $row['total_stock'] : 0;
                            $total_stock += !empty($row['total_free_item_stock']) ? $row['total_free_item_stock'] : 0;
                            echo $total_stock;?></td>
                            <td><?php 
                            $sold_stock = !empty($med_sold_stock[$row['medicine_category_id']]) ? $med_sold_stock[$row['medicine_category_id']] : 0;
                            echo $total_stock - $sold_stock; ?></td>
                            <td><?php echo !empty($row['total_amount']) ? $row['total_amount'] : 0;?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
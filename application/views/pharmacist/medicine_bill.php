<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style>
	body{margin:0px; padding:0px; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666;}
	#Container{width:950px; margin:0 auto;}
	#TopRow{border:1px solid #ccc; width:100%; height: auto; border-radius:4px; margin-top:20px; overflow:hidden;}
	#TopRow ul{ margin:0px; padding:0px;}
	#TopRow li{ margin:0px; padding:0px 20px; list-style:none; float:left; width:28%; height:auto; line-height:40px; font-weight:bold;}
	
	#SecondRow{border:1px solid #ccc; width:100%; height: auto; border-radius:4px; margin-top:10px; overflow:hidden;}
	#SecondRow H1{ font-size:58px; text-transform: uppercase; text-align:center; border-bottom:double #999; margin:20px 0px; padding:0px;}
	#SecondRow H4{ font-size:28px; text-transform: uppercase; text-align:center; margin:10px 0px; padding:0px;}
	
	#ThirdRow{border:1px solid #ccc; width:100%; height: auto; border-radius:4px; margin-top:10px; overflow:hidden;}	
	#ThirdRow table td{ width:50%; height:auto;}	
	#ThirdRow ul{ margin:0px; padding:0px;}
	#ThirdRow li{ margin:0px; padding:0px 10px; list-style:none; float:left; width:40%; height:auto; line-height:40px; font-weight:bold;}
	#ThirdRow li:nth-child(even){width:49%;}
	
	#FourthRow{border:1px solid #ccc; width:100%; height: auto; border-radius:4px; margin-top:10px; overflow:hidden;}	
	#FourthRow td{ padding:10px;}	
	#FourthRow th{ padding:10px; font-weight:bold; background:#f1f1f1;}	
	
	#FifthRow{border:1px solid #ccc; width:100%; height: auto; border-radius:4px; margin-top:10px; overflow:hidden;}	
	#FifthRow td{ padding:10px; text-align:center;}	

	#SixthRow{ width:100%; height: auto; border-radius:4px; margin-top:10px;}	
	#SixthRow td{ padding:0px 0px;}	
	#SixthRow td h1{ padding:0px; margin:0px; font-size:32px; text-align:center;}	
	
	#SixthRow td div{ border-radius:4px; border:1px solid #ccc; width:100%; height: auto; border-radius:4px; min-height:50px;}	
	#SixthRow td ol{ margin:0px 20px; padding:0px 10px;}
	#SixthRow td li{ margin:0px; padding:5px; line-height:22px; font-weight:bold;}	
</style>



</head>

<body>



<div id="Container">


<div id="TopRow">

<ul>
	<li>DL No. : 31(1093) 20, 21</li>
<li> Retail Invoice/Cash Memo</li>
<li> TIN No. - 7260261259</li>
</ul>
</div>
<div style="clear:both"></div>
<!--Second Row STarts-->
<div id="SecondRow">
	<h1>Satnarayan Hospital</h1>
	<h4>U-1/78, Budh Vihar,pooth kalan Delhi - 110042</h4>

</div>
<!--3rd Row STarts-->

<div id="ThirdRow">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <ul>
	<li>Cash Memo No.:</li>
    <li>S0 - 74529</li>
	</ul>
</td>
    <td> <ul>
	<li>Date:</li>
    <li><?php echo date('d/m/Y') ?></li>
	</ul></td>
  </tr>
  
   <tr>
    <td>
    <ul>
	<li>Patient:</li>
    <li><?php echo !empty($content[0]['patient_name']) ? $content[0]['patient_name'] : ''; ?></li>
	</ul>
</td>
    <td> <ul>
	<li>Rx by Doctor:</li>
    <li>Dr. Ashish Bansal</li>
	</ul></td>
  </tr>
  
   <tr>
    <td>
    <ul>
	<li>Address:</li>
    <li>S0 - 74529</li>
	</ul>
</td>
    <td> <ul>
	<li>C.R. No.:</li>
    <li>CS</li>
	</ul></td>
  </tr>
 
</table>

</div>

<!--4th Row STarts-->

<div id="FourthRow">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th width="10%">Sr.</th>
    <th width="10%">Qty.</th>
    <th width="25%">Description</th>
    <th width="15%">Batch</th>
    <th width="10%">Rate</th>
    <th width="10%">Amount</th>
  </tr>
 
</table>


</div>

<!--5th Row STarts-->


<div id="FifthRow">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <?php
    $sr_no = 1;
    if (is_array($content) && count($content) > 0) {
        foreach ($content as $row) { ?>
        <tr>
            <td width="10%"><?php echo $sr_no; ?></td>
            <td width="10%"><?php echo !empty($row['quantity']) ? $row['quantity'] : 0; ?></td>
            <td width="25%"></td>
            <td width="15%"><?php echo !empty($row['batch']) ? $row['batch'] : ''; ?></td>
            <td width="10%"><?php echo !empty($row['mrp']) ? $row['mrp'] : 0; ?></td>
            <td width="10%"><?php echo !empty($row['amount']) ? $row['amount'] : 0; ?></td>
      </tr>
    <?php
        $sr_no++;
        }
    }
    ?>
    
     <tr>
        <td width="10%"></td>
        <td width="10%"></td>
        <td width="25%"></td>
        <td width="15%"></td>
        <td width="10%"></td>
        <td width="10%"></strong></td>
    </tr>
    
     <tr>
        <td width="10%"></td>
        <td width="10%"></td>
        <td width="25%"></td>
        <td width="15%"></td>
        <td width="10%"></td>
        <td width="10%"></strong></td>
    </tr>
    
    <tr>
        <td width="10%"></td>
        <td width="10%"></td>
        <td width="25%"></td>
        <td width="15%"></td>
        <td width="10%"><strong>Total Amount:</strong></td>
        <td width="10%"><strong><?php echo !empty($content[0]['total_amount']) ? number_format($content[0]['total_amount'], 2) : 0; ?></strong></td>
    </tr>
</table>


</div>
<!--5th Row STarts-->

<div id="SixthRow">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td width="40%">
    	<div>
        	<ol>
            	<li>All Desputes are subject to Delhi Jurisdiction</li>
            	<li>All Desputes are subject to Delhi Jurisdiction</li>
            
            </ol>
        
        
        </div>
    </td>
    <td width="20%"> <h1>Open All Day's</h1></td>
    <td width="40%">
    
    	<div>
        	<ol>
            	<li>All Desputes are subject to Delhi Jurisdiction</li>
            	<li>All Desputes are subject to Delhi Jurisdiction</li>
            
            </ol>
        
        
        </div>
    
    </td>
   
  </tr>
</table>
    <div></br></br>
    <button id="print_bill" name="print_bill" style="margin-left: 400px;" onclick="window.print();window.close();">Print</button>
    </div>
</div>


<!--Main container ends here-->
	
</div>
</body>
</html>
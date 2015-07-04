<!DOCTYPE html>
<html>
<head>
<title>Grocery- Cart Details</title>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=false;" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;" />
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" href="datebox/jquery.mobile-1.4.5.min.css" />
<!-- NOTE: Script load order is significant! -->

<style>
.ui-datepicker{ z-index: 9999 !important;}
</style>
<style>
.container {
	margin:5px auto 0;
}
.cart_detl_pan {
    padding: 10px 0 0;
}

.total {
    position:fixed;
	bottom:86px;
	width:100%;
}
.cat_d_greentxt {
    bottom: 48px;
    position: fixed;
    width: 100%;
}
.footer {
    bottom: 0;
    position: fixed;
    width: 100%;
}
</style>
<style>
.dateSpan{display:inline-block;
margin-bottom:10px;
margin-left:10px;
}
.singleDateDiv2 {
    font-size: 12px;
    margin-bottom: 150px;
}

.cat_d_label {
    display: inline-block;
    font-size: 14px;
    margin-bottom: 10px;
    margin-left: 10px;
}
.cart_d_tbl {
    background-color: #e8e8e8;
    color: #000 !important;
    font-size: 13px;
    font-weight: bold;
    height: 24px;
    margin: 0;
    padding: 4px 0;
    width: 100%;
}
.cont_shop3.cart_checkout2 {
    float: inherit;
}

.cont_shop.cart_checkout2 {
    float: left;
    margin-left:10px;
}
.cont_shop2.cart_checkout2 {
    float: right;
    margin-right:10px;
}
</style> 
<script type="text/javascript">
function frmSubmit(total, cut_off,option)
{
	if(option=='couldnt_purchase' && (parseFloat(total)<parseFloat(cut_off)))
	{
		alert("To place a order you have to purchase a minimum of Rs. "+cut_off);
	}
	else
	{
		var type = $('input[name="order_type"]:checked').val();
		var disable = '<?=$disable?>';
		$("#continueCart").val('y');
		
		if(disable=='disabled')
		{
			$("#outsider").val(1);
			$("#frm1").submit();
		}
		else
		{
			if(type=='single')
			{
				var dateToday=$("#datepicker1").val();
				//var timeToday=$("#timepicker1").val();
				if(dateToday!='')
				{
					$("#frm1").submit();
				}
				else
				{
					if(dateToday=='')
					{
						alert("Select Delivery Date!");
						return false;
					}
					/*if(timeToday=='')
					{
						alert("Select Delivery Time!");
						return false;
					}*/
				}
			}
			else
			{
				var dateFrom=$("#datepicker2").val();
				var dateTo=$("#datepicker3").val();
				//var timeToday=$("#timepicker2").val();
				if(dateFrom!='' && dateTo!='')
				{
					$("#frm1").submit();
				}
				else
				{
					if(dateFrom=='')
					{
						alert("Select Delivery Date From!");
						return false;
					}
					if(dateTo=='')
					{
						alert("Select Delivery Date To!");
						return false;
					}
				}
			}
		}
	}
}

function divSelect(type)
{
	if(type=='single')
	{
		$("#singleDateDiv").css('display','');
		$("#regularDateDiv").css('display','none');
	}
	else
	{
		$("#singleDateDiv").css('display','none');
		$("#regularDateDiv").css('display','');
	}
}

function clearOrder()
{
	var cartid=$("#cartid").val();
	$.ajax({
		type: 'post',
		url: 'ajax_delete_cart.php',
		data: 'cartid='+cartid,
		success: function()
		{
			window.location.href='index.php';
		}
	});
}

function updateCart(id, sl)
{
	var qty = $("#qty"+sl).val();
	//alert(qty);
	var cartid=$("#cartid").val();
	$.ajax({
		type: 'post',
		url: 'ajax_update_cart.php',
		data: 'id='+id+'&qty='+qty+'&cartid='+cartid,
		success: function(msg)
		{
			if(msg=='Yes')
			{
				//alert("Cart Updated!");
				window.location.href='cart_details.php';
			}
			else
			{
				alert("Error Occured!");
			}
		}
	});
}

function removeCart(id)
{
	var cartid=$("#cartid").val();
	$.ajax({
		type: 'post',
		url: 'ajax_remove_cart.php',
		data: 'id='+id+'&cartid='+cartid,
		success: function(msg)
		{
			if(msg=='Yes')
			{
				//alert("Product Removed!");
				window.location.href='cart_details.php';
			}
			else
			{
				alert("Error Occured!");
			}
		}
	});
}
</script>
</head>
<body>
<div class="container">
  <header class="">
    <div class="logo"><a href="javascript:void(0);" title="Shop page" onClick="window.location.href='shop.php'"><img src="images/logo.jpg" alt=""></a></div>
    <div class="spacer"></div>
  </header>
  <span class="toggleMenu" href="shop.php"><big style="float:left; position:relative; top:10px;">SHOP</big>
<div class="carttxt" onClick="javascript: window.location.href='cart_details.php'" style="cursor:pointer"><p class="itmlft">Total Items Added : <span id="cartNo"><?=getCart();?></span></p>
<div class="spacer"></div>
<p class="itmrgt">Total Amount: Rs.<span id="cartPrice"><?=number_format(floatval(getCartAmount()),2,'.','');?></span></p></div>
<div class="spacer"></div>
</span>
  
  <section class="cart_detl_pan">
  	<form method="post" id="frm1">
    <?php
	$sql=mysql_query("SELECT * FROM temp_order WHERE status='unsuccessfull' AND ip='".$ip."' AND session_id='".$_SESSION['session_id']."'");
	$num=mysql_num_rows($sql);
	if($num>0)
	{
	$res=mysql_fetch_assoc($sql);
	$num2=mysql_num_rows(mysql_query("SELECT * FROM temp_order_details WHERE cartid='".$res['cartid']."'"));
	if($num2>0)
	{
	?>
    <input type="hidden" name="cartid" id="cartid" value="<?=$res['cartid']?>">
    <input type="hidden" name="continueCart" id="continueCart" value="n">
    <input type="hidden" name="outsider" id="outsider" value="0">
    <div class="cart__dtl_bx">
    	
        <table class="cart_single2">
          <thead>
            <tr>
              <th>Item Name<hr /></th>
              <th>Unit<hr /></th>
              <th>Rate<hr /></th>
              <th>Qty<hr /></th>
              <th>Total<hr /></th>
            </tr>
          </thead>
          <?php
		$detail=mysql_query("SELECT * FROM  temp_order_details WHERE cartid='".$res['cartid']."'");
		$sl=0;
		$net_total=0;
		?>
          <tbody>
            <?php
				while($rec=mysql_fetch_assoc($detail)){
				$sl++;
				if($rec['type']=='product'){
				$pro_details=mysql_fetch_assoc(mysql_query("SELECT * FROM product_master WHERE product_id='".$rec['productid']."'"));
				$unit=mysql_fetch_assoc(mysql_query("SELECT * FROM product_qty WHERE product_id='".$rec['productid']."' AND weight_id='".$rec['size_id']."'"));
				$proName=$pro_details['product_name'];
				$unt=$unit['sku'];
				}elseif($rec['type']=='pro_special'){
				$pro_details=mysql_fetch_assoc(mysql_query("SELECT * FROM product_master WHERE product_id='".$rec['productid']."'"));
				$unit=mysql_fetch_assoc(mysql_query("SELECT * FROM product_qty WHERE product_id='".$rec['productid']."' AND weight_id='".$rec['size_id']."'"));
				$proName=$pro_details['product_name'];
				$unt=$unit['sku'];
				}elseif($rec['type']=='hotdeal'){
				$pro_details=mysql_fetch_assoc(mysql_query("SELECT * FROM hotdeal_master WHERE deal_id='".$rec['productid']."'"));
				$proName=$pro_details['deal_name'];
				$unit1=mysql_fetch_assoc(mysql_query("SELECT * FROM product_qty WHERE weight_id='".$pro_details['main_weight']."'"));
				$unit2=mysql_fetch_assoc(mysql_query("SELECT * FROM product_qty WHERE weight_id='".$pro_details['free_weight']."'"));
				$unt=$pro_details['qty'].' x '.$unit1['sku'].' + '.$pro_details['free_qty'].' x '.$unit2['sku'];
				}elseif($rec['type']=='promo'){
				$pro_details=mysql_fetch_assoc(mysql_query("SELECT * FROM promo_master WHERE deal_id='".$rec['productid']."'"));
				$unit1=mysql_fetch_assoc(mysql_query("SELECT * FROM product_qty WHERE weight_id='".$pro_details['main_weight']."'"));
				$unit2=mysql_fetch_assoc(mysql_query("SELECT * FROM product_qty WHERE weight_id='".$pro_details['free_weight']."'"));
				$unt=$pro_details['qty'].' x '.$unit1['sku'].' + '.$pro_details['free_qty'].' x '.$unit2['sku'];
				$proName=$pro_details['deal_name'];
				}else{
				$pro_details=mysql_fetch_assoc(mysql_query("SELECT * FROM gift_master WHERE gift_id='".$rec['productid']."'"));
				$proName=$pro_details['name'];
				$unt='1 Coupon';
				}
				$price=($rec['price']-$rec['discount']);
				?>
            <tr>
              <td><?=$proName?><br><a href="javascript:void(0);" onClick="removeCart('<?=$rec['id']?>');" style="text-decoration:none; font-size:10px; font-weight:normal">Remove</a></td>
              <td><?=$unt?></td>
              <td><?=number_format(floatval($price),2)?></td>
              <td><select name="qty[]" class="addtocart_txtfield" id="qty<?=$sl?>" data-role="none">
				<?php
                if($rec['type']=='product'){
                $sql_check_qty=mysql_fetch_assoc(mysql_query("SELECT * FROM current_stock WHERE qty_id='".$unit['weight_id']."' AND price='".$rec['price']."' AND qty>0 ORDER BY id ASC LIMIT 1"));
                $engage_sql=mysql_query("SELECT * FROM tempo_engage WHERE cur_id='".$sql_check_qty['id']."'");
                $engage_qty=0;
                while($res_engage=mysql_fetch_assoc($engage_sql)){
                if((strtotime(date("Y-m-d H:i:s"))-strtotime($res_engage['engage_date_time']))<=1800)
                {
                    $engage_qty=$engage_qty+$res_engage['qty'];
                }
                }
                $limit_qty=intval($sql_check_qty['qty'])-intval($engage_qty);
                for($m=1; $m<=$limit_qty; $m++)
                {
                ?>
                <option value="<?=$m?>" <?php if($m==intval($rec['qty'])){ echo 'selected'; } ?>><?=$m?></option>
                <?php
                }
                }elseif($rec['type']=='pro_special'){
                if($unit['unit']=='Kg'){ $unitQty=$unit['qty_weght']*1000; }else{ $unitQty=$unit['qty_weght']; }
                $sql_check_qty=mysql_fetch_assoc(mysql_query("SELECT * FROM stock_detail_special JOIN stock_master_special ON stock_detail_special.txn_id=stock_master_special.txn_id WHERE stock_detail_special.qty_id='".$unit['weight_id']."' AND stock_master_special.product_qty>='".$unitQty."' AND stock_detail_special.price='".$rec['price']."' ORDER BY stock_master_special.txn_id ASC LIMIT 1"));
                
                $limit_qty=(intval($sql_check_qty['product_qty'])/$unitQty);
                for($m=1; $m<=$limit_qty; $m++)
                {
                ?>
                <option value="<?=$m?>" <?php if($m==intval($rec['qty'])){ echo 'selected'; } ?>><?=$m?></option>
                <?php
                }
                }elseif($rec['type']=='hotdeal'){
                $sql_check_qty=mysql_fetch_assoc(mysql_query("SELECT * FROM current_stock_hotdeal WHERE deal_id='".$rec['productid']."' AND price='".$rec['price']."'"));
                $engage_sql=mysql_query("SELECT * FROM tempo_engage_hotdeal WHERE cur_id='".$sql_check_qty['id']."'");
                $engage_qty=0;
                while($res_engage=mysql_fetch_assoc($engage_sql)){
                if((strtotime(date("Y-m-d H:i:s"))-strtotime($res_engage['engage_date_time']))<=1800)
                {
                    $engage_qty=$engage_qty+$res_engage['qty'];
                }
                }
                $limit_qty=intval($sql_check_qty['qty'])-intval($engage_qty);
                for($m=1; $m<=$limit_qty; $m++)
                {
                ?>
                <option value="<?=$m?>" <?php if($m==intval($rec['qty'])){ echo 'selected'; } ?>><?=$m?></option>
                <?php
                }
                }elseif($rec['type']=='promo'){
                $sql_check_qty=mysql_fetch_assoc(mysql_query("SELECT * FROM current_stock_promo WHERE deal_id='".$rec['productid']."' AND price='".$rec['price']."'"));
                $engage_sql=mysql_query("SELECT * FROM tempo_engage_promo WHERE cur_id='".$sql_check_qty['id']."'");
                $engage_qty=0;
                while($res_engage=mysql_fetch_assoc($engage_sql)){
                if((strtotime(date("Y-m-d H:i:s"))-strtotime($res_engage['engage_date_time']))<=1800)
                {
                    $engage_qty=$engage_qty+$res_engage['qty'];
                }
                }
                $limit_qty=intval($sql_check_qty['qty'])-intval($engage_qty);
                for($m=1; $m<=$limit_qty; $m++)
                {
                ?>
                <option value="<?=$m?>" <?php if($m==intval($rec['qty'])){ echo 'selected'; } ?>><?=$m?></option>
                <?php
                }
                }else{
                for($h=1;$h<=100;$h++)
                {
                ?>
                <option value="<?=$h?>" <?php if($h==intval($rec['qty'])){ echo 'selected'; } ?>><?=$h?></option>
                <?php }} ?>
            </select>
            <a href="javascript: void(0);" onClick="updateCart('<?=$rec['id']?>', '<?=$sl?>');" style="text-decoration:none; font-size:10px; font-weight:normal">Update</a></td>
              <td><?php $price=($rec['price']-$rec['discount']);
			?>
            <?php $total=$price*$rec['qty']; echo number_format(floatval($total),2); ?>
            <?php $net_total=$net_total+$total; ?></td>
            </tr>
            <?php } ?>
        
          </tbody>
        </table>
       
        <label class="cat_d_label"><input type="radio" name="order_type" title="<?=$title?>" value="single" <?=$disable?> checked="checked" onClick="divSelect('single');"><b>Single Time Order</b></label>
        <div id="singleDateDiv" class="singleDateDiv2">
          <span class="dateSpan"><b>Date of Delivery:</b> &nbsp;<input type="text" title="<?=$title?>" <?=$disable?> name="delivery_date" readonly id="datepicker1" class="dateBox"></span><br>
			<span class="dateSpan"><b>Time of Delivery:</b> <input type="text" title="<?=$title?>" <?=$disable?> name="delivery_time" readonly class="dateBox" id="time1" onClick="javascript: alert('Select date first!');" data-role="datebox"></span>
            <!--<span class="dateSpan"><b>Date of Delivery:</b> &nbsp;<input name="mode3" id="mode3" type="text" data-role="datebox" data-options='{"mode":"flipbox", "afterToday":"true"}' /></span>
            <span class="dateSpan"><b>Time of Delivery:</b> <input name="mode7" id="mode7" type="text" data-role="datebox" data-options='{"mode":"timeflipbox"}' /></span>-->
        </div>
			
        <?php
		}else{
		echo 'There is no item in your Cart!';
		}
		}else{
		echo 'There is no item in your Cart!';
		}
		?>
    </div><!-- /cart__dtl_bx-->
    
    <div class="total">Total Amount Purchashed: <?=number_format(floatval($net_total),2)?>/-</div>
    
    <input type="hidden" id="net_total" value="<?=$net_total?>">
    <input type="hidden" id="id" value="<?=$res['cartid']?>">
    <div class="cat_d_greentxt">
    <table class="cart_d_tbl">
        <tr>
        	<td align="left"><button class="cont_shop3 cart_checkout2" type="button" value="Checkout" onClick="clearOrder();">Clear All</button></td>
            
            <td align="center"><button class="cont_shop cart_checkout2" type="button" value="Checkout" onClick="window.history.go(-1);">Continue Shop</button></td>
            <td align="right"><button class="cont_shop2 cart_checkout2" type="button" value="Checkout" onClick="frmSubmit('<?=floatval($net_total)?>', '<?=$shipping_cutoff?>', '<?=$purchasing_option?>');">Checkout</button></td>
        </tr>
    </table>
    </div>
    <!--<p class="greentxt">Products 1 of 25</p>-->
    
    <div class="spacer"></div>
    </form>
  </section>
  <!-- /cart_detl_pan--> 
  
</div>
<!-- /container--> 

<footer class="footer">
<pre><a href="#">About US</a> | <a href="#">Contact us</a></pre>
<div class="spacer"></div>
</footer>


</body>
</html>
<?php
$mindate=0;
$currHour=date("H");
$currMin=date("i")+intval($delivery_time_gap)+(date("i")%15);
if($currMin>=240)
{
	$minMin=$currMin-240;
	$minHour=$currHour+4;
	if($minHour>=22)
	{
		$minHour=07;
		$mindate=1;
	}
	if($minHour<07)
	{
		$minHour=07;
	}
}
elseif($currMin>=180 AND $currMin<240)
{
	$minMin=$currMin-180;
	$minHour=$currHour+3;
	if($minHour>=22)
	{
		$minHour=07;
		$mindate=1;
	}
	if($minHour<07)
	{
		$minHour=07;
	}
}
elseif($currMin>=120 AND $currMin<180)
{
	$minMin=$currMin-120;
	$minHour=$currHour+2;
	if($minHour>=22)
	{
		$minHour=07;
		$mindate=1;
	}
	if($minHour<07)
	{
		$minHour=07;
	}
}
else
{
	$minMin=$currMin-60;
	$minHour=$currHour+1;
	if($minHour>=22)
	{
		$minHour=07;
		$mindate=1;
	}
	if($minHour<07)
	{
		$minHour=07;
	}
}

?>
<link href="css/ui-lightness/jquery-ui-1.10.4.custom.css" rel="stylesheet">
<script src="js/jquery-1.10.2.js"></script>
<script src="js/jquery-ui-1.10.4.custom.js"></script>

<script type="text/javascript" src="http://tazamandi.in/include/ui-1.10.0/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="http://tazamandi.in/include/ui-1.10.0/jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="http://tazamandi.in/include/ui-1.10.0/jquery.ui.tabs.min.js"></script>
<script type="text/javascript" src="http://tazamandi.in/include/ui-1.10.0/jquery.ui.position.min.js"></script>

<script type="text/javascript" src="http://tazamandi.in/jquery.ui.timepicker.js?v=0.3.3"></script>

<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>




<script type="text/javascript">
$( "#datepicker1" ).datepicker({
	inline: true,
	dateFormat: 'dd-mm-yy',
	minDate: <?=$mindate?>,
	onSelect: function(date)
	{
		$("#time1").attr("id","timepicker1");
		$("#timepicker1").attr("onClick","");
		if(date=='<?=date("d-m-Y")?>')
		{
			$('#timepicker1').timepicker({
				onHourShow: OnHourShowCallback1,
				onMinuteShow: OnMinuteShowCallback1,
				minutes: { interval: 15 },
				minTime: {
				   hour: <?=$minHour?>, minute: <?=$minMin?>
			   },
			   maxTime: {
				   hour: 22, minute: 00
			   }
			});
		}else{
			$('#timepicker1').timepicker({
				onHourShow: OnHourShowCallback,
				onMinuteShow: OnMinuteShowCallback,
				minutes: { interval: 15 },
				minTime: {
				   hour: 07, minute: 00
			   },
			   maxTime: {
				   hour: 22, minute: 00
			   }
			});
		}
	}
});

$( "#datepicker2" ).datepicker({
	inline: true,
	dateFormat: 'dd-mm-yy',
	minDate: <?=$mindate?>,
	onSelect: function(date)
	{
		$("#time2").attr("id","timepicker2");
		$("#timepicker2").attr("onClick","");
		if(date=='<?=date("d-m-Y")?>')
		{
			$('#timepicker2').timepicker({
				onHourShow: OnHourShowCallback1,
				onMinuteShow: OnMinuteShowCallback1,
				minutes: { interval: 15 },
				minTime: {
				   hour: <?=$minHour?>, minute: <?=$minMin?>
			   },
			   maxTime: {
				   hour: 22, minute: 00
			   }
			});
		}else{
			$('#timepicker2').timepicker({
				onHourShow: OnHourShowCallback,
				onMinuteShow: OnMinuteShowCallback,
				minutes: { interval: 15 },
				minTime: {
				   hour: 07, minute: 00
			   },
			   maxTime: {
				   hour: 22, minute: 00
			   }
			});
		}
	}
});
function OnHourShowCallback(hour) {
    if ((hour > 22) || (hour < 7)) {
        return false; // not valid
    }
    return true; // valid
}
function OnMinuteShowCallback(hour, minute) {
    if ((hour == 22) && (minute > 00)) { return false; } // not valid
    if ((hour == 7) && (minute < 00)) { return false; }   // not valid
    return true;  // valid
}
function OnHourShowCallback1(hour) {
    if ((hour > 22) || (hour < <?=$minHour?>)) {
        return false; // not valid
    }
    return true; // valid
}
function OnMinuteShowCallback1(hour, minute) {
    if ((hour == 22) && (minute > 00)) { return false; } // not valid
    if ((hour == <?=$minHour?>) && (minute < <?=$minMin?>)) { return false; }   // not valid
    return true;  // valid
}

$( "#datepicker3" ).datepicker({
	inline: true,
	dateFormat: 'dd-mm-yy',
	minDate: <?=($mindate+1)?>
});
</script>
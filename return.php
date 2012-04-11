<?php
/*******************************************************************\

2Checkout INS Test Class

The return.php file converts the parameters on the approved URL 
passback and saves them to a JSON file. Some defaults have to be 
used because some parameter sets return more data than others. 
The test.php script loads the JSON data, computes the hash and 
returns the parameters as an INS message.

Date: 04-11-2012
Version: 0.0.1
Author: Craig Christenson
Organization: 2Checkout.com

********************************************************************/
error_reporting(0);

class Test {

	public $params;

	public function __set($key,$val) {
		$this->$key=$val;
	}

	public function __get($key) {
		return $this->$key;
	}

	public function preg_grep_keys_products($input) {
		if (isset($input['li_0_type'])) {
		    $keyword = 'li_';
		    $match = preg_grep("/{$keyword}/i", array_keys( $input ),0);
		    $vals = array();
		    foreach ( $match as $key )
		    {
		        $vals[$key] = $input[$key];
		    }
		    $i = 0;
		    $ii = 1;		    
		    $products = array();
			while ($vals['li_'.$i.'_type']) {
				$iii = 1;
				while ($vals['li_'.$i.'_quantity'] >= $iii) {
					$products['item_cust_amount_'.$ii] = $vals['li_'.$i.'_price'];
					$products['item_duration_'.$ii] = $vals['li_'.$i.'_description'];
					$products['item_id_'.$ii] = $vals['li_'.$i.'_product_id'];
					$products['item_list_amount_'.$ii] = $vals['li_'.$i.'_price'];
					$products['item_name_'.$ii] = $vals['li_'.$i.'_name'];
					$products['item_rec_date_next_'.$ii] = date("Y-m-d");
					$products['item_rec_install_billed_'.$ii] = '1';
					$products['item_rec_list_amount_'.$ii] = $vals['li_'.$i.'_price'];
					$products['item_rec_status_'.$ii] = 'live';
					$products['item_recurrence_'.$ii] = $vals['li_'.$i.'_recurrence'];
					$products['item_type_'.$ii] = 'bill';
					$products['item_usd_amount_'.$ii] = $vals['li_'.$i.'_price'];
	            	$iii++;
	            	$ii++;
	            }
	            $iii = 1;
	            $i++;
			}

		} elseif (isset($input['product_id'])) {
		    $keyword1 = 'product';
		    $match1 = preg_grep("/{$keyword1}/i", array_keys( $input ),0);
		    $vals = array();
		    foreach ( $match1 as $key )
		    {
		        $vals[$key] = $input[$key];
		    }
		    $keyword2 = 'quantity';
		    $match2 = preg_grep("/{$keyword2}/i", array_keys( $input ),0);
		    foreach ( $match2 as $key )
		    {
		        $vals[$key] = $input[$key];
		    }

		    $products = array();
		    $i = 1;
			$iii = 1;
			while ($vals['quantity'] >= $iii) {
				$products['item_cust_amount_'.$i] = '1.00';
				$products['item_duration_'.$i] = 'Forever';
				$products['item_id_'.$i] = $vals['merchant_product_id'];
				$products['item_list_amount_'.$i] = '1.00';
				$products['item_name_'.$i] = $vals['product_description'];
				$products['item_rec_date_next_'.$i] = date("Y-m-d");
				$products['item_rec_install_billed_'.$i] = '1';
				$products['item_rec_list_amount_'.$i] = '1.00';
				$products['item_rec_status_'.$i] = 'live';
				$products['item_recurrence_'.$i] = '1 Month';
				$products['item_type_'.$i] = 'bill';
				$products['item_usd_amount_'.$i] = '1.00';
				$i++;
				$iii++;
			}

		    $ii = 1;
			while ($vals['product_id'.$ii]) {
				$iii = 1;
				while ($vals['quantity'.$ii] >= $iii) {
					$products['item_cust_amount_'.$i] = '1.00';
					$products['item_duration_'.$i] = 'Forever';
					$products['item_id_'.$i] = $vals['merchant_product_id'.$ii];
					$products['item_list_amount_'.$i] = '1.00';
					$products['item_name_'.$i] = $vals['product_description'.$ii];
					$products['item_rec_date_next_'.$i] = date("Y-m-d");
					$products['item_rec_install_billed_'.$i] = '1';
					$products['item_rec_list_amount_'.$i] = '1.00';
					$products['item_rec_status_'.$i] = 'live';
					$products['item_recurrence_'.$i] = '1 Month';
					$products['item_type_'.$i] = 'bill';
					$products['item_usd_amount_'.$i] = '1.00';
	            	$i++;
	            	$iii++;
	            }
	            $ii++;
			}

		} elseif (isset($input['cart_order_id'])) {
		    $products = array();
			$products['item_cust_amount_1'] = $input['total'];
			$products['item_duration_1'] = '';
			$products['item_id_1'] = $input['cart_order_id'];
			$products['item_list_amount_1'] = $input['total'];
			$products['item_name_1'] = $input['cart_order_id'];
			$products['item_rec_date_next_1'] = '';
			$products['item_rec_install_billed_1'] = '';
			$products['item_rec_list_amount_1'] = '';
			$products['item_rec_status_1'] = '';
			$products['item_recurrence_1'] = '';
			$products['item_type_1'] = 'bill';
			$products['item_usd_amount_1'] = $input['total'];

		} elseif (isset($input['x_invoice_num'])) {
		    $products = array();
			$products['item_cust_amount_1'] = $input['x_amount'];
			$products['item_duration_1'] = '';
			$products['item_id_1'] = $input['x_invoice_num'];
			$products['item_list_amount_1'] = $input['x_amount'];
			$products['item_name_1'] = $input['x_invoice_num'];
			$products['item_rec_date_next_1'] = '';
			$products['item_rec_install_billed_1'] = '';
			$products['item_rec_list_amount_1'] = '';
			$products['item_rec_status_1'] = '';
			$products['item_recurrence_1'] = '';
			$products['item_type_1'] = 'bill';
			$products['item_usd_amount_1'] = $input['x_amount'];
		}

		return $products;
	}

	public function params($codata) {

		$insdata = array();
		$insdata['auth_exp'] = date("Y-m-d");		
		$insdata['bill_city'] = $codata['city'];
		$insdata['bill_country'] = $codata['country'];
		$insdata['bill_postal_code'] = $codata['zip'];
		$insdata['bill_state'] = $codata['state'];
		$insdata['bill_street_address'] = $codata['street_address'];
		$insdata['bill_street_address2'] = '';
		$insdata['cust_currency'] = 'USD';
		$insdata['customer_email'] = $codata['email'];
		$insdata['customer_first_name'] = $codata['first_name'];
		$insdata['customer_ip'] = '66.194.132.135';
		$insdata['customer_ip_country'] = 'United States';
		$insdata['customer_last_name'] = $codata['last_name'];
		$insdata['customer_name'] = $codata['card_holder_name'];
		$insdata['customer_phone'] = $codata['phone'];
		$insdata['invoice_id'] = $codata['invoice_id'];
		
		$lineitems = $this->preg_grep_keys_products($codata);
		foreach ($lineitems as $key => $value) {
		 	$insdata[$key] = $value;
		}

		$insdata['key_count'] = '50';
		$insdata['list_currency'] = 'USD';
		$insdata['message_description'] = 'New order created';
		$insdata['message_id'] = '1000';
		$insdata['payment_type'] = 'paypal ec';
		$insdata['recurring'] = '1';
		$insdata['sale_date_placed'] = date("Y-m-d H:i:s");
		$insdata['sale_id'] = $codata['order_number'];
		if(isset($codata['ship_city'])) {
			$insdata['ship_city'] = $codata['ship_city']; 
		}
		if(isset($codata['ship_country'])) {
				$insdata['ship_country'] = $codata['ship_country'];
			}
		if(isset($codata['ship_name'])) {		
				$insdata['ship_name'] = $codata['ship_name'];
			}
		if(isset($codata['ship_postal_code'])) {		
				$insdata['ship_postal_code'] = $codata['ship_postal_code'];
			}
		if(isset($codata['ship_state'])) {		
				$insdata['ship_state'] = $codata['ship_state'];
			}	
		$insdata['ship_status'] = '';
		if(isset($codata['ship_street_address'])) {		
				$insdata['ship_street_address'] = $codata['ship_street_address'];
			}
		if(isset($codata['ship_street_address2'])) {		
				$insdata['ship_street_address2'] = $codata['ship_street_address2'];
			}
		$insdata['ship_tracking_number'] = '';
		$insdata['timestamp'] = date("Y-m-d H:i:s");
		$insdata['vendor_id'] = $codata['sid'];
		$insdata['vendor_order_id'] = $codata['merchant_order_id'];

		return $insdata;
	}
}
?>
<html>
<head>
	<title>2Checkout INS Test Application</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<div id="main_container">
		<div class="main_content">
			<div class="center_content">
				<div class="right_content">

					<?php

					$codata = array();
					foreach ($_REQUEST as $k => $v) {
					    $codata[$k] = $v;
					}

					$new_sale = new Test();
					$new_sale->params = $new_sale->params($codata);

					$fp = fopen($new_sale->params['sale_id'].'.json', 'w');
					fwrite($fp, json_encode($new_sale->params));
					fclose($fp);

					if ($_REQUEST) {
					    $data = array();
					    foreach($_REQUEST as $k => $v) {
					        $data[$k] = $v;
					    }
					}
					if (isset($_POST['sid']))
						$return_method = "post";
					else
						$return_method = "get";


					echo "<p>Order Number (Sale ID) is ".$new_sale->params['sale_id']."</p>";
					echo "<p>INS Params saved under <strong><a href='test.php?sale_id=".$new_sale->params['sale_id']."' target='_blank'>".$new_sale->params['sale_id'].".json</a></strong></p>";
					?>

						<div class='form'>
							<form  id="returnform" method=<?php echo "'".$return_method."'" ?> onsubmit="foo()" > 
								<fieldset>
									<span class='legend'><strong>Return Params to Approved URL</strong></span>
									<dl>
									<dt><label for='x_receipt_link_url'>Approved URL:</label></dt>
									<dd><input type='text' name='x_receipt_link_url' id='x_receipt_link_url' size='40' value="" /></dd>
									
									<?php
									    foreach ($data as $key => $value) {
									        echo "<dd><input type='hidden' name='".$key."' id='".$key."' size='40' value='".$value."' /></dd>";
									    }
									?>
									</dl>
									<dl class='submit'>
										<input type="submit" value="Submit" />
									</dl>
								</fieldset>
							</form>

						    <script type="text/javascript">
						        function foo() {
						            document.forms["returnform"].action= document.forms["returnform"]["x_receipt_link_url"].value;
						            return true; 
						        }
						    </script>

		    			</div>
				</div>
			</div>
		</div>
	</div>	
</body>
</html>


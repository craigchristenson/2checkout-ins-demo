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
?>
<html>
<head>
	<title>2Checkout INS Test Application</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

<?php

error_reporting(0);

$file = $_GET['sale_id'].'.json';
$sale = json_decode(file_get_contents($file), true);
?>

<div id="main_container">
	<div class="main_content">
		<div class="center_content">
			<div class="right_content">
			<div class="form">
			<form action="" method="get" class="niceform">
				<fieldset>
					<span class="legend"><strong>Load Sale Data</strong></span>
					<dl>
					<dt><label for="sale_id">Sale ID:</label></dt>
					<dd><input type="text" name="sale_id" id="sale_id" size="40" value=<?php if (isset($_GET['sale_id'])) {echo '"'.$_GET['sale_id'].'"';} ?> /></dd>
					</dl>
					<dl>
					<dt><label for="ins_url">INS URL:</label></dt>
					<dd><input type="text" name="ins_url" id="ins_url" size="40" value=<?php if (isset($_GET['ins_url'])) {echo '"'.$_GET['ins_url'].'"';} ?> ></dd>
					</dl>
					<dl>
					<dt><label for="secret_word">Secret Word:</label></dt>
					<dd><input type="text" name="secret_word" id="secret_word" size="40" value=<?php if (isset($_GET['secret_word'])) {echo '"'.$_GET['secret_word'].'"';} ?> ></dd>
					</dl>
					<dl>
					<dt><label for="return_type">Return Type:</label></dt>
					<dd><select name="return_type" id="return_type">
						<option value="form" selected="selected">Use HTML Form</option>
						<option value="curl">Use Curl</option>}
					</select></dd>
					</dl>
					<dl class="submit">
					<input type="submit" name="submit" id="submit" value="Load Data" />
					</dl>
				</fieldset>
			</form>
			</div>
			<?php
			if (isset($sale) && isset($_GET['secret_word']) && isset($_GET['ins_url'])) {
			$md5_hash = strtoupper(md5($sale['sale_id'] . $sale['vendor_id'] . $sale['invoice_id'] . $_GET['secret_word']));
			if ($_GET['return_type'] == 'form') {
				$form_url = $_GET['ins_url'];
			} else {
				$form_url = '';
			}

			echo "
			<div class='form'>
			<form action='".$form_url."' method='post' class='niceform'>
				<fieldset>
					<span class='legend'><strong>Send INS Message</strong></span>
					<dl>
					<dt><label for='message_type'>message_type:</label></dt>
					<dd><select name='message_type' id='message_type' />
						<option value='ORDER_CREATED'>ORDER_CREATED</option>
						<option value='FRAUD_STATUS_CHANGED'>FRAUD_STATUS_CHANGED</option>
						<option value='SHIP_STATUS_CHANGED'>SHIP_STATUS_CHANGED</option>
						<option value='INVOICE_STATUS_CHANGED'>INVOICE_STATUS_CHANGED</option>
						<option value='REFUND_ISSUED'>REFUND_ISSUED</option>
						<option value='RECURRING_INSTALLMENT_SUCCESS'>RECURRING_INSTALLMENT_SUCCESS</option>
						<option value='RECURRING_INSTALLMENT_FAILED'>RECURRING_INSTALLMENT_FAILED</option>
						<option value='RECURRING_STOPPED'>RECURRING_STOPPED</option>
						<option value='RECURRING_COMPLETE'>RECURRING_COMPLETE</option>
						<option value='RECURRING_RESTARTED'>RECURRING_RESTARTED</option>
						</select>
					</dd>";

					foreach ($sale as $key => $value) {
					echo "<dt><label for='".$key."'>".$key.":</label></dt>
					<dd><input type='text' name='".$key."' id='".$key."' size='40' value='".$value."' /></dd>";
					}

					echo "
					<dt><label for='md5_hash'>md5_hash:</label></dt>
					<dd><input type='text' name='md5_hash' id='md5_hash' size='40' value='".$md5_hash."' ></dd>
					</dl>
					<dl class='submit'>
					<input type='submit' name='submit' id='submit' value='Send INS Message' />
					</dl>
				</fieldset>
			</form>
			</div>
";
}

//Use the code below is provided incase you would rather use curl to replicate the call.

if ($_POST) {

  $data = array();
    foreach($_POST as $k => $v) {
	    //Sanitize Input Data
		$v = htmlspecialchars($v);
        $v = stripslashes($v);
	$data[$k] = $v;
    }

	$url = $data['ins_url'];
	unset($data['submit']);
	unset($data['ins_url']);
        $data['key_count'] = count($data);

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);
	curl_close($ch);

        echo "<p><pre>";
        print_r($data);
        echo "</pre></p>";
}
?>
</div>
</div>
</div>
</body>
</html>

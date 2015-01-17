<?php if ($index_refer <> 1) { exit(); } ?>
<?php
	// admin check
	if (IsAdmin() == false) {
		PutWindow($gfx_dir, $txt['general12'], $txt['general2'], "warning.gif", "50");
	}
	else {
	
		if ($action == "add_discount") {
			$number = $_POST['number'];
			for ($i = 0; $i <= $number; $i++) {
				$code = CreateRandomCode(15);
				$percentage = CheckBox($_POST['percentage']);
				$amount = $_POST['amount'];
				$createdate = Date($date_format);
				$discount_query="INSERT INTO `".$dbtablesprefix."discount` (`code`, `orderid`, `amount`, `percentage`, `createdate`) VALUES ('".$code."', '0', '".$amount."', '".$percentage."', '".$createdate."')";
				$discount_sql = mysql_query($discount_query) or die(mysql_error());
				
			}
		}
		echo '<table width="100%" class="borderless">
				<tr><td>
					<form method="POST" action="index.php?page=discountadmin">
						<input type="hidden" name="action" value="add_discount">
						'.$txt['discountadmin2'].' <input type="text" name="number" value="10" size="4" maxlength="4"><br />
						'.$txt['discountadmin3'].' <input type="text" name="amount" value="" size="7" maxlength="7"><br />
						'.$txt['discountadmin4'].' <input type="checkbox" name="percentage"><br />
						<input type="submit" value="'.$txt['discountadmin5'].'">
					</form>
				</td></tr>	
			</table>
			<br /><br />';		
			
		echo '<table width="100%" class="datatable">
				<caption>'.$txt['discountadmin6'].'</caption>
				<tr><th>'.$txt['discountadmin7'].'</th><th>'.$txt['discountadmin3'].'</th><th>'.$txt['discountadmin9'].'</th></tr>';
		
		$discount_query="SELECT * FROM `".$dbtablesprefix."discount` WHERE `orderid` = '0'";
		$discount_sql = mysql_query($discount_query) or die(mysql_error());
		if (mysql_num_rows($discount_sql) == 0) {
			echo '<tr><td colspan="3">'.$txt['discountadmin8'].'</td></tr>';
		}
		else { 
			// let's read the discount codes
			while ($discount_row = mysql_fetch_row($discount_sql)) {
				$discount = $discount_row[2];
				if ($discount_row[3] == 1) { $discount .= "%"; }
				else { $discount = $currency_symbol_pre.myNumberFormat($discount,$number_format).$currency_symbol_post; }
				echo '<tr><td>'.$discount_row[0].'</td><td>'.$discount.'</td><td>'.$discount_row[4].'</td></tr>';
			}
		}
		echo '</table>';
	}
?>
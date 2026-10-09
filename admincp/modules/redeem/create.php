<?php
/**
 * Redeem Code
 * https://webenginecms.org/
 * 
 * @version 1.2.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2026 Lautaro Angelico, All Rights Reserved
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 * @build w641ba01901cb0925ec50f543e59acbd
 */

echo '<h2>Create new Redeemable Code</h2>';
echo '<p>Please select the type of redeemable code you would like to create.</p>';

if(check_value($_GET['type'])) {
	
	try {
		if(!in_array($_GET['type'], array('regular', 'limited', 'account'))) throw new Exception('The selected code type is not valid.');
		$creditSystem = new CreditSystem();
		
		// code submit
		if(check_value($_POST['code_submit'])) {
			try {
				
				if($_POST['code_configid'] == 0) throw new Exception('The selected credit configuration is not valid.');
				
				$RedeemCodeAdd = new \Plugin\RedeemCode\RedeemCode();
				$RedeemCodeAdd->setCode($_POST['code_value']);
				$RedeemCodeAdd->setCodeType($_GET['type']);
				
				if($_GET['type'] == 'limited') {
					$RedeemCodeAdd->setLimit($_POST['code_limit']);
				}
				
				if($_GET['type'] == 'account') {
					$RedeemCodeAdd->setUser($_POST['code_user']);
				}
				
				$RedeemCodeAdd->setCreditsConfigId($_POST['code_configid']);
				$RedeemCodeAdd->setReward($_POST['code_reward']);
				$RedeemCodeAdd->addRewardCode();
				
				header('Location: ' . admincp_base('redeem&page=list'));
			} catch(Exception $ex) {
				message('error', $ex->getMessage());
			}
		}
		
		echo '<div class="row">';
			echo '<div class="col-xs-12 col-md-5 col-lg-5">';
				echo '<div class="panel panel-success">';
					echo '<div class="panel-heading">Create new <strong>'.$_GET['type'].'</strong> code</div>';
					echo '<div class="panel-body">';
						
						echo '<form role="form" action="'.admincp_base('redeem&page=create&type='.$_GET['type']).'" method="post">';
							echo '<div class="form-group">';
								echo '<label for="input_1">Code:</label>';
								echo '<input type="text" class="form-control" id="input_1" name="code_value" maxlength="50" placeholder="MY-CODE-123"/>';
							echo '</div>';
							
							if($_GET['type'] == 'limited') {
								echo '<div class="form-group">';
									echo '<label for="input_1">Limit:</label>';
									echo '<input type="text" class="form-control" id="input_2" name="code_limit" placeholder="25"/>';
								echo '</div>';
							}
							
							if($_GET['type'] == 'account') {
								echo '<div class="form-group">';
									echo '<label for="input_1">Account Username:</label>';
									echo '<input type="text" class="form-control" id="input_3" name="code_user"/>';
								echo '</div>';
							}
							
							echo '<div class="form-group">';
								echo '<label for="input_1">Credit Config:</label>';
								echo $creditSystem->buildSelectInput('code_configid', 0, 'form-control');
							echo '</div>';
							
							echo '<div class="form-group">';
								echo '<label for="input_1">Reward:</label>';
								echo '<input type="text" class="form-control" id="input_5" name="code_reward" placeholder="500"/>';
							echo '</div>';	

							echo '<button type="submit" name="code_submit" value="1" class="btn btn-success">Create Redeemable Code</button>';
						echo '</form>';
						
						
					echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
	
} else {
	echo '<div class="row">';
		echo '<div class="col-xs-12 col-md-4 col-lg-4">';
			echo '<div class="panel panel-info">';
				echo '<div class="panel-heading">Regular</div>';
				echo '<div class="panel-body text-center">';
					echo '<p>Redeemable by any account.</p>';
					echo '<p>Can only be redeemed once per account.</p>';
					echo '<a href="'.admincp_base('redeem&page=create&type=regular').'" class="btn btn-info">Create</a>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		echo '<div class="col-xs-12 col-md-4 col-lg-4">';
			echo '<div class="panel panel-warning">';
				echo '<div class="panel-heading">Limited</div>';
				echo '<div class="panel-body text-center">';
					echo '<p>Redeemable by any account until the limit has been reached.</p>';
					echo '<p>Can only be redeemed once per account.</p>';
					echo '<a href="'.admincp_base('redeem&page=create&type=limited').'" class="btn btn-warning">Create</a>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		echo '<div class="col-xs-12 col-md-4 col-lg-4">';
			echo '<div class="panel panel-danger">';
				echo '<div class="panel-heading">Account</div>';
				echo '<div class="panel-body text-center">';
					echo '<p>Redeemable by a single account.</p>';
					echo '<p>Can only be redeemed once.</p>';
					echo '<a href="'.admincp_base('redeem&page=create&type=account').'" class="btn btn-danger">Create</a>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
}
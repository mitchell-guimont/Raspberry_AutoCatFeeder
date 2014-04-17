<?php 
	require_once 'config.php';
	
	session_start();
	
	if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
		if (isset($_POST["feed_type"])) {
			
			// Set feed_cat.py API key
			$api='';
			
			if ($_POST["feed_type"] == "feed-early") {
				$early=1;
			} else {
				$early=0;
			}
			if ($_POST["feed_type"] == "feed-extra") {
				$extra=1;
			} else {
				$extra=0;
			}
			$feedCatArg = $api . ' ' . $extra . ' ' . $early . ' ' . $_SESSION['person_id'];

			// Execute the python script with the JSON data
			$result = shell_exec('sudo python /var/www/python_scripts/feed_cat.py ' . $feedCatArg);
			
			print($result);
		}
	}
?>
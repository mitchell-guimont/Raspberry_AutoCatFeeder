<?php 
	require_once 'config.php';
	
	session_start();
	 
	try {
		$db = new PDO("sqlite:" . conf::DB_File);
				
		$result = $db->prepare('SELECT log_datetime, is_extra, is_early, side, person.username FROM feed_log INNER JOIN person ON feed_log.person_id = person.person_id WHERE is_cat_feed = 1 ORDER BY log_datetime DESC LIMIT 9');
		
		$result->execute();
		
		if ($rows = $result->fetchAll()) {
			foreach ($rows as $row) {
				echo "<li class='log'>" . $row['log_datetime'] . ": " . $row['username'];
				if ($row['is_extra']==1) {
					echo " - Extra ";	
				}
				if ($row['is_early']==1) {
					echo " - Early ";	
				}
				echo "</li>";
			}
		}
	} catch(PDOException $e) {
		echo $e->getMessage();
	}
?>
<?php 
	require_once 'config.php';
	
	session_start();
	
	/*** Create new person. ***/
	/*$username = '';
	$password = '';
	
	$intermediateSalt = md5(uniqid(rand(), true));
    $salt = substr($intermediateSalt, 0, 6);
    $passwordHash = hash("sha256", $password.$salt);
	
	try {
		$db = new PDO("sqlite:" . conf::DB_File);
    	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$result = $db->prepare('INSERT INTO person (person_id, username, password, is_active, salt) VALUES (null, :username, :password, 1, :salt)');
		
		$result->bindParam(':username', $username, PDO::PARAM_STR);
		$result->bindParam(':password', $passwordHash, PDO::PARAM_STR);
		$result->bindParam(':salt', $salt, PDO::PARAM_STR);
		
		$result->execute();
		
		$db = NULL;
	} catch(PDOException $e) {
		echo $e->getMessage();
	}*/
	 
	try {
		$foundPerson = "false";
		
		if (isset($_POST["username"]) && isset($_POST["pwd"])) {
			$db = new PDO("sqlite:" . conf::DB_File);
			
			$result = $db->prepare('SELECT person_id, username, password, salt FROM person WHERE username = :username AND is_active = 1');
			
			$result->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
			$result->execute();
			
			if ($rows = $result->fetchAll()) {
				if (count($rows) == 1) {
					foreach ($rows as $row) {
						$passwordHash = hash('sha256', $_POST['pwd'].$row['salt']);
						
						if ($passwordHash == $row['password']) {
							$foundPerson = "true";
							$_SESSION['logged_in'] = true;
							$_SESSION['person_id'] = $row['person_id'];
							$_SESSION['username'] = $row['username'];
						}
					}
				}
			}
			
			$db = NULL;
		}
		
		echo $foundPerson;
	} catch(PDOException $e) {
		echo $e->getMessage();
	}
?>
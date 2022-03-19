<?php

        $host_name = "localhost";
        $db_user = "root";
        $db_pass = "";
        $db_name = "basic_banking_system";

// Create connection
        $conn = mysqli_connect($host_name, $db_user, $db_pass, $db_name);

	if(!$conn){
		die(" There was some error in connecting ".mysqli_connect_error());
	}

?>

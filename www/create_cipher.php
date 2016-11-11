<?php
function scramble($msg, $orig_alphabet, $new_alphabet)
{
    for($i = 0; $i < strlen($msg); $i++)
    {
        $char = $msg[$i];
        if(ctype_alpha($char) == TRUE)
        {
            $pos = strpos($orig_alphabet, $char);
            $msg[$i] = $new_alphabet[$pos];
        }
    }
    return $msg;
}

function cipher($message, $alias, $attempts, $age) {

    $db_servername = "localhost";
    $db_username = "root";
    $db_password = "pass";
    $table_name = "crack_the_code.Ciphers";

    $conn = new mysqli($db_servername, $db_username, $db_password);

    // Check connection
    if ($conn->connect_error) {
        //echo "Connection failed: " . $conn->connect_error;
//        $logger->error('Connection to database failed: ', $conn->connect_error);
    }
    # $orig_alphabet = "abcdefghijklmnopqrstuvwxyz";
    $orig_alphabet = "abcdefghijklmnopqrstuvwxyz";
    $alphabet = str_shuffle("~!@#$%^&*+=-<>?:;][{}=|,()");
    $cipher = scramble($message, $orig_alphabet, $alphabet);
    // regex check input
    /*
    $name_pattern = "/^\w+$/";
    $email_pattern = "/^\w+@\w+\.\w{3}$/";
    $age_pattern = "/^\d{1,3}$/";
    $loc_serv_pattern = "/^(True|False)$/";

    $n_match = preg_match($name_pattern, $name);
    $e_match = preg_match($email_pattern, $email);
    $a_match = preg_match($age_pattern, $age);
    $l_match = preg_match($loc_serv_pattern, $allow_loc_services);
    */
    #echo $cipher;
    // if the input fields are all valid, the user is added to the users table
    if (1 < 2/*$n_match == 1 && $e_match == 1 && $a_match == 1 && $l_match == 1*/) {
        // salt password

        // prepare query
        $sql = "INSERT INTO {$table_name} (Alphabet, Message, Cipher, Alias, Attempts, Age) VALUES ('$alphabet', '$message', '$cipher', '$alias', '$attempts', '$age')";

        // execute query
        if ($conn->query($sql) === TRUE) {
            #echo "New cipher success";
            $conn->close();
            return TRUE;
        } else {
            $conn->close();
            return "Error adding new cipher: " . $conn->error;
        }
    }
    $conn->close();
    return "Invalid input.";
}

function clean_message($msg)
{
    if(strlen($msg) <= 2)
    {
        #echo "must be at least 3 characters";
        header('Location: new_cipher.html');
        exit;
    }
    $msg = strtolower($msg);
    $msg = rtrim(ltrim($msg));
    return $msg;
}

function clean_alias($alias)
{
    if(strlen($alias) <= 1)
    {
        header('Location: new_cipher.html');
        exit;
    }
    $alias = rtrim(ltrim($alias));
    $alias = preg_replace('/\s+/', '', $alias);
    return $alias;
}

    $message = $_POST['message'];
    $alias = $_POST['alias'];
    $attempts = 0;#$_POST['attempts'];
    $age = 0;#$_POST['age'];

    $message = clean_message($message);
    $alias = clean_alias($alias);

    $status = cipher($message, $alias, $attempts, $age);

    if ($status === TRUE) {
        #$response->getBody()->write("New cipher created successfully.");
    	#echo "new cipher created successfully";
    } else {
        #$response->getBody()->write("Error creating cipher: ". $status);
    	echo "error creating new cipher";
    }
    header('Location: info.php');
	exit;
    #return $response;
?>
<?php
function attempt($attempt, $alias)
{
    //sql server
    $db_servername = "localhost";
    $db_username = "root";
    $db_password = "pass";
    $table_name = "crack_the_code.Ciphers";
    $conn = new mysqli($db_servername, $db_username, $db_password);
    //
    $sql = "SELECT * FROM {$table_name} ORDER BY CID DESC LIMIT 0, 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) 
    {
        // output data of each row
        $row = $result->fetch_assoc(); 
        $g = $row["Message"];
        $g = strtolower($g);
        $cid = $row["CID"];

        // increment attempts count for the cipher
        $sql = "UPDATE {$table_name} SET Attempts = Attempts + 1 WHERE CID ='$cid'";          
        $result = $conn->query($sql);

        // if the answer is correct
        if(strcmp($attempt,$g) == 0)
        {
            $conn->close();
            header('Location: new_cipher.html');
            exit;
        }
        else
        {       
            // WORK ON A WAY TO GIVE FEEDBACK / HINTS SO ITS NOT COMPLETELY IMPOSSIBLE (also cant be easily brute-forcible)
        }
        return TRUE;
        #echo "CID: " . $row["CID"]. ", : " . $row["firstname"]. " " . $row["lastname"]. "<br>";
    }
    else 
    {
        return TRUE;
    }
}

$attempt = $_POST['attempt'];
$alias = 'user';#$_POST['alias'];

#include "add_cipher.php";

$status = attempt($attempt, $alias);

if ($status === TRUE) {
    #$response->getBody()->write("New cipher created successfully.");
    #echo "new cipher created successfully";
} else {
    #$response->getBody()->write("Error creating cipher: ". $status);
    echo "error creating new cipher";
    exit;
}
header('Location: info.php');
exit;
?>
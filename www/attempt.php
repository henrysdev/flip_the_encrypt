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
        $cipher_to_display = $row['Cipher'];
        $attempt = strtolower($attempt);
        $g = strtolower($g);
        $cid = $row["CID"];
        // increment attempts count for the cipher
        $sql = "UPDATE {$table_name} SET Attempts = Attempts + 1 WHERE CID ='$cid'";          
        $result = $conn->query($sql);
        // if the answer is correct
        echo '<fieldset>';
        if(strcmp($attempt,$g) == 0)
        {
            $conn->close();
            echo '<a href="new_cipher.html">Success! Change the message</a></fieldset>';
            #header('Location: new_cipher.html');
            #exit;
        }
        else
        {       
            // WORK ON A WAY TO GIVE FEEDBACK / HINTS SO ITS NOT COMPLETELY IMPOSSIBLE (also cant be easily brute-forcible)
            $found_string = "";
            for($i = 0; $i < strlen($g); $i++)
            {
                if(strlen($attempt) > $i)
                {
                    if($attempt[$i] == $g[$i])
                    {
                        $found_string = $found_string . $g[$i];
                    }
                    else
                    {
                        $found_string = $found_string . '.';
                    }   
                }
                else
                {
                    $found_string = $found_string . '_';
                }      
            }
            echo '<div><h3>' . $cipher_to_display . '</h3></div>';
            echo '<h3>';
            for($i = 0; $i < strlen($found_string); $i++)
            {
                if($found_string[$i] == '.')
                {
                    echo '<mark style="background-color:red;">' . $attempt[$i] . '</mark>';
                }  
                else if($found_string[$i] == '_')
                {
                    echo '<mark style="background-color:red;">' . $found_string[$i] . '</mark>';
                }
                else
                {
                    echo '<mark style="background-color:green;">' . $attempt[$i] . '</mark>';
                }     
            }
            echo '</h3>';
            echo '<a href="index.php">Try again</a></fieldset>';
        }
        return TRUE;
        #echo "CID: " . $row["CID"]. ", : " . $row["firstname"]. " " . $row["lastname"]. "<br>";
    }
    else 
    {
        return TRUE;
    }
}
#header('Location: info.php');
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
}
?>
<html>
  <style>
  p{padding:0;margin:0;font-family:"Times New Roman", serif;
  letter-spacing: 1px;background-color:#f1f1f1;margin-bottom:5px;margin-top:5px;}
  legend{margin-top:10px;border:1px solid #444;padding:5px;margin-bottom:15px;background-color:#f1f1f1;}
  fieldset{width:300px;margin-left:20px;border:1px solid #444;padding:5px;}
  h2 {text-align: center; margin-bottom: 1px;};
  </style>

    <header>
      <h1>
        Flip the Encrypt
      </h1>
    </header>
  <div>
    <fieldset>
    <legend>Crack the Code to Change it!</legend>
    <title>Flip the Encrypt</title>
    <strong name="current_cipher"></strong>

<?php
  //sql server
  $db_servername = "localhost";
  $db_username = "root";
  $db_password = "pass";
  $table_name = "crack_the_code.Ciphers";
  $conn = new mysqli($db_servername, $db_username, $db_password);
  //

  $cipher_to_display = "";
  $alias_to_display = "";
  $attempts_to_display = 0;

  $sql = "SELECT * FROM {$table_name} ORDER BY CID DESC LIMIT 0, 1";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) 
  {
    // output data of each row
    while($row = $result->fetch_assoc()) 
    {
      $cipher_to_display = $row["Cipher"];
      $alias_to_display = $row["Alias"];
      $attempts_to_display = $row["Attempts"];
      #echo "CID: " . $row["CID"]. ", : " . $row["firstname"]. " " . $row["lastname"]. "<br>";
    }
  }
  else 
  {
    echo "0 results";
  }
  $conn->close();

  $count = strlen($cipher_to_display);
  echo '<div><h2>' . htmlspecialchars($cipher_to_display) . '</h2></div>'."\n";
  echo '<div style="text-align:center;">(' . $count . ' chars)</div>'."\n";
  echo '<div>by: <i>' . htmlspecialchars($alias_to_display) . '</i></div>'."\n";
  echo '<div> attempted <strong>' . htmlspecialchars($attempts_to_display) . '</strong> times</div>'."\n";
?>
</fieldset>
</div>
<form action="attempt.php" method="POST">
<fieldset>
<p>Your Attempt</p><textarea maxlength="20" name="attempt" rows="4" cols="29"></textarea><br />
<br />

<input type="submit" value="Send"><input type="reset" value="Clear">
</fieldset>
</form>

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
            if(strlen($attempt) == strlen($g))
            {
                $found_string = "";
                for($i = 0; $i < strlen($attempt); $i++)
                {
                    if($attempt[$i] == $g[$i])
                    {
                        $found_string = $found_string . $g[$i];
                    }
                    else
                    {
                        $found_string = $found_string . '_';
                    }
                }
                echo '<fieldset><div>' . $cipher_to_display . '</div>';
                echo '<h3>';
                for($i = 0; $i < strlen($attempt); $i++)
                {
                    if($found_string[$i] == '_')
                    {
                        echo '<mark style="background-color:red;">'. $attempt[$i] . '</mark>';
                    }  
                    else
                    {
                        echo '<mark style="background-color:green;">' . $attempt[$i] . '</mark>';
                    }     
                }
                echo '</h3></fieldset>';
                exit;
            }
            else
            {
                echo "Hint: Your entry is not the same length as the code";
                exit;
            }
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
</html>
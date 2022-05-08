<?php
/* login for index.html */
$email = $_POST['email'];
$password = $_POST['password'];
/* open the password.txt file */
$file = fopen("passwords.txt", "r") or die("Unable to open file!");
/* array of keys  5, -14, 31, -9, 3 */
$key = array(5, -14, 31, -9, 3);
/* add the key numbers until a 0x0A for each line */
$key = array_merge($key, array(0x0A));
/* array of values */
$values = array();
/* read the file line by line */
while(!feof($file)) {
  /* read the file line by line */
    $line = fgets($file);
    /* add the values to the array */
    $values[] = $line;
}
/* close the file */
fclose($file);
/* decrypt the values */
$values = array_map('decrypt', $values, array_fill(0, count($values), $key));
/* split the values with * separator */
$values = explode("*", implode("", $values));
/* check if the email and password are correct */
if ($email == $values[0] && $password == $values[1]) {
    /* set the session variables */
    $_SESSION['email'] = $email;
    $_SESSION['password'] = $password;
/* check the email in the database */
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);
/* if the email is in the database */
if ($result->num_rows > 0) {
    /* check the titok row in the database */
    $row = $result->fetch_assoc();
    /* if the titok is not empty */
    if ($row["titok"] != "") {
        /* if piros then create a red box with caption Piros */
        if ($row["titok"] == "piros") {
            echo "<div class='alert alert-danger' role='alert'>
            <strong>Piros!</strong>
            </div>";
        } 
        /* if zold then create a green box with caption Zold */
        elseif ($row["titok"] == "zold") {
            echo "<div class='alert alert-success' role='alert'>
            <strong>Zold!</strong>
            </div>";
        }
        /* if sarga then create a yellow box with caption Sarga */
        elseif ($row["titok"] == "sarga") {
            echo "<div class='alert alert-warning' role='alert'>
            <strong>Sarga!</strong>
            </div>";
        }
        /* if fekete then create a black box with caption Fekete */
        elseif ($row["titok"] == "fekete") {
            echo "<div class='alert alert-dark' role='alert'>
            <strong>Fekete!</strong>
            </div>";
        }
        /* if feher then create a white box with caption Feher */
        elseif ($row["titok"] == "feher") {
            echo "<div class='alert alert-light' role='alert'>
            <strong>Feher!</strong>
            </div>";
        }
        /* if kek then create a blue box with caption Kek */
        elseif ($row["titok"] == "kek") {
            echo "<div class='alert alert-primary' role='alert'>
            <strong>Kek!</strong>
            </div>";
        }
    }
    /* if the titok is empty then create a box with caption Nemtitok */
    else {
        echo "<div class='alert alert-secondary' role='alert'>
        <strong>Nemtitok!</strong>
        </div>";
    }
} else {
    /* if the email is not in the database then create a box with caption Nemtitok */
    echo "<div class='alert alert-secondary' role='alert'>
    <strong>Nemtitok!</strong>
    </div>";
}
} else {
    /* if the email and password are not correct, redirect to the url www.police.hu */
    header("Location: http://www.police.hu");
}
?>


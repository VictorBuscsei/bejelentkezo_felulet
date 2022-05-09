<?php
/* login for index.html */
$email = $_POST['email'];
$password = $_POST['password'];
/* open the password.txt file in the local folder */
$file = fopen("/home/vol8_3/epizy.com/epiz_31683703/htdocs/password.txt", "r") or die("Unable to open file!");
/* array of keys  5, -14, 31, -9, 3 */
$key = array(5, -14, 31, -9, 3);
/* array of values */
$values = array();
/* read the file line by line */
while(!feof($file)) {
  /* read the file line by line */
    $line = fgets($file);
    for ($i = 0; $i<=strlen($line); $i+=2) {
        /* bin2hex the line and add to values array */
        array_push($values, bin2hex($line[$i]));
    }
    /* loop through the vales array */
    for ($i = 0; $i<count($values); $i++) {
        /* loop through the key array */
        for ($j = 0; $j<count($key); $j++) {
            /* if values equals 0A or 0a and count($values) not equal i*/
            if (($values[$i] == "0A" || $values[$i] == "0a")&& ($i != count($values)||!($i>count($values)))) {
                $j=0;
                $i++;
            } elseif ($i == count($values)||$i>count($values)) {
                /* break all the loops */
                break;
            } else {
                $values[$i]+=$key[$j];
            }
        }
    }
    /* values hex2bin*/
    for ($i = 0; $i<count($values); $i++) {
        $values[$i] = hex2bin($values[$i]);
    }
    /* values to string*/
    $values = implode("", $values);
    /* close the file */
    fclose($file);
}
/* split the values with * separator */
$values = explode("*", $values);
/* check if the email and password are correct */
if ($email == $values[0] && $password == $values[1]) {
    /* set the session variables */
    $_SESSION['email'] = $email;
    $_SESSION['password'] = $password;
/* db connection */
$servername = "sql311.epizy.com";
$username = "epiz_31683703";
$password = "Ob6yXlQJrC";
$dbname = "epiz_31683703_adatok";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
/* check the email in the database */
$sql = "SELECT * FROM tabla WHERE Username = '$email'";
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


<?php
require 'config.php';

$username = $_POST['username'];
$password = $_POST['password'];
$refer    = $_POST['refer'];

if ($username == '' || $password == '') {
    // No login information
    header('Location: login.php?refer=' . urlencode($_POST['refer']));
} else {
    // Authenticate user
    $con = mysqli_connect($db_host, $db_user, $db_pass);
    mysqli_select_db($con, $db_name);

    $query = "SELECT userid, MD5(UNIX_TIMESTAMP() + userid )
        guid FROM susers WHERE username = '$username' AND password = password('$password')";

    $result = mysqli_query($con, $query)
    or die('Error in query');

    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_row($result);
        // Update the user record
        $query = "UPDATE susers SET guid = '$row[1]' WHERE userid = $row[0]";

        mysqli_query($con, $query)
        or die('Error in query');

        // Set the cookie and redirect
        // setcookie( string name [, string value [, int expire [, string path
        // [, string domain [, bool secure]]]]])
        // Setting cookie expire date, 6 hours from now
        $cookieexpiry = (time() + 21600);
        setcookie("session_id", $row[1], $cookieexpiry);

        if (empty($refer) || !$refer) {
            $refer = 'index.php';
        }

        header('Location: ' . $refer);
    } else {
        // Not authenticated
        header('Location: login.php?refer=' . urlencode($refer));
    }
}

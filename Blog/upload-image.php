<?php

define("UPLOAD_DIR", "/var/www/html/Classwork_24/Blog/post_images/");
$name = '';

if (!empty($_FILES["pic"])) {
    $myFile = $_FILES["pic"];
    if ($myFile["error"] !== UPLOAD_ERR_OK) {
        echo "<h1>An error occurred.</h1>";
        exit;
    }

    // ensure a safe filename
    $name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);

    // don't overwrite an existing file
    $i = 0;
    $parts = pathinfo($name);
    while (file_exists(UPLOAD_DIR . $name)) {
        $i++;
        $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
    }

    // preserve file from temporary directory
    $success = move_uploaded_file($myFile["tmp_name"],
        UPLOAD_DIR . $name);
    // set proper permissions on the new file
    chmod(UPLOAD_DIR . $name, 777);
}
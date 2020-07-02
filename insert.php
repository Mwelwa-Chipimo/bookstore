<?php
session_start();
var_dump($_SESSION); 

$id = $_SESSION['id'];

// Connect to a database
require_once 'incl/connect.php';
    
    echo "Processing...";

    //This code gets check for a post variable.
    if(isset($_POST["task"])) {
        $task = mysqli_real_escape_string($mysqli, $_POST["task"]);
        echo "POST: Your name is ". $task;

        $sql = "INSERT INTO onlineshop_cart (id, user, item, quantity) VALUES (NULL, '$id', )"; 


        if ($result = $mysqli -> query($sql)) {
            echo "Added to cart";
            } else {
                echo "ERROR:".mysqli_error($mysqli);
            }

    }

?>
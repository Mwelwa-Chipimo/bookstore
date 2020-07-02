<?php
include "../incl/connect.php";

$email = $_GET["email"];
$confirmNumHash = $_GET["confirmNum"];

$sql = "SELECT confirmNum FROM users WHERE email = ?";

    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("s", $param_email);
        $param_email = $email;
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                $stmt->store_result();
                $stmt->bind_result($confirmNum);
                $stmt->fetch();
                    if($stmt->num_rows == 1){
                        password_verify($confirmNum, $confirmNumHash);
                        $sql = "UPDATE users SET confirm='1' where email=? AND confirmNum=?";
                                if($stmt = $conn->prepare($sql)){
                                    $stmt->bind_param("ss", $param_email, $param_confirmNum);
                                    // Set parameters
                                    $param_email = $email;
                                    $param_confirmNum = $confirmNum;
                                    $stmt->execute();
                                    $stmt->store_result();
                                    echo "<html>Thanks for confirming your details, you can now <a href=\"signin.php\"> login</a></html>";        
                                }              
                        }
                }else{
                echo "Oops! Something went wrong. Please try again later.";
                }
        }

?>
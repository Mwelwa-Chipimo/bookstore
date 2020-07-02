<?php
//Set PHPMailer namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include config file
include "../incl/connect.php";
require_once  "vendor/autoload.php";
session_start();
 
// Define variables and initialize with empty values
$email = $_SESSION["email"];
$firstname = $_SESSION["firstname"];
$confirmNum = $_SESSION["confirmNum"];
$username_info = "";

// STEP 1: Create sql variable that stores the 'SELECT' MySQL command.
$sql = "SELECT confirmNum FROM users WHERE email = ?";

// STEP 2: Call 'prepare' function from mysqli class.
// STEP 2.1: Assign this to the $stmt variable.
// The result of  the '$mysqli->prepare($sql)' method is assigned to the $stmt variable.
if($stmt = $conn->prepare($sql)){

            //STEP 3
            // Bind variables to the prepared statement as parameters
            // This assigns the value of $param_email to the '?' in the prepared statement.
            $stmt->bind_param("s", $param_email);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            // This executes the statement "SELECT confirmNum FROM users WHERE email = users email from POST superglobal".
            if($stmt->execute()){
                
                // The result of the execute statement is stored.
                //The result should be a random number from the confirmNum column.
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    // The result should be assigned to the new variable $boundConfirmNum.
                    $stmt->bind_result($boundConfirmNum);
                    // Prepare an insert statement
                    
                    // Fetches the record of the query.
                    if($stmt->fetch()){
                        //Checks that the result 
                        if($boundConfirmNum == $confirmNum){
                        $sql = "UPDATE users SET confirm='0' WHERE email=?";
                        if($stmt = $conn->prepare($sql)){
                            // Bind variables to the prepared statement as parameters
                            $stmt->bind_param("s", $param_email);

                            // Set parameters
                            $param_email = $email;

                            // Attempt to execute the prepared statement
                            if($stmt->execute()){
                                // Redirect to login page
                                $username_info = "Thanks for registering, ". $firstname . ". Please check your email for further instructions.";
                            } else{
                                echo "Something went wrong. Please try again later.";
                            }
                        }
                        }
                    }
                    $mail = new PHPMailer(true);
                    sendEmail($mail, $firstname, $email, $confirmNum);
                }else{
                    $email_err = "The email address provided could not be found on our database.";
                }
            }else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
$stmt->close();
$conn->close();
 


    //PHPMailer send email to user
    function sendEmail($param, $param3, $param4, $param5){
            try {
                //Server settings
                $param->SMTPDebug = 0;                    // Enable verbose debug output
                $param->isSMTP();                         // Set mailer to use SMTP
                $param->Host       = 'smtp.mailtrap.io';  // Specify main and backup SMTP servers
                $param->SMTPAuth   = true;                // Enable SMTP authentication
                $param->Username   = 'bfb9212b37d350';    // SMTP username
                $param->Password   = 'd638d03ac22af5';    // SMTP password
                $param->SMTPSecure = 'TLS';               // Enable TLS encryption, `ssl` also accepted
                $param->Port       = 2525;                // TCP port to connect to

                //Recipients
                $param->setFrom('mwelwachipimo96@gmail.com', 'Mwelwa');
                $param->addAddress($param4, 'a good guy');     // Add a recipient
                $param->addReplyTo('mwelwachipimo96@gmail.com', 'Information');

                // Content
                $param->isHTML(true);                                  // Set email format to HTML
                $param->Subject = 'CheckIt Account Confirmation';
                $param->Body    = 
        "<h1>Hey ".$param3. "</h1> <br> 
        Welcome to the Books Store! <br>
        Are you ready to kick some tasks? <br>
        Then please confirm your email address by visiting the link below <br>
        <a href=\"http://localhost:8888/book_store/confirmEmail.php?email=".$param4."&confirmNum=".password_hash($param5, PASSWORD_DEFAULT)."\">here</a> ";
                $param->AltBody = 'This is the body in plain text for non-HTML mail clients';
                $param->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$param->ErrorInfo}";
            }
    }//endsendEmail

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thanks for signing up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Thanks for Signing Up</h2>
        <p><?php echo $username_info ?></p>
        <p>Already have an account? <a href="signin.php">Login here</a>.</p>
    </div>    
</body>
</html>
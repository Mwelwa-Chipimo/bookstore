<?php
// Include config file
include "incl/connect.php";

session_start();
 
// Define variables and initialize with empty values
$firstname = $surname = $email = $password = $confirm_password = "";
$firstname_err = $surname_err = $email_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // VALIDATE   FIRSTNAME //////////////////////
    if(empty(trim($_POST['firstname']))) {
        $firstname_err = "Please enter your firstname.";
    } else {
        $firstname = trim($_POST["firstname"]);
        $_SESSION["firstname"] = $firstname;
    }


    // VALIDATE   SURNAME ////////////////////////
    if(empty(trim($_POST['surname']))) {
        $surname_err = "Please enter your surname.";
    } else {
        $surname = trim($_POST["surname"]);
        $_SESSION["surname"] = $surname;
    }
    
    // Validate email ////////////////////////////////////////
    
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a vaild email.";
    }else{

        // BACK-END VALIDATION //////////

        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                echo "line 47 the execute worked";
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $email_err = "This email is already in use.";
                }else{
                    $email = trim($_POST["email"]);
                    $_SESSION["email"] = $email;
                }
            }else{
                echo "Oops! Something went wrong with the email validation. Please try again later.";
            }
        }
         
        // Close statement and deallocate it from memory
        $stmt->close();
    }
    
    // Validate password /////////////////////////////////////////
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    }elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    }else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm your password.";     
    }else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Your passwords did not match.";
        } 
    }

    echo $firstname;
    echo $surname;
    echo $email;
    echo $password;
    echo $confirm_password;
    
    // Check input errors before inserting in database
    if(empty($firstname_err) && empty($surname_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        echo "no errors reported";
        // Prepare an insert statement
        $sql = "INSERT INTO users (firstname, surname, email, pwd, confirmNum) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssi", $param_firstname, $param_surname, $param_email, $param_password, $param_confirmNum);
            
            // Set parameters
            $param_firstname = $firstname;
            $param_surname = $surname;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_confirmNum = rand(100000, 999999);
            
            //Check to see that varaibels above have been set
            echo $param_firstname;
            echo $param_surname;
            echo $param_email;
            echo $param_confirmNum;
            echo $param_password;
            echo "parameters set";

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                $_SESSION["confirmNum"] = $param_confirmNum;
                header("location: confirm.php");
                echo "Session set";
            } else{
                echo "Something went wrong. Theres an error with the execute method.";
            }
        }
         
        // Close statement
        $stmt->close();
    } else {
        echo "Theres a problem with the if statement for the query";
    }
    
    // Close connection
    $conn->close();
}
?>


<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    </head>

    <body>

    <!--------------------------   N A V B A R   ------------------------------->

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light nav-custom static-top">
        <div class="container">
        <a class="navbar-brand" href="#">Book Store</a>
       
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="signin.php"><strong>LOGIN</strong>
                <span class="sr-only">(current)</span>
                </a>
            </li>
        </ul>
        </div>
    </nav>

    <!-----------------    E N D   O F   N A V B A R   -------------------->

    <main>

    <div class="container-fluid my-auto"> <!-- flexbox container -->
    <div class="row">

        <div class="col-sm mx-auto my-auto mb-5">
            <h1><strong>Welcome to the Book Store</strong></h1>
            <h3>Buy your books here.</h3>
        </div>


        <div class="col-sm mb-5 mx-auto my-auto">
        <div class="card">
         <!---------------------- SIGN UP FORM ----------------------->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="col-lg-10 mx-auto">

            <!-------------- First Name & Surname ----------------->
            <div class="form-group">
            <h2>Sign Up</h2>
            <p>Please fill this form to create an account.</p>
                <div class="row">
                    <div class="col-md <?php echo (!empty($firstname_err)) ? 'has-error' : ''; ?>">
                        <label for="firstname">First Name</label>
                        <input type="text" name="firstname" class="form-control" value="<?php echo $firsname; ?>" placeholder="First Name" id="firstname">
                        <span class="help-block"><?php echo $firstname_err ?></span>
                    </div>
                    <div class="col-md <?php echo (!empty($surname_err)) ? 'has-error' : ''; ?>">
                        <label for="surname">Surname</label>
                        <input type="text" name="surname" class="form-control" value="<?php echo $surname; ?>" placeholder="Surname" id="surname">
                        <span class="help-block"><?php echo $surname_err ?></span>
                    </div>
                </div>
            </div>

            <!------------------------ Email -------------------------->
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label for="email">Email address</label>
                <input type="email" name="email" class="form-control" placeholder="name@example.com" aria-describedby="emailHelp" value="<?php echo $email; ?>" id="email">
                <small id="emailHelp" class="form-text text-muted">Use your email to login.</small>
                <span class="help-block"><?php echo $email_err ?></span>
            </div>

            <!---------------------- Password ------------------------->
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" id="password">
                <span class="help-block"><?php echo $password_err ?></span>
            </div>

            <!------------------ Confirm Password -------------------->
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" id="confirm_password">
                <span class="help-block"><?php echo $confirm_password_err ?></span>
            </div>

            <!---------------------- Submit -------------------------->
            <button type="submit" name="submit" class="btn btn-block btn-primary" value="submit"><strong>Sign up for CheckIt</strong></button>
            <div class="list-inline">
                <p>Already have an account?<a href="signin.php"> Sign In</a></p>
            </div>
        </div>
        </form>
        </div>
        </div>
    </div>
    </div>
    </main>  
        
        <script src="" async defer></script>
    </body>
</html>
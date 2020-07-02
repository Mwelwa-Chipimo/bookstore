<?php

session_start();
include "incl/connect.php";

$username = $_SESSION['email'];
$loggedInUser = $_SESSION['firstname'];
$id = $_SESSION['id'];


    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: userprofile/register.php");
        exit;
    }




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">
    <title>Online Store Project</title>
    
    <!--Template based on URL below-->
    <link rel="canonical" href="https://getbootstrap.com/docs/4.3/examples/starter-template/">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Place your stylesheet here-->
    <link href="css/styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="shoppingApp">
<nav class="navbar navbar-expand-md navbar-light bg-light fixed-top">
    <a class="navbar-brand" href="#">Book Store</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="logout.php">Logout<span class="sr-only">(current)</span></a>
            </li>
            
            <!--<li class="nav-item">
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
            </li>-->
            <li class="nav-item dropdown">
                <img id="shoppingCartMenu" src="images/cart.png" alt="shopping cart">
                <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Shopping Cart</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item" href="#" onclick="overlayOn()" @click="duplicateObj(itemsObj)">View</a>
                    <a class="dropdown-item" href="#">
                        <input type="hidden" name="cmd" value="_ext-enter">
                        <form action="https://www.paypal.com/us/cgi-bin/webscr" method="post">
                           <input type="hidden" name="cmd" value="_xclick">
                           <input type="hidden" name="business" value="nate@natestore.com">
                           <input type="hidden" name="item_name" value="Stuff from Nate's Store">
                           <input type="hidden" name="currency_code" value="USD">
                           <input type="hidden" name="amount" v-bind:value=ZARtoUSD>
                            <button type="submit" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">Checkout</button>
                            <!--More info on Paypal API https://www.paypal.com/lv/smarthelp/article/how-do-i-add-paypal-checkout-to-my-custom-shopping-cart-ts1200-->
                        </form>
                    </a>
                </div>
                <span class="cartItems">{{"Item Count : " +totalItems}}</span>
                <span class="cartItems">{{"--- R"+totalPrice}}</span> 
            </li>
        </ul>
        <!--<form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
            <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
        </form>-->
    </div>
</nav>

    <main role="main" class="container">
        <div id="overlay">
            <div id="closeButton">
              <button onclick="overlayOff()" @click="resetChanges(quantities, itemsObj)">
                <div id="orangeBox">
                    <span id="x">X</span>
                </div>
              </button>
            </div><!--close button-->
            <div id="editCart">
                <ul>
                    <li v-for="(item, key, index) in itemsObj" v-if="item.quantity > 0">
                        {{ key }} - <input type="number" v-model=item.quantity min="1" max="5"> 
                        <div id="deleteItemID">
                            <button name="deleteItem" @click="deleteItem()" v-bind:value= key>X</button>
                            <button name="apply" id= key @click="upDateEditCart(itemsObj)">&#10004;</button>
                        </div>
                    </li>   
                </ul>
                <input type="hidden" name="cmd" value="_ext-enter">
                <!--
                <form action="https://www.paypal.com/us/cgi-bin/webscr" method="post">
                   <input type="hidden" name="cmd" value="_xclick">
                   <input type="hidden" name="business" value="nate@natestore.com">
                   <input type="hidden" name="item_name" value="Stuff from Nate's Store">
                   <input type="hidden" name="currency_code" value="USD">
                   <input type="hidden" name="amount" v-bind:value=ZARtoUSD>
                   <input type="image" src="images/checkout.png" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
                    !--More info on Paypal API https://www.paypal.com/lv/smarthelp/article/how-do-i-add-paypal-checkout-to-my-custom-shopping-cart-ts1200-- >
                </form>
                -->
                <div class="row">
                <button class="btn-lg btn-success">Checkout</button>
                </div>
                <div class="row">
                <button class="btn-primary" onclick="overlayOff()" @click="continueShopping">Continue Shopping</button>  
                </div>
            </div>
        </div>

        <div class="text-center mt-5 pt-5">

                <h1>The Book Store</h1>
                <p class="lead">Welcome to the Book Store<br>Find all your books here.</p>

            <button onclick="updateCartAjax()">update</button>

            <div class="row row-cols-3 row-cols-md-3">
            
<?php
$sql = "SELECT * FROM onlineshop_product";
$result = $conn->query($sql);
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()) {


          echo "\n\t\t\t<div class='col-md-3 mb-3'> ";
          echo       "<div class='card' id=\"". $row["product"] ."-container\"> ";
          echo        "<img class='card-img-top' src='images/". $row['image'] .".jpg' alt='Card image cap'>";
          echo            "<div class='card-body'>";
          echo                "<h4 class='card-title'><a href='#'>" . $row['product'] ."</a></h4>";
          echo                 "<br><h5>" . $row["short_desc"] . "</h5>";
          echo                "<br><h5>". $row['price'] ."</h5>";
          echo            "\n\t\t\t\t<div class=\"purchase\">
                          <label for=\"" . $row["product"] . "quantity\">Quantity (Max 5):</label>
                          <input type=\"number\" id=\"" . $row["product"] . "quantity\" name=\"". $row["product"] ."quantity\" value=\"1\" min=\"1\" max=\"5\">";
          echo            "</div>";
          echo            "<div class='card-footer'>";
          echo            "<button type=\"submit\" class=\"btn-lg btn-primary\" name=\"addToCart\" onclick=\"addToCart()\" @click=\"addToCart\" id=\"". $row["product"] ."\">Add to Cart</button>\n
                          <input type=\"hidden\" readonly value=\"" . $row["price"] . "\" id=\"". $row["product"] . "price" . "\">Price : R" . $row["price"] . "
                          </div>";
          echo            "</div>"; // CLose card footer
          echo            "</div>"; // Close card-body
          echo       "</div>"; // Close card class
           
        }
    }
?>    
        </div>
        </div>
        
        <br>
    </main><!-- /.container -->
</div><!--close shoppingApp-->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster MUST be outside of Vue managed elements-->
    <!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/vue"></script>
    <script src="scripts/onlineStore.js"></script>
    <script src="scripts/vueScripts.js"></script>
    <?php
               
                echo $loggedInUser;
                $cartArr = array();
                $sql = "SELECT item, quantity FROM onlineshop_cart WHERE user = '$username'";
                $result = $conn->query($sql);
                if($result->num_rows > 0){    
                    while($row = $result->fetch_assoc()) {
                        array_push($cartArr,$row["item"],$row["quantity"]);
                    }
                } else { 
                    echo "Your cart is empty";
                }

                var_dump($cartArr);
                //call to JS function with a Vue hook
                echo '<script> let paramArr = [];';
                //echo count($cartArr);
                for($x = 0; $x < count($cartArr); $x++){
                    echo 'paramArr.push("' . $cartArr[$x] . '");';
                }
                
                echo 'console.log(paramArr);
                cleanUpVue(paramArr);
                
                </script>';
            
    ?>
</body>
</html>
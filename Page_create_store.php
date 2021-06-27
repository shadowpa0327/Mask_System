<?php
    session_start();
    if(!isset($_SESSION['Authenticated'])||!$_SESSION['Authenticated']){
        header("Location: index.php");
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="annoymous">
        <link rel="stylesheet" href="index.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <title>DB HW2</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light navbar-laravel" style = "border-bottom : 2px solid rgba(235, 231, 231, 0.842); background-color:rgb(236, 236, 236);">
            <div class="container" id = "top-navigation" style = "margin-bottom:0px;" >
                <a class="navbar-brand" href="#"><h3>NMSL口罩訂購網</h3></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="home_page.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Check_exist_shop.php">Shop</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Logout.php">LogOut</a>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>
        
    <main class="login-form">
        <div class="cotainer" style = " margin-top:40px">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Register</div>
                            <div class="card-body">
                                <form action="Check_create.php" method = "post">
                                    <div class="form-group row">
                                        <label for="store_name" class="col-md-4 col-form-label text-md-right">Store name</label>
                                        <div class="col-md-6">
                                        <input type = "text" name="store_name" class="form-control" maxlength="19"></input>
                                            <?php
                                                if(isset($_SESSION["error_store_name"])){
                                                    $error = $_SESSION["error_store_name"];
                                                    unset($_SESSION["error_store_name"]);
                                                    echo "<span style=\"color:red\">$error</span>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="city" class="col-md-4 col-form-label text-md-right">City</label>
                                        <div class="col-md-6">
                                            <select name="city" class="form-control">
                                                    <optgroup label="North area">
                                                        <option value="Taipei">Taipei</option>
                                                        <option value="Hsinchu">Hsinchu</option>
                                                    </optgroup>
                                                    <optgroup label="Central area">
                                                        <option value="Taichung">Taichung</option>
                                                        <option value="Chiayi">Chiayi</option>
                                                    </optgroup>
                                                    <optgroup label="South area">
                                                        <option value="Tainan">Tainan</option>
                                                        <option value="Kaohsiung">Kaohsiung</option>
                                                    </optgroup>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="mask_price" class="col-md-4 col-form-label text-md-right">Mask_price</label>
                                        <div class="col-md-6">
    
                                        <input type = "number" name="mask_price" class="form-control"></input>
                                            <?php
                                                if(isset($_SESSION["error_mask_price"])){
                                                    $error = $_SESSION["error_mask_price"];
                                                    echo "<span style=\"color:red\">$error</span>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="mask_amount" class="col-md-4 col-form-label text-md-right">Mask_amount</label>
                                        <div class="col-md-6">
                                    
                                        <input type= "number" name = "mask_amount" class="form-control"></input>
                                            <?php
                                                if(isset($_SESSION["error_mask_amount"])){
                                                    $error = $_SESSION["error_mask_amount"];
                                                    echo "<span style=\"color:red\">$error</span>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                                <button type="submit" class="btn btn-primary">
                                                    Register
                                                </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    </body>
</html>
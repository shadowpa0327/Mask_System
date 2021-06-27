<?php
    session_start();
    $_SESSION['Authenticated'] = false;
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="annoymous">
        <link rel="stylesheet" href="index.css">
       <!-- <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css"/> -->
        <title>DB HW2</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
        <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.1/jquery.twbsPagination.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="#">NMSL口罩訂購網</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Page_register.php">Register</a>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>

        <script>

            $.fn.serializeObject = function() {
                    var o = {};
                    var a = this.serializeArray();
                    $.each(a, function() {
                        if (o[this.name]) {
                            if (!o[this.name].push) {
                                o[this.name] = [o[this.name]];
                            }
                            o[this.name].push(this.value || '');
                        } else {
                            o[this.name] = this.value || '';
                        }
                    });
                    console.log(o);
                    return o;
                };
            $(document).ready(function() {
                    $('form.create').on('submit', function(e) {
                        e.preventDefault();
                        console.log("trigger");
                        var formData = $(this).serializeObject();
                        $.ajax({
                            type: "POST",
                            url: "Check_create_account.php",
                            data: formData, // serializes the form's elements.
                            success: function(data)
                            {       
                                console.log(data);
                                var jsArray = JSON.parse(data);
                                $('#name_mess').html(jsArray['error_username']);
                                $('#password_mess').html(jsArray['error_password']);
                                $('#password_2nd_mess').html(jsArray['error_second_password']);
                                $('#phone_number_mess').html(jsArray['error_phone_number']);
                                if(!jsArray['error_detect']){
                                    console.log("NO");
                                    alert("Create success ");
                                    window.location.replace("index.php");
                                }
                                else{
                                    document.getElementById('show_create_message').style.display = "block";
                                    //document.getElementById('show_create_message').style.color = "red";
                                    $('#show_create_message').html("There are some error please check!");
                                }
                            }
                        });
                    });
                });
            function check_length(obj,len,error_loc,name){
                if(obj.length>len){
                    document.getElementsByName(name)[0].value = obj.slice(0,len);
                    document.getElementById(error_loc).innerHTML = "The maximum lenght should less than " + len;
                }
            }
        </script>

        <main class="login-form">
                <div class="cotainer">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">Register</div>
                                <div class="card-body">
                                    <p class="text-danger text-center" id = "show_create_message"></p>
                                    <form action="login.php" method="post" class= "create">
                                        <div class="form-group row">
                                            <label for="email_address" class="col-md-4 col-form-label text-md-right">Account</label>
                                            <div class="col-md-6">
                                                <input type="text" id="email_address" class="form-control" name="user_name" oninput="check_length(value,15,'name_mess','user_name')" required autofocus>
                                                <span id = "name_mess" style="color:red"></span>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="password" class="col-md-4 col-form-label text-md-right"  >Password</label>
                                            <div class="col-md-6">
                                                <input type="password" id="password" class="form-control" name="pwd" oninput="check_length(value,35,'password_mess','pwd')" required>
                                                <span id = "password_mess" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="password_2nd" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                                            <div class="col-md-6">
                                                <input type="password" id="password_2nd" class="form-control" name="pwd_2nd" oninput="check_length(value,35,'password_2nd_mess','pwd_2nd')"  required>
                                                <span id = "password_2nd_mess" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="phone_number" class="col-md-4 col-form-label text-md-right">Phone number</label>
                                            <div class="col-md-6">
                                                <input type="text" pattern="^09[0-9]{8}$" id="phone_number" class="form-control" name="phone_number"  title="Phone number should with 10 digit and start with 09..."  >
                                                <span id = "phone_number_mess" style="color:red"></span>
                                            </div>
                                        </div>


                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                Register
                                            </button>
                                            <a href="index.php" class="btn btn-link">
                                                Already has an account?
                                            </a>
                                        </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

        </main>
       
    </body>
</html>
<?php
session_start();
if(!isset($_SESSION['Authenticated'])||!$_SESSION['Authenticated']){
    header("Location: index.php");
    exit();
}
if(!isset($_SESSION['exist_store'])||!$_SESSION['exist_store']){
    header("Location: Page_create_store.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="annoymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="homepage.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <title>DB HW2</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.1/jquery.twbsPagination.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
        <div class="container" style = " margin-top:40px">
            <div class="card bg-light mb-6" style="max-width:48rem;">
                <div class="card-header"><h4>Store Information</h4></div>
                <table class="table table-striped custab" style="margin-bottom:0;">
                    <?php
                        $dbservername='localhost';
                        $dbname='Mask_system';
                        $dbusername='examdb';
                        $dbpassword='examdb';

                        $uname = $_SESSION['Username'];
                        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $prereq = $conn->prepare("select * from Store where shop_holder = :username");
                        $prereq->execute(array("username"=>$uname));
                        foreach($prereq as $result){
                            echo ("<tr><td>Shop</td><td colspan=2>".$result['store_name']."</td></tr>");
                            echo ("<tr><td>City</td><td colspan=2>".$result['city']."</td></tr>");
                            echo ("<tr>
                                    <td>Mask Price</td>
                                    <td>
                                        <form action = \"Edit_store_info_price.php\" method = \"post\">
                                            <input type = \"number\" name = \"new_mask_price\" value = " .$result['price']." required max=\"2147483647\" min=\"0\"></input>
                                            <input type = \"submit\" value = \"Edit\" class='btn btn-info btn-xxs' required max=\"2147483647\" min=\"0\"></input>
                                        </form>
                                    </td>
                                </tr>");
                            echo ("<tr>
                                    <td>Mask Amount</td>
                                    <td>
                                        <form action = \"Edit_store_info_number.php\" method = \"post\">
                                            <input type = \"number\" name = \"new_mask_number\" value = " .$result['number']." required max=\"2147483647\" min=\"0\"></input>
                                            <input type = \"submit\" value = \"Edit\" class='btn btn-info btn-xxs'></input>
                                        </form>
                                    </td>
                                </tr>");
                        }
                    ?>
                </table>
            </div>
        </div>
        <div class="container">
            <div class="card bg-light mb-6" style="max-width:48rem;">
                <div class="card-header">
                    <h4 style = "float:left;">Employee</h4>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" style="float:right;">Add
                </button></div>
                   
            <div class="table-wrapper">
            <div class="table-title">
                <!--<div class="row">
                    <div class="col-sm-8"><h2>Employee <b>Details</b></h2></div>
                    <div class="col-sm-4">
                        <button type="button" class="btn btn-info add-new"><i class="fa fa-plus"></i> Add New</button>
                    </div>
                </div>-->
            </div>
                <table class="table table-striped custab" style="margin-bottom:0;">
                    <?php
                            $dbservername='localhost';
                            $dbname='Mask_system';
                            $dbusername='examdb';
                            $dbpassword='examdb';

                            $store_name = $_SESSION['store_name'];
                            //echo($store_name);
                            $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $prereq = $conn->prepare("select username , phone_number from Clerk natural join Users where store_name = :store_name");
                            $prereq->execute(array("store_name"=>$store_name));
                            if($prereq->rowCount()==0){
                                echo("<div class=\"card-body\">You didn't have any clerk yet try to hire somebody!!</div>");
                            }
                            else{
                                echo("
                                    <thead class=\"thead-dark\">
                                        <tr>
                                            <td>Account</td>
                                            <td colspan = 2>Phone</td>
                                        </tr>
                                    </thead>"
                                    );
                                foreach($prereq as $result){
                                    echo ("<tr>
                                            <td>".$result['username']."</td>
                                            <td>".$result['phone_number']."</td>
                                            <td>
                                                    <form action = \"Delete_clerk.php\" method = \"post\" >
                                                        <input type = \"hidden\" value =".$result['username']." name=\"clerk_name\">
                                                        <input type = \"hidden\" value =".$store_name." name=\"store_name\">
                                                        <button type = \"submit\" value = \"Delete\" class=\"btn btn-danger btn-sm\" name=\"remove_levels\">Delete</button>
                                                    </form>
                                            </td>   
                                        </tr>");
                                    }
                                }
                            ?>
                </table>
            </div>
                   

            </div>
        </div>
    <script>
        $(document).ready(function(){
            $('button[name="remove_levels"]').on('click', function(e) {
                var $form = $(this).closest('form');
                e.preventDefault();
                $('#confirm').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                .on('click', '#delete', function(e) {
                    $('#myModal .modal-body').html(`<p>${"Delete Successfully"}</p>`);
                    $('#myModal').modal('show');
                    $form.trigger('submit');
                    console.log("hello");
                    //console.log("yo");
                    });    
                $("#cancel").on('click',function(e){
                    e.preventDefault();
                    $('#confirm').modal.model('hide');
                });
            });
        });
    </script>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Adding employ</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action = "Check_adding_clerk.php" method = "post" id = "adding_employ">
                <div class="form-group">
                    <label class="col-form-label">Please type in the username of the employee</label>
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                    </div>
                    <input type = "text" name = "clerk_account" placeholder = "Type account to add some employee" style="width:30%" class="form-control"></input>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary" form="adding_employ" value="Submit">Add</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            
        </div>
        </div>
    </div>
    </div>
    <div id="confirm" class="modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">System notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure?
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
                    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">System notification</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
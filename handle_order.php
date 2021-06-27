<?php
    session_start();
    if(!isset($_SESSION['Authenticated'])||!$_SESSION['Authenticated']){
        header("Location: index.php");
    }

    $return_val = array(
        "row" => $_POST['row_number'],
        "message" => "",
        "outofdate"=>"false",
        "value" => $_POST['order_mask_amount'],
        "time" => ""
    );

    $error = false;
    
    if(empty($_POST['order_mask_amount'])||$_POST['order_mask_amount']<0){
        //echo("*please type in correct mask_amount");
        $return_val['message'] = "*please type in correct mask_amount";
        $error = true;
    }
    $return_val['message'] = "*Order success";
    $dbservername='localhost';
    $dbname='Mask_system';
    $dbusername='examdb';
    $dbpassword='examdb';
    $uname = $_SESSION['Username'];
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pre_query = $conn->prepare("SELECT number from Store WHERE number != :number and store_name =:store_name ");
    $pre_query->execute(array(
        "number" => ($_POST['store_mask_amount'] ),
        "store_name" => $_POST['store_name']
    ));

    if($pre_query->rowCount()>0){
        //echo("not enough");
        $result = $pre_query->fetchall();
        $latest_amount = $result[0]['number'];
        if($latest_amount<$_POST['order_mask_amount']){
            $return_val['outofdate']="True";
            $return_val['message'] = "*The mask of ". $_POST['store_name']." is not enough \n(p.s please reload the data to get the latest information)";
            $error = true;
        }
        $_POST['store_mask_amount'] = $latest_amount;
    }
    else if($_POST['store_mask_amount']<$_POST['order_mask_amount']){
        //echo("not enough");
        $return_val['message'] = "not enough";
        $error = true;
    }

    $pre_query = $conn->prepare("SELECT price from Store WHERE price != :price and store_name =:store_name ");
    $pre_query->execute(array(
        "price" => ($_POST['mask_price'] ),
        "store_name" => $_POST['store_name']
    ));
    if($pre_query->rowCount()>0){
        $return_val['outofdate']="True";
        $result = $pre_query->fetchall();
        $latest_price = $result[0]['price'];
        $return_val['message'] = "*The mask price has been change to ". $latest_price ."\n(p.s please reload the data to get the latest information)";
        $error = true;
    }

    //echo( ($_POST['store_mask_amount']-$_POST['order_mask_amount'] ));
    if(!$error){
        
        $pre_query = $conn->prepare("UPDATE Store SET number = :number WHERE store_name = :store_name");
        $pre_query->execute(array(
            "number" => ($_POST['store_mask_amount']-$_POST['order_mask_amount'] ),
            "store_name" => $_POST['store_name']
        ));

        $pre_query = $conn->prepare("INSERT INTO mask_order(status,StartTime,Create_user,StoreName,Order_Number,Order_Price) VALUES(:status,:time,:user,:store_name,:amount,:price)");
        $dateTime = new DateTime;
        $return_val['time'] = $dateTime->format('Y-m-d H:i:s');
        $pre_query->execute(array(
            "status"=>"Not Finished",
            "time"=>$return_val['time'],
            "user"=>$uname,
            "store_name" =>$_POST['store_name'],
            "amount"=> $_POST['order_mask_amount'],
            "price" =>$_POST['mask_price']
        ));
        //echo("Order success");
    }

    echo(json_encode($return_val));
   /* if($error){
        echo <<<EOT
        <!DOCTYPE html>
        <html>
            <body>
            <script>
                alert("Error order");
                window.location.replace("home_page.php");
            </script>
            </body>
        </html>
        EOT;
        exit();
    }*/
?>
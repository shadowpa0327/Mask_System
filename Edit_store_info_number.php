<?php
    session_start();
    if(!isset($_SESSION['Authenticated'])||!$_SESSION['Authenticated']){
        header("Location: index.php");
    }
    $error_msg;
    $error_detect = false;
    if(!isset($_POST['new_mask_number'])){
        header("Location: Store_page.php");
    }
    if(!isset($_SESSION['exist_store'])||!$_SESSION['exist_store']){
        header("Location: Page_create_store.php");
        exit();
    }

    if(empty($_POST['new_mask_number'])&&$_POST['new_mask_number']!=0){
        $error_msg = "*The number cannot be empty";
        $error_detect = true;
    } 
    else if($_POST['new_mask_number']<0){
        $error_msg = "*The number cannot be negative";
        $error_detect = true;
    }

    if($error_detect){
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
            alert("$error_msg");
            window.location.replace("Store_page.php");
            </script>
        </body>
        </html>
        EOT;
        exit();
    }
    else{
        $uname=$_SESSION['Username'];
        $dbservername='localhost';
        $dbname='Mask_system';
        $dbusername='examdb';
        $dbpassword='examdb';
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $new_val = $_POST['new_mask_number'];
        $pre_req = $conn->prepare(" UPDATE Store SET number = :number WHERE shop_holder = :shop_holder");
        $pre_req->execute(array('number'=>$new_val,'shop_holder' => $uname));
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
            <script>
            alert("Edit successfully");
            window.location.replace("Store_page.php");
            </script>
        </body>
        </html>
        EOT;
        //header("Location: Store_page.php");
    }


?>
<?php
    session_start();
    //foreach($_POST as $P)echo($P);
    $dbservername='localhost';
    $dbname='Mask_system';
    $dbusername='examdb';
    $dbpassword='examdb';
    //echo "error";
    $error_show_flag=false;
    foreach($_POST as $KEY => $val){
       // echo($KEY.$val)."\n";
        //echo("hello".isset($val));
        if(!empty($val)|| $val ==='0'){
            unset($_SESSION['error_'.$KEY]);
        }
        else {
            $_SESSION['error_'.$KEY] = "*Required";
            //echo($_SESSION['error_mask_price']);
                   $error_show_flag = true;
            
        }
    }

    if(!empty($_POST['mask_price'])&&$_POST['mask_price']<0){
       // echo "price error";
        $_SESSION['error_mask_price'] = "*Input a non-negative number";
        $error_show_flag = true;
    }
    if(!empty($_POST['mask_amount'])&&$_POST['mask_amount']<0){
        //echo "amount error";
        $_SESSION['error_mask_amount'] = "*Input a non-negative number";
        $error_show_flag = true;
    }
    if($error_show_flag){
        header("Location: Page_create_store.php");
        exit();
    }

        
        //header("Location: Page_create_store.php");
    
    $shop_name = $_POST['store_name'];
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pre_query = $conn->prepare("select store_name from Store where store_name=:store_name");
    $pre_query->execute(array('store_name'=>$shop_name));
    if($pre_query->rowCount()==0){
        $city = $_POST['city'];
        $uname = $_SESSION['Username'];
        $mask_price = $_POST['mask_price'];
        $mask_amount = $_POST['mask_amount'];
        $pre_query = $conn->prepare("insert into Store(store_name,city,price,number,shop_holder) values(:store_name,:city,:price,:number,:shop_holder)");
        $pre_query->execute(array('store_name'=>$shop_name,'city'=>$city,'price'=>$mask_price,'number'=>$mask_amount,'shop_holder'=>$uname));
        $_SESSION['store_name'] = $shop_name;
        $_SESSION['exist_store'] = true;
        echo <<<EOT
            <!DOCTYPE html>
            <html>
                <body>
                <script>
                    alert("Create success ");
                    window.location.replace("Store_page.php");
                </script>
                </body>
            </html>
        EOT;
        exit();
    }
    else{
        $_SESSION["error_store_name"] = "*The store name has already existed,please use another one";
        header("Location: Page_create_store.php");
    }
    

?>
<?php
    session_start();
    $dbservername='localhost';
    $dbname='Mask_system';
    $dbusername='examdb';
    $dbpassword='examdb';
    $error_show_flag = false;
    //echo "error";

    foreach($_POST as $KEY => $val){
        if(empty($val)){
            echo($KEY.$val);
            $_SESSION['error_'.$KEY] = "*Required";
            $error_show_flag = true;
        }
        else {
            unset($_SESSION['error_'.$KEY]);
        }
    }

    if(!empty($_POST['pwd_2nd']) && $_POST['pwd'] != $_POST['pwd_2nd']){
        $_SESSION['error_pwd_2nd'] = "*Password mismatch";
        $error_show_flag = true;
    }
    else if(!isset($_SESSION['error_pwd_2nd'])){
        unset($_SESSION['error_pwd_2nd']);
    }

    if(!empty($_POST['pwd'])&&!ctype_alnum($_POST['pwd'])){
      //  echo "yo";
        $_SESSION['error_pwd'] = "*Invalid format(only upper/lower-case character and number are allowed)";
        $error_show_flag = true;
        if(!isset($_SESSION['error_pwd_2nd'])){
            if(!empty($_POST['pwd_2nd'])){
                $_SESSION['error_pwd_2nd'] = "*Invalid password format";  
            } 
            else{
                unset($_SESSION['error_pwd_2nd']);
            }
        }
    }
    
    if(!empty($_POST['phone_number'])&&!ctype_alnum($_POST['phone_number'])){
        $_SESSION['error_phone_number'] = "*Invalid format(only upper/lower-case character and number are allowed)";
        $error_show_flag = true;
    }

    if($error_show_flag)
        header("Location: Page_register.php");
    
    $uname = $_POST['user_name'];
    $phone_num = $_POST['phone_number'];
    $pwd = $_POST['pwd'];
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pre_query = $conn->prepare("select username from Users where username = :username");
    $pre_query->execute(array('username'=>$uname));
    $message;
    if($pre_query->rowCount()==0){
        $salt = strval(rand(1000,9999));
        $hashvalue = hash('sha256',$salt.$pwd);
        $pre_query = $conn->prepare("insert into Users(username,password,salt,phone_number) values (:username, :password, :salt, :phone_number)");
        $pre_query->execute(array('username'=>$uname,'password'=>$hashvalue,'salt'=>$salt,'phone_number'=>$phone_num));
        $message = "Create successfully";
        echo <<<EOT
            <!DOCTYPE html>
            <html>
                <body>
                <script>
                    alert("Create success ");
                    window.location.replace("index.php");
                </script>
                </body>
            </html>
        EOT;
        exit();
    }
    else{
        $_SESSION['error_user_name'] = "*The username is already existed";
        header("Location: Page_register.php");
    }
    

?>
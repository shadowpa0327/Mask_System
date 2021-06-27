<?php
    session_start();
    $dbservername='localhost';
    $dbname='Mask_system';
    $dbusername='examdb';
    $dbpassword='examdb';
if(!isset($_POST['submit'])){
    $name = $_POST['user_name'];
    $password = $_POST['pwd'];
    $second_password = $_POST['pwd_2nd'];
    $phone_number = $_POST['phone_number'];
    $error_detect = false;
    $error_name="";
    $error_password="";
    $error_second_password="";
    $error_phone_number="";
    $correct_msg ="";
    if(empty($name)){
        $error_name = "*Cannot be empty!!";
        $error_detect = true;
    }
    if(empty($password)){
        $error_password = "*Cannot be empty!!";
        $error_detect = true;
    }
    if(empty($second_password)){
        $error_second_password = "*Cannot be empty!!";
        $error_detect = true;
    }
    if(empty($phone_number)){
        $error_phone_number = "*Cannot be empty!!";
        $error_detect = true;
    }
    if(!empty($_POST['pwd_2nd']) && $_POST['pwd'] != $_POST['pwd_2nd']){
        $error_second_password = "*Password mismatch";
        $error_detect = true;
    }
    
    if(!empty($_POST['pwd'])&&!ctype_alnum($_POST['pwd'])){
          $error_password = "*Invalid format(only upper/lower-case character and number are allowed)";
          $error_detect = true;
          if(!empty($error_second_password)){
              if(!empty($_POST['pwd_2nd'])){
                    $error_second_password = "*Invalid password format";  
              } 
          }
    }
    if(!empty($_POST['phone_number'])&&!ctype_alnum($_POST['phone_number'])){
        $error_phone_number = "*Invalid format(only upper/lower-case character and number are allowed)";
        $error_detect = true;
    }
    if(!empty($_POST['user_name'])&&!ctype_alnum($_POST['user_name'])){
        $error_name = "*Invalid format(only upper/lower-case character and number are allowed)";
        $error_detect = true;
    }
    if(!empty($_POST['user_name'])){
        $uname = $_POST['user_name'];
        $phone_num = $_POST['phone_number'];
        $pwd = $_POST['pwd'];
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pre_query = $conn->prepare("select username from Users where username = :username");
        $pre_query->execute(array('username'=>$uname));
        $message;
        if($pre_query->rowCount()==0&&!$error_detect){
            $salt = strval(rand(1000,9999));
            $hashvalue = hash('sha256',$salt.$pwd);
            $pre_query = $conn->prepare("insert into Users(username,password,salt,phone_number) values (:username, :password, :salt, :phone_number)");
            $pre_query->execute(array('username'=>$uname,'password'=>$hashvalue,'salt'=>$salt,'phone_number'=>$phone_num));
            $correct_msg = "Create successfully";
            //header("Location: index.php");
            //exit();
        }
        else{
            $error_detect = true;
            $error_name= "*The username is already existed";
        }
    }

    

    $error_message = array(
        'error_username' => $error_name,
        'error_password' => $error_password,
        'error_second_password' =>$error_second_password,
        'error_phone_number' =>$error_phone_number,
        'correct_message' =>$correct_msg,
        'error_detect' =>$error_detect 
    );
    //echo(json_encode($error_message));
    echo(json_encode($error_message));
}



?>
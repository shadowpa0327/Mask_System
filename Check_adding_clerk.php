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

    if(!isset($_POST['clerk_account'])||empty($_POST['clerk_account'])){
        header("Location: Store_page.php");
    }
    $error_detect = false;
    $dbservername='localhost';
    $dbname='Mask_system';
    $dbusername='examdb';
    $dbpassword='examdb';
    $shop_name = $_SESSION['store_name'];
    $uname = $_POST['clerk_account'];
    if($uname == $_SESSION['Username']){
        echo <<<EOT
            <!DOCTYPE html>
            <html>
                <body>
                <script>
                    alert("You are the owner of the shop ! ");
                    window.location.replace("Store_page.php");
                </script>
                </body>
            </html>
        EOT;
        exit();
    }
    //echo("hi");
   
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pre_query = $conn->prepare("select username from Users where username = :username");
    $pre_query->execute(array('username'=>$uname));
    if($pre_query->rowCount()==0){
        echo <<<EOT
        <!DOCTYPE html>
        <html>
            <body>
            <script>
                alert("*The account(user_name) do not exist please check");
                window.location.replace("Store_page.php");
            </script>
            </body>
        </html>
        EOT;
        $error_detect = true;
        header("Location: Store_page.php");
        exit();
    }

    $pre_query = $conn->prepare("select username from Clerk where store_name = :store_name and username = :username");
    $pre_query->execute(array('store_name'=>$shop_name,'username'=>$uname));
    if($pre_query->rowCount()>=1){
        echo <<<EOT
        <!DOCTYPE html>
        <html>
            <body>
            <script>
                alert("*The user is already the clerk , please check ");
                window.location.replace("Store_page.php");
            </script>
            </body>
        </html>
        EOT;
        $error_detect = true;
        exit();
    }
    if(!$error_detect){
        $pre_query = $conn->prepare("insert into Clerk(username,store_name) values(:username,:store_name)" );
        $pre_query->execute(array('username'=>$uname,'store_name'=>$shop_name));
        echo <<<EOT
            <!DOCTYPE html>
            <html>
                <body>
                <script>
                    alert("Adding Clerk successfully ");
                    window.location.replace("Store_page.php");
                </script>
                </body>
            </html>
        EOT;
    }
?>
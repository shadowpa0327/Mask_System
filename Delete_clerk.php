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
    
    $dbservername='localhost';
    $dbname='Mask_system';
    $dbusername='examdb';
    $dbpassword='examdb';
    $clerk_name = $_POST['clerk_name'];
    $store_name = $_SESSION['store_name'];
   // echo("<h1>Del</h1>,$clerk_name,$store_name");
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pre_query = $conn->prepare("delete from Clerk where username=:username and store_name=:store_name ");
    $pre_query->execute(array('username'=>$clerk_name,'store_name'=>$store_name));

    header("Location: Store_page.php");
    
?>
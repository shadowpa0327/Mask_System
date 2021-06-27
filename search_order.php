<?php
    session_start();
    if(!isset($_SESSION['Authenticated'])||!$_SESSION['Authenticated']){
        header("Location: index.php");
        exit();
    }
    
   // echo("trigger");
    //echo($_POST['amount']);
    $dbservername='localhost';
    $dbname='Mask_system';
    $dbusername='examdb';
    $dbpassword='examdb';
    $uname = $_SESSION['Username'];
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pre_query = $conn->prepare("SELECT * from mask_order where Create_user = :user and (status =:status OR ".(($_POST['amount']=="all")?"1 )":"0 )"));
    $pre_query->execute(array(
        "user" => $uname,
        "status" => $_POST['amount']
    ));
    $info = $pre_query->fetchall();
    //$info['status'] = $_POST['amount'];
    echo(json_encode($info));
    

?>
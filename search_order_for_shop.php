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
    $sql = "SELECT * from mask_order where "."(status =:status OR ".(($_POST['amount']=="all")?"1 )":"0 )") ." and (StoreName = :store OR ".(($_POST['shop']=="All")?"1 )":"0 )").  " and StoreName in ((SELECT store_name FROM Clerk where username = :uname) union (SELECT store_name FROM Store where shop_holder = :sname))";
    //echo($sql);
    //$sql = "SELECT * from mask_order where Create_user = :user and (status =:status OR ".(($_POST['amount']=="all")?"1 )":"0 )");
    $pre_query = $conn->prepare($sql);
    $pre_query ->execute(array(
        "status" => $_POST['amount'],
        "store" => $_POST['shop'],
        "uname" => $uname,
        "sname" =>$uname
    ));
    $info = $pre_query->fetchall();
    echo(json_encode($info));
    //"and (StoreName = :storename OR ".(($_POST['shop']=="All")?"1 )":"0 )").

?>

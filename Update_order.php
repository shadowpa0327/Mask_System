<?php
     session_start();
     if(!isset($_SESSION['Authenticated'])||!$_SESSION['Authenticated']){
         header("Location: index.php");
     }
    $return_val = array(
        "msg" => "none"
    );
    $dateTime = new DateTime;
    //echo($_POST['update_mode']);
    $params = ["status"=>$_POST['update_mode'],"endtime"=>$dateTime->format('Y-m-d H:i:s'),"username"=>$_SESSION['Username']];
    $ids = [1,2,3];
    $in_params=[];
    $in = "";
    $i = 0;// we are using an external counter 
    // because the actual array keys could be dangerous
    if(isset($_POST['multiselect'])){
        foreach ($_POST['multiselect'] as $item)
        {
           // echo($item);
            $key = ":id".$i;
            $in .= "$key,";
            $in_params["id".$i++] = $item; // collecting values into a key-value array
        }
        $in = rtrim($in,","); // :id0,:id1,:id2

    }   
    else{
        echo(json_encode($return_val));
       // header("Location: Page_personal_order.php");
        exit();
    }
    $return_val['msg'] = $_POST['update_mode'];
    $dbservername='localhost';
    $dbname='Mask_system';
    $dbusername='examdb';
    $dbpassword='examdb';
    //$uname = $_SESSION['Username'];
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $sql = "SELECT OID,status FROM mask_order  WHERE OID IN ($in) and status != 'Not Finished'";
    $stm = $conn->prepare($sql);
    //echo(array_merge($params,$in_params));
    $stm->execute($in_params);
    if($stm->rowCount()>0){
        $result = $stm -> fetchall();
        $tmp = "*Some of the order are being modified please research!<br>---------------------------<br>";
       // echo("mother fucker");
       // echo($stm->rowCount());
        foreach($result as $row)
            $tmp.= ("* OID:".$row['OID']." has already been ".$row['status']."<br>");

        $tmp = rtrim($tmp,",");
        $return_val['msg'] = $tmp;
        echo(json_encode($return_val));
        exit();
    }
    $sql = "UPDATE mask_order SET status = :status  ,Endtime=:endtime ,Finish_user=:username  WHERE OID IN ($in)";
    $stm = $conn->prepare($sql);
    $stm->execute(array_merge($params,$in_params)); // just merge two arrays
    echo(json_encode($return_val));
    if($_POST['update_mode']!="Cancelled")exit();
    $sql = "UPDATE Store as S ,(select StoreName, sum(Order_Number) as NumberSum from mask_order WHERE OID in($in) GROUP BY StoreName ) as t2 
            SET S.number = S.number + t2.NumberSum 
            WHERE S.store_name = t2.StoreName";
    $stm = $conn->prepare($sql);
    $stm->execute($in_params);
?>
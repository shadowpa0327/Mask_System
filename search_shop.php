<?php
    session_start();
    if(!isset($_SESSION['Authenticated'])||!$_SESSION['Authenticated']){
        header("Location: index.php");
    }
    //if(!isset($_POST['city']))
        //echo("NO setting");
    
    $constraint = array(
        'name' => "1",
        'city' => "1",
        'price-min'=>0,
        'price-max'=>2147483647,
        'number-min'=>0,
        'number-max'=>2147483647,
        'show-only'=>"1"
    );
    $uname = $_SESSION['Username'];
    //echo('username:'.$uname);
    if(!empty($_POST['shopname'])){
        $_POST['shopname'] = strtolower($_POST['shopname']);
        //$_POST['shopname'] =  mysql_real_escape_string($_POST['shopname']) ;
        $constraint['name'] ="lower(store_name) like '%{$_POST['shopname']}%'";
    }
    if(!empty($_POST['city'])&&$_POST['city']!="All"){
        $constraint['city'] = $_POST['city'];
    }
    if(!empty($_POST['price_minimum'])||$_POST['price_minimum']=="0"){
        $constraint['price-min'] = $_POST['price_minimum'];
    }
    if(!empty($_POST['price_maximum'])||$_POST['price_maximum']=="0"){
        $constraint['price-max'] = $_POST['price_maximum'];
    }
    if($_POST['amount']=="sold_out"){
        $constraint['number-max'] = 0;
    }
    else if($_POST['amount']=="sparse"){
        $constraint['number-min'] = 1;
        $constraint['number-max'] = 99;
    }
    else if($_POST['amount']=="sufficient"){
        $constraint['number-min'] = 100;
    }

    if(isset($_POST['show_working'])){
       $constraint['show-only'] = $uname;
    }
   // echo($_POST['city']);
    //foreach($_POST as $ptr)
      //  echo($ptr);
    $dbservername='localhost';
    $dbname='Mask_system';
    $dbusername='examdb';
    $dbpassword='examdb';
    $query = "SELECT * from Store WHERE lower(store_name) like :constraint_name and  ((city=:constraint_city)OR ".(($constraint['city']=="1")?"1":"0").") and price >= :constraint_price_min and price<= :constraint_price_max and number>= :constraint_number_min and number<=:constraint_number_max and ((Store.store_name in (select store_name from Clerk where Clerk.username = :constraint_show_only) or shop_holder = :constraint_show_only2 ) OR ".(($constraint['show-only']=="1")?"1)":"0)");
    //echo($constraint['city']);
    //echo($query);
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pre_query = $conn->prepare($query);
    $pre_query->execute(array(
        'constraint_name' => '%'.$_POST['shopname'].'%',
        'constraint_city' => $constraint['city'],
        'constraint_price_min' => $constraint['price-min'],
        'constraint_price_max' => $constraint['price-max'],
        'constraint_number_min' => $constraint['number-min'],
        'constraint_number_max' => $constraint['number-max'],
        'constraint_show_only' =>$constraint['show-only'],
        'constraint_show_only2' =>$constraint['show-only']
    ));
    //$conn->debugDumpParams();
    $info = $pre_query->fetchall();
    echo(json_encode($info));
    /*echo('<table id="myTable">');
    echo('
    <tr>
        <td>Shop</td>
        <td>City</td>
        <td class = "sorting_button" onclick="sortTable(2,1)">Mask Price</td>
        <td class = "sorting_button" onclick="sortTable(3,1)">Mask Amount</td>
    </tr>   
');
    foreach($info as $result)
    {
       // echo($pre_query->rowCount());
        echo ("<tr>");
            echo ("<td>".$result['store_name']."</td>");
            echo ("<td>".$result['city']."</td>");
            echo ("<td>".$result['price']."</td>");
            echo ("<td>".$result['number']."</td>");
        echo ("</tr>");
    }
    echo('</table>');*/
?>
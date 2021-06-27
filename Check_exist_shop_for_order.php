<?php
   session_start();
   if(!isset($_SESSION['Authenticated'])||!$_SESSION['Authenticated']){
       //echo("NO!!");
       header("Location: index.php");
   }
   $_SESSION['exist_store'] = false;
   $dbservername='localhost';
   $dbname='Mask_system';
   $dbusername='examdb';
   $dbpassword='examdb';
   $uname = $_SESSION['Username'];
   //echo($uname);
   $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   $pre_query = $conn->prepare("select * from Store where shop_holder = :shop_holder");
   $pre_query->execute(array('shop_holder'=>$uname));
   if($pre_query->rowCount()==0){
      // echo("NO!!");
      echo <<<EOT
        <!DOCTYPE html>
        <html>
            <body>
            <script>
                alert("You don't have a shop yet! Go to create one ! ");
                window.location.replace("Page_create_store.php");
            </script>
            </body>
        </html>
       EOT;
   }
   else{
       //echo("YES!!");
      // $_SESSION['']
       $result = $pre_query->fetch();
       $_SESSION['store_name'] = $result['store_name'];
       //echo($_SESSION['store_name']);
       $_SESSION['exist_store'] = true;
       header("Location: Page_shop_order.php");
   }
?>
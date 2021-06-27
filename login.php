<?php
session_start();

$_SESSION['Authenticated']=false;

$dbservername='localhost';
$dbname='Mask_system';
$dbusername='examdb';
$dbpassword='examdb';

try
{
  if (!isset($_POST['user_name']) || !isset($_POST['pwd']))
  {
    header("Location: index.php");
    exit();
  }
  if (empty($_POST['user_name']) || empty($_POST['pwd']))
    throw new Exception('Please input user name and password.');

  $user_name=$_POST['user_name'];
  $pwd=$_POST['pwd'];
  $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
  # set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
  $stmt=$conn->prepare("select username, password, salt from Users where username=:username");
  $stmt->execute(array('username'=>$user_name));

  if ($stmt->rowCount()==1)
  {
    $row = $stmt->fetch();
    if ($row['password']==hash('sha256',$row['salt'].$_POST['pwd']))
    {
      $_SESSION['Authenticated']=true;
      $_SESSION['Username']=$row[0];
      header("Location: home_page.php");
      exit();
	}
	else
	  throw new Exception('Login failed.');
  }
  else
     throw new Exception('Login failed.');
}
catch(Exception $e)
{
  $msg=$e->getMessage();
  session_unset(); 
  session_destroy(); 
  echo <<<EOT
    <!DOCTYPE html>
    <html>
      <body>
	    <script>
          alert("$msg");
		  window.location.replace("index.php");
        </script>
	  </body>
	</html>
EOT;
}
?>

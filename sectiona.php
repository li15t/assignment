<html>
<head>
</head> 
<body>
<?php 
  /* datebase login info */
  $hn = 'localhost'; //hostname
  $db = 'li15t_pbl'; //database
  $un = 'li15t_pbl'; //username
  $pw = 'mypassword'; //password
  
/* Connect to database */
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die($conn->connect_error);
 
/* to read the values dynamically from user_codes table */
  $query  = "SELECT user_description,user_code FROM user_codes;";
  $result = $conn->query($query);   
  if (!$result) die($conn->error); 

  $rows = $result->num_rows;
  $dynamic_opt="";
  for ($j = 0 ; $j < $rows ; ++$j)
  {$result->data_seek($j);
   $dym_usercode=$result->fetch_assoc()['user_code'];
   $result->data_seek($j);
   $dym_user_description=$result->fetch_assoc()['user_description'];
   $dynamic_opt.='<option value='.$dym_usercode.'>'.$dym_user_description.'</option>'; 
  }
/* output the form */
  echo <<<_END
   <form action="sectiona.php" method="post">
    First name:<input type="text" name="fname" required><br>
    Last name:<input type="text" name="lname" required><br>
    User Type:<select name="user_type">
_END;
echo $dynamic_opt;
echo <<<_END
    </select><br> 
    E-mail:<input type="email" name="email" required><br>
    Password:<input type="password" name="password" required><br>
    <input type="submit" value="Submit"><br>
   </form>
_END;



 /* insert statement which uses prepare and execute with placeholders for the inserted values */
  if (isset($_POST['fname'])   &&
      isset($_POST['lname'])   &&
      isset($_POST['user_type'])&&
      isset($_POST['email'])    &&
      isset($_POST['password']))
  {
    $fname = get_post($conn, 'fname');
    $lname = get_post($conn, 'lname');
    $usercode = get_post($conn, 'user_type');
    $email = get_post($conn, 'email');
    $password = get_post($conn, 'password');
       

  $stmt=$conn->prepare("INSERT INTO user_profiles (fname,lname,usercode,email,password) VALUES(?,?,?,?,?)");
  $stmt->bind_param("sssss",$fname,$lname,$usercode,$email,$password);
  $is_success= $stmt->execute();
    if($is_success) 
    echo "Insert record successfully! <br>";
    else
    echo "Insert failed:".$conn->error."<br>";    
  }

$stmt->close();
$result->close();
$conn->close();


  function get_post($conn, $var)
  {
    return $conn->real_escape_string($_POST[$var]);
  }

?>



</body>
</html>
<html lang="en-US">
<head>
<?php
//insert java scirpt 
echo <<<_END
<script src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
  var data = google.visualization.arrayToDataTable([
  ['Publications', 'Publications for each catagory'],
_END;



 /* datebase login info */
  $hn = 'localhost'; //hostname
  $db = 'li15t_pbl'; //database
  $un = 'li15t_pbl'; //username
  $pw = 'mypassword'; //password
  
/* Connect to database */
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die($conn->connect_error);
  
/* sql operation */
  $query  = "SELECT category, count(*)as cnt FROM `classics` group by category";
  $result = $conn->query($query);
   
  if (!$result) die($conn->error); 
    $rows= $result->num_rows;
   for ($j = 0 ; $j < $rows-1 ; ++$j)
   { $result->data_seek($j);
     $category=$result->fetch_assoc()['category'];
     $result->data_seek($j);
     $cnt=$result->fetch_assoc()['cnt'];
     echo "['".$category."',".$cnt."],";
   }
     $result->data_seek($rows-1);
     $category=$result->fetch_assoc()['category'];
     $result->data_seek($rows-1);
     $cnt=$result->fetch_assoc()['cnt'];
     echo "['".$category."',".$cnt."]";

$result->close();
$conn->close();

echo <<<_END
]);


  var options = {'width':550, 'height':400};
  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);
}
</script>
_END;

?>
</head> 
<body>
<div id="piechart"></div>
</body></html>
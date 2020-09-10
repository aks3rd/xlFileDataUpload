<?php require_once 'AJAXResponse.php'; ?>
<?php require_once 'Station.php'; ?>
<?php 

$entityBody = json_decode(file_get_contents('php://input'), true);
$code=$entityBody['code'];
$station_name=$entityBody['station_name'];
$energy=$entityBody['energy'];
 try
 {
   $connection=new PDO( "mysql:host=localhost;dbname=stations", "stationmanager", "StationManager_2020");
   $connection->setAttribute( PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);    
   if($connection)
   {
     $sql="update station set energy=? where code=?";
     $statement=$connection->prepare($sql);
     $statement->bindParam(1,$energy);
     $statement->bindParam(2,$code);
     $statement->execute();


     $sql="select code,station_name,energy from station where code=?";
     $statement=$connection->prepare($sql);
     $statement->bindParam(1,$code);
     $statement->execute();
     $statement->setFetchMode(PDO::FETCH_ASSOC);
     $row=$statement->fetch();
     $station=new Station($row['code'],$row['station_name'],$row['energy']);
     $ajaxResponse=new AJAXResponse(true,$station,"");
     echo json_encode($ajaxResponse);
   }
 }
 catch(PDOException $e)
 {
   echo $sql."<br>" . $e->getMessage();
 }
?>

<?php require_once 'lib/SimpleXLSX.php'; ?>
<?php include("MasterPageTopSection.php"); ?>
<?php

$target_dir = "uploads/";
$target_file = $target_dir.basename($_FILES["xlFile"]["name"]);

$uploadOk = 1;
$xlFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Allow certain file formats
if($xlFileType != "xlsx" && $xlFileType != "xls")
{
  echo "Sorry, only xlsx & xls files are allowed.";
  $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk==0)
{
  echo "<h3>Sorry, your file was not uploaded.</h3>";
} // if everything is ok, try to upload file
else 
{
  if($xlsx=SimpleXLSX::parse($_FILES["xlFile"]["name"]))
  {
    try
    {
      $connection=new PDO("mysql:host=localhost;dbname=stations", "stationmanager", "StationManager_2020");
      $connection->setAttribute( PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);    
      if($connection)
      {
         //echo "Connection stablish <br/>";
         $statement = $connection->prepare( "INSERT INTO station(station_name,energy) VALUES (?,?)");
         $statement->bindParam( 1, $station_name);
         $statement->bindParam( 2, $energy);

         $rows=$xlsx->rows();
         for($i=0;$i<count($rows);$i++)
         {
            if($i==0)continue;
            $fields=$rows[$i];
            $station_name=$fields[0];
            $energy=$fields[1];
            //echo "<br/>".$station_name."  ,  ".$energy."<br/>";
            $statement->execute();
         }
      }
      //echo "Data saved<br/>";
      header("Location:displayList.php");
      exit;
    }
    catch(PDOException $e)
    {
        echo "<br>" . $e->getMessage();
    }
  }
  else
  {
      echo SimpleXLSX::parseError();
  }
}
?>
<?php include("MasterPageBottomSection.php"); ?>

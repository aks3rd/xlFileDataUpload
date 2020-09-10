<?php 
class Station
{
  public $code;
  public $station_name;
  public $energy;
  function __construct($code,$station_name,$energy)
  {
    $this->code=$code;
    $this->station_name=$station_name;
    $this->energy=$energy;
  }
  public function setCode($code)
  {
    $this->code=$code;
  }
  public function getCode()
  {
    return $this->code;
  }
  public function setStationName($station_name)
  {
    $this->station_name=$station_name;
  }
  public function getStationName()
  {
    return $this->station_name;
  }
  public function setEnergy($energy)
  {
    $this->energy=$energy;
  }
  public function getEnergy()
  {
    return $this->energy;
  }
}
?>

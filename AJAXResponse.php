<?php 
class AJAXResponse
{
  public $success;
  public $response;
  public $exception;
  function __construct($success,$response,$exception)
  {
    $this->success=$success;
    $this->response=$response;
    $this->exception=$exception;
  }
  public function setSuccess($success)
  {
    $this->success=$success;
  }
  public function getSuccess()
  {
    return $this->success;
  }
  public function setResponse($response)
  {
    $this->response=$response;
  }
  public function getResponse()
  {
    return $this->response;
  }
  public function setException($exception)
  {
    $this->exception=$exception;
  }
  public function getException()
  {
    return $this->exception;
  }
}
?>

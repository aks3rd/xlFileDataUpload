<?php
 include("MasterPageTopSection.php");
?>
<script>
function checkFileExtention(sender)
{
  var spanObj=document.getElementById("extentionError");
  spanObj.innerHTML="";
  var validExtentions=new Array(".xlsx", ".xls");
  var fileExtention=sender.xlFile.value;
  if(fileExtention.length==0)
  {
    spanObj.innerHTML="Please select file";
    return false;
  }
  fileExtention=fileExtention.substring(fileExtention.lastIndexOf('.'));
  if(validExtentions.indexOf(fileExtention)<0)
  {
      spanObj.innerHTML="Invalid file selected, valid files are of "+validExtentions.toString()+" types.";
      return false;
  }
  else return true;
}
</script>
   <!-- Page Heading -->
   <div style="width:100%;height:600px">
     <center>
      <h1>Welcome to file upload  web application</h1><br/><br/>
	<form action="upload.php" method="post" enctype="multipart/form-data" onsubmit="return checkFileExtention(this);" >
	   Select File : <input type="file" name="xlFile" id="xlFile" ><br/>
           <span class="text-danger" id="extentionError"></span><br/><br/>
	   <button type="submit" name="submit">Upload</button>
	</form><br/><br/><br/>
     </center>
   </div>
<?php include("MasterPageBottomSection.php"); ?>

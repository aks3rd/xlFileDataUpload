<?php require_once 'Station.php'; ?>
<?php
 include("MasterPageTopSection.php"); 
?>
<?php
 try
 {
   $connection=new PDO( "mysql:host=localhost;dbname=stations", "stationmanager", "StationManager_2020");
   $connection->setAttribute( PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);    
   if($connection)
   {
     //echo "Connection stablish <br/>";
     $statement="select code,station_name,energy from station";
     $resultSet=$connection->query($statement);
     if($resultSet)$resultSet->setFetchMode(PDO::FETCH_ASSOC);
     $stationList=array();
     while($row = $resultSet->fetch()):
         $s=new Station($row['code'],$row['station_name'],$row['energy']);
         array_push($stationList,$s);
     endwhile;
?>
<script>

   var list=<?php echo json_encode($stationList); ?>;
   function Station()
   {
     this.code=0;
     this.stationName="";
     this.energy=0;
   }
   function ViewModel()
   {
     this.stations=[];
     this.selectedRow=null;
     this.selectedStationIndex=-1;
   }
   var viewModel=new ViewModel();
   function createEditIconClickedHandler(index,row)
   {
     return function(){
       displayEditSection(index,row);
     };
   }
   function createUpdateIconClickedHandler(index,row)
   {
     return function(){
       updateSection(index,row);
     };
   }
   function createRowClickedHandler(index,row)
   {
     return function(){
       selectRow(index,row);
     };
   }
   function displayEditSection(index,row)
   {
      if(viewModel.selectedRow!=null)
      {
        viewModel.selectedRow.style.background="white";
        viewModel.selectedRow.style.color="black";
        document.getElementById("energyTextixix"+viewModel.selectedStationIndex).style.display="block";
        document.getElementById("energyixix"+viewModel.selectedStationIndex).style.display="none";
        document.getElementById("editixix"+viewModel.selectedStationIndex).style.display="block";
        document.getElementById("updateixix"+viewModel.selectedStationIndex).style.display="none";
      }
      let label=document.getElementById("energyTextixix"+index);
      let input=document.getElementById("energyixix"+index);
      let editButton=document.getElementById("editixix"+index);
      let updateButton=document.getElementById("updateixix"+index);
      label.style.display="none";
      input.value=label.innerHTML;
      input.style.display="block";
      editButton.style.display="none";
      updateButton.style.display="block";
      
      viewModel.selectedStationIndex=index;
      viewModel.selectedRow=row;
      row.style.background="lightgray";
      row.style.color="black";
   }
   function updateSection(index,row)
   {
      let span=document.getElementById("Errorixix"+index);
      let label=document.getElementById("energyTextixix"+index);
      let input=document.getElementById("energyixix"+index);
      let editButton=document.getElementById("editixix"+index);
      let updateButton=document.getElementById("updateixix"+index);
      span.innerHTML="";
      let s=viewModel.stations[index]; 
      let jsonObject={"code":s.code,"station_name":s.stationName,"energy":input.value};
      if(input.value.length==0)
      {
         span.innerHTML="Required";
         return;
      }
      let number='0123456789.';
      for(var i=0;i<input.value.length;i++)
      {
        if(number.indexOf(input.value.charAt(i))==-1)
        {
          span.innerHTML="Invalid energy";
          return;
        }
      }
      var xmlHttpRequest=new XMLHttpRequest();
      xmlHttpRequest.onreadystatechange=function(){
         if(this.readyState==4 && this.status==200)
         {
            let ajaxResponse=JSON.parse(xmlHttpRequest.responseText);
            if(ajaxResponse.success==true)
            {
               let st=ajaxResponse.response;
               label.innerHTML=parseFloat(st.energy);
               s.energy=parseFloat(st.energy);
               viewModel.stations[index]=s;
               updateTotalEnergy();
            }
            else
            {
               console.error(ajaxResponse.exception);
            }
         }
      }
      xmlHttpRequest.open("POST","update.php",true);
      xmlHttpRequest.setRequestHeader("Content-type","application/json");
      xmlHttpRequest.send(JSON.stringify(jsonObject));

      label.style.display="block";
      input.value=label.innerHTML;
      input.style.display="none";
      editButton.style.display="block";
      updateButton.style.display="none";      
   }
   function selectRow(index,row)
   {
     if(index==viewModel.selectedStationIndex) return;
     if(index==viewModel.stations.length)return;
     if(viewModel.selectedRow!=null)
     {
       viewModel.selectedRow.style.background="white";
       viewModel.selectedRow.style.color="black";
       document.getElementById("energyTextixix"+viewModel.selectedStationIndex).style.display="block";
       document.getElementById("energyixix"+viewModel.selectedStationIndex).style.display="none";
       document.getElementById("editixix"+viewModel.selectedStationIndex).style.display="block";
       document.getElementById("updateixix"+viewModel.selectedStationIndex).style.display="none";
       document.getElementById("Errorixix"+viewModel.selectedStationIndex).innerHTML="";
     }
     viewModel.selectedStationIndex=index;
     viewModel.selectedRow=row;
     row.style.background="lightgray";
     row.style.color="black";
   }
   function updateTotalEnergy()
   {
     let total2=0;
     let label=document.getElementById("totalixix"+viewModel.stations.length);
     let i=0;
     while(i<viewModel.stations.length)
     {
       total2+=parseFloat(viewModel.stations[i].energy);
       i++;
     }
     label.innerHTML=total2;
   }
   function initView()
   {
     list.forEach((s)=>{
       var st=new Station();
       st.code=s.code;
       st.stationName=s.station_name;
       st.energy=parseFloat(s.energy);
       viewModel.stations.push(st);
     });
     var total=0;
     var grid=document.getElementById('stationViewGrid');
     var i=0;
     var tr,td,img,textNode,label,input,span;
     while(i<viewModel.stations.length)
     {
        tr=document.createElement("tr");
        td=document.createElement("td");
        textNode=document.createTextNode((i+1)+".");
        td.appendChild(textNode);
        td.scope="row";
        tr.appendChild(td);

        td=document.createElement("td");
        textNode=document.createTextNode(viewModel.stations[i].stationName);
        td.appendChild(textNode);
        tr.appendChild(td);

        td=document.createElement("td");
        label=document.createElement("Label");
        label.id="energyTextixix"+i;
        label.style.display="block";
        label.innerHTML=viewModel.stations[i].energy;
        td.appendChild(label);


        input=document.createElement("input");
        input.type="text";
        input.readOnly=false;
        input.id="energyixix"+i;
        input.style.display="none";
        input.value=viewModel.stations[i].energy
        td.appendChild(input);

        span=document.createElement("span");
        span.id="Errorixix"+i;
        span.innerHTML="";  
	span.style.color="red";
        td.appendChild(span);

        tr.appendChild(td);

        

        total+=parseFloat(viewModel.stations[i].energy);
        td=document.createElement("td");
        img=document.createElement("img");
        img.src='images/edit_icon.png';
        img.id="editixix"+i;
        img.style.display="block";
        img.onclick=createEditIconClickedHandler(i,tr);
        td.appendChild(img);
        img=document.createElement("img");
        img.src='images/update_icon.png';
        img.id="updateixix"+i;
        img.style.display="none";
        img.onclick=createUpdateIconClickedHandler(i,tr);
        td.appendChild(img);
        tr.appendChild(td);

        tr.style.cursor='pointer';
        tr.style.background="white";
        tr.id='ixix'+i;
        tr.onclick=createRowClickedHandler(i,tr);
        grid.appendChild(tr);
        i++;
     }
     tr=document.createElement("tr");
     td=document.createElement("td");
     td.colSpan="2";
     textNode=document.createTextNode("Total Energy : ");
     td.appendChild(textNode);
     td.scope="row";
     tr.appendChild(td);
     td=document.createElement("td");
     td.colSpan="2";

     label=document.createElement("Label");
     label.id="totalixix"+i;
     label.innerHTML=total;
     td.appendChild(label);
     td.scope="row";
     tr.appendChild(td);
     
     tr.style.cursor='pointer';
     tr.style.background="white";
     tr.id='ixix'+i;
     tr.onclick=createRowClickedHandler(i,tr);
     grid.appendChild(tr);
   }



window.addEventListener('load',initView);

</script>
<?php
   }
 }
 catch(PDOException $e)
 {
   echo "<br>" . $e->getMessage();
 }
?>
<h3>List of Stations : </h3>
<!-- Here Bootstrap Table Starts -->
	<!-- gridTableSection -->
	<div class="gridTableSection table-responsive" id="tableContent">
		<table id="stationTable" class="table table-bordered " > <!--table-striped -->
			<thead class="thead-light shadow">
				<tr>
					<th scope="col">S.No.</th>
					<th scope="col">Name</th>
					<th scope="col">Energy</th>
					<th scope="col">Edit</th>
				</tr>
			</thead>
			<tbody id="stationViewGrid">

			</tbody>
		</table>
	</div>
<!-- Here Bootstrap Table Ends -->




<?php include("MasterPageBottomSection.php"); ?>

<?php
   

         $jsonString= file_get_contents('php://input');

         $dataa = json_decode($jsonString,true);
        
         $con = new mysqli("localhost", "id2217679_00894_home_made", "teri@meri1", "id2217679_home_made") or 
         die(mysqli_error());
         foreach ($dataa as $key => $value) 
          {
              
                $DeviceId= $value['deviceId'];
		
		$ApplicationId= $value['applicationId'];
		
		$DateTime= $value['dateTime'];
		
		$Latitude= $value['latitude'];

                $Longitude= $value['longitude'];   
                
                mysqli_query($con,"insert into 
                      User_Mobile_Details(DeviceId,ApplicationId,DateTime,Latitude,Longitude) 
                      values('$DeviceId','$ApplicationId','$DateTime','$Latitude','$Longitude')");
                 mysqli_close($con);
 
                 
                 $fjson =array("status" => "success");

               
                 echo json_encode($fjson) ;
          }	

exit(0);

?>
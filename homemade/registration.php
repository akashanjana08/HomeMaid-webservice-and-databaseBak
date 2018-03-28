<?php
   

         $jsonString= file_get_contents('php://input');

         $dataa = json_decode($jsonString,true);
        
         $con = new mysqli("localhost", "id2217679_00894_home_made", "teri@meri1", "id2217679_home_made") or 
         die(mysqli_error());
         foreach ($dataa as $key => $value) 
          {
              
		$FullName= $value['fullName'];
		
		$Mobile= $value['mobile'];
		
		$Email= $value['email'];

                $Profile_Pic= $value['profilePic']; 

                $Locality= $value['locality']; 

                $LoginType= $value['loginType'];

                $deviceId= $value['deviceId'];

                $UserId= getUserId(getAutoMaxId($con),strtoupper($FullName));

                
                mysqli_query($con,"insert into 
                      User_Detail(UserId,FullName,Mobile,Email,Profile_Pic,Locality,LoginType,Status,DeviceID) 
                      values('$UserId','$FullName','$Mobile','$Email','$Profile_Pic','$Locality','$LoginType','A',$deviceId)");
                
                 

                 $returnJson = array("fullName" => $FullName,"mobile"=> $Mobile,"email"=> 
                                       $Email,"profilePic"=> $Profile_Pic,"locality"=> $Locality,"userId"=> $UserId);
                 
                 $fjson =array("status" => "success","userDetails" => $returnJson);

               
                 echo json_encode($fjson) ;
          }	


         function getUserId($maxid,$name)
          {
             // $firstPart =substr($mobileNo, 3, 4);
             //$finalString = substr($name,0,2).$mobileNo[8].$firstPart.$mobileNo[0] ;
             $day = date("d"); 
             $month = date("m"); 
             $finalString = "HM".substr($name,0,2).$day."".$month."".$maxid;
             return $finalString;  
          }

          function getAutoMaxId($con)
          {
                 
                 $sql = "SELECT max(Autoid) as Autoid FROM User_Detail";
                 $result = $con->query($sql);
                  while($data = $result->fetch_assoc())
                  {
                     $maxid =$data['Autoid'];
                  }
             return $maxid;  
          }
         mysqli_close($con);
                 
exit(0);

?>
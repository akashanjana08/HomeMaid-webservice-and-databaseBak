<?php
   

         $jsonString= file_get_contents('php://input');

         $dataa = json_decode($jsonString,true);
        
         $con = new mysqli("localhost", "id2217679_00894_home_made", "teri@meri1", "id2217679_home_made") or 
         die(mysqli_error());
         foreach ($dataa as $key => $value) 
          {
               
                $mobile = $value['mobile'];
                $email  = $value['email'];
                $Profile_Pic  = $value['profilePic'];
                $LoginType    = $value['loginType'];
              
		
                if($mobile=="NA")
                {
                  $sql = "SELECT * FROM User_Detail WHERE Email='".$email."'";
                  $updateSqli = "UPDATE User_Detail SET LoginType='".$LoginType."' ,Profile_Pic='".$Profile_Pic."' WHERE 
                                 Email='".$email."'";
                  
                }
                else
                {
                   $sql = "SELECT * FROM User_Detail WHERE Mobile='".$mobile."'";
                   $updateSqli  = "UPDATE User_Detail SET LoginType='".$LoginType."' WHERE Mobile='".$mobile."'";
                }
                  
                 mysqli_query($con ,  $updateSqli);
                $result = $con->query($sql);
                $msg=json_encode (json_decode ("{}"));
                $msg=json_decode($msg);
                   if ($result->num_rows > 0)               
                   {
                    while($r = $result->fetch_assoc()) {
                            $msg= array("fullName" => $r['FullName'], "mobile" => $r['Mobile'], "email" =>$r['Email'], 
                           "profilePic" => $r['Profile_Pic'], "locality" => $r['Locality'], "userId" => $r['UserId']);
                   }

                   
                    $fjson =array("status" => "success","userDetails" => $msg);
                   }
                   else              
                   {
                     
                     $fjson =array("status" => "failed" ,"userDetails" => $msg);
                   }

                    echo json_encode($fjson) ;
          }	

exit(0);

?>
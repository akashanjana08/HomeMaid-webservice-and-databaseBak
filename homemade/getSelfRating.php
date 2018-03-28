<?php
   

         $jsonString= file_get_contents('php://input');

         $dataa = json_decode($jsonString,true);
        
         $con = new mysqli("localhost", "id2217679_00894_home_made", "teri@meri1", "id2217679_home_made") or 
         die(mysqli_error());
         foreach ($dataa as $key => $value) 
          {
              
		$userId   = $value['userId'];
		$maidId   = $value['maidId'];
		
                 $sql = "SELECT * FROM Miad_Rating WHERE UserId='".$userId."' and maidId='".$maidId."'";
                 $result = $con->query($sql);
                 if ($result->num_rows > 0)               
                 {

                    while($r = $result->fetch_assoc()) 
                     {
                            $ratingDetails = array("status" => "Success","rating" => (int)$r['rating'], "review" => 
                                                     $r['review']);        
                     }
                    
                 }
                 else{
                            $ratingDetails = array("status" => "Success","rating" => 1, "review" => "");
                 }
            mysqli_close($con);
          echo json_encode($ratingDetails) ;
          }	
exit(0);

?>
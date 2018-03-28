<?php
   
         $con = new mysqli("localhost", "id2217679_00894_home_made", "teri@meri1", "id2217679_home_made") or 
         die(mysqli_error());
        
         $sql = "SELECT * FROM Category WHERE Status='A'";
         $result = $con->query($sql);
         if ($result->num_rows > 0)               
         {

                    while($r = $result->fetch_assoc()) 
                     {
                            $categoryDetails[]= array("id" => $r['Id'], "name" => $r['Name'], "image" =>$r['Image'], 
                           "colorCode" => $r['ColorCode']);
                     }
                  mysqli_close($con);
                  $fjson =array("status" => "success" ,"categoryDetails" => $categoryDetails);
         }
          echo json_encode($fjson) ;
          	
 
exit(0);

?>
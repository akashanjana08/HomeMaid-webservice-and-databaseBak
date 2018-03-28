<?php
   

         $jsonString= file_get_contents('php://input');

         $dataa = json_decode($jsonString,true);
        
         $con = new mysqli("localhost", "id2217679_00894_home_made", "teri@meri1", "id2217679_home_made") or 
         die(mysqli_error());
         foreach ($dataa as $key => $value) 
          {
               
                $userId     = $value['userId'];
                $maidId     = $value['maidId'];
                $categoryId = $value['categoryId'];
                $action     = $value['action'];
                // need to modify this
                //$categoryId='c101';
		
                if($action=="True")
                {
                  $date = date('Y-m-d H:i:s');
                  mysqli_query($con,"insert into 
                      UserFavourateMaid(UserId,MaidId,DateTime,categoryId) 
                      values('$userId','$maidId','$date','$categoryId')");
                  
                }
                else
                {
                  $sqlDelete = "DELETE FROM UserFavourateMaid WHERE UserId='".$userId."' and MaidId='".$maidId."'";
                  mysqli_query($con ,  $sqlDelete);
                }
                    mysqli_close($con);
                    $fjson =array("status" => "success");
                    echo json_encode($fjson) ;
          }	

exit(0);

?>
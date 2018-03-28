<?php
   

         $jsonString= file_get_contents('php://input');

         $dataa = json_decode($jsonString,true);
        
         $con = new mysqli("localhost", "id2217679_00894_home_made", "teri@meri1", "id2217679_home_made") or 
         die(mysqli_error());
         foreach ($dataa as $key => $value) 
          {
              
		$count  = $value['count'];
                $maidId = $value['maidId'];
                 $sql = "SELECT * FROM `Miad_Rating` WHERE maidId='".$maidId."' ORDER BY date DESC LIMIT 0,".$count;
                 $result = $con->query($sql);
                 if ($result->num_rows > 0)               
                 {
                          while($r = mysqli_fetch_assoc($result))
                          {

                             $getUserDetailSql = "SELECT * FROM `User_Detail` where UserId='".$r['UserId']."'";
                             $UserDetailSqlresult = $con->query($getUserDetailSql);
                             while($r1 = mysqli_fetch_assoc($UserDetailSqlresult))
                             {
                                   $userName   = $r1['FullName'];
                                   $profilePic = $r1['Profile_Pic'];
                             }

                             $arr[]=array("rating" => (int)$r['rating'],"review" => $r['review'],
                                     "date" =>(int)$r['date'],"userName" => $userName,"userPhoto" => $profilePic);

                                    $userName ="";
                                    $profilePic ="";
                          }
                       $fjson = array("status" => "Success", "maidReviewList" => $arr);
                 }
                 else  
                 {
                      $maidReviewList =json_encode (json_decode ("[]"));
                      $maidReviewList = json_decode($maidReviewList);
                      $fjson = array("status" => "Success", "maidReviewList" =>  $maidReviewList);
                 }
                 
                
          }	

         // Get Total Ratings
         function getUserDataOverId($userId,$con)
          {  
            
             $getTotalRatingMaidSql = "SELECT * FROM `Miad_Rating` where maidId='".$maidId."'";
             if ($result=mysqli_query($con,$getTotalRatingMaidSql))
             {
                  // Return the number of rows in result set
                   $numOfMaid=mysqli_num_rows($result);
             }
             return $numOfMaid;
         }


         function getAverageMaidRating($totalRating,$con,$maidId){
		 
		     $ratingSum = 0.0;
		     $getTotalRatingMaidSql = "SELECT * FROM `Miad_Rating` where maidId='".$maidId."'";
			 $getTotalRatingMaidSqlResult = $con->query($getTotalRatingMaidSql);
			 while($r = $getTotalRatingMaidSqlResult ->fetch_assoc()) 
                         {       
                         $ratingSum = $ratingSum +(float)$r['rating'];
                         
                         }
                        
			if($totalRating!=0)
                        {
                           $result =  round((float)($ratingSum/$totalRating),1);
                           //echo (float) $result;
			   return $result ;
                          
			}	
			else{
			   return 0.0;
			 }	
	 }

           mysqli_close($con);
           echo json_encode($fjson) ;
        
                 
exit(0);

?>
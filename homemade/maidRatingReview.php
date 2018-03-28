<?php
   

         $jsonString= file_get_contents('php://input');

         $dataa = json_decode($jsonString,true);
        
         $con = new mysqli("localhost", "id2217679_00894_home_made", "teri@meri1", "id2217679_home_made") or 
         die(mysqli_error());
         foreach ($dataa as $key => $value) 
          {
              
		$userId   = $value['userId'];
		$maidId   = $value['maidId'];
		$rating   = $value['rating'];
                $review   = $value['review'];
                $date     = $value['date']; 

                
                $sql = "SELECT * FROM Miad_Rating WHERE UserId='".$userId."' and maidId='".$maidId."'";
                 $result = $con->query($sql);
                 if ($result->num_rows > 0)               
                 {
                    $sqli = "UPDATE Miad_Rating SET rating='".$rating."' , review='".$review."', date='".$date."'  
                                WHERE 
                              UserId='".$userId."' and maidId='".$maidId."'";

                        mysqli_query($con , $sqli );
                 }
                 else{
                      mysqli_query($con,"insert into 
                      Miad_Rating(UserId,maidId,rating,review,date) 
                      values('$userId','$maidId','$rating','$review','$date')");
                 }
                
                 $maidToatalRating  = getTotalRatingMaid($maidId,$con);
                 $maidavarageRating = getAverageMaidRating($maidToatalRating,$con,$maidId);
                 $fjson =array("status" => "success","averageRating" => $maidavarageRating,"maidRatingCount" => 
                             $maidToatalRating);

             
                 
          }	

         // Get Total Ratings
         function getTotalRatingMaid($maidId,$con)
          {  
             $numOfMaidRatings = 0;
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
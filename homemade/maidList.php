<?php
         $jsonString= file_get_contents('php://input');
         $dataa = json_decode($jsonString,true);
         $con = new mysqli("localhost", "id2217679_00894_home_made", "teri@meri1", "id2217679_home_made") or 
         die(mysqli_error());
         $MaidId = null;
         foreach ($dataa as $key => $value) 
          {
             $categoryId = $value['categoryId'];
             $userId     = $value['userId'];

             $mailIDsql = "SELECT MaidId FROM `Maid_Details` where WorkType='".$categoryId."'";
             $resultm = $con->query($mailIDsql);
             while($r = $resultm ->fetch_assoc()) 
                     {
                            $MaidId =  $r['MaidId'];
                     if($MaidId==null){ $MaidId=NA; }       

                                     $sql = "SELECT made_Id,Name,Phone_num,age,Work_Location,Religion,Cooking_Type,Experience,Photo,Verification,Work_Time,Local_Address,
Work_style,working_flat,verify_card_img,Rating,(SELECT cost FROM `Maid_Details` where WorkType='".$categoryId."' and MaidId ='".$MaidId."') as Cost FROM `Maid_Master` WHERE made_Id in ('".$MaidId."')";
         $result = $con->query($sql);
         if ($result->num_rows > 0)               
         {

                    while($r = $result->fetch_assoc()) 
                     {
                            $isfav = isFavUserMaid($userId,$MaidId,$categoryId,$con);
                            $maidCostList = getmaidCategoryCost($MaidId,$categoryId,$con);
                            
                            $maidRatingCount   = getTotalRatingMaid($MaidId,$con);
			   $maidAverageRating = getAverageMaidRating($maidRatingCount,$con,$MaidId);
							 
							 
                             $cost=    getRateConcat(getCategoryNameOverId($categoryId,$con),$r['Cost']);
                          $maidList[]= array("madeId" => $r['made_Id'], "name" => $r['Name'], 
                            "phoneNumber" =>$r['Phone_num'],"age" =>$r['age'],"workLocation" => $r['Work_Location'],
                            "religion" => $r['Religion'],"cookingType" => $r['Cooking_Type'],
                            "experience" => $r['Experience'],"photo" => $r['Photo'],
                            "verification" => $r['Verification'],"workTime" => $r['Work_Time'],
                            "cost" => $cost,"rating" => $maidAverageRating,"workStyle" => $r['Work_style'],
                            "localAddress" => $r['Local_Address'],"maidCostList" =>$maidCostList,
                            "workingFlat" => $r['working_flat'],"isFavourite" => $isfav,"maidVerificationImage" => 
                             $r['verify_card_img'],"maidRatingCount" => 
                             $maidRatingCount);          
                     }
                 
                      
         } 
                     }

          }
         
            if($MaidId!=null)
            { 
              $fjson =array("status" => "success" ,"maidList" => $maidList);
            }
            else{
                $maidList =json_encode (json_decode ("[]"));
                $maidList = json_decode($maidList);
                $fjson =array("status" => "success" ,"maidList" => $maidList);
             }
            mysqli_close($con);
 
           echo json_encode($fjson) ;   
          	

           

         function isFavUserMaid($userId,$maidId,$categoryId,$con)
          {
             $getUserFavsql = "select * from UserFavourateMaid where UserId='".$userId."' and MaidId='".$maidId."'";
             $result = $con->query($getUserFavsql);
             if ($result->num_rows > 0)               
             {
                return 'true';
             }
             else{
                   return 'false';
             }  
         }

          function getmaidCategoryCost($maidId,$categoryId,$con)
          {
             $getMaidCostDetails = "SELECT * FROM `Maid_Details` where MaidId='".$maidId."'";
                    $getMaidCostDetailsResult = $con->query($getMaidCostDetails); 
                    while($r3 = $getMaidCostDetailsResult ->fetch_assoc()) 
                    {       
                           $catName = getCategoryNameOverId($r3['WorkType'],$con);
                            $cost=    getRateConcat($catName,$r3['cost']);

                            $maidCostList[] = array("categoryId" => $r3['WorkType'], "cost" => $cost, "categoryName" => $catName);
                    }
                    return $maidCostList;
         }



         function getCategoryNameOverId($categoryId,$con)
          {
             $getcatNameSql = "SELECT * FROM `Category` where Id='".$categoryId."'";
                    $getcatNameSqlResult = $con->query($getcatNameSql); 
                    while($r3 = $getcatNameSqlResult ->fetch_assoc()) 
                    {       
                         $catName = $r3['Name'];
                        
                    }
                    return $catName;
         }


        
         // Get Total Ratings
         function getTotalRatingMaid($maidId,$con)
          {  $numOfMaid = 0;
             $getTotalRatingMaidSql = "SELECT * FROM `Miad_Rating` where maidId='".$maidId."'";
             if ($result=mysqli_query($con,$getTotalRatingMaidSql))
             {
                  // Return the number of rows in result set
                   $numOfMaid=mysqli_num_rows($result);
             }
             return $numOfMaid;
         }

         // Get Cost String Concat
         function getRateConcat($catName,$cost_param)
          {  
                         if($catName=='Floor Cleaning'){
                           $cost = $cost_param.' for 2BHK';
                          }else if($catName=='Utensils Wash'){
                           $cost = $cost_param.' per month';
                          }else{
                            $cost = $cost_param.' per person/month';
                          }
                         return $cost;
         }



        function getAverageMaidRating($totalRating,$con,$maidId){
		 
		     $ratingSum = 0;
		     $getTotalRatingMaidSql = "SELECT * FROM `Miad_Rating` where maidId='".$maidId."'";
			 $getTotalRatingMaidSqlResult = $con->query($getTotalRatingMaidSql);
			 while($r = $getTotalRatingMaidSqlResult ->fetch_assoc()) 
                         {       
                         $ratingSum = $ratingSum +(int)$r['rating'];
                        
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


  exit(0);

?>
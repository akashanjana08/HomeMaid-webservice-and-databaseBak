<?php
         $jsonString= file_get_contents('php://input');
         $dataa = json_decode($jsonString,true);
         $con = new mysqli("localhost", "id2217679_00894_home_made", "teri@meri1", "id2217679_home_made") or 
         die(mysqli_error());
         $maidList = null;
         foreach ($dataa as $key => $value) 
          {
             $userId = $value['userId'];
             $getUserFavsql = "select * from UserFavourateMaid where UserId='".$userId."'";
             $getUserFavResult = $con->query($getUserFavsql);
             while($r = $getUserFavResult ->fetch_assoc()) 
             {

                $getMaidMastersql = "SELECT made_Id,Name,Phone_num,age,Work_Location,Religion,Cooking_Type,Experience,Photo,Verification,Work_Time,Local_Address,
Work_style,working_flat,Rating,verify_card_img,(SELECT cost FROM `Maid_Details` where WorkType='".$r['categoryId']."' and MaidId ='".$r['MaidId']."') as Cost FROM `Maid_Master` WHERE made_Id in ('".$r['MaidId']."')";
            
                $getMaidMastersqlResult = $con->query($getMaidMastersql);
                 while($r2 = $getMaidMastersqlResult ->fetch_assoc()) 
                 {
                    $getMaidCostDetails = "SELECT * FROM `Maid_Details` where MaidId='".$r['MaidId']."'";
                    $getMaidCostDetailsResult = $con->query($getMaidCostDetails); 
                    while($r3 = $getMaidCostDetailsResult ->fetch_assoc()) 
                    {       
                            $catName = getCategoryNameOverId($r3['WorkType'],$con);
                            $cost=    getRateConcat($catName,$r3['cost']);
                            $maidCostList[] = array("categoryId" => $r3['WorkType'], "cost" => $cost, "categoryName" => $catName);
                          
                    }
                       $maidRatingCount   = getTotalRatingMaid($r2['made_Id'],$con);
                       $maidAverageRating = getAverageMaidRating($maidRatingCount,$con,$r2['made_Id']);
                       $maidList[]= array("madeId" => $r2['made_Id'], "name" => $r2['Name'], 
                            "phoneNumber" =>$r2['Phone_num'],"age" =>$r2['age'],"workLocation" => $r2['Work_Location'],
                            "religion" => $r2['Religion'],"cookingType" => $r2['Cooking_Type'],
                            "experience" => $r2['Experience'],"photo" => $r2['Photo'],
                            "verification" => $r2['Verification'],"workTime" => $r2['Work_Time'],
                            "cost" => $r2['Cost'],"rating" =>  $maidAverageRating,"workStyle" => $r2['Work_style'],
                            "maidCostList" =>$maidCostList,"localAddress" => $r2['Local_Address'],
                            "workingFlat" => $r2['working_flat'],"isFavourite" => 'true',"maidRatingCount" => 
                             $maidRatingCount,"maidVerificationImage" =>$r2['verify_card_img']);        
                             
                 }
                          $maidCostList =null;
                      // $madeFavList[] =array("madeFavList" => $maidList);
             }


          }
           if($maidList!=null)
		  {
		        $fjson =array("status" => "success" ,"maidList" => $maidList);
		  }
		  else{
		        $maidList =json_encode (json_decode ("[]"));
                $maidList = json_decode($maidList);
                $fjson =array("status" => "success" ,"maidList" => $maidList);
		  }
            echo json_encode($fjson) ;

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
<?php
          $add_images=array("http://homemade.hostzi.com/homemade/advertiseImages/ad_contact_one.jpg","http://homemade.hostzi.com/homemade/advertiseImages/ad_image_two.jpg","http://homemade.hostzi.com/homemade/advertiseImages/add_rg.jpg");
        
         $arrlength=count($add_images);

         for($x=0;$x<$arrlength;$x++)
          {
            $add_image[]= array("image" => $add_images[$x]);
         }         

          $fjson =array("status" => "success" ,"advertiseImages" => $add_image);
          echo json_encode($fjson) ;
          	
 
exit(0);

?>
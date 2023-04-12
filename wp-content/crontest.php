<?php
//date_default_timezone_set("America/New_York");
ini_set('max_execution_time', 1200); 



//$timestamp = strtotime($string);

//echo date("Y-m-d H:i:s", $timestamp);
//die();
$con = mysqli_connect("localhost","theshedu_cronjob","Cron@098765","theshedu_wp_rfbdz");


    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        die();
    }


        $sql="select * from af_posts where cflag is null";
 $result = mysqli_query($con, $sql) or die("Error in Selecting 1" . mysqli_error($con));
        if(mysqli_num_rows($result)>0){
                //$row = mysqli_fetch_array($result);
              
                  while($row =mysqli_fetch_assoc($result))
    {
       
      addrows(5,$row,$con);
   
      
       
 
  // die();

        
                  }

}

function addrows($n,$row,$con){

    $idup=$row['ID'];
    $sql_update="update af_posts set cflag='e' where ID='$idup'";
    $result_update = mysqli_query($con, $sql_update) or die("Error in Selecting 2" . mysqli_error($con));    
    
        $b1=$row['post_author'];
        $b2=$row['post_date'];
        $b3=$row['post_date_gmt'];
        $b4=$row['post_content'];
        $b5=$row['post_title'];
        $b6=$row['post_excerpt'];
        $b7=$row['post_status'];
        $b8=$row['comment_status'];
        $b9=$row['ping_status'];
        $b10=$row['post_password'];
        $b11=$row['post_name'];
        $b12=$row['to_ping'];
        $b13=$row['pinged'];
           $b14=$row['post_modified'];
        $b15=$row['post_modified_gmt'];
        $b16=$row['post_content_filtered'];
        $b17=$row['post_parent'];
        
         $b18=$row['guid'];
        $b19=$row['menu_order'];
        $b20=$row['post_type'];
        
          //$b21=$row['em_event'];
        $b22=$row['post_mime_type'];
        $b23=$row['comment_count'];
        $b24='w';
        $b25=$row['ID'];
        
        
    for($i=0;$i<$n;$i++)
    {
    
 
        
        
       $sql_insert="insert into af_posts (post_author,post_date,post_date_gmt,post_content,post_title,post_excerpt,post_status,comment_status,ping_status,post_password,post_name,to_ping,pinged,post_modified,post_modified_gmt,post_content_filtered,post_parent,guid,menu_order,post_type,post_mime_type,comment_count,cflag,ppid)values('$b1','$b2','$b3','$b4','$b5','$b6','$b7','$b8','$b9','$b10','$b11','$b12','$b13','$b14','$b15','$b16','$b17','$b18','$b19','$b20','$b22','$b23','$b24','$b25')";
       
      mysqli_query($con,$sql_insert);
       
    
       
          $sql_sel="select ID from af_posts where ppid='$b25'";
      $result_sel = mysqli_query($con, $sql_sel) or die("Error in Selecting 3" . mysqli_error($con));
      
         while($row_sel =mysqli_fetch_assoc($result_sel)){
              $ID=$row_sel['ID'];
             $get_sel="select * from af_postmeta where post_id='$b25'";
             $result_get = mysqli_query($con, $get_sel) or die("Error in Selecting " . mysqli_error($con));
                    while($row_get =mysqli_fetch_assoc($result_get)){
                        
                        $metakey=$row_get['meta_key'];
                        $metavalue=$row_get['meta_value'];
                        
                        if($metakey=='em_start_date' || $metakey=='em_end_date'){
                         
                        $date = new DateTime();
                        $date->setTimestamp($metavalue);
                        //Add 4 hours
                        //$date->modify('+ 4 hour');
                        $v=$i+1;
                         $period="+ ".$v." years";

                        $date->modify($period);
                        $sdate=$date->format('Y-m-d H:i:s');
                         $sdate=strtotime($sdate);
                        $sql_final="insert into af_postmeta (post_id,meta_key,meta_value)values('$ID','$metakey','$sdate') ";
                        }else {
                        $sql_final="insert into af_postmeta (post_id,meta_key,meta_value)values('$ID','$metakey','$metavalue') ";
                        }
                            mysqli_query($con,$sql_final);
                        
                        
                        
                    }
             
             
    } //end first while loop
       
       
      
    } //forloop 
    
    
} //function


?>
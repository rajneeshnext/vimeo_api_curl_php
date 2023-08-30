<?php
//Delete Vimeo videos if not loaded properly using a scheduled script
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.vimeo.com/me/videos",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
	"Accept: application/vnd.vimeo.*+json;version=3.4",
	"Authorization: bearer xxxxxxxxxxxxxxxxx",
	"Content-Type: application/json"
  ),
));
// It loads the last videos from Vimeo account.
$response = curl_exec($curl);
$data = json_decode($response, true);
$records = $data['data'];
foreach($records as $record)
{
    // loop through each video object
    $video_url = $record['link'];
    $vimeo_id = explode('/',$video_url);
    $vimeo_id = $vimeo_id[3];
    $created_time = date("Y-m-d H:i:s", strtotime($record['created_time']));
    $enddate = date("Y-m-d H:i:s");
    $starttimestamp = strtotime($created_time);
	$endtimestamp = strtotime($enddate);
	$difference = abs($endtimestamp - $starttimestamp)/60;
    //echo $record['status']."====".$vimeo_id."=====".$record['created_time']."=====hours====".$difference."=====";

   / check status and time set to 20min, delete if video is not available after 20min 
    if($record['status']!="available" && $difference>20){
            $curl = curl_init();
        	curl_setopt_array($curl, array(
        	  CURLOPT_URL => "https://api.vimeo.com/videos/".$vimeo_id,
        	  CURLOPT_RETURNTRANSFER => true,
        	  CURLOPT_ENCODING => "",
        	  CURLOPT_MAXREDIRS => 10,
        	  CURLOPT_TIMEOUT => 30,
        	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        	  CURLOPT_CUSTOMREQUEST => "DELETE",
        	  CURLOPT_HTTPHEADER => array(
        		"Accept: application/vnd.vimeo.*+json;version=3.4",
        		"Authorization: bearer xxxxxxxxxxxxxxxxxxxxxxxxxxxx",
        		"Content-Type: application/json"
        	  ),
        	));
        
        	$response = curl_exec($curl);
        	$err = curl_error($curl);
        	curl_close($curl);
    }
}
echo "<br/>";
$err = curl_error($curl);
curl_close($curl);

?>

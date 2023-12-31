<?php
  // Create a Vimdeo Form through an API call
	// Vimeo generates an iFrame form which facilitates the upload of later
		
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.vimeo.com/me/videos",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "\n{\n  \"upload\": {\n    \"approach\": \"post\",\n    \"size\": \"9000000\",\n    \"redirect_url\": \"https://anyurlToYourWebsite.com/manage/video_uploading.php\"\n  },\n  \"name\": \"Video Name\"\n}",
	  CURLOPT_HTTPHEADER => array(
		"Accept: application/vnd.vimeo.*+json;version=3.4",
		"Authorization: bearer xxxxxxxxxxxxxxxxxxxxxxx",
		"Content-Type: application/json"
	  ),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  $video_data = json_decode($response,true);
	  file_put_contents($path.'/vimeoPost/'.$_GET['pid'].'.txt', $video_data['embed']['html']);
	  $uri = $video_data['uri'];
	  $vimeoId = explode('/',$uri);
	  $_SESSION['vimeo_id'] = $vimeoId[2];
	  //Returns iFrame HTML form to upload a video direct to Vimeo
    $video =  $video_data['upload'][' '];
	}
   echo $html="<div class='custom-upload'> <h4>Click the browse button to locate and upload your video</h4> <p>$video</p> </div>";
?>

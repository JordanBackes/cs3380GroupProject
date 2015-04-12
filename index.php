<!DOCTYPE html>
<html>
<head>
<meta charset=UTF-8>
<title>Face++ App</title>

</head>
<body>
<form method="POST" action="/~cs3380s15grp6/project/index.php">
    
    Enter URL: <input type="text" name="query_string" value="" /> <br /><br />
    
    <input type="submit" name="submit" value="Submit" />
</form>

<p></p>

<?php
	//only use if button is pressed
	if (isset($_POST['submit'])) {

		//get api
		require_once 'facepp_sdk.php';

		//declare face class with key and secret
		$facepp = new Facepp();
		//keys and secret are generated from account on website
		$faceapp->api_key       = 'e008841d3f30cd1b005f5bd98866a1e9';
		$faceapp->api_secret    = 'D3u_LYApVChX762QsniMwhsFTwEgXy0h';

		//get input text from text box
		//example: http://www.faceplusplus.com.cn/wp-content/themes/faceplusplus/assets/img/demo/1.jpg
		//change the 1 above to a number between 1 and 20 for different examples 
		$url = htmlspecialchars($_POST['query_string']); 

		// code below for detecting local image and getting information
		//$params['img']          = '{image file path}';
		//$params['attribute']    = 'gender,age,race,smiling,glass,pose';
		//$response               = $facepp->execute('/detection/detect',$params);

		//set group name
		$group_name = 'Sample Group';
		//get the group info if it exists,
		//if it doesn't exist then create a new group 
		$response = $facepp->execute('/group/get_info', array("group_name" => $group_name));
		if($response['http_code'] != 200) {
		
			$response = $facepp->execute('/group/delete', array("group_name" => $group_name));
			$response = $facepp->execute('/group/create', array("group_name" => $group_name));
			echo "<p>Group created</p>\n";
		}
		
		//detect image by url and get image information
		$params['url']          = $url;
		$response               = $facepp->execute('/detection/detect',$params);

		//get photo data
		$faces_data = json_decode($response['body'], 1);
		
		//initialize random variables
		$face_count = 0;
		$person_count = 1;
		$found = false;

		//if detection was successfull
		if($response['http_code'] == 200) {
	
			//iterate through the faces of the photo
			foreach ($faces_data['face'] as $face) {
  				
  				$face_count++;
  				
  				//print face information
  				echo "Face: " . $face_count . "<p></p>\n";	
      			echo "<p> ID: " . $face['face_id'] . "</p>\n";
      			echo "<p>     Age: " . $face['attribute']['age']['value'] . "</p>\n";
      			echo "<p>     Age range: " . $face['attribute']['age']['range'] . "</p>\n";
      			echo "<p>     Gender: " . $face['attribute']['gender']['value'] . "</p>\n";
      			echo "<p>     Gender confidence: " . $face['attribute']['gender']['confidence'] . "</p>\n";
      			echo "<p>     Race: " . $face['attribute']['race']['value'] . "</p>\n";
  				echo "<p>     Race confidence: " . $face['attribute']['race']['confidence'] . "</p>\n";
  				echo "<p>     Smiling: " . $face['attribute']['smiling']['value'] . "</p>\n";
  			
  				echo "<p>     Position X: " . $face['position']['center']['x'] . "</p>\n";
  				echo "<p>     Position Y: " . $face['position']['center']['y'] . "</p>\n";
  						
  				//get group information and data
  				$response = $facepp->execute('/group/get_info', array('group_name' => $group_name));
  				
  				$group_data = json_decode($response['body'], 1);
  				
  				//iterate through each person in group
  				//this code searches through every person and checks if the scanned face belongs to an existing person
  				//if the new person does exist then a face is added to that person
  				//if the new person does not exist then a new person with that face is created and is added to the group
  				foreach ($group_data['person'] as $person) {
  					
  					//train person, this is required for recognition verification
  					train($facepp, $person['person_id']);
  					
  					//get recognition info and data
  					$response = $facepp->execute('/recognition/verify', array('face_id' => $face['face_id'],
  																			'person_id' => $person['person_id']));														
  					$person_data = json_decode($response['body'], 1);
  					
  					//only run if no error
  					if ($response['http_code'] == 200) {
  					
  						//print out recognition results for each person checked
  						//	echo "<p> Recognition confidence for person " . $person_count . ": " . $person_data['confidence'] . "</p>\n";
  						//	if ($person_data['is_same_person'] == false) echo "<p> Recognition status for person " . $person_count . ": false</p>\n";
	  					//	if ($person_data['is_same_person'] == true) echo "<p> Recognition status for person " . $person_count . ": true</p>\n";

  						//check if face belongs to existing person
  						//this is done by checking if is_same_person variable for person is true
  						//this also checks if confidence level is below 80% because is_same_person variable usually is wrong
  						if ($person_data['is_same_person'] || $person_data['confidence'] < 80) {
  							
  							//add face to matched person and break from loop
  							$response = $facepp->execute('/person/add_face', array('person_id' => $person['person_id'], 
																				'face_id' => $face['face_id']));
  							$found = true;
  							
  							echo "<p> Face was added to existing person: Person " . $person_count . "</p>\n";
  							break;
  						}
  					}
  					else echo "<p>Error in recognition</p>\n";
  					
  					$person_count++;
  				}
  				//this code runs if person was never found
  				if (!$found) {
  				
  					//new person is created, face is added to person then person is added to group
  					$response = $facepp->execute('/person/create', array('person_name' => 'Person ' . $person_count));
					$response = $facepp->execute('/person/add_face', array('person_name' => 'Person ' . $person_count, 
																			'face_id' => $face['face_id']));
  					$response = $facepp->execute('/group/add_person', array('group_name' => $group_name,
																		'person_name' => 'Person ' . $person_count));
																		  				
  					echo "<p>A new person, Person " . $person_count . ", was created with the face</p>\n";
  					$found = false;
  				}	
  			
  				$person_count = 0;
  			  	echo "<br/>\n";
  				echo "<p></p>\n";
  						
  			}
			echo "<p></p>\n There are " . $face_count . " faces <p></p>\n";
		}
		else echo "Error: " . $response['http_code'];
	}

	//function to train person	
	function train(&$api, $person_id) {
   		// train model
   		$response = $api->execute('/train/verify', array('person_id' => $person_id));
   		$session = json_decode($response['body'], 1);
		
		if (empty($session->session_id))
    	{
        	// something went wrong, skip
        	return false;
    	}
   	 	$session_id = $session->session_id;
    	// wait until training process done
    	while ($response = $api->execute('/info/get_session', array('session_id' => $session_id))) {
    		$session = json_decode($response['body'], 1);
        	sleep(1);
	
        	if (!empty($session->status)) {
            	if ($session->status != "INQUEUE") 
            		break;
        	}
    	}
		// done
    	return true;
	}
?>

</body>
</html>

<?php

$conn = mysqli_connect("localhost", "lakshay", "password","featureme");

$client_id = "49565012e4074700b018c3b589ee8a37";
$redirect_uri = "http://localhost/featureme";
$client_secret = "10932033lol238982did3820you208think084lol";
include('header.php');

echo "<a id='mainbutton' href='https://api.instagram.com/oauth/authorize/?client_id=$client_id&redirect_uri=$redirect_uri&response_type=code'>Click here to feature your profile</a><br><br>";

include('body.php');

if(isset($_GET['reportfeatured'])){
	mysqli_query($conn, "delete from images");
	$reported_user = mysqli_fetch_array(mysqli_query($conn, "select username from currentuser order by user_number desc limit 1"))[0];
	mail("contact@featureme.ml","User report!","User reported! Username = $reported_user");
	echo "<script>alert('Images are deleted from the server and will not be shown on the website anymore. If you can still see the images, try refreshing the page. Thanks!')</script>";
}


if(isset($_GET['code'])){
	$token_url = "https://api.instagram.com/oauth/access_token";
	$access_token_params = array(
		'client_id' => $client_id,
		'client_secret' => $client_secret,
		'grant_type' => 'authorization_code',
		'redirect_uri' => $redirect_uri,
		'code' => $_GET['code']
	);

	$curl = curl_init($token_url);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $access_token_params);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$curl_result = json_decode(curl_exec($curl),true);
	//print_r($curl_result);
	$username = $curl_result['user']['username'];
	$profile_pic = $curl_result['user']['profile_picture'];
	$full_name = $curl_result['user']['full_name'];
	$bio = $curl_result['user']['bio'];

	$insert_user_query = "insert into currentuser(username, profile_pic, full_name, bio) values('$username','$profile_pic','$full_name','$bio')";
	mysqli_query($conn, $insert_user_query);
	mysqli_query($conn, "delete from images");
	$access_token = $curl_result['access_token'];

	$recent_media = json_decode(file_get_contents("https://api.instagram.com/v1/users/self/media/recent/?access_token=".$access_token),true);
	foreach($recent_media['data'] as $image){
		$img_url = $image['images']['standard_resolution']['url'];
		mysqli_query($conn, "insert into images values('$img_url')");
		//echo "<img src='$img_url'/><br>";
	}
	echo "<script>window.location='http://featureme.ml'</script>";

}




//echo"<a href='https://api.instagram.com/oauth/authorize/?client_id=$client_id&redirect_uri=$redirect_uri&response_type=token'>Click to replace profile</a><br>";


/*
if($_SERVER)
	$recent_media = json_decode(files_get_content("https://api.instagram.com/v1/users/self/media/recent/?access_token=".$_GET['access_token']),true);


	foreach($recent_media['data'] as $image){
		$img_url = $image['images']['standard_resolution']['url'];
		echo "<img src='$img_url'/><br>";
	}
}
*/


include('footer.php');
?>

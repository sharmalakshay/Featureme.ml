<body>
<br><br>
<div id="profile">
    <?php

    $profile_query_result = mysqli_query($conn, "select * from currentuser order by user_number desc limit 1");
    while($profile = mysqli_fetch_assoc($profile_query_result)){
        $db_username = $profile['username'];
        $db_profile_pic = $profile['profile_pic'];
        $db_fullname = ucwords($profile['full_name']);
        $db_bio = $profile['bio'];
    
        echo "<img id='profilepic' src='$db_profile_pic'/><br>";
        echo "<a id='user' href='https://instagram.com/$db_username'>instagram.com/$db_username</a><br>";
        echo "<b id='fullname'>$db_fullname</b><br>";
        echo "<span id='bio'>$db_bio</span>";
    }
    ?>
</div>
<br><br>

<a id='removebutton' href='http://featureme.ml/?reportfeatured=1'>Click here if the profile featured is not appropriate.</a>

<div id="pictures">

<?php
$image_database = mysqli_query($conn, "select * from images");
	while($row = mysqli_fetch_assoc($image_database)){
		$link = $row['link'];
		echo "<div class='pic'><img src='$link'/></div>";
    }
?>

</div>

</body>
<?php
session_start();
include 'twitter.php';
$twitter = new Twitter('jGrDrZUnUQAkGeGdsZo7Og', 'n44LC4FcDsPUyWkkjcR00Dtz1ZncRHAlQSPOE0wsIs');
$twitter->setOAuthToken($_SESSION['oauth_token']);
$twitter->setOAuthTokenSecret($_SESSION['oauth_token_secret']);

foreach($_REQUEST['to_delete'] as $delete_id)
{
	$twitter->directMessagesDestroy($delete_id);
}

echo 'success';
?>
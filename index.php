<?php
include 'twitter.php';

// create instance
$twitter = new Twitter('jGrDrZUnUQAkGeGdsZo7Og', 'n44LC4FcDsPUyWkkjcR00Dtz1ZncRHAlQSPOE0wsIs');

// get a request token
$twitter->oAuthRequestToken('http://caleb.wasabi.cc/dm_deleter');

// authorize
if(!isset($_GET['oauth_token'])) $twitter->oAuthAuthorize();

// get tokens
$response = $twitter->oAuthAccessToken($_GET['oauth_token'], $_GET['oauth_verifier']);

$twitter->setOAuthToken($response['oauth_token']);
$twitter->setOAuthTokenSecret($response['oauth_token_secret']);

$dms = $twitter->directMessages(null, null, 200, 3);
echo '<pre>';
var_dump($dms);
echo '</pre>';
?>
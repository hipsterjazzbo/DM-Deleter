<?php
session_start();
include 'twitter.php';
$twitter = new Twitter('jGrDrZUnUQAkGeGdsZo7Og', 'n44LC4FcDsPUyWkkjcR00Dtz1ZncRHAlQSPOE0wsIs');

if(isset($_SESSION['oauth_token']) && isset($_SESSION['oauth_token_secret']))
{	
	$twitter->setOAuthToken($_SESSION['oauth_token']);
	$twitter->setOAuthTokenSecret($_SESSION['oauth_token_secret']);
}

else
{
	$twitter->oAuthRequestToken('http://caleb.wasabi.cc/dm_deleter');
	if(!isset($_GET['oauth_token'])) $twitter->oAuthAuthorize();
	$response = $twitter->oAuthAccessToken($_GET['oauth_token'], $_GET['oauth_verifier']);
	
	$_SESSION['oauth_token'] = $response['oauth_token'];
	$_SESSION['oauth_token_secret'] = $response['oauth_token_secret'];
	$twitter->setOAuthToken($_SESSION['oauth_token']);
	$twitter->setOAuthTokenSecret($_SESSION['oauth_token_secret']);
}

$dms = array();

for($i = 1; $i <= 25; $i++)
{
	$dms = array_merge($dms, $twitter->directMessages(null, null, 200, $i));
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>DM Deleter</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
	<script>
	$(function(){
		$('.select_all').change(function(){
			if($(this).is(':checked'))
			{
				$('.checker').attr('checked', true).trigger('change');
			}
			
			else
			{
				$('.checker').attr('checked', false).trigger('change');
			}
		});
		
		$('#the_form').submit(function(){
			return false;
		});
		
		$('.go').click(function(){
			var to_delete = $('#the_form').serializeArray();
			$('.checker, .select_all').attr('disabled', 'disabled');
			
			$.post('delete.php', to_delete, function(){
				//alert('wham');
				$('.checker:checked').parent().parent().slideUp().remove();
				$('.checker, .select_all').removeAttr('disabled');
			});
		});
	});
	</script>
</head>
<body>
	<form method="post" action="#" id="the_form">
	<table border="0" cellspacing="0" cellpadding="5">
		<thead style="background-color: #ccc;">
			<tr>
				<th><input type="checkbox" class="select_all" value="true" /></th>
				<th>Received</th>
				<th>From</th>
				<th>Message (<?=count($dms)?> total)</th>
				<th><input type="button" class="go" value="Do it!" /></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($dms as $dm): ?>
			<tr>
				<td><input type="checkbox" name="to_delete[]" class="checker" value="<?=$dm['id']?>" /></td>
				<td><?=date('j/n/Y', strtotime($dm['created_at']))?></td>
				<td><?=$dm['sender']['name']?> (<?=$dm['sender']['screen_name']?>)</td>
				<td colspan="2"><?=$dm['text']?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
		<tfoot style="background-color: #ccc;">
			<tr>
				<th><input type="checkbox" class="select_all" value="true" /></th>
				<th>Received</th>
				<th>From</th>
				<th>Message</th>
				<th><input type="button" class="go" value="Do it!" /></th>
			</tr>
		</tfoot>
	</table>
	</form>
	
	<ul id="checked"></ul>
</body>
</html>
<?php

/*echo '<pre>';
var_dump($dms);
echo '</pre>';*/
?>
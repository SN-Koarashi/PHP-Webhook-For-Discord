<?php
require_once('./class.webhook.public.php');
$fields = array();

array_push($fields,array(
	'name'=>"fields",
	'value'=>"fields content",
	'inline'=>true
));

$wh = new DiscordWebhook();
$wh->webhookURL = "YOUR WEBHOOK URL";
$wh->description = "TEST FOR HERE";
$wh->username = "Webhook API";
$wh->icon_url = "";
$wh->avatar_url = "";
$wh->title = "Hello world";
$wh->fields = $fields;
$wh->footer = "Footer here";
$wh->author = 'Example';
$em = $wh->getEmbeds();

$wh->sendMessage($em);
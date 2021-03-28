<?php

$dir = dirname(__FILE__) .'/';
$files = [
	'User.php',
	'Chat.php',
	'Bot.php',

	'Keyboards/Keyboard.php',
	'Keyboards/InlineKeyboard.php',

	'Payments/Stripe.php',

	'Sticker.php',

	'Elements/Base.php',
	'Elements/Voice.php', // REQUIRED for priority
];

foreach(scandir($dir .'Elements/') as $file){
	if(substr($file, -4) != ".php"){ continue; }
	$files[] = 'Elements/' .$file;
}

$files[] = 'Receiver.php';
$files[] = 'Sender.php';

foreach($files as $file){
	require_once $dir .$file;
}

unset($dir);
unset($files);

?>

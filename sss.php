<?php
    const TOKEN = '1043515481:AAEv3rCKCnDC89dpHAVQGqak0OpFbjhimLA';
$method = 'setWebhook';
$url = 'https://api.telegram.org/bot' . TOKEN . '/' . $method;
$options = ['url' => ВАШ АДРЕС];
$response = file_get_contents(
$url . '?' . http_build_query($options)
);
?>

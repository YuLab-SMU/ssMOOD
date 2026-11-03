<?php

//------------------------------------------------------------------------------
// Remove this code for release; only used in production and test environments.
// CORS configuration
header("Access-Control-Allow-Origin: *");
//------------------------------------------------------------------------------


header('Content-Type: application/json');
// Database configuration
$servername = "localhost";

/*
/etc/ssmood/db.php example:
<?php
return [
    'user' => 'database username',
    'pass' => 'database password',
];

Set permissions to prevent leaks
sudo chown www-data:www-data /etc/ssmood/db.php
sudo chmod 600       /etc/ssmood/db.php
*/

$creds = require '/etc/ssmood/db.php';
$username = $creds['user'];
$password = $creds['pass'];
$dbname = "ssmood";
$logFile = "./logfile.log";

// Kimi
//$openaiApiKey = 'sk-xxx'; 
//$url = 'https://api.moonshot.cn/v1/chat/completions';
//$model = 'moonshot-v1-8k';

//DeepSeek-Chat
//$openaiApiKey = 'sk-xxx'; 
//$url = 'https://api.deepseek.com/v1/chat/completions';
//$model = 'deepseek-chat';

// Doubao
$openaiApiKey = 'xxx'; 
$url = 'https://ark.cn-beijing.volces.com/api/v3/chat/completions';
$model = 'doubao-1-5-pro-32k-250115';
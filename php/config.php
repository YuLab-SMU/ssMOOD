<?php

//------------------------------------------------------------------------------
// 发布时去掉此处代码，仅用于生产和测试环境。
// 跨域配置
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
//------------------------------------------------------------------------------


header('Content-Type: application/json');
// 数据库配置
$servername = "localhost";


/*
/etc/ssmood/db.php示例：
<?php
return [
    'user' => '数据库用户名',
    'pass' => '数据库密码',
];

设置权限，避免泄漏
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

// 豆包
$openaiApiKey = 'xxx'; 
$url = 'https://ark.cn-beijing.volces.com/api/v3/chat/completions';
$model = 'doubao-1-5-pro-32k-250115';
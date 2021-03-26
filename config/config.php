<?php

//タイムゾーン
define('TIME_ZONE','Asia/Tokyo');
date_default_timezone_set(TIME_ZONE);

define('APPNAME','memoAPP');
define('URLROOT','http://localhost/memoApp/');
define('APPROOT','/memoApp');

// DB Params
define('DB_HOST','localhost');
define('DB_USER','testuser');
define('DB_PASS','testuser');
define('DB_NAME','memoApp');

//name属性に対応する日本語
define('ATTR_JA',[
  'email' => 'メールアドレス',
  'password' => 'パスワード',
  'confirmPassword' => '確認用パスワード',
  'cat_name' => 'メモのカテゴリー名',
  'heading' => 'メモの見出し',
  'content' => '内容'
]);

//ログインから1週間後にログアウト
define('SESSION_TIMEOUT',604800);
<?php
require_once './config.php' ;
require_once 'HTTP/Request.php' ;
global $image_files ;

// 投稿キーのチェック
if (defined('SECRET') && SECRET !== $_GET['key'])
{
    result('投稿キーが不正です。') ;
}

// モードチェック
if ($_GET['mode'] === 'api')
{
    $api_mode = true ;
}

// 画像番号のチェック
$img_index = $_GET['image'] ;
if (!preg_match('/^[0-9]+$/', $img_index) ||
    $img_index < 0 || count($image_files) <= $img_index)
{
    result('画像番号が不正です。') ;
}

// 書き換えを行う
if (post($img_index))
{
    result('画像を変更しました。', true) ;
}
exit ;


function post($img_index)
{
    global $image_files ;

    $url = 'http://twitter.com/account/update_profile_image.json' ;

    $file_name = $image_files[$img_index] ;
    if (!file_exists($file_name))
    {
        result('画像ファイル名が不正です。') ;
    }

    $pathinfo = pathinfo($file_name) ;
    $extension = strtolower($pathinfo['extension']) ;
    switch ($extension)
    {
        case 'png' :
        case 'gif' :
        {
            // do nothing.
            break ;
        }
        case 'jpeg' :
        case 'jpg' :
        {
            $extension = 'jpg' ;
            break ;
        }
        default :
        {
            result('画像の拡張子が不正です。') ;
        }
    }

    $request = new HTTP_Request($url) ;
    $request->setMethod(HTTP_REQUEST_METHOD_POST) ;
    $request->setBasicAuth(USERNAME, PASSWORD) ;

    $result = $request->addFile('image', $file_name, "image/{$extension}") ;
    if (PEAR::isError($result))
    {
        result($result->getMessager()) ;
    }

    $response = $request->sendRequest() ;
    if (PEAR::isError($response))
    {
        result($response->getMessage()) ;
    }
    $body = $request->getResponseBody() ;

    return true ;
}

function result($msg, $succeeded = false)
{
	global $api_mode ;
    if ($api_mode)
    {
        die($succeeded ? 'OK' : 'NG') ;
    }
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>エラーが発生しました</title>
</head>
<body>
<h1><?php echo $msg ?></h1>
<a href="index.php">戻る</a>
</body>
</html>
<?php
    exit ;
}

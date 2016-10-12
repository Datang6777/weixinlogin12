<?php
require_once("config.php");
$returnurl = urlencode("http://bm.huatu.com/zt/weixinlogin/callback.php");
//$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.APPID.'&redirect_uri=http://bm.huatu.com/zt/weixinlogin/callback.php&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
$url = 'https://open.weixin.qq.com/connect/qrconnect?appid='.APPID.'&redirect_uri='.$returnurl.'&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect';
header("Location:".$url);

?>

<?php
require_once("config.php");
require_once("functions.php");
require_once("../../include/common.inc.php");
require_once("../../include/memberlogin.class.php");
 $code = $_GET['code'];
if(isset($code)){
 $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxfeb1976a94110e83&secret=f1574124e252abc8418c314d4b3994e6&code=".$code."&grant_type=authorization_code";
$res = httpsrequest($url);
$json_obj=json_decode($res, true);
$access_token = $json_obj['access_token']; 
$opeid = $json_obj['openid'];
if(isset($opeid)&&isset($access_token)){
    $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$opeid.'&lang=zh_CN';  
	echo $getuserinfo = httpsrequest($get_user_info_url);
	$userinfo=json_decode($getuserinfo, true);
	$dsql = $db = new DedeSql(FALSE);
$rs = $dsql->GetOne("Select * From `#@__member` where wxkey ='".$opeid."' ");
$kptime==-1;
	$M_KeepTime = 3600;
$cfg_ml = new MemberLogin($M_KeepTime);

if(!empty($rs[mid])) {

$cfg_ml->M_ID = $rs[mid];
$cfg_ml->M_LoginTime = $rs[logintime];


$cfg_ml->M_ID = $rs[mid];
$cfg_ml->M_LoginID = $rs[userid];
$cfg_ml->M_LoginTime = $rs[logintime];  
PutCookie('DedeUserID',$rs[mid],$M_KeepTime);
PutCookie('DedeLoginTime',$rs[logintime],$M_KeepTime);
PutCookie('DedeUsername',$rs[userid],$M_KeepTime); 
$cfg_ml->PutLoginInfo($rs[mid],$rs[logintime],$rs[userid]); 
			
				
 header('Location: http://bm.huatu.com/member/');


}else{

// ����Ƿ��¼ ���Ѿ���¼���˺� ���а�  

 $jointime = time();
	$logintime = time();
	$joinip = GetIP();
	$loginip = GetIP();

	
	$spaceSta = 2;
	$mtype='����';
	
	$new_userid="htwx".rand (100,200000);
	$userid=$new_userid;
	$pwd="111111";
	$pwd = md5($pwd);
	$email = $new_userid."@qq.com";
	$uname=iconv("utf-8","gbk",$userinfo["nickname"]);
	$sex = "";
	if($userinfo['sex']==1){
	  $sex="��";
	}else{
	  $sex = "Ů";
	}
	
	$mid =0;
	$inQuery = "INSERT INTO `#@__member` (`mtype` ,`userid` ,`pwd` ,`uname` ,`sex` ,`rank` ,`money` ,`email` ,`scores` ,
	`matt`, `spacesta` ,`face`,`safequestion`,`safeanswer` ,`jointime` ,`joinip` ,`logintime` ,`loginip` ,`wxkey` )
   VALUES ('$mtype','$userid','$pwd','$uname','$sex','10','$dfmoney','$email','$dfscores',
   '0','$spaceSta','','$safequestion','$safeanswer','$jointime','$joinip','$logintime','$loginip','$opeid'); ";
	if($dsql->ExecuteNoneQuery($inQuery))
	{
		$mid = $dsql->GetLastID();

		//д��Ĭ�ϻ�Ա��ϸ����
		if($mtype=='����')
		{
			$infosquery = "INSERT INTO `#@__member_person` (`mid` , `onlynet` , `sex` , `uname` , `qq` , `msn` , `tel` , `mobile` , `place` , `oldplace` ,
	           `birthday` , `star` , `income` , `education` , `height` , `bodytype` , `blood` , `vocation` , `smoke` , `marital` , `house` ,
	            `drink` , `datingtype` , `language` , `nature` , `lovemsg` , `address`,`uptime`)
             VALUES ('$mid', '1', '{$sex}', '{$uname}', '', '', '', '', '0', '0',
              '1980-01-01', '1', '0', '0', '160', '0', '0', '0', '0', '0', '0','0', '0', '', '', '', '','0'); ";
			$space='person';
		}
		else if($mtype=='��ҵ')
		{
			$infosquery = "INSERT INTO `#@__member_company`(`mid`,`company`,`product`,`place`,`vocation`,`cosize`,`tel`,`fax`,`linkman`,`address`,`mobile`,`email`,`url`,`uptime`,`checked`,`introduce`)
                VALUES ('{$mid}','{$uname}','product','0','0','0','','','','','','{$email}','','0','0',''); ";
			$space='company';
		}
		else
		{
			$infosquery = '';
			$space='person';
		}
		/** �˴����Ӳ�ͬ����Ա���������ݴ���sql��� **/

		$dsql->ExecuteNoneQuery($infosquery);

		//д��Ĭ��ͳ������
		$membertjquery = "INSERT INTO `#@__member_tj` (`mid`,`article`,`album`,`archives`,`homecount`,`pagecount`,`feedback`,`friend`,`stow`)
               VALUES ('$mid','0','0','0','0','0','0','0','0'); ";
		$dsql->ExecuteNoneQuery($membertjquery);

		//д��Ĭ�Ͽռ���������
		$spacequery = "Insert Into `#@__member_space`(`mid` ,`pagesize` ,`matt` ,`spacename` ,`spacelogo` ,`spacestyle`, `sign` ,`spacenews`)
	            Values('{$mid}','10','0','{$uname}�Ŀռ�','','$space','',''); ";
		$dsql->ExecuteNoneQuery($spacequery);

		//д������Ĭ������
		$dsql->ExecuteNoneQuery("INSERT INTO `#@__member_flink`(mid,title,url) VALUES('$mid','֯�����ݹ���ϵͳ','http://www.dedecms.com'); ");
		}

		$cfg_ml->M_ID = $mid;
		$cfg_ml->M_LoginTime = $logintime;
		$cfg_ml->M_LoginID = $userid;
		$cfg_ml->PutLoginInfo($mid,$logintime,$userid); 
		PutCookie('DedeUserID',$mid,$M_KeepTime);

		PutCookie('DedeLoginTime',$logintime,$M_KeepTime);
		PutCookie('DedeUsername',$userid,$M_KeepTime); 
  header('Location: http://bm.huatu.com/member/');

}
}



}else{
echo "NO CODE";
}




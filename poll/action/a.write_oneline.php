<?php
if(!defined('__KIMS__')) exit;

if (!$my['uid']) getLink('','','권한이 없는 접근입니다.','');
$C = getUidData($table[$m.'comment'],$parent);
if (!$C['uid']) getLink('','','부모댓글이 지정되지 않았습니다. parent='.$parent,'');

include_once $g['dir_module'].'var/var.php';
include_once $g['dir_module'].'var/var.'.$bid.'.php';

$parentmbr	= $C['mbruid'];
$mbruid		= $my['uid'];
$id				= $my['id'];
$name			= $my['uid'] ? $my['name'] : trim($name);
$nic				= $my['uid'] ? $my['nic'] : $name;
$pw				= $pw ? md5($pw) : ''; 
$content		= trim($content);
$html			= $html ? $html : 'TEXT';
$singo			= 0;
//$point			= $d['comment']['give_opoint'];
$d_regis		= $date['totime'];
$d_modify	= '';
$d_oneline	= '';
$ip				= $_SERVER['REMOTE_ADDR'];
$agent			= $_SERVER['HTTP_USER_AGENT'];
$adddata		= trim($adddata);


if( !$content )	getLink('', '', '내용을 입력해 주세요');

// BSKR - 제한단어 체크
if ($d['bbs']['badword_action'])
{
	$badwordarr = explode(',' , $d['bbs']['badword']);
	$badwordlen = count($badwordarr);
	for($i = 0; $i < $badwordlen; $i++)
	{
		if(!$badwordarr[$i]) continue;

		if(strstr($content,$badwordarr[$i]))
		{
			if ($d['bbs']['badword_action'] == 1)
			{
				getLink('','','등록이 제한된 단어를 사용하셨습니다.','');
			}
			else {
				$badescape = strCopy($badwordarr[$i],$d['bbs']['badword_escape']);
				$content = str_replace($badwordarr[$i],$badescape,$content);
			}
		}
	}
}

if ($uid)
{
	getLink('','','지원하지 않는 동작입니다.','');
	/***
	$O = getUidData($table[$m.'oneline'],$uid);
	if (!$O['uid']) getLink('','','존재하지 않는 한줄의견입니다.','');

	$QVAL = "hidden='$hidden',content='$content',html='$html',d_modify='$d_regis',adddata='$adddata'";
	getDbUpdate($table[$m.'oneline'],$QVAL,'uid='.$O['uid']);
	***/
}
else 
{
	$maxuid = getDbCnt($table[$m.'oneline'],'max(uid)','');
	$uid = $maxuid ? $maxuid+1 : 1;
	
	$QKEY = "uid,site,parent,parentmbr,hidden,name,nic,mbruid,id,content,html,singo,point,d_regis,d_modify,ip,agent,adddata";
	$QVAL = "'$uid','$s','$parent','$parentmbr','$hidden','$name','$nic','$mbruid','$id','$content','$html','$singo','$point','$d_regis','$d_modify','$ip','$agent','$adddata'";
	getDbInsert($table[$m.'oneline'],$QKEY,$QVAL);
	getDbUpdate($table[$m.'data'],'oneline=oneline+1','uid='.$post);	
	getDbUpdate($table[$m.'comment'],"oneline=oneline+1,d_oneline='".$d_regis."'",'uid='.$parent);
	//getDbUpdate($table['s_numinfo'],'oneline=oneline+1',"date='".$date['today']."' and site=".$s);

	if ($uid == 1) db_query("OPTIMIZE TABLE ".$table[$m.'oneline'],$DB_CONNECT); 

	/***
	// 현재 지원하지 않음
	if ($point&&$my['uid'])
	{
		getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$my['uid']."','0','".$point."','한줄의견(".getStrCut(str_replace('&amp;',' ',strip_tags($content)),15,'').")포인트','".$date['totime']."'");
		getDbUpdate($table['s_mbrdata'],'point=point+'.$point,'memberuid='.$my['uid']);
	}
	***/
	
	$link = $g['s'].'/?r='.$r.'&c='.$c.'&m='.$m.'&bid='.$bid.'&uid='.$post.'&pos=oline-'.$uid.'&mod=comment&iframe=Y&p='.$p;
	getLink($link, 'parent.', '', '');
}
?>
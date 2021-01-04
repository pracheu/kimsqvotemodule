<?php
if(!defined('__KIMS__')) exit;

if (!$my['uid']) 
	getLink('','','잘못된 요청입니다.','');

$R = getUidData($table[$m.'data'],$uid);
if (!$R['uid'])
	getLink('','','정상적인 접근이 아닙니다.','');

include_once $g['dir_module'].'var/var.'.$R['bbsid'].'.php';

if( $my['admin'] && $my['uid']==0 )						$isAdmin	= 'super';
else if( $my['admin'] && $my['uid']!=0 )				$isAdmin	= 'admin';
else if( !$my['admin'] && strstr(','.($d['bbs']['admin']?$d['bbs']['admin']:'.').',',','.$my['id'].',') )	$isAdmin = 'bbs';

if( !$isAdmin && $my['uid']!=$R['mbruid'] )
{
	if ($my['point'] < $d['bbs']['point2'])
	{
		getLink('','','회원님의 보유포인트가 열람포인트보다 적습니다.','');
	}

	getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$my['uid']."','0','-".$d['bbs']['point2']."','게시물열람(".getStrCut($R['subject'],15,'').")','".$date['totime']."'");
	getDbUpdate($table['s_mbrdata'],'point=point-'.$d['bbs']['point2'].',usepoint=usepoint+'.$d['bbs']['point2'],'memberuid='.$my['uid']);
	getDbUpdate($table[$m.'data'],'hit=hit+1','uid='.$R['uid']);
	
	$_SESSION['module_'.$m.'_view'] .= '['.$R['uid'].']';	
	$UT = getDbData($table[$m.'xtra'],'parent='.$R['uid'],'*');
	if( !$UT['parent'] )
		getDbInsert($table[$m.'xtra'],'parent,site,bbs,point2',"'".$R['uid']."','".$s."','".$R['bbs']."','[".$my['uid']."]'");
	else
		getDbUpdate($table[$m.'xtra'],"point2='".$UT['point2']."[".$my['uid']."]'",'parent='.$R['uid']);
		
	getLink('reload','parent.','결제되었습니다.','');
}
else 
{	
	getDbUpdate($table[$m.'data'],'hit=hit+1','uid='.$R['uid']);
	$_SESSION['module_'.$m.'_view'] .= '['.$R['uid'].']';
	
	if ($my['uid'] == $R['mbruid'])
	{
		getLink('reload','parent.','게시물 등록회원님으로 인증되셨습니다.','');
	}
	else 
	{
		$UT = getDbData($table[$m.'xtra'],'parent='.$R['uid'],'*');
		if( !$UT['parent'] )
			getDbInsert($table[$m.'xtra'],'parent,site,bbs,point2',"'".$R['uid']."','".$s."','".$R['bbs']."','[".$my['uid']."]'");
		else
			getDbUpdate($table[$m.'xtra'],"point2='".$UT['point2']."[".$my['uid']."]'",'parent='.$R['uid']);
	
		getLink('reload','parent.','관리자님으로 인증되셨습니다.','');
	}
}
?>
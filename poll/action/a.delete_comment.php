<?php
if(!defined('__KIMS__')) exit;

// 게시판 정보 확인
if (!$bid) getLink('','','게시판 아이디가 지정되지 않았습니다.','');
$B = getDbData($table[$m.'list'],"id='".$bid."'",'*');
if (!$B['uid']) getLink('','','존재하지 않는 게시판입니다.','');

include_once $g['dir_module'].'var/var.php';
include_once $g['dir_module'].'var/var.'.$B['id'].'.php';

$bbsuid			= $B['uid'];
$bbsid				= $B['id'];
$mbruid			= $my['uid'];
$id					= $my['id'];
$name				= $my['uid'] ? $my['name'] : trim($name);
$nic					= $my['uid'] ? $my['nic'] : $name;
$parent			= trim($parent);
$post 				= str_replace('bbs', '', $parent);		// 'bbs...' 에서 문자열 bbs 제거 

// 댓글 정보 확인
$C = getUidData($table[$m.'comment'],$uid);
if (!$C['uid']) getLink('','','존재하지 않는 댓글입니다.','');


// 삭제권한 확인 (비회원, 비밀번호)
if( $my['admin'] && $my['uid']==0 )						$isAdmin	= 'super';
else if( $my['admin'] && $my['uid']!=0 )				$isAdmin	= 'admin';
else if( !$my['admin'] && strstr(','.($d['bbs']['admin']?$d['bbs']['admin']:'.').',',','.$my['id'].',') )	$isAdmin = 'bbs';

if( $my['uid']!=$C['mbruid'] && !$isAdmin )
{
	if (!$pw)
		getLink('','','정상적인 접근이 아닙니다.','');
	else 
	{
		if(md5($pw) != $C['pw'])
			getLink('','','올바른 비밀번호가 아닙니다.','');
	}
}


if ($d['bbs']['c_onelinedel'])
{
	if($C['oneline'])
	{
		getLink('','','한줄의견이 있는 댓글은 삭제할 수 없습니다.','');
	}
}


//첨부파일삭제
/***
if ($C['upload'])
{
	include_once $g['path_module'].'upload/var/var.php';
	$UPFILES = getArrayString($C['upload']);

	foreach($UPFILES['data'] as $_val)
	{
		$U = getUidData($table['s_upload'],$_val);
		if ($U['uid'])
		{
			getDbUpdate($table['s_numinfo'],'upload=upload-1',"date='".substr($U['d_regis'],0,8)."' and site=".$U['site']);
			getDbDelete($table['s_upload'],'uid='.$U['uid']);

			if ($U['url']==$d['upload']['ftp_urlpath'])
			{
				$FTP_CONNECT = ftp_connect($d['upload']['ftp_host'],$d['upload']['ftp_port']); 
				$FTP_CRESULT = ftp_login($FTP_CONNECT,$d['upload']['ftp_user'],$d['upload']['ftp_pass']); 
				if (!$FTP_CONNECT) getLink('','','FTP서버 연결에 문제가 발생했습니다.','');
				if (!$FTP_CRESULT) getLink('','','FTP서버 아이디나 패스워드가 일치하지 않습니다.','');

				ftp_delete($FTP_CONNECT,$d['upload']['ftp_folder'].$U['folder'].'/'.$U['tmpname']);
				if($U['type']==2) ftp_delete($FTP_CONNECT,$d['upload']['ftp_folder'].$U['folder'].'/'.$U['thumbname']);
				ftp_close($FTP_CONNECT);
			}
			else {
				unlink($g['path_file'].$U['folder'].'/'.$U['tmpname']);
				if($U['type']==2) unlink($g['path_file'].$U['folder'].'/'.$U['thumbname']);
			}
		}
	}
}
***/

//한줄의견삭제
if ($C['oneline'])
{
	$_ONELINE = getDbSelect($table[$m.'oneline'],'parent='.$C['uid'],'*');
	while($_O=db_fetch_array($_ONELINE))
	{
		getDbDelete($table[$m.'oneline'],'parent='.$C['uid']);
		getDbUpdate($table[$m.'data'],'oneline=oneline-1','uid='.$post);
		//getDbUpdate($table['s_numinfo'],'oneline=oneline-1',"date='".substr($_O['d_regis'],0,8)."' and site=".$_O['site']);

		if ($_O['point']&&$_O['mbruid'])
		{
			getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$_O['mbruid']."','0','-".$_O['point']."','한줄의견삭제(".getStrCut(str_replace('&amp;',' ',strip_tags($_O['content'])),15,'').")환원','".$date['totime']."'");
			getDbUpdate($table['s_mbrdata'],'point=point-'.$_O['point'],'memberuid='.$_O['mbruid']);
		}
	}
}

getDbDelete($table[$m.'comment'],'uid='.$C['uid']);
getDbUpdate($table[$m.'data'],'comment=comment-1','uid='.$post);
//getDbDelete($table['s_xtralog'],"module='".$m."' and parent=".$C['uid']);
//getDbUpdate($table['s_numinfo'],'comment=comment-1',"date='".substr($C['d_regis'],0,8)."' and site=".$C['site']);

/***
if ($C['point']&&$C['mbruid'])
{
	getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$C['mbruid']."','0','-".$C['point']."','댓글삭제(".getStrCut($C['subject'],15,'').")환원','".$date['totime']."'");
	getDbUpdate($table['s_mbrdata'],'point=point-'.$C['point'],'memberuid='.$C['mbruid']);
}
***/

$link = $g['s'].'/?r='.$r.'&c='.$c.'&m='.$m.'&bid='.$bid.'&parent='.$parent.'&p='.$p.'&mod=comment&iframe=Y';
getLink($link, 'parent.', '', '');		
?>


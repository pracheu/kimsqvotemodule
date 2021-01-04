<?php
if(!defined('__KIMS__')) exit;

if (!$my['uid']) getLink('','','권한이 없는 접근입니다.','');
$O = getUidData($table[$m.'oneline'],$uid);
if (!$O['uid']) getLink('','','존재하지 않는 한줄의견입니다.','');

include_once $g['dir_module'].'var/var.php';
include_once $g['dir_module'].'var/var.'.$bid.'.php';

/**
if( $O['singo'] >= $d['comment']['singo_del_num'] and !$my['admin'] )	getLink('','','신고 누적으로 게시제한 처리된 한줄의견은 직접 삭제할 수 없습니다. 관리자 확인 후, 삭제처리 될 수 있음을 유의하십시요.','');
**/
	
$C = getUidData($table[$m.'comment'],$O['parent']);
if( !$C['uid'] ) getLink('','','정상적인 접근이 아닙니다.','');

/***
$cyncArr = getArrayString($C['cync']);
if( $O['id']!=$my['id']&&!$my['admin'] )
{
	// BSKR - 게시판 관리자 한줄의견 삭제권한 부여
	$POST = getDbData($cyncArr['data'][3], 'uid='.$cyncArr['data'][1], 'bbsid');
	include_once $g['path_module'].$cyncArr['data'][0].'/var/var.'.$POST['bbsid'].'.php';
	if( !($my['id'] && strstr(','.($d['bbs']['admin']?$d['bbs']['admin']:'.').',',','.$my['id'].',')) )
		getLink('','','삭제권한이 없습니다.','');
		
	// 최고관리자 한줄의견 삭제금지
	$M = getDbData($table['s_mbrdata'], 'memberuid='.$O['mbruid'], 'admin');
	if( $M['admin'] )	getLink('', '', '최고관리자의 한줄의견은 삭제할 수 없습니다.', '');		
}
***/
	
getDbDelete($table[$m.'oneline'],'uid='.$O['uid']);
getDbUpdate($table[$m.'data'],'oneline=oneline-1','uid='.$post);	
getDbUpdate($table[$m.'comment'],'oneline=oneline-1','uid='.$C['uid']);
//getDbUpdate($table['s_numinfo'],'oneline=oneline-1',"date='".substr($O['d_regis'],0,8)."' and site=".$O['site']);

/***
if ($O['point']&&$O['mbruid'])
{
	getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$O['mbruid']."','0','-".$O['point']."','한줄의견삭제(".getStrCut(str_replace('&amp;',' ',strip_tags($O['content'])),15,'').")환원','".$date['totime']."'");
	getDbUpdate($table['s_mbrdata'],'point=point-'.$O['point'],'memberuid='.$O['mbruid']);
}
***/

/***
//동기화
//$cyncArr = getArrayString($C['cync']);
$fdexp = explode(',',$cyncArr['data'][2]);
if ($fdexp[0]&&$fdexp[2]&&$cyncArr['data'][3]) getDbUpdate($cyncArr['data'][3],$fdexp[2].'='.$fdexp[2].'-1',$fdexp[0].'='.$cyncArr['data'][1]);
***/

$link = $g['s'].'/?r='.$r.'&c='.$c.'&m='.$m.'&bid='.$bid.'&uid='.$post.'&mod=comment&iframe=Y';
getLink($link, 'parent.', '', '');
?>
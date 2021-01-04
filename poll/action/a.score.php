<?php
if(!defined('__KIMS__')) exit;

$useGUEST = 0; //비회원도 접근허용할 경우 1로 변경
$score_limit = 1; //점수한계치(이 점수보다 높은 갚을 임의로 보낼 경우 제한)
$score = $score ? $score : 1;
if ($score > $score_limit) $score = $score_limit;

if (!$useGUEST)
{
	if (!$my['uid']) getLink('','','로그인해 주세요.','');
	$scorelog = '['.$my['uid'].']';
}
else {
	$scorelog = '['.$_SERVER['REMOTE_ADDR'].']';
	if ($my['uid']) $scorelog .= '['.$my['uid'].']';
}


$R = getUidData($table[$m.'data'],$uid);
if (!$R['uid']) getLink('','','존재하지 않는 게시물입니다.','');

include_once $g['dir_module'].'var/var.php';
include_once $g['dir_module'].'var/var.'.$R['bbsid'].'.php';

// 관리자 권한 체크
if( $my['admin'] && $my['uid']==0 )						$isAdmin	= 'super';
else if( $my['admin'] && $my['uid']!=0 )				$isAdmin	= 'admin';
else if( !$my['admin'] && strstr(','.($d['bbs']['admin']?$d['bbs']['admin']:'.').',',','.$my['id'].',') )	$isAdmin = 'bbs';

// 테마설정 체크
$d['bbs']['skin'] = ( $bid )? $d['bbs']['skin_main']: $d['bbs']['skin_total'];
include_once $g['dir_module'].'themes/'.$d['bbs']['skin'].'/_var.php';		
if( !$value=='good' && !$value=='bad' )							getLink('','','지원하지 않는 기능입니다.','');
if( !$d['theme']['show_score1'] && $value=='good' )	getLink('','','지원하지 않는 기능입니다.','');
if( !$d['theme']['show_score2'] && $value=='bad' )	getLink('','','지원하지 않는 기능입니다.2','');

if( $my['uid']==$R['mbruid'] and !$isAdmin )	getLink('','','자신의 게시물은 평가할 수 없습니다.','');
if( $R['hidden'] )	getLink('','','비밀글은 평가할 수 없습니다.','');
if( $R['notice'] )	getLink('','','공지글은 평가할 수 없습니다.','');


$UT = getDbData($table[$m.'xtra'],'parent='.$R['uid'],'*');
$scoreset = array('good'=>'score1','bad'=>'score2');

if( !$isAdmin )
{
	// 공감,비공감 또는 추천,비추천 등 2개이상의 중복 체크가 가능토록 하려면, 아래를 주석처리 하세요.
	if (strpos('_'.$UT['score1'],'['.$my['uid'].']') || strpos('_'.$UT['score1'],'['.$_SERVER['REMOTE_ADDR'].']') || strpos('_'.$UT['score2'],'['.$my['uid'].']') || strpos('_'.$UT['score2'],'['.$_SERVER['REMOTE_ADDR'].']'))
		getLink('','','이미 평가하신 글입니다.','');
}
	
getDbUpdate($table[$m.'data'],$scoreset[$value].'='.$scoreset[$value].'+'.$score,'uid='.$R['uid']);
if (!$UT['parent'])
{
	getDbInsert($table[$m.'xtra'],'parent,site,bbs,'.$scoreset[$value],"'".$R['uid']."','".$s."','".$R['bbs']."','".$scorelog."'");
}
else {
	getDbUpdate($table[$m.'xtra'],$scoreset[$value]."='".$UT[$scoreset[$value]].$scorelog."'",'parent='.$R['uid']);
}


// BSKR - 평가 카운트 동적 업데이트
if( $value=='good' ) 	$type='up';
else							$type='down';
getLink('', 'parent.updateCount("'.$type.'", '.$R['uid'].');', '감사합니다! 평가내용이 반영되었습니다.', '');
?>
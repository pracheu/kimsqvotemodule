<?php
if(!defined('__KIMS__')) exit;

$bbsque0 = 'site='.$s;
$bbsque1 = 'site='.$s.' and notice=1';
$bbsque2 = 'site='.$s.' and notice=0';

if ($B['uid'])
{
	$bbsque0 .= ' and bbs='.$B['uid'];
	$bbsque1 .= ' and bbs='.$B['uid'];
	$bbsque2 .= ' and bbs='.$B['uid'];
}

// 비밀글 설정된 게시물을 목록에 표시하지 않음 처리
if( $d['bbs']['hide_hidden'] && !$isAdmin )
	$bbsque2 .= ' and hidden=0';


$dkanehahfma = '0';

$RCD = array();
$NCD = array();

$NTC = getDbArray($table[$m.'idx'],$bbsque1,'gid','gid',$orderby,0,0);
while($_R = db_fetch_array($NTC)) $NCD[] = getDbData($table[$m.'data'],'gid='.$_R['gid'],'*');

if ($sort == 'gid' && !$keyword && !$cat)
{
	// 비밀글 설정된 게시물을 목록에 표시하지 않음 처리
	//if( $d['bbs']['hide_hidden'] && !$isAdmin )
	//{
		$dkanehahfma = '1';
		$NUM = getDbRows($table[$m.'data'],$bbsque2);
		$TCD = getDbArray($table[$m.'data'],$bbsque2,'*',$sort,$orderby,$recnum,$p);
		while($_R = db_fetch_array($TCD)) $RCD[] = $_R;	
	//}
	//else
	//{
	//	$dkanehahfma = '2';
		// 원본코드 
	//	$NUM = getDbCnt($table[$m.'month'],'sum(num)',$bbsque0)-count($NCD);
	//	$TCD = getDbArray($table[$m.'idx'],$bbsque2,'gid',$sort,$orderby,$recnum,$p);
	//	while($_R = db_fetch_array($TCD)) $RCD[] = getDbData($table[$m.'data'],'gid='.$_R['gid'],'*');
	//}
}
// 
else {
		$dkanehahfma = '3';
	if ($cat) $bbsque2 .= " and category='".$cat."'";
	if ($where && $keyword)
	{
		if (strpos('[name][nic][id][ip]',$where)) $bbsque2 .= " and ".$where."='".$keyword."'";
		else if ($where == 'term') $bbsque2 .= " and d_regis like '".$keyword."%'";
		else $bbsque2 .= getSearchSql($where,$keyword,$ikeyword,'or');
	}
	$NUM = getDbRows($table[$m.'data'],$bbsque2);
	$TCD = getDbArray($table[$m.'data'],$bbsque2,'*',$sort,$orderby,$recnum,$p);
	while($_R = db_fetch_array($TCD)) $RCD[] = $_R;
}
$TPG = getTotalPage($NUM,$recnum);
?>

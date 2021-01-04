<?php
if(!defined('__KIMS__')) exit;

$parent	= ($parent)? $parent: 'bbs'.$uid;
$post 		= ($uid)? $uid: str_replace('bbs', '', $parent);		// 'bbs...' 에서 문자열 bbs 제거

// iframe 사용 시, 액션 프레임의 이름을 다르게 구성토록 해준다. (게시판 액션 프레임과 중복 방지)
if( $iframe ) {
	$atarget = '_action_frame_'.$m.'_cmt';
	$R = getUidData($table[$m.'data'], $post);						// 게시물 정보를 테마에 전달
}
else
	$atarget = '_action_frame_'.$m;


$sort			= 'uid';
$orderby		= ($d['bbs']['c_orderby'])? $d['bbs']['c_orderby']: '';
$recnum		= $d['bbs']['c_recnum'];
$cmentque 	= " and parent='".$parent."'";		

$NCD = array();
$RCD = array();

$NUM = getDbRows($table[$m.'comment'],'notice=0'.$cmentque);
$TPG = getTotalPage($NUM,$recnum);

// 댓글 페이징 자동조정 요청 처리 (소팅 방식에 따라, 마지막 페이지 또는 첫 페이지로 설정)
if( $p < 0 )	{ 
	if( $orderby=='desc' )	$p = $TPG;
	else 							$p =1;	
}

// 잘못된 페이지 정보를 재조정 (마지막 페이지 + 마지막 댓글 삭제 시 등)
if( $p > $TPG )	
	$p = $TPG;
	
// 특정 댓글 페이지 자동설정 (댓글이 존재하는 페이지로 설정)
if( $cp && $pos ) {
	$_TMP = getDbArray($table[$m.'comment'],'notice=0'.$cmentque,'uid',$sort,$orderby,'','');
	$_index = 1;
	while($_C = db_fetch_array($_TMP)) {
		if( $pos == 'cmt-'.$_C['uid'] ) {			
			$p = ceil($_index/$recnum);
			break;
		}
		$_index++;
	}
}

// 특정 한줄의견 페이지 자동설정 (댓글이 존재하는 페이지로 설정)
if( $op && $pos ) {
	$oid = str_replace('oline-', '', $pos);
	$O = getDbData($table[$m.'oneline'], 'uid='.$oid, 'parent');
	$_TMP = getDbArray($table[$m.'comment'],'notice=0'.$cmentque,'uid',$sort,$orderby,'','');
	$_index = 1;
	while($_C = db_fetch_array($_TMP)) {
		if( $O['parent'] == $_C['uid'] ) {			
			$p = ceil($_index/$recnum);
			break;
		}
		$_index++;
	}
}

$PCD = getDbArray($table[$m.'comment'],'notice=1'.$cmentque,'*',$sort,$orderby,0,0);
$TCD = getDbArray($table[$m.'comment'],'notice=0'.$cmentque,'*',$sort,$orderby,$recnum,$p);
while($_R = db_fetch_array($PCD)) $NCD[] = $_R;
while($_R = db_fetch_array($TCD)) $RCD[] = $_R;
?>
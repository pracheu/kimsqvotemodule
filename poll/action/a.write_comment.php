<?php
if ( ! defined( '__KIMS__' ) ) {
	exit;
}

//if (!$_SESSION['wcode']||$_SESSION['wcode']!=$pcode) exit;
if ( ! $bid ) {
	getLink( '', '', '게시판 아이디가 지정되지 않았습니다.', '' );
}
$B = getDbData( $table[ $m . 'list' ], "id='" . $bid . "'", '*' );
if ( ! $B['uid'] ) {
	getLink( '', '', '존재하지 않는 게시판입니다.', '' );
}

include_once $g['dir_module'] . 'var/var.php';
include_once $g['dir_module'] . 'var/var.' . $B['id'] . '.php';

$bbsuid = $B['uid'];
$bbsid  = $B['id'];
$mbruid = $my['uid'];
$id     = $my['id'];
$name   = $my['uid'] ? $my['name'] : trim( $name );
$nic    = $my['uid'] ? $my['nic'] : $name;

// 댓글 관련정보
$parent = trim( $parent );
//$subject	= $my['admin'] ? trim($subject) : htmlspecialchars(trim($subject));  일단 지원 안 함
$content = trim( $content );
$subject = $subject ? $subject : getStrCut( str_replace( '&amp;', ' ', strip_tags( $content ) ), 35, '..' );
//$html		= $html ? $html : 'TEXT'; 일단 지원안함
$d_regis   = $date['totime'];
$d_modify  = '';
$d_oneline = '';
$ip        = $_SERVER['REMOTE_ADDR'];
$agent     = $_SERVER['HTTP_USER_AGENT'];
//$upload		= $upfiles;일단 지원안함
$adddata = trim( $adddata );
$hit     = 0;
$down    = 0;
$oneline = 0;
$score1  = 0;
$score2  = 0;
$singo   = 0;
$point   = $d['comment']['give_point'];
$hidden  = $hidden ? intval( $hidden ) : 0;
$notice  = $notice ? intval( $notice ) : 0;
$display = $hidepost || $hidden ? 0 : 1;


// BSKR - 공지 권한체크
if ( $my['admin'] && $my['uid'] == 0 ) {
	$isAdmin = 'super';
} else if ( $my['admin'] && $my['uid'] != 0 ) {
	$isAdmin = 'admin';
} else if ( ! $my['admin'] && strstr( ',' . ( $d['bbs']['admin'] ? $d['bbs']['admin'] : '.' ) . ',', ',' . $my['id'] . ',' ) ) {
	$isAdmin = 'bbs';
}

$post = str_replace( 'bbs', '', $parent );        // 'bbs...' 에서 문자열 bbs 제거
$R    = getDbData( $table[ $m . 'data' ], "uid='" . $post . "'", 'bbsid, mbruid' );
if ( $notice && ! ( $isAdmin || $R['mbruid'] == $my['uid'] ) ) {
	getLink( '', '', '공지댓글 작성 권한이 없습니다.', '' );
}
if ( $notice && $hidden ) {
	getLink( '', '', '공지글은 비밀글로 등록할 수 없습니다.', '' );
}


// BSKR - 제한단어 체크
if ( $d['bbs']['badword_action'] ) {
	$badwordarr = explode( ',', $d['bbs']['badword'] );
	$badwordlen = count( $badwordarr );
	for ( $i = 0; $i < $badwordlen; $i ++ ) {
		if ( ! $badwordarr[ $i ] ) {
			continue;
		}

		if ( strstr( $subject, $badwordarr[ $i ] ) || strstr( $content, $badwordarr[ $i ] ) ) {
			if ( $d['bbs']['badword_action'] == 1 ) {
				getLink( '', '', '등록이 제한된 단어를 사용하셨습니다.', '' );
			} else {
				$badescape = strCopy( $badwordarr[ $i ], $d['bbs']['badword_escape'] );
				$content   = str_replace( $badwordarr[ $i ], $badescape, $content );
				$subject   = str_replace( $badwordarr[ $i ], $badescape, $subject );
			}
		}
	}
}

if ( $d['bbs']['perm_l_write'] > $my['level'] ) {
	getLink( '', '', '댓글등록 권한이 없습니다.', '' );
}


// 댓글수정
if ( $uid ) {
	$C = getUidData( $table[ $m . 'comment' ], $uid );
	if ( ! $C['uid'] ) {
		getLink( $link, 'parent.', '존재하지 않는 댓글입니다.', '' );
	}

	if ( ! $my['id'] || ( $my['id'] != $C['id'] && ! $isAdmin ) ) {
		if ( ! $pw ) {
			getLink( $link, 'parent.', '정상적인 접근이 아닙니다.', '' );
		} else {
			if ( md5( $pw ) != $C['pw'] ) {
				getLink( $link, 'parent.', '올바른 비밀번호가 아닙니다.', '' );
			}
		}
	}

	/***
	 * // BSKR - 신고누적으로 게시제한 시, 글작성자도 편집 불허
	 * if( $C['singo'] >= $d['comment']['singo_del_num'] and !$my['admin'] )    getLink('','','신고 누적으로 게시제한 처리된 댓글은 수정할 수 없습니다.\n관리자 확인 후, 삭제처리 될 수 있음을 유의하십시요.','');
	 ***/

	$QVAL = "display='$display',hidden='$hidden',notice='$notice',subject='$subject',content='$content',html='$html',d_modify='$d_regis',upload='$upload',adddata='$adddata'";
	getDbUpdate( $table[ $m . 'comment' ], $QVAL, 'uid=' . $uid );
} else {
	$pw        = $hidden && $my['uid'] ? $my['uid'] : ( $pw ? md5( $pw ) : '' );
	$minuid    = getDbCnt( $table[ $m . 'comment' ], 'min(uid)', '' );
	$uid       = $minuid ? $minuid - 1 : 1000000000;
	$parentmbr = $R['mbruid'];

	$QKEY = "uid,site,parent,parentmbr,display,hidden,notice,name,nic,mbruid,id,pw,subject,content,html,";
	$QKEY .= "hit,down,oneline,score1,score2,singo,point,d_regis,d_modify,d_oneline,upload,ip,agent,cync,sns,adddata";
	$QVAL = "'$uid','$s','$parent','$parentmbr','$display','$hidden','$notice','$name','$nic','$mbruid','$id','$pw','$subject','$content','$html',";
	$QVAL .= "'$hit','$down','$oneline','$score1','$score2','$singo','$point','$d_regis','$d_modify','$d_oneline','$upload','$ip','$agent','$cync','','$adddata'";

	getDbInsert( $table[ $m . 'comment' ], $QKEY, $QVAL );
	$LASTUID = getDbCnt( $table[ $m . 'comment' ], 'max(uid)', '' );
	getDbUpdate( $table[ $m . 'data' ], 'comment=comment+1, d_comment="' . $d_regis . '"', 'uid=' . $post );
	//getDbUpdate($table['s_numinfo'],'comment=comment+1',"date='".$date['today']."' and site=".$s);

	if ( $uid == 1000000000 ) {
		db_query( "OPTIMIZE TABLE " . $table[ $m . 'comment' ], $DB_CONNECT );
	}

	if ( $d['bbs']['push_server'] ) {

		include_once $g['dir_module'] . 'mod/_push.php';
		$method            = "POST";
		$url               = $d['bbs']['push_server'] . "api/push"; // "http://board.enjsoft.com/push/api/push";
		$params            = array();
		$params['r']       = $r;
		$params['bid']     = $bid;
		$params['uid']     = str_replace( 'bbs', '', $parent );
		$params['cid']     = $uid;
//		$params['title']   = '새 댓글이 있습니다';
		$params['title']   = '댓글 등록 - '.$B['name'];
		$params['message'] = $subject;
		enjPush( $method, $url, $params );
	}

	/***
	 * if ($point&&$my['uid'])
	 * {
	 * getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$my['uid']."','0','".$point."','댓글(".getStrCut($subject,15,'').")포인트','".$date['totime']."'");
	 * getDbUpdate($table['s_mbrdata'],'point=point+'.$point,'memberuid='.$my['uid']);
	 * }
	 *
	 * if ($snsCallBack && is_file($g['path_module'].$snsCallBack))
	 * {
	 * $xcync = $cync.',CMT:'.$uid;
	 * $orignSubject = strip_tags($subject);
	 * $orignContent = getStrCut($orignSubject,60,'..');
	 * $orignUrl = 'http://'.$_SERVER['SERVER_NAME'].str_replace('./','/',getCyncUrl($xcync)).'#CMT';
	 *
	 * include_once $g['path_module'].$snsCallBack;
	 * if ($snsSendResult)
	 * {
	 * getDbUpdate($table['s_comment'],"sns='".$snsSendResult."'",'uid='.$uid);
	 * }
	 * }
	 ***/
}

$link = $g['s'] . '/?r=' . $r . '&c=' . $c . '&m=' . $m . '&bid=' . $bid . '&parent=' . $parent . '&p=' . $p . '&pos=cmt-' . $uid . '&mod=comment&iframe=Y';
getLink( $link, 'parent.', '', '' );
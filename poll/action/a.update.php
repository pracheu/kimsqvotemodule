<?php
if ( ! defined( '__KIMS__' ) ) {
	exit;
}

if($smod == 'update'){
	
	/*$po_site = $s;
	$po_subject
	$po_poll1
	$po_poll2
	$po_poll3
	$po_poll4
	$po_poll5
	$po_poll6
	$po_poll7
	$po_poll8
	$po_poll9
	$po_cnt1
	$po_cnt2
	$po_cnt3
	$po_cnt4
	$po_cnt5
	$po_cnt6
	$po_cnt7
	$po_cnt8
	$po_cnt9
	$po_etc
	$po_level
	$po_point
	$po_date
	$po_ips
	$mb_ids
	$start
	$end
	$content*/
	
	$QVAL = "po_subject='$po_subject',po_poll1='$po_poll1',po_poll2='$po_poll2',po_poll3='$po_poll3',po_poll4='$po_poll4',po_poll5='$po_poll5',po_poll6='$po_poll6',po_poll7='$po_poll7',po_poll8='$po_poll8',po_poll9='$po_poll9',content='$content'";
	getDbUpdate( $table[ $m . 'list' ], $QVAL, 'po_id=' . $pid );
	
}else{




	$bbsuid    = $B['uid'];
	$bbsid     = $B['id'];
	$mbruid    = $my['uid'];
	$id        = $my['id'];
	$name      = $my['uid'] ? $my['name'] : trim( $name );
	$nic       = $my['uid'] ? $my['nic'] : $name;
	$category  = trim( $category );
	$subject   = $my['admin'] ? trim( $subject ) : htmlspecialchars( trim( $subject ) );
	$content   = trim( $content );
	$html      = $html ? $html : 'TEXT';
	$tag       = trim( $tag );
	$d_regis   = $date['totime'];
	$d_comment = '';
	$ip        = $_SERVER['REMOTE_ADDR'];
	$agent     = $_SERVER['HTTP_USER_AGENT'];
	$upload    = $upfiles;
	$adddata   = trim( $adddata );
	$hidden    = $hidden ? intval( $hidden ) : 0;
	$notice    = $notice ? intval( $notice ) : 0;
	$display   = $d['bbs']['display'] || $hidepost || $hidden ? 0 : 1;
	$parentmbr = 0;
	$point1    = trim( $d['bbs']['point1'] );
	$point2    = trim( $d['bbs']['point2'] );
	$point3    = $point3 ? filterstr( trim( $point3 ) ) : 0;
	$point4    = $point4 ? filterstr( trim( $point4 ) ) : 0;

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


	// BSKR - 공지 권한체크
	if ( $my['admin'] && $my['uid'] == 0 ) {
		$isAdmin = 'super';
	} else if ( $my['admin'] && $my['uid'] != 0 ) {
		$isAdmin = 'admin';
	} else if ( ! $my['admin'] && strstr( ',' . ( $d['bbs']['admin'] ? $d['bbs']['admin'] : '.' ) . ',', ',' . $my['id'] . ',' ) ) {
		$isAdmin = 'bbs';
	}

	if ( $notice && ! $isAdmin ) {
		if ( ! ( $my['id'] && strstr( ',' . ( $d['bbs']['admin'] ? $d['bbs']['admin'] : '.' ) . ',', ',' . $my['id'] . ',' ) ) ) {
			getLink( '', '', '공지글 작성 권한이 없습니다.', '' );
		}
	}


	if ( ! $uid || $reply == 'Y' ) {
		if ( ! getDbRows( $table[ $m . 'day' ], "date='" . $date['today'] . "' and site=" . $s . ' and bbs=' . $bbsuid ) ) {
			getDbInsert( $table[ $m . 'day' ], 'date,site,bbs,num', "'" . $date['today'] . "','" . $s . "','" . $bbsuid . "','0'" );
		}
		if ( ! getDbRows( $table[ $m . 'month' ], "date='" . $date['month'] . "' and site=" . $s . ' and bbs=' . $bbsuid ) ) {
			getDbInsert( $table[ $m . 'month' ], 'date,site,bbs,num', "'" . $date['month'] . "','" . $s . "','" . $bbsuid . "','0'" );
		}
	}

	if ( $uid ) {
		$R = getUidData( $table[ $m . 'data' ], $uid );
		if ( ! $R['uid'] ) {
			getLink( '', '', '존재하지 않는 게시물입니다.', '' );
		}

		// BSKR - 신고누적으로 게시제한 시, 글작성자도 편집 불허
		if ( $R['singo'] >= $d['bbs']['singo_del_num'] and ! $isAdmin ) {
			getLink( '', '', '신고 누적으로 게시제한 처리된 게시물은 수정하거나 답변을 달 수 없습니다. 관리자 확인 후, 삭제처리 될 수 있음을 유의하십시요.', '' );
		}


		if ( $reply == 'Y' ) {
			if ( ! $isAdmin ) {
				if ( $d['bbs']['perm_l_write'] > $my['level'] || strstr( $d['bbs']['perm_g_write'], '[' . $my['mygroup'] . ']' ) ) {
					getLink( '', '', '정상적인 접근이 아닙니다.', '' );
				}
			}

			$RNUM = getDbRows( $table[ $m . 'idx' ], 'gid >= ' . $R['gid'] . ' and gid < ' . ( intval( $R['gid'] ) + 1 ) );
			if ( $RNUM > 98 ) {
				getLink( '', '', '죄송합니다. 더이상 답글을 달 수 없습니다.', '' );
			}

			getDbUpdate( $table[ $m . 'idx' ], 'gid=gid+0.01', 'gid > ' . $R['gid'] . ' and gid < ' . ( intval( $R['gid'] ) + 1 ) );
			getDbUpdate( $table[ $m . 'data' ], 'gid=gid+0.01', 'gid > ' . $R['gid'] . ' and gid < ' . ( intval( $R['gid'] ) + 1 ) );

			if ( $R['hidden'] && $hidden ) {
				if ( $R['mbruid'] ) {
					$pw = $R['mbruid'];
				} else {
					// $pw = $my['uid'] ? $R['pw'] : ($pw == $R['pw'] ? $R['pw'] : md5($pw));
					$pw = $my['uid'] ? $R['pw'] : ( $pw == $R['pw'] ? $R['pw'] : $pw );
				}
			} else {
				// $pw = $pw ? md5($pw) : '';
				$pw = $pw ? $pw : '';
			}

			$gid       = $R['gid'] + 0.01;
			$depth     = $R['depth'] + 1;
			$parentmbr = $R['mbruid'];

			$QKEY = "site,gid,bbs,bbsid,depth,parentmbr,display,hidden,notice,name,nic,mbruid,id,pw,category,subject,content,html,tag,";
			$QKEY .= "hit,down,comment,oneline,trackback,score1,score2,singo,point1,point2,point3,point4,d_regis,d_modify,d_comment,d_trackback,upload,ip,agent,sns,adddata";
			$QVAL = "'$s','$gid','$bbsuid','$bbsid','$depth','$parentmbr','$display','$hidden','$notice','$name','$nic','$mbruid','$id','$pw','$category','$subject','$content','$html','$tag',";
			$QVAL .= "'0','0','0','0','0','0','0','0','$point1','$point2','$point3','$point4','$d_regis','','','','$upload','$ip','$agent','','$adddata'";
			getDbInsert( $table[ $m . 'data' ], $QKEY, $QVAL );
			getDbInsert( $table[ $m . 'idx' ], 'site,notice,bbs,gid', "'$s','$notice','$bbsuid','$gid'" );
			getDbUpdate( $table[ $m . 'list' ], "num_r=num_r+1,d_last='" . $d_regis . "'", 'uid=' . $bbsuid );
			getDbUpdate( $table[ $m . 'month' ], 'num=num+1', "date='" . $date['month'] . "' and site=" . $s . ' and bbs=' . $bbsuid );
			getDbUpdate( $table[ $m . 'day' ], 'num=num+1', "date='" . $date['today'] . "' and site=" . $s . ' and bbs=' . $bbsuid );
			$LASTUID = getDbCnt( $table[ $m . 'data' ], 'max(uid)', '' );
			if ( $cuid ) {
				getDbUpdate( $table['s_menu'], "num='" . getDbCnt( $table[ $m . 'month' ], 'sum(num)', 'site=' . $s . ' and bbs=' . $bbsuid ) . "',d_last='" . $d_regis . "'", 'uid=' . $cuid );
			}

			if ( $point1 && $my['uid'] ) {
				getDbInsert( $table['s_point'], 'my_mbruid,by_mbruid,price,content,d_regis', "'" . $my['uid'] . "','0','" . $point1 . "','게시물(" . getStrCut( $subject, 15, '' ) . ")포인트','" . $date['totime'] . "'" );
				getDbUpdate( $table['s_mbrdata'], 'point=point+' . $point1, 'memberuid=' . $my['uid'] );
			}
		} else {
			if ( $my['uid'] != $R['mbruid'] && ! $isAdmin ) {
				if ( ! strstr( $_SESSION[ 'module_' . $m . '_pwcheck' ], $R['uid'] ) ) {
					getLink( '', '', '정상적인 접근이 아닙니다.', '' );
				}
			}

			$pw = ! $R['pw'] && ! $R['hidden'] && $hidden && $R['mbruid'] ? $R['mbruid'] : $R['pw'];

			$QVAL = "display='$display',hidden='$hidden',notice='$notice',pw='$pw',category='$category',subject='$subject',content='$content',html='$html',tag='$tag',point3='$point3',point4='$point4',d_modify='$d_regis',upload='$upload',adddata='$adddata'";
			getDbUpdate( $table[ $m . 'data' ], $QVAL, 'uid=' . $R['uid'] );
			getDbUpdate( $table[ $m . 'idx' ], 'notice=' . $notice, 'gid=' . $R['gid'] );
			if ( $cuid ) {
				getDbUpdate( $table['s_menu'], "num='" . getDbCnt( $table[ $m . 'month' ], 'sum(num)', 'site=' . $R['site'] . ' and bbs=' . $R['bbs'] ) . "'", 'uid=' . $cuid );
			}
		}
	} else {
		if ( ! $isAdmin ) {
			if ( $d['bbs']['perm_l_write'] > $my['level'] || strstr( $d['bbs']['perm_g_write'], '[' . $my['mygroup'] . ']' ) ) {
				getLink( '', '', '정상적인 접근이 아닙니다.', '' );
			}
		}

		// $pw = $hidden && $my['uid'] ? $my['uid'] : ($pw ? md5($pw) : '');
		$pw     = $hidden && $my['uid'] ? $my['uid'] : $pw;
		$mingid = getDbCnt( $table[ $m . 'data' ], 'min(gid)', '' );
		$gid    = $mingid ? $mingid - 1 : 100000000.00;

		$QKEY = "site,gid,bbs,bbsid,depth,parentmbr,display,hidden,notice,name,nic,mbruid,id,pw,category,subject,content,html,tag,";
		$QKEY .= "hit,down,comment,oneline,trackback,score1,score2,singo,point1,point2,point3,point4,d_regis,d_modify,d_comment,d_trackback,upload,ip,agent,sns,adddata";
		$QVAL = "'$s','$gid','$bbsuid','$bbsid','$depth','$parentmbr','$display','$hidden','$notice','$name','$nic','$mbruid','$id','$pw','$category','$subject','$content','$html','$tag',";
		$QVAL .= "'0','0','0','0','0','0','0','0','$point1','$point2','$point3','$point4','$d_regis','','','','$upload','$ip','$agent','','$adddata'";
		getDbInsert( $table[ $m . 'data' ], $QKEY, $QVAL );
		getDbInsert( $table[ $m . 'idx' ], 'site,notice,bbs,gid', "'$s','$notice','$bbsuid','$gid'" );
		getDbUpdate( $table[ $m . 'list' ], "num_r=num_r+1,d_last='" . $d_regis . "'", 'uid=' . $bbsuid );
		getDbUpdate( $table[ $m . 'month' ], 'num=num+1', "date='" . $date['month'] . "' and site=" . $s . ' and bbs=' . $bbsuid );
		getDbUpdate( $table[ $m . 'day' ], 'num=num+1', "date='" . $date['today'] . "' and site=" . $s . ' and bbs=' . $bbsuid );
		$LASTUID = getDbCnt( $table[ $m . 'data' ], 'max(uid)', '' );
		if ( $cuid ) {
			getDbUpdate( $table['s_menu'], "num='" . getDbCnt( $table[ $m . 'month' ], 'sum(num)', 'site=' . $s . ' and bbs=' . $bbsuid ) . "',d_last='" . $d_regis . "'", 'uid=' . $cuid );
		}
		if ( $point1 && $my['uid'] ) {
			getDbInsert( $table['s_point'], 'my_mbruid,by_mbruid,price,content,d_regis', "'" . $my['uid'] . "','0','" . $point1 . "','게시물(" . getStrCut( $subject, 15, '' ) . ")포인트','" . $date['totime'] . "'" );
			getDbUpdate( $table['s_mbrdata'], 'point=point+' . $point1, 'memberuid=' . $my['uid'] );

			getDbInsert( $table[ $m . 'xtra' ], 'parent,site,bbs,point1', "'" . $LASTUID . "','" . $s . "','" . $bbsuid . "','[" . $my['uid'] . "]'" );
		}

		if ( $gid == 100000000.00 ) {
			db_query( "OPTIMIZE TABLE " . $table[ $m . 'idx' ], $DB_CONNECT );
			db_query( "OPTIMIZE TABLE " . $table[ $m . 'data' ], $DB_CONNECT );
			db_query( "OPTIMIZE TABLE " . $table[ $m . 'month' ], $DB_CONNECT );
			db_query( "OPTIMIZE TABLE " . $table[ $m . 'day' ], $DB_CONNECT );
		}

		// 게시글 등록 후 푸시메세지 전송을 위한 곳
		if ( $d['bbs']['push_server'] ) {

			include_once $g['dir_module'] . 'mod/_push.php';
			$method            = "POST";
			$url               = $d['bbs']['push_server'] . "api/push"; // "http://board.enjsoft.com/push/api/push";
			$params            = array();
			$params['r']       = $r;
			$params['bid']     = $bbsid;
			$params['uid']     = $LASTUID;
	//		$params['title']   = '새 개시글이 있습니다';
			$params['title']   = '게시글 등록 - '.$B['name'];
			$params['message'] = $subject;
			$params['targetapp'] = 'default'; // 푸시 보낼 앱 타겟 이름
			enjPush( $method, $url, $params );  //./modules/bskrbbs/mod/_push.php

	$myfile = fopen("./push_log.txt", "a");
	if($myfile) {
		fwrite($myfile, $url);
		fwrite($myfile, "\r\n");
		fwrite($myfile, $params['r']);
		fwrite($myfile, "\r\n");
		fwrite($myfile, $params['bid']);
		fwrite($myfile, "\r\n");
		fwrite($myfile, $params['uid']);
		fwrite($myfile, "\r\n");
		fwrite($myfile, $params['title']);
		fwrite($myfile, "\r\n");
		fwrite($myfile, $params['message']);
		fwrite($myfile, "\r\n");
		fwrite($myfile, $params['targetapp']);
		fwrite($myfile, "\r\n==\r\n");
		fclose($myfile);
	}

		}

	}

	if ( $upload ) {

		$converterurl = $g['url_root'] . "/modules/" . $m . "/action/converter.php?uploads=" . $upload;

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $converterurl );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 1 );
		curl_exec( $ch );
		curl_close( $ch );
	}

	$NOWUID = $LASTUID ? $LASTUID : $R['uid'];

	if ( $tag || $R['tag'] ) {
		$_tagarr1 = array();
		$_tagarr2 = explode( ',', $tag );
		$_tagdate = $date['today'];

		if ( $R['uid'] && $reply != 'Y' ) {
			$_tagdate = substr( $R['d_regis'], 0, 8 );
			$_tagarr1 = explode( ',', $R['tag'] );
			foreach ( $_tagarr1 as $_t ) {
				if ( ! $_t || in_array( $_t, $_tagarr2 ) ) {
					continue;
				}
				$_TAG = getDbData( $table['s_tag'], "site=" . $R['site'] . " and date='" . $_tagdate . "' and keyword='" . $_t . "'", '*' );
				if ( $_TAG['uid'] ) {
					if ( $_TAG['hit'] > 1 ) {
						getDbUpdate( $table['s_tag'], 'hit=hit-1', 'uid=' . $_TAG['uid'] );
					} else {
						getDbDelete( $table['s_tag'], 'uid=' . $_TAG['uid'] );
					}
				}
			}
		}

		foreach ( $_tagarr2 as $_t ) {
			if ( ! $_t || in_array( $_t, $_tagarr1 ) ) {
				continue;
			}
			$_TAG = getDbData( $table['s_tag'], 'site=' . $s . " and date='" . $_tagdate . "' and keyword='" . $_t . "'", '*' );
			if ( $_TAG['uid'] ) {
				getDbUpdate( $table['s_tag'], 'hit=hit+1', 'uid=' . $_TAG['uid'] );
			} else {
				getDbInsert( $table['s_tag'], 'site,date,keyword,hit', "'" . $s . "','" . $_tagdate . "','" . $_t . "','1'" );
			}
		}
	}


	$_SESSION['bbsback'] = $backtype;

	if ( $backtype == 'list' ) {
		getLink( $nlist, 'parent.', '', '' );
	} else if ( $backtype == 'view' ) {
		if ( $_HS['rewrite'] && ! strstr( $nlist, '&' ) ) {
			getLink( $nlist . '/' . $NOWUID, 'parent.', '', '' );
		} else {
			getLink( $nlist . '&mod=view&uid=' . $NOWUID, 'parent.', '', '' );
		}
	} else {
		getLink( 'reload', 'parent.', '', '' );
	}
}
getLink( 'reload', 'parent.', '', '' );
?>
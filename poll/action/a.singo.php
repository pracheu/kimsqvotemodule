<?php
if(!defined('__KIMS__')) exit;

if (!$my['uid']) getLink('','','로그인해 주세요.','');

$R = getUidData($table[$m.'data'],$uid);
if (!$R['uid']) getLink('','','삭제되었거나 존재하지 않는 게시물입니다.','');
$B = getUidData($table[$m.'list'],$R['bbs']);
if (!$B['uid']) getLink('','','존재하지 않는 게시판입니다.','');

include_once $g['dir_module'].'var/var.php';
include_once $g['dir_module'].'var/var.'.$R['bbsid'].'.php';

// 관리자 체크
if( $my['admin'] && $my['uid']==0 )						$isAdmin	= 'super';
else if( $my['admin'] && $my['uid']!=0 )				$isAdmin	= 'admin';
else if( !$my['admin'] && strstr(','.($d['bbs']['admin']?$d['bbs']['admin']:'.').',',','.$my['id'].',') )	$isAdmin = 'bbs';

// 테마설정 체크
$d['bbs']['skin'] = ( $bid )? $d['bbs']['skin_main']: $d['bbs']['skin_total'];
include_once $g['dir_module'].'themes/'.$d['bbs']['skin'].'/_var.php';		
if( !$d['theme']['use_singo'] )	getLink('','','지원하지 않는 기능입니다.','');

if( $R['singo'] >= $d['bbs']['singo_del_num'] )		getLink('','','이미 신고 누적으로 게시제한처리 되었습니다.','');
if( $my['uid'] == $R['mbruid'] && !$isAdmin )	getLink('','','자신의 게시물은 신고할 수 없습니다.','');
if( $R['notice'] )	getLink('','','공지글은 신고할 수 없습니다.','');


// 중복신고 거부
$UT = getDbData($table[$m.'xtra'],'parent='.$R['uid'],'*');
if( strpos('_'.$UT['singo'],'['.$my['uid'].']') && !$isAdmin )	getLink('','','이미 신고하신 게시물입니다.','');

if( $d['bbs']['singo_del_num'] > $R['singo']+1 )
{
	// 관리자는 중복신고 가능 + 신고 시, 카운트 실시간 업데이트
	getDbUpdate($table[$m.'data'],'singo=singo+1','uid='.$R['uid']);
	if (!$UT['parent'])
		getDbInsert($table[$m.'xtra'],'parent,site,bbs,singo',"'".$R['uid']."','".$s."','".$R['bbs']."','[".$my['uid']."]'");
	else
		getDbUpdate($table[$m.'xtra'],"singo='".$UT['singo']."[".$my['uid']."]'",'parent='.$R['uid']);
	getLink('', 'parent.updateCount("singo", '.$R['uid'].');', '신고처리 되었습니다.', '');
}
else
{
	// ~이상 이므로, 신고 개수는 업데이트 해주어야 함
	getDbUpdate($table[$m.'data'],'singo=singo+1','uid='.$R['uid']);
	if (!$UT['parent'])
		getDbInsert($table[$m.'xtra'],'parent,site,bbs,singo',"'".$R['uid']."','".$s."','".$R['bbs']."','[".$my['uid']."]'");
	else
		getDbUpdate($table[$m.'xtra'],"singo='".$UT['singo']."[".$my['uid']."]'",'parent='.$R['uid']);


	// 자동삭제 처리
	if ($d['bbs']['singo_del_act'] == 1)
	{
		//댓글삭제
		if ($R['comment'])
		{
			$CCD = getDbArray($table[$m.'comment'],"parent='bbs".$R['uid']."'",'*','uid','asc',0,0);
			while($_C=db_fetch_array($CCD))
			{
				/** 현재 지원하지 않음
				if ($_C['upload'])
				{
					$UPFILES = getArrayString($_C['upload']);

					foreach($UPFILES['data'] as $_val)
					{
						$U = getUidData($table['s_upload'],$_val);
						if ($U['uid'])
						{
							getDbUpdate($table['s_numinfo'],'upload=upload-1',"date='".substr($U['d_regis'],0,8)."' and site=".$U['site']);
							getDbDelete($table['s_upload'],'uid='.$U['uid']);					
							if( $U['type']==2 ) getDbUpdate($table['s_uploadcat'],'r_num=r_num-1', 'uid='.$U['category']);					
								
							if ($U['url']==$d['upload']['ftp_urlpath'])
							{
								$FTP_CONNECT = ftp_connect($d['upload']['ftp_host'],$d['upload']['ftp_port']); 
								$FTP_CRESULT = ftp_login($FTP_CONNECT,$d['upload']['ftp_user'],$d['upload']['ftp_pass']); 
								if (!$FTP_CONNECT) getLink('','','FTP서버 연결에 문제가 발생했습니다.','');
								if (!$FTP_CRESULT) getLink('','','FTP서버 아이디나 패스워드가 일치하지 않습니다.','');
								if($d['upload']['ftp_pasv']) ftp_pasv($FTP_CONNECT, true);

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
				**/
				if ($_C['oneline'])
				{
					$_ONELINE = getDbSelect($table[$m.'oneline'],'parent='.$_C['uid'],'*');
					while($_O=db_fetch_array($_ONELINE))
					{
						getDbDelete($table[$m.'oneline'],'parent='.$_C['uid']);
						/*** 현재 미지원
						getDbUpdate($table['s_numinfo'],'oneline=oneline-1',"date='".substr($_O['d_regis'],0,8)."' and site=".$_O['site']);
						if ($_O['point']&&$_O['mbruid'])
						{
							getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$_O['mbruid']."','0','-".$_O['point']."','한줄의견삭제(".getStrCut(str_replace('&amp;',' ',strip_tags($_O['content'])),15,'').")환원','".$date['totime']."'");
							getDbUpdate($table['s_mbrdata'],'point=point-'.$_O['point'],'memberuid='.$_O['mbruid']);
						}
						***/
					}
					//getDbDelete($table['s_oneline'],'parent='.$_C['uid']);
				}
				getDbDelete($table[$m.'comment'],'uid='.$_C['uid']);
				//getDbDelete($table['s_xtralog'],"module='comment' and parent=".$_C['uid']);	// BSKR - 댓글 삭제 시, 엑스트라 정보도 삭제
				//getDbUpdate($table['s_numinfo'],'comment=comment-1',"date='".substr($_C['d_regis'],0,8)."' and site=".$_C['site']);

				/** 현재 미지원
				if ($_C['point']&&$_C['mbruid'])
				{
					getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$_C['mbruid']."','0','-".$_C['point']."','댓글삭제(".getStrCut($_C['subject'],15,'').")환원','".$date['totime']."'");
					getDbUpdate($table['s_mbrdata'],'point=point-'.$_C['point'],'memberuid='.$_C['mbruid']);
				}
				**/
			}
		}
		//첨부파일삭제
		if ($R['upload'])
		{
			$UPFILES = getArrayString($R['upload']);
			foreach($UPFILES['data'] as $_val)
			{
				$U = getUidData($table['s_upload'],$_val);
				if ($U['uid'])
				{
					getDbUpdate($table['s_numinfo'],'upload=upload-1',"date='".substr($U['d_regis'],0,8)."' and site=".$U['site']);			
					getDbDelete($table['s_upload'],'uid='.$U['uid']);			
					if( $U['type']==2 ) getDbUpdate($table['s_uploadcat'],'r_num=r_num-1', 'uid='.$U['category']);
					
					/** 현재 미지원
					if ($U['url']==$d['upload']['ftp_urlpath'])
					{
						$FTP_CONNECT = ftp_connect($d['upload']['ftp_host'],$d['upload']['ftp_port']); 
						$FTP_CRESULT = ftp_login($FTP_CONNECT,$d['upload']['ftp_user'],$d['upload']['ftp_pass']); 
						if (!$FTP_CONNECT) getLink('','','FTP서버 연결에 문제가 발생했습니다.','');
						if (!$FTP_CRESULT) getLink('','','FTP서버 아이디나 패스워드가 일치하지 않습니다.','');
						if($d['upload']['ftp_pasv']) ftp_pasv($FTP_CONNECT, true);

						ftp_delete($FTP_CONNECT,$d['upload']['ftp_folder'].$U['folder'].'/'.$U['tmpname']);
						if($U['type']==2) ftp_delete($FTP_CONNECT,$d['upload']['ftp_folder'].$U['folder'].'/'.$U['thumbname']);
						ftp_close($FTP_CONNECT);
					}
					else 
					**/
					{
						unlink($g['path_file'].$U['folder'].'/'.$U['tmpname']);
						if($U['type']==2) unlink($g['path_file'].$U['folder'].'/'.$U['thumbname']);
					}
				}
			}
		}

		//태그삭제
		if ($R['tag'])
		{
			$_tagdate = substr($R['d_regis'],0,8);
			$_tagarr1 = explode(',',$R['tag']);
			foreach($_tagarr1 as $_t)
			{
				if(!$_t) continue;
				$_TAG = getDbData($table['s_tag'],"site=".$R['site']." and date='".$_tagdate."' and keyword='".$_t."'",'*');
				if($_TAG['uid'])
				{
					if($_TAG['hit']>1) getDbUpdate($table['s_tag'],'hit=hit-1','uid='.$_TAG['uid']);
					else getDbDelete($table['s_tag'],'uid='.$_TAG['uid']);
				}
			}
		}

		getDbUpdate($table[$m.'month'],'num=num-1',"date='".substr($R['d_regis'],0,6)."' and site=".$R['site'].' and bbs='.$R['bbs']);
		getDbUpdate($table[$m.'day'],'num=num-1',"date='".substr($R['d_regis'],0,8)."' and site=".$R['site'].' and bbs='.$R['bbs']);
		getDbDelete($table[$m.'idx'],'gid='.$R['gid']);
		getDbDelete($table[$m.'data'],'uid='.$R['uid']);
		getDbDelete($table[$m.'xtra'],'parent='.$R['uid']);
		getDbUpdate($table[$m.'list'],'num_r=num_r-1','uid='.$R['bbs']);
		if ($cuid) getDbUpdate($table['s_menu'],"num='".getDbCnt($table[$m.'month'],'sum(num)','site='.$s.' and bbs='.$R['bbs'])."'",'uid='.$cuid);
		getDbDelete($table['s_trackback'],"parent='".$R['bbsid'].$R['uid']."'");

		if ($R['point1']&&$R['mbruid'])
		{
			getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$R['mbruid']."','0','-".$R['point1']."','게시물삭제(".getStrCut($R['subject'],15,'').")환원','".$date['totime']."'");
			getDbUpdate($table['s_mbrdata'],'point=point-'.$R['point1'],'memberuid='.$R['mbruid']);
		}		

		$backUrl = getLinkFilter($g['s'].'/?'.($_HS['usescode']?'r='.$r.'&amp;':'').($c?'c='.$c:'m='.$m),array('bid','skin','iframe','cat','p','sort','orderby','recnum','type','where','keyword'));
		getLink($backUrl ,'parent.' , '신고건수 누적으로 삭제처리 되었습니다.' , $history);
	}
	// 비밀글(게시제한) 처리
	else 
	{
		getDbUpdate($table[$m.'data'],'hidden=1','uid='.$R['uid']);
		getDbUpdate($table[$m.'data'],'pw='.$R['mbruid'],'uid='.$R['uid']);		// pw 필드에 글쓴이 mbruid를 설정해 주어야, 패스워드 보호 정상 동작함.
		$backUrl = getLinkFilter($g['s'].'/?'.($_HS['usescode']?'r='.$r.'&amp;':'').($c?'c='.$c:'m='.$m),array('bid','skin','iframe','cat','p','sort','orderby','recnum','type','where','keyword'));
		getLink($backUrl ,'parent.' , '신고건수 누적으로 게시제한처리 되었습니다.' , $history);
	}
}
?>
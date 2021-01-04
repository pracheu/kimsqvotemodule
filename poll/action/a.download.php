<?php
if(!defined('__KIMS__')) exit;

include_once $g['dir_module'].'var/var.php';

$R=getUidData($table['s_upload'],$uid);
if (!$R['uid']) getLink('','','정상적인 요청이 아닙니다.','');

$filename = getUTFtoKR($R['name']);
$filetmpname = getUTFtoKR($R['tmpname']);

if ($R['url']==$d['upload']['ftp_urlpath'])
{
	$filepath = $d['upload']['ftp_urlpath'].$R['folder'].'/'.$filetmpname;
	$filesize = $R['size'];
}
else {
	$filepath = $g['path_file'].$R['folder'].'/'.$filetmpname;
	$filesize = filesize($filepath);
}


if( $post && $uid )
{
	$P = getUidData($table[$m.'data'], $post);

	// 보안 상, 다운로드 권한 등 반드시 확인이 필요함.
	$cfg_file = $g['path_module'].$m.'/var/var.'.$P['bbsid'].'.php';
	if( !file_exists($cfg_file) )	
		getLink('','','접근이 거부되었습니다. 게시판 설정을 확인할 수 없습니다.',-1);
	
	include_once $cfg_file;			
	$B['var'] = $d['bbs'];
	
	if( $my['admin'] && $my['uid']==1 )						$isAdmin	= 'super';
	else if( $my['admin'] && $my['uid']>1 )				$isAdmin	= 'admin';
	if( !$isAdmin && strstr(','.($d['bbs']['admin']?$d['bbs']['admin']:'.').',',','.$my['id'].',') )	$isAdmin = 'bbs';		

	if( !$isAdmin && $my['uid']!=$P['mbruid'] )
	{
		if ($B['var']['perm_l_down'] > $my['level'] || strstr($B['var']['perm_g_down'],'['.$my['mygroup'].']'))
		{
			getLink('','','다운로드 권한이 없습니다.','-1');
		}
	}
	

	// 다운로드 포인트 차감
	if( $B['var']['point3'] )
	{
		if( !$my['uid'] ) 
			getLink('','','로그인하신 후에 이용해 주세요.','-1');
						
		$UT = getDbData($table[$m.'xtra'],'parent='.$P['uid'],'*');
		if( !strpos('_'.$UT['down'],'['.$my['uid'].']') )
		{
			if( $confirm=='Y' && $my['point']>=$B['var']['point3'] )
			{
				if( $my['uid']!=$P['mbruid'])
				{
					if( !$isAdmin )
					{
						getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$my['uid']."','0','-".$B['var']['point3']."','다운로드(".getStrCut($P['subject'],15,'').")','".$date['totime']."'");
						getDbUpdate($table['s_mbrdata'],'point=point-'.$B['var']['point3'].',usepoint=usepoint+'.$B['var']['point3'],'memberuid='.$my['uid']);
					}
					
					if (!$UT['parent'])
						getDbInsert($table[$m.'xtra'],'parent,site,bbs,down',"'".$P['uid']."','".$s."','".$P['bbs']."','[".$my['uid']."]'");
					else
						getDbUpdate($table[$m.'xtra'],"down='".$UT['down']."[".$my['uid']."]'",'parent='.$P['uid']);
				}
				
				// 결제 완료 후, 다시 뷰 페이지로 이동
				if( $isAdmin )
					getLink($g['s'].'/?r='.$r.'&m='.$m.'&bid='.$P['bbsid'].'&uid='.$P['uid'],'parent.','관리자님으로 인증되셨습니다. 다운로드 받으세요.','');
				else
					getLink($g['s'].'/?r='.$r.'&m='.$m.'&bid='.$P['bbsid'].'&uid='.$P['uid'],'parent.','결제되었습니다. 다운로드 받으세요.','');
			}
			else 
			{
				// 결제 안내페이지로 이동
				getLink($g['s'].'/?r='.$r.'&m='.$m.'&bid='.$P['bbsid'].'&mod=down&dfile='.$uid.'&uid='.$P['uid'], 'parent.', '', '');
				exit;
			}
		}
	}

	getDbUpdate($table[$m.'data'], 'down=down+1', 'uid='.$post);
	getDbUpdate($table['s_upload'],'down=down+1','uid='.$R['uid']);
	//getDbUpdate($table['s_numinfo'],'download=download+1',"date='".$date['today']."' and site=".$s);
}
else
	getLink('','','정상적인 요청이 아닙니다.','');


header("Content-Type: application/octet-stream"); 
header("Content-Length: " .$filesize); 
header('Content-Disposition: attachment; filename="'.$filename.'"'); 
header("Cache-Control: private, must-revalidate"); 
header("Pragma: no-cache");
header("Expires: 0");

if ($R['url']==$d['upload']['ftp_urlpath'])
{
	$FTP_CONNECT = ftp_connect($d['upload']['ftp_host'],$d['upload']['ftp_port']); 
	$FTP_CRESULT = ftp_login($FTP_CONNECT,$d['upload']['ftp_user'],$d['upload']['ftp_pass']); 
	if (!$FTP_CONNECT) getLink('','','FTP서버 연결에 문제가 발생했습니다.','');
	if (!$FTP_CRESULT) getLink('','','FTP서버 아이디나 패스워드가 일치하지 않습니다.','');
	if($d['upload']['ftp_pasv']) ftp_pasv($FTP_CONNECT, true);
	
	$filepath = $g['path_tmp'].'session/'.$filetmpname;
	ftp_get($FTP_CONNECT,$filepath,$d['upload']['ftp_folder'].$R['folder'].'/'.$filetmpname,FTP_BINARY);
	ftp_close($FTP_CONNECT);
	$fp = fopen($filepath, 'rb');
	if (!fpassthru($fp)) fclose($fp);
	unlink($filepath);
}
else {
	$fp = fopen($filepath, 'rb');
	if (!fpassthru($fp)) fclose($fp);
}
exit;
?>
<?php
if(!defined('__KIMS__')) exit;

if (!$bid) die('{"code": "-1", "msg": "게시판 아이디가 지정되지 않았습니다."}');
$B = getDbData($table[$m.'list'],"id='".$bid."'",'*');
if (!$B['uid']) die('{"code": "-2", "msg": "존재하지 않는 게시판입니다."}');

include_once $g['dir_module'].'var/var.php';
include_once $g['dir_module'].'var/var.'.$B['id'].'.php';

if( $my['admin'] && $my['uid']==1 )						$isAdmin	= 'super';
else if( $my['admin'] && $my['uid']>1 )				$isAdmin	= 'admin';
if( !$isAdmin && strstr(','.($d['bbs']['admin']?$d['bbs']['admin']:'.').',',','.$my['id'].',') )	$isAdmin = 'bbs';

if( ($my['level']<$d['bbs']['perm_l_upload'] || strstr($d['bbs']['perm_g_upload'],'['.$my['mygroup'].']')) && !$isAdmin )
	die('{"code": "-1", "msg": "권한이 없는 요청입니다."}');


$bbsuid			= $B['uid'];
$bbsid				= $B['id'];
$mbruid			= $my['uid'];
$subject			= $my['admin'] ? trim($subject) : htmlspecialchars(trim($subject));
$html				= $html ? $html : 'TEXT';
$d_regis			= $date['totime'];

$category		= '';
$CAT = getDbData($table['s_uploadcat'], 'name="BSKR 게시판"', 'uid');
if( !$CAT['uid'] ) {
	$gid = getDbCnt($table['s_uploadcat'],'max(gid)','');
	$myuid = 1;
	$ablum_type = 1;
	$xname = 'BSKR 게시판';
	
	getDbInsert($table['s_uploadcat'],'gid,site,mbruid,type,hidden,users,name,r_num,d_regis,d_update', "'$gid','".$s."', '".$myuid."', '".$ablum_type."','0','','".$xname."','0','".$date['totime']."',''");
	$category = getDbCnt($table['s_uploadcat'],'max(uid)','');
}
else {
	$category = $CAT['uid'];
}


include_once $g['path_core'].'function/thumb.func.php';
$d['upload']['width_img'] = $d['bbs']['thumb_width']? $d['bbs']['thumb_width']: 350;

$fserver		= '';							// Not supported.
// 20170304 한상범 수정 -- 서버URL 제거  $g['url_root'].'/files/';	
$fserverurl 	= '/files/';		// Not supported.
$saveDir		= $g['path_file'];
$savePath1	= $saveDir.substr($date['today'],0,4);
$savePath2	= $savePath1.'/'.substr($date['today'],4,2);
$savePath3	= $savePath2.'/'.substr($date['today'],6,2);
$up_folder	= substr($date['today'],0,4).'/'.substr($date['today'],4,2).'/'.substr($date['today'],6,2);
$up_caption	= $subject;
$up_sync		= '['.$m.']';		//cync => sync로 바뀜. 모듈 내에서는 쓰이지는 않고, 그냥 "[bskrbbs]" 문자열을 식별자로 넣어둔다.

$res_url		= '';										// 업로드 이미지 URL (이미지만 해당)
$res_uid		= $upfiles ? $upfiles : '';		// 업로드 파일 UID ([1][2]... 형식)


for ($i = 1; $i < 4; $i++)
{
	if (!is_dir(${'savePath'.$i}))
	{
		mkdir(${'savePath'.$i},0707);
		@chmod(${'savePath'.$i},0707);
	}
}


for ($i = 0; $i < count($_FILES['upfile']['tmp_name']); $i++)
{
	if (!$_FILES['upfile']['tmp_name'][$i]) continue;
			
	$width				= 0;
	$height				= 0;
	$up_name		= strtolower($_FILES['upfile']['name'][$i]);
	$up_size			= $_FILES['upfile']['size'][$i];
	$up_fileExt		= getExt($up_name);
	$up_fileExt		= $up_fileExt == 'jpeg' ? 'jpg' : $up_fileExt;
	$up_type			= getFileType($up_fileExt);
	$up_tmpname	= md5($up_name).substr($date['totime'],8,14);
	$up_tmpname	= $up_type == 2 ? $up_tmpname.'.'.$up_fileExt : $up_tmpname;
	$up_mingid		= getDbCnt($table['s_upload'],'min(gid)','');
	$up_gid			= $up_mingid ? $up_mingid - 1 : 100000000;
	$up_saveFile	= $savePath3.'/'.$up_tmpname;
	//$up_hidden	= $up_type == 2 ? 1 : 0;
	
	
	if (!is_file($up_saveFile))
	{
		move_uploaded_file($_FILES['upfile']['tmp_name'][$i], $up_saveFile);
		if ($up_type == 2)
		{
			$up_tmpname2 = basename($up_tmpname, '.'.$up_fileExt);	// 확장자 제외하고 파일이름 획득 (abcdefg.jgp => abcdefg)
			$up_thumbname = $up_tmpname2.'_thumb.'.$up_fileExt;			// 썸네일 이름 설정 (abcdefg_thumb.jpg)
			$up_thumbFile = $savePath3.'/'.$up_thumbname;
			
			$response .= ' / up_thumbFile='.$up_thumbFile;
			ResizeWidth($up_saveFile,$up_thumbFile,  ($d['upload']['width_img'])? $d['upload']['width_img']: 150);
			@chmod($up_thumbFile,0707);
			$IM = getimagesize($up_saveFile);
			$width = $IM[0];
			$height= $IM[1];
		}
		@chmod($up_saveFile,0707);
	}
	
	
	//cync => sync로 바뀜, category 추가
	$QKEY = "gid,category,hidden,tmpcode,site,mbruid,type,ext,fserver,url,folder,name,tmpname,thumbname,size,width,height,caption,down,d_regis,d_update,sync";
	$QVAL = "'$up_gid','$category','$up_hidden','','$s','$mbruid','$up_type','$up_fileExt','$fserver','$fserverurl','$up_folder','$up_name','$up_tmpname','$up_thumbname','$up_size','$width','$height','$up_caption','0','$d_regis','','$up_sync'";	
	getDbInsert($table['s_upload'],$QKEY,$QVAL);		
	
	$up_lastuid = getDbCnt($table['s_upload'],'max(uid)','');
	$res_uid = $up_lastuid;
	if ($up_type == 2)
	{
		getDbUpdate($table['s_uploadcat'],'r_num=r_num+1', 'uid='.$category);
		$res_url = $g['url_root'].'/files/'.$up_folder.'/'.$up_tmpname;				// 이미지 URL Only
	}

	getDbUpdate($table['s_numinfo'],'upload=upload+1',"date='".$date['today']."' and site=".$s);

	if ($up_gid == 100000000) db_query("OPTIMIZE TABLE ".$table['s_upload'],$DB_CONNECT);
}


$arrRes = array('code' => 1, 'upid' => $res_uid, 'url' => $res_url, 'name' => $up_name, 'size' => $up_size, 'type' => $up_type);
die(json_encode($arrRes));
?>
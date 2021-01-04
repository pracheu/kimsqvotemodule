<?php
if (!defined('__KIMS__'))
	exit ;

$upid = trim($upid);
if (!$upid)
	die('{"code": "-1", "msg": "올바르지 않은 요청입니다."}');

if (!$bid)
	die('{"code": "-1", "msg": "게시판 아이디가 지정되지 않았습니다."}');
$B = getDbData($table[$m . 'list'], "id='" . $bid . "'", '*');
if (!$B['uid'])
	die('{"code": "-2", "msg": "존재하지 않는 게시판입니다."}');

include_once $g['dir_module'] . 'var/var.php';
include_once $g['dir_module'] . 'var/var.' . $B['id'] . '.php';

if ($d['bbs']['perm_l_write'] && !$my['uid'])
	die('{"code": "-1", "msg": "권한이 없는 요청입니다."}');

$U = getUidData($table['s_upload'], $upid);
if ($U['uid']) {

	if (($U['mbruid'] and $U['mbruid'] != $my['uid']) && (!$my['admin'])) {

		if (!strstr(',' . ($d['bbs']['admin'] ? $d['bbs']['admin'] : '.') . ',', ',' . $my['id'] . ',')) {

			if ($U['mbruid'])
				die('{"code": "-1", "msg": "권한이 없는 요청입니다..."}');
		}
	}

	getDbUpdate($table['s_numinfo'], 'upload=upload-1', "date='" . substr($U['d_regis'], 0, 8) . "' and site=" . $U['site']);
	getDbDelete($table['s_upload'], 'uid=' . $U['uid']);

	if (file_exists($g['path_file'] . $U['folder'] . '/' . $U['tmpname']))
		unlink($g['path_file'] . $U['folder'] . '/' . $U['tmpname']);

	if ($U['type'] == 2) {

		getDbUpdate($table['s_uploadcat'], 'r_num=r_num-1', 'uid=' . $U['category']);

		if (file_exists($g['path_file'] . $U['folder'] . '/' . $U['thumbname']))
			unlink($g['path_file'] . $U['folder'] . '/' . $U['thumbname']);
	}

	$xps = $g['path_file'] . $U['folder'] . '/' . $U['tmpname'] . '.xps';
	if (file_exists($xps))
		unlink($xps);

	$zip = $g['path_file'] . $U['folder'] . '/' . $U['tmpname'] . '.zip';
	if (file_exists($zip))
		unlink($zip);

	$arrRes = array('code' => 1, 'upid' => $upid, 'name' => $U['name'], 'type' => $U['type']);
	die(json_encode($arrRes));
} else {
	die('{"code": "-1", "msg": "존재하지 않는 첨부파일입니다."}');
}
?>

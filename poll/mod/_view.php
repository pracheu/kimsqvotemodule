﻿<?php
if (!defined('__KIMS__'))
	exit ;

if (!$my['admin'] && !strstr(',' . ($d['bbs']['admin'] ? $d['bbs']['admin'] : '.') . ',', ',' . $my['id'] . ',')) {

	if ($d['bbs']['perm_l_view'] > $my['level'] || strpos('_' . $d['bbs']['perm_g_view'], '[' . $my['mygroup'] . ']') || ($my['addfield'] && $my['addfield'] != $B['id'])) {
		if ( $B['imghead'] ) {
			$g['add_header_img'] = $g['url_module'] . '/var/files/' . $B['imghead'];
		}
		$d['bbs']['page-title'] = '본문열람';
		$g['main'] = $g['dir_module'] . 'themes/' . (($d['bbs']['skin']) ? $d['bbs']['skin'] : $d['bbs']['skin_main']) . '/permcheck.php';
		$d['bbs']['isperm'] = false;
	}
}

if ($R['hidden']) {

	if ($my['uid'] != $R['mbruid'] && $my['uid'] != $R['pw'] && !$isAdmin) {

		if (!strpos('_' . $_SESSION['module_' . $m . '_pwcheck'], '[' . $R['uid'] . ']')) {
			$g['main'] = $g['dir_module'] . 'themes/' . (($d['bbs']['skin']) ? $d['bbs']['skin'] : $d['bbs']['skin_main']) . '/pwcheck.php';
			$d['bbs']['isperm'] = false;
		}
	}
}

if ($d['bbs']['isperm'] && ($d['bbs']['hitcount'] || !strpos('_' . $_SESSION['module_' . $m . '_view'], '[' . $R['uid'] . ']'))) {

	if ($d['bbs']['point2'] && $my['uid'] != $R['mbruid']) {

		$UT = getDbData($table[$m . 'xtra'], 'parent=' . $R['uid'], '*');
		if (!strpos('_' . $UT['point2'], '[' . $my['uid'] . ']')) {
			$g['main'] = $g['dir_module'] . 'themes/' . (($d['bbs']['skin']) ? $d['bbs']['skin'] : $d['bbs']['skin_main']) . '/pointcheck.php';
			$d['bbs']['isperm'] = false;
		}
	} else {
		getDbUpdate($table[$m . 'data'], 'hit=hit+1', 'uid=' . $R['uid']);
		$_SESSION['module_' . $m . '_view'] .= '[' . $R['uid'] . ']';
	}
}

if ($d['bbs']['isperm'] && $R['upload']) {
	$d['upload'] = array();
	$d['upload']['tmp'] = $R['upload'];
	$d['_pload'] = getArrayString($R['upload']);
	foreach ($d['_pload']['data'] as $_val) {
		$U = getUidData($table['s_upload'], $_val);
		if (!$U['uid']) {
			$R['upload'] = str_replace('[' . $_val . ']', '', $R['upload']);
			$d['_pload']['count']--;
		} else {
			$d['upload']['data'][] = $U;
			if (!$U['cync']) {
				$_CYNC = "cync='[" . $m . "][" . $R['uid'] . "][uid,down][" . $table[$m . 'data'] . "][" . $R['mbruid'] . "][m:" . $m . ",bid:" . $R['bbsid'] . ",uid:" . $R['uid'] . "]'";
				getDbUpdate($table['s_upload'], $_CYNC, 'uid=' . $U['uid']);
			}
		}
	}
	if ($R['upload'] != $d['upload']['tmp']) {
		getDbUpdate($table[$m . 'data'], "upload='" . $R['upload'] . "'", 'uid=' . $R['uid']);
	}
	$d['upload']['count'] = $d['_pload']['count'];
}

$mod = $mod ? $mod : 'view';
if($download){
	$mod = "download";
}else if($view){
	$mod .= "-single";
}

$bid = $R['bbsid'];
?>
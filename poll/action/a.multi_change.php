<?php
if (!defined('__KIMS__'))
	exit ;

checkAdmin(0);
include_once $g['path_module'] . 'bskrbbs/var/var.php';

for ($i = 0; $i < count($post_members); $i++) {

	$R = getUidData($table['s_mbrid'], $post_members[$i]);
	if (!$R['uid']) {

		getLink('', '', '존재하지 않은 회원', '');
		continue;
	}

	// $B = getDbData($table['bskrbbslist'], "id='" . $addfield[$i] . "'", '*');
	// if (!$B['uid']) {
		// getLink('', '', $addfield[$i].'존재하지 않은 게시판', '');
		// continue;
	// }

	getDbUpdate($table['s_mbrdata'], "addfield='" . ${'addfield'.$post_members[$i]} . "'", 'memberuid=' . $post_members[$i]);

}

getLink('reload', 'parent.', '', '');
?>
<?php
if(!defined('__KIMS__')) exit;

checkAdmin(0);
include_once $g['path_module'].'bskrbbs/var/var.php';

foreach ($statistics_members as $val)
{
	$R = getDbData( 'rb_bskrbbs_stop', 'uuid="'.$val.'"', '*' );
	if (!$R['uuid']) continue;

	
	getDbDelete('rb_bskrbbs_stop',"uuid='".$R['uuid']."'");

}


getLink('reload','parent.','','');
?>
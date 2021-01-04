<?php
if(!defined('__KIMS__')) exit;

checkAdmin(0);

if (trim($theme) && is_dir($g['dir_module'].'themes/'.$theme))
{
	include_once $g['path_core'].'function/dir.func.php';
	DirDelete($g['dir_module'].'themes/'.$theme);
}
getLink($g['s'].'/?r='.$r.'&m=admin&module='.$m.'&front=skin','parent.','','');
?>
<?php
if(!defined('__KIMS__')) exit;

checkAdmin(0);

$i=0;
foreach($bbsmembers as $val) getDbUpdate($table[$m.'list'],'gid='.($i++),'uid='.$val);

$backUrl = $g['s'].'/?r='.$r.'&m=admin&module='.$m.'&uid='.$uid.'&front=main';
getLink($backUrl,'parent.','','');
?>
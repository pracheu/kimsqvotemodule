<?php
if ( ! defined( '__KIMS__' ) ) {
	exit;
}


$USER = getDbData($table[ $m . 'user' ], 'dong="'.$dong.'" and hosu="'.$hosu.'" and name="'.$name.'" and birth="'.$birth.'" and po_id="'.$pid.'"', '*');

if($USER['idx'] != ''){
	getLink($g['s'].'/?r='.$r.'&c='.$c.'&m='.$m.'&mod='.$mod.'&smod='.$smod.'&pid='.$pid.'&idx='.$USER['idx'],'parent.','인명부 확인되었습니다.','');
}else{
	getLink( '', '', '투표권한이 없습니다. 다시 확인해주세요.', '' );
}


?>
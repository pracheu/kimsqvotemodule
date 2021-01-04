<?php
if(!defined('__KIMS__')) exit;

$BP = getDbData('rb_bskrbbs_permit','muid='.$uid.' and buid='.$buid,'*');
if(!$BP) {
	getDbInsert('rb_bskrbbs_permit', 'muid,buid,permit,d_access', "'".$uid."','".$buid."','0','".$date['totime']."'" );
} else {
	getDbUpdate('rb_bskrbbs_permit', 'd_access="'.$date['totime'].'"', 'muid="'.$uid.'" and buid="'.$buid.'"' );
}

if($acflag == 'false') {
	//echo "<script>alert('".$mod."')</script>";
}
getLink($g['s'].'/?r='.$r,'parent.','가입신청이 접수되었습니다. 관리자 승인 후에 이용하여 주십시오.','');
?>
<?php
if ( ! defined( '__KIMS__' ) ) {
	exit;
}


$chkArray = $_POST['chk'];
for($i=0; $i<count($chkArray); $i++){
	getDbDelete($table[ $m . 'list' ],'po_id=' . $chkArray[$i]);
}
getLink( 'reload', 'parent.', '삭제되었습니다.', '' );

?>
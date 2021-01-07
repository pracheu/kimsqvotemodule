<?php
if ( ! defined( '__KIMS__' ) ) {
	exit;
}

if($smod == 'update'){
	
	
	$TEMP = getDbData($table[ $m . 'list' ], 'po_id=' . $pid, '*');
	
	if($TEMP){	
		$QVAL = "po_subject='$po_subject',po_poll1='$po_poll1',po_poll2='$po_poll2',po_poll3='$po_poll3',po_poll4='$po_poll4',po_poll5='$po_poll5',po_poll6='$po_poll6',po_poll7='$po_poll7',po_poll8='$po_poll8',po_poll9='$po_poll9',content='$content'";
		getDbUpdate( $table[ $m . 'list' ], $QVAL, 'po_id=' . $pid );
	}
	getLink( 'reload', 'parent.', '수정되었습니다.', '' );
	
}else{

	$po_site = $s;
	$po_poll3 = $po_poll3 ? $po_poll3 : "";
	$po_poll4 = $po_poll4 ? $po_poll4 : "";
	$po_poll5 = $po_poll5 ? $po_poll5 : "";
	$po_poll6 = $po_poll6 ? $po_poll6 : "";
	$po_poll7 = $po_poll7 ? $po_poll7 : "";
	$po_poll8 = $po_poll8 ? $po_poll8 : "";
	$po_poll9 = $po_poll9 ? $po_poll9 : "";
	$po_date = date("Y-m-d");
	$content = $content ? $content : "";
	
	$QKEY = "po_site,po_subject,po_poll1,po_poll2,po_poll3,po_poll4,po_poll5,po_poll6,po_poll7,po_poll8,po_poll9,po_date,start,end,content";
	$QVAL = "'$po_site','$po_subject','$po_poll1','$po_poll2','$po_poll3','$po_poll4','$po_poll5','$po_poll6','$po_poll7','$po_poll8','$po_poll9','$po_date','$start','$end','$content'";
	getDbInsert( $table[ $m . 'list' ], $QKEY, $QVAL );
	getLink( 'reload', 'parent.', '등록이 완료되었습니다.', '' );
}
?>
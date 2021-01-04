<?php
include_once '../../../_var/db.info.php';
include_once '../../../_var/table.info.php';
include_once '../../../_core/function/db.mysql.func.php';
include_once '../../../_core/function/sys.func.php';
$DB_CONNECT = @mysql_connect($DB['host'].':'.$DB['port'] , $DB['user'], $DB['pass']);
mysql_select_db($DB['name'], $DB_CONNECT);

$data = $_POST['data'];
$confernceid = $_POST['confernceid'];
$userid = $_POST['userid'];
$username = $_POST['username'];
//$time = $_POST['time'];
$result = $data.",".$confernceid.",".$userid.",".$username;//.",".$time;

$QKEY = "confernceid,userid,username,data";
$QVAL = "'$confernceid','$userid','$username','$data'";
getDbInsert('rb_chat_message',$QKEY,$QVAL);
echo $result;
?>
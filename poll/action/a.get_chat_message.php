<?php
include_once '../../../_var/db.info.php';
include_once '../../../_var/table.info.php';
include_once '../../../_core/function/db.mysql.func.php';
include_once '../../../_core/function/sys.func.php';
$DB_CONNECT = @mysql_connect($DB['host'].':'.$DB['port'] , $DB['user'], $DB['pass']);
mysql_select_db($DB['name'], $DB_CONNECT);

$confernceid = $_REQUEST['confernceid'];
$userid = $_REQUEST['userid'];

//$RCD = getDbArray('rb_chat_message','confernceid='.$confernceid,'*','time','desc',2,1);
$RCD = getDbArray('(SELECT * FROM rb_chat_message WHERE confernceid="'.$confernceid.'" ORDER BY time DESC LIMIT 200) A','','*','time','asc','','');
//$RCD = db_query('select * from (SELECT * FROM rb_chat_message WHERE confernceid="'.$confernceid.'" ORDER BY time DESC LIMIT 2) A order by time asc ',$DB_CONNECT);
/*<chat>
<info>
<ownerid>배쟁이</ownerid>
</info>
<message>
<id>0001</id>
<time>2020-05-20 10:41.28</time>
<userid>이선달</userid>
<usericon>./contents/images/ic_user_man.png</usericon>
<data>1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890</data>
</message>
*/
$doc = new DomDocument('1.0', 'UTF-8');
$chat = $doc->createElement('chat');
$doc->appendChild($chat);

$info = $doc->createElement('info');
$chat->appendChild($info);

$ownerid = $doc->createElement('ownerid', $userid);
$info->appendChild($ownerid);

$index = 1;
while($R=db_fetch_array($RCD)){
	$message = $doc->createElement('message');
	$info->appendChild($message);
	
	$id = $doc->createElement('id', '000'.$index);
	$message->appendChild($id);
	
	$time = $doc->createElement('time', $R['time']);
	$message->appendChild($time);
	
	$userid = $doc->createElement('userid', $R['userid']);
	$message->appendChild($userid);
	
	$username = $doc->createElement('username', $R['username']);
	$message->appendChild($username);
	
	$data = $doc->createElement('data', $R['data']);
	$message->appendChild($data);
	
	$index++;
}

$xml = $doc->saveXML();
header('Content-type: text/xml');
echo $xml;

?>
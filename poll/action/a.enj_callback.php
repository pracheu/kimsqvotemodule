¿<?php
/**
 * Created by PhpStorm.
 * User: rl
 * Date: 30/09/2017
 * Time: 03:24
 */

if (!defined('__KIMS__')) {
    exit;
}

//header('Content-type: application/json');

// 콜백 로그 저장 시작
$enter = "\n";
$log = date("Y-m-d H:i:s") . $enter;
$log .= json_encode($_REQUEST) . $enter . $enter;

if( 1 ) {
    //콜백 로그 저장
    $myfile = fopen($g['dir_module'] . 'action/a.enj_callback.log', "a") or die("Unable to open file!");
    fwrite($myfile, $log);
    fclose($myfile);
}

// 라이브 녹화 종료 후 콜백
$paramsRecord = '{"m":"bskrbbs","a":"enj_callback","vodname":"ezpt","event":"Record","timelength":"10794","videofile":"\/ezpt_cam0\/2017\/10\/01\/ezpt_cam0_2017_10_01_18_20_38.mp4"}';
$paramsRecord2 = '{"m":"bskrbbs","a":"enj_callback","upstoragename":"ezpt","event":"Record","timelength":"10794","videofile":"\/ezpt_cam0\/2017\/10\/01\/ezpt_cam0_2017_10_01_18_20_38.mp4"}';

// 동영상 업로드 후 변환 종료 후 콜백
$paramsConvert = '{"m":"bskrbbs","a":"enj_callback","jobid":"20171015024923806d699ce2f05724923965ead7b9a03c016","result":"success","path":"\/2017\/10\/15\/20171015024923806d699ce2f05724923965ead7b9a03c016.android_phone_720x1280.mp4.cvt_0.mp4","dur":"57000","orgname":"android_phone_720x1280.mp4","upstoragename":"vod","cvtoption":"800|1000000|30","etcparam":"testparam","event":"Convert"}';

// Stream URL http://enjsoft.movieupservice.net/plapt.html?ssl=0&pk=auto&streamurl=apt.movieupservice.net:1935/ezpt/ezpt_cam0/2017/10/01/ezpt_cam0_2017_10_01_18_23_53.mp4

//$event = "Record";

$server_ = "mvup://apt.movieupservice.net:1935/";

if(file_exists($g["dir_module"]."var/var.".$bid.".php")){
	include $g["dir_module"]."var/var.".$bid.".php";
}else{
}
if($d['bbs']['video_server']){
    $server_ = str_replace("http", "mvup", str_replace("https", "mvups", $d['bbs']['video_server']))."/";
}

if ($event == "Record") { // 라이브 녹화

    $server_ = $server ? $server : $server_;

//    테스트 용
//    $r = "m"; // 사이트 정보
//    $m = "bskrbbs"; // 게시판 모듈
//    $a = "enj_callback"; // action
//    $bid = "enjsoft"; // 업로드 할 게시판
//    $event = "Record"; // 콜백 구분
//    $server = "mvup://enjsoft.movieupservice.net:1935/"; // 서버정
//    $upstoragename = "ezpt";
//    $timelength = "10794";
//    $videofile = "/ezpt_cam0/2017/10/01/ezpt_cam0_2017_10_01_18_20_38.mp4";


// 자동녹화파일 [2017년 09월 29일 17시 28분 27초] ~ (xx분 xx초)

    $timelength = floor($timelength / 1000);
    //$duration = gmstrftime('%M:%S', $timelength);
    if ($timelength > 60) {
        $duration = floor($timelength / 60) . "분 ";
    } else {
        $duration = ($timelength % 60) . "초";
    }

    $subject = $title . date(" 자동녹화파일 [Y년 m월 d일 H시 i분] ~ ($duration)");

//	$streamurl = "http://apt.movieupservice.net:1935/" . $upstoragename . $videofile;
    $streamurl = $server_ . $upstoragename . $videofile;
    $content = '<p><a href="' . $streamurl . '" target="_blank">' . $streamurl . '</a></p>';

    $name = "StreamX"; // 게시자
    $category = ""; // 구분

    $html = "HTML";

    if (!$r) {
        die("존재하지 않는 사이트입니다.");
    } else {
        $R = getDbData($table['s_site'], "id='" . $r . "'", '*');
        if (!$R['uid']) {
            die("존재하지 않는 사이트입니다.");
        }
    }

    $B = getDbData($table[$m . 'list'], "id='" . $bid . "'", '*');

    if (!$B['uid']) {

        //die("존재하지 않는 게시판입니다.");
    }else{

		if (!$videofile) {
			die("동영상이 존재하지 않습니다.");
		}

		$subject = date(" 자동녹화파일 [Y년 m월 d일 H시 i분]  ~ ($duration)", strtotime(str_replace("_", "", substr($videofile, -23, 19))));

		if (!$subject) {
			die('제목이 입력되지 않았습니다.');
		}

		include_once $g['dir_module'] . 'var/var.php';
		include_once $g['dir_module'] . 'var/var.' . $B['id'] . '.php';

		//include_once $g['dir_module'].'action/a.write.php';

		$bbsuid = $B['uid'];
		$bbsid = $B['id'];
		$mbruid = $my['uid'];
		$id = $my['id'];
		$name = $my['uid'] ? $my['name'] : trim($name);
		$nic = $my['uid'] ? $my['nic'] : $name;
		$category = trim($category);
		$subject = $my['admin'] ? trim($subject) : htmlspecialchars(trim($subject));
		$content = trim($content);
		$html = 'HTML'; //$html ? $html : 'TEXT';
		$tag = trim($tag);
		$d_regis = $date['totime'];
		$d_comment = '';
		$ip = $_SERVER['REMOTE_ADDR'];
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$upload = $upfiles;
		$adddata = trim($adddata);
		$hidden = $hidden ? intval($hidden) : 0;
		$notice = $notice ? intval($notice) : 0;
		$display = $d['bbs']['display'] || $hidepost || $hidden ? 0 : 1;
		$parentmbr = 0;
		$point1 = trim($d['bbs']['point1']);
		$point2 = trim($d['bbs']['point2']);
		$point3 = $point3 ? filterstr(trim($point3)) : 0;
		$point4 = $point4 ? filterstr(trim($point4)) : 0;

		$pw = $hidden && $my['uid'] ? $my['uid'] : $pw;
		$mingid = getDbCnt($table[$m . 'data'], 'min(gid)', '');
		$gid = $mingid ? $mingid - 1 : 100000000.00;

		$QKEY = "site,gid,bbs,bbsid,depth,parentmbr,display,hidden,notice,name,nic,mbruid,id,pw,category,subject,content,html,tag,";
		$QKEY .= "hit,down,comment,oneline,trackback,score1,score2,singo,point1,point2,point3,point4,d_regis,d_modify,d_comment,d_trackback,upload,ip,agent,sns,adddata";
		$QVAL = "'$s','$gid','$bbsuid','$bbsid','$depth','$parentmbr','$display','$hidden','$notice','$name','$nic','$mbruid','$id','$pw','$category','$subject','$content','$html','$tag',";
		$QVAL .= "'0','0','0','0','0','0','0','0','$point1','$point2','$point3','$point4','$d_regis','','','','$upload','$ip','$agent','','$adddata'";
		getDbInsert($table[$m . 'data'], $QKEY, $QVAL);
		getDbInsert($table[$m . 'idx'], 'site,notice,bbs,gid', "'$s','$notice','$bbsuid','$gid'");
		getDbUpdate($table[$m . 'list'], "num_r=num_r+1,d_last='" . $d_regis . "'", 'uid=' . $bbsuid);
		getDbUpdate($table[$m . 'month'], 'num=num+1', "date='" . $date['month'] . "' and site=" . $s . ' and bbs=' . $bbsuid);
		getDbUpdate($table[$m . 'day'], 'num=num+1', "date='" . $date['today'] . "' and site=" . $s . ' and bbs=' . $bbsuid);
		$LASTUID = getDbCnt($table[$m . 'data'], 'max(uid)', '');
		if ($cuid) {
			getDbUpdate($table['s_menu'], "num='" . getDbCnt($table[$m . 'month'], 'sum(num)', 'site=' . $s . ' and bbs=' . $bbsuid) . "',d_last='" . $d_regis . "'", 'uid=' . $cuid);
		}
		if ($point1 && $my['uid']) {
			getDbInsert($table['s_point'], 'my_mbruid,by_mbruid,price,content,d_regis', "'" . $my['uid'] . "','0','" . $point1 . "','게시물(" . getStrCut($subject, 15, '') . ")포인트','" . $date['totime'] . "'");
			getDbUpdate($table['s_mbrdata'], 'point=point+' . $point1, 'memberuid=' . $my['uid']);

			getDbInsert($table[$m . 'xtra'], 'parent,site,bbs,point1', "'" . $LASTUID . "','" . $s . "','" . $bbsuid . "','[" . $my['uid'] . "]'");
		}

		if ($gid == 100000000.00) {
			db_query("OPTIMIZE TABLE " . $table[$m . 'idx'], $DB_CONNECT);
			db_query("OPTIMIZE TABLE " . $table[$m . 'data'], $DB_CONNECT);
			db_query("OPTIMIZE TABLE " . $table[$m . 'month'], $DB_CONNECT);
			db_query("OPTIMIZE TABLE " . $table[$m . 'day'], $DB_CONNECT);
		}
	}
} else if ($event == "Convert") { // 첨부 변환

    $tableConvert = "rb_bskrbbs_convert";
    $J = getDbData($tableConvert, "jobid=$jobid", "*");
    if ($J["jobid"]) {

        $QSET = "result = '$result', bid = '$bid', upstoragename = '$upstoragename', path = '$path', orgname = '$orgname', dur = $dur, reg_dt = now()";
        getDbUpdate($tableConvert, $QSET, "jobid=$jobid");
    } else {

        $QKEY = "jobid, result, bid, upstoragename, path, orgname, dur";
        $QVAL = "'$jobid', '$result', '$bid', '$upstoragename', '$path', '$orgname', $dur";
        getDbInsert($tableConvert, $QKEY, $QVAL);
    }

    /*
if ($result == "success") {

    $server_ = $server ? $server : $server_;

    $R = getDbData("rb_bskrbbs_data", "content like '%$jobid%'", "*");

    $uid = $R["uid"];
    $content = $R["content"];

    $html = new DOMDocument();
    $html->loadHTML($content);

    $element = $html->getElementById($jobid);
//        var_dump($element);

    $origin_url = $element->getAttribute("href");

//        $streamurl = "mvup://apt.movieupservice.net:1935/" . $upstoragename . $path;
    $streamurl = $server_ . $upstoragename . $path;


    $content = str_replace($origin_url, $streamurl, $content);


    $thisUrl = "http://gw.powerpt.net/movieup.php?cid=20171015024923806d699ce2f05724923965ead7b9a03c016";

    $thisUrl = '<p><a href="http://gw.powerpt.net/movieup.php?cid=20171015024923806d699ce2f05724923965ead7b9a03c016" target="_blank">http://gw.powerpt.net/movieup.php?cid=20171015024923806d699ce2f05724923965ead7b9a03c016</a></p>';


    getDbUpdate('rb_bskrbbs_data', "content='$content'", "uid=$uid");

    echo "Convert Success";
} else {

    echo $result;
}
*/
    echo "Convert Success";
} else if ($type == "StreamAuth") { // 인증

    $result = "expire=";
    if ($key) {

        session_unset();
        session_destroy();

        session_id($key);

//        session_reset();

//        session_regenerate_id();
        session_start();


//        var_dump(session_get_cookie_params());

//        if (isset($_COOKIE[session_name()])) {
//            setcookie(session_name(), '', time() - 42000, '/');
//        }

        if ($_SESSION["mbr_uid"]) {


            $result .= date("Y-m-d H:i:s", strtotime("+2 hour")); // 로그인시 2시간 재생 가능
        } else {

            $result .= date("Y-m-d H:i:s");
        }

    } else {

        $result .= date("Y-m-d H:i:s");
    }

    exit($result);

} else if ($event == "Check") {

//if(session_abort())
//    echo "1";
//    else
//        echo "2";

    $result = "";
    if ($sid) {


        session_unset();
        session_destroy();

        session_id($sid);

//        session_reset();

//        session_regenerate_id();
        session_start();


//        var_dump(session_get_cookie_params());

        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }


        $result = $_SESSION;
    } else {

    }

    exit(json_encode($result));
} else if ($event == "Stop" || $event == "stop") {


//    var_dump($_REQUEST);
    //event=stop&tflen={전송바이트수}&tfstart={스트리밍시작시간}&tfend={스트리밍종료시간}&timespan={시작과 종료 시간차 밀리초}&dev={단말기 종류}&path={스트리밍서브경로}&userhost={단말IP}

    // upstoragename vod/bid
    // tflen 전송바이트수
    // tfstart 스트리밍시작시간
    // tfend 스트리밍종료시간
    // timespan 시작과 종료 시간차 밀리초
    // dev 단말기 종류
    // path 스트리밍서브경로
    // userhost 단말IP

    if (!$timespan)
        $timespan = 0;

    if(!$id)
        $id = 'null';
	
    if( 1 ) {
    //if(!$key) {

	//$islocals = '';
	$islocals = $islocalstreamx;

	$d_regis = date("YmdHis");
	$qkey = "bid, tflen, tfstart, tfend, timespan, dev, path, userhost, id, d_regis, islocalstreamx";
	$qvalue = "'$bid','$tflen', '$tfstart', '$tfend', $timespan, '$dev', '$path', '$userhost', '$id', '$d_regis', '$islocals'";

	$insertResult = getDbInsertReturn('rb_bskrbbs_stop', $qkey, $qvalue);
	if($insertResult){
		echo 'success';
	} else {
		echo 'fail for the log of playback';
	}

	if( 0 ) {
	//if( !strncmp($bid, "JIC", 3) {
		$myfile = fopen($g['dir_module'] . 'action/a.enj_callback2.log', "a"); // or die("Unable to open file!");
		if($myfile) {
			fwrite($myfile, $qkey);
			fwrite($myfile, "\r\n");
			fwrite($myfile, $qvalue);
			if($insertResult){
				fwrite($myfile, "\r\ngetDbInsertReturn(rb_bskrbbs_stop):true\r\n");
			}
			else {
				fwrite($myfile, "\r\ngetDbInsertReturn(rb_bskrbbs_stop):false\r\n");
			}

			if($key)
				fwrite($myfile, $key);
			else
				fwrite($myfile, "(null)");

			fwrite($myfile, "\r\n");
			fclose($myfile);
		}
	}
    }
    else {
	echo 'fail for the log of rtmp annouance';
    }

} else {
    exit("알수없음");
}


exit();
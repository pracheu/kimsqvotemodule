<?php
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
$fmode = "w"; // 새로 쓰기
$fmode = "a"; // 추가 쓰기
$myfile = fopen($g['dir_module'] . 'action/a.enj_log_temp.txt', $fmode) or die("Unable to open file!");
fwrite($myfile, $log);
fclose($myfile);
// 콜백 로그 저장 종료


// 라이브 녹화 종료 후 콜백
$paramsRecord = '{"m":"bskrbbs","a":"enj_callback","vodname":"ezpt","event":"Record","timelength":"10794","videofile":"\/ezpt_cam0\/2017\/10\/01\/ezpt_cam0_2017_10_01_18_20_38.mp4"}';
$paramsRecord2 = '{"m":"bskrbbs","a":"enj_callback","upstoragename":"ezpt","event":"Record","timelength":"10794","videofile":"\/ezpt_cam0\/2017\/10\/01\/ezpt_cam0_2017_10_01_18_20_38.mp4"}';

// 동영상 업로드 후 변환 종료 후 콜백
$paramsConvert = '{"m":"bskrbbs","a":"enj_callback","jobid":"20171015024923806d699ce2f05724923965ead7b9a03c016","result":"success","path":"\/2017\/10\/15\/20171015024923806d699ce2f05724923965ead7b9a03c016.android_phone_720x1280.mp4.cvt_0.mp4","dur":"57000","orgname":"android_phone_720x1280.mp4","upstoragename":"vod","cvtoption":"800|1000000|30","etcparam":"testparam","event":"Convert"}';

// Stream URL http://enjsoft.movieupservice.net/plapt.html?ssl=0&pk=auto&streamurl=apt.movieupservice.net:1935/ezpt/ezpt_cam0/2017/10/01/ezpt_cam0_2017_10_01_18_23_53.mp4

//$event = "Record";

$server_ = "mvup://apt.movieupservice.net:1935/";

include $g["dir_module"]."var/var.".$bid.".php";
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

    if(!$uid)
        $uid = 'null';

    $d_regis = date("YmdHis");
    $qkey = "bid, tflen, tfstart, tfend, timespan, dev, path, userhost, uid, d_regis";
    echo $qvalue = "'$bid','$tflen', '$tfstart', '$tfend', $timespan, '$dev', '$path', '$userhost', $uid, $d_regis";
    getDbInsert('rb_bskrbbs_stop', $qkey, $qvalue);


} else {

    exit("알수없음");
}


exit();
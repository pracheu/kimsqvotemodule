<?php
if ( ! defined( '__KIMS__' ) ) {
	exit;
}

$isMobile = false;
if ( $g['mobile'] && $_SESSION['pcmode'] != 'Y' ) {
	$isMobile = true;
}

include_once $g['dir_module'] . 'mod/_func.php';
include_once $g['dir_module'] . 'var/var.php';

$d['poll']['skin']   = $d['poll']['skin_total'];
$d['poll']['isperm'] = true;

$spage = $spage ? $spage : 'header'; // spage 파라미터로 페이지를 선택하여 호출할 수 있도록 함
$_mod = $_mod ? $_mod : 'list';
 
$g['dir_module_skin'] = $g['dir_module'].'themes/'.$d['poll']['skin_main'].'/';
$g['url_module_skin'] = $g['url_module'].'/themes/'.$d['poll']['skin_main'];
$g['img_module_skin'] = $g['url_module_skin'].'/images';
 
$g['dir_module_mode'] = $g['dir_module_skin'].$spage;
$g['url_module_mode'] = $g['url_module_skin'].'/'.$spage;
 
$g['url_reset'] = $g['s'].'/?r='.$r.'&m='.$m; // 기본링크
$g['push_location'] = '<li class="active">'.$_HMD['name'].'</li>'; // 현재위치 셋팅
 
$g['main'] = $g['dir_module_mode'].'.php'; // 실제 화면에 출력할 파일 셋팅
?>
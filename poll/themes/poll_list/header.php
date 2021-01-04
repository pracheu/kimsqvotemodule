<?php
include_once $g['path_module'].'poll/mod/_func.php';

$SITES = getDbArray($table['s_site'],'','*','gid','asc',0,1);
$year1	= $year1  ? $year1  : substr($date['today'],0,4);
$month1	= $month1 ? $month1 : substr($date['today'],4,2);
$day1	= $day1   ? $day1   : 1;//substr($date['today'],6,2);
$year2	= $year2  ? $year2  : substr($date['today'],0,4);
$month2	= $month2 ? $month2 : substr($date['today'],4,2);
$day2	= $day2   ? $day2   : substr($date['today'],6,2);


$sort	= $sort ? $sort : 'po_id';
$orderby= $orderby ? $orderby : 'asc';
$recnum	= $recnum && $recnum < 200 ? $recnum : 20;

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';
$RCD = getDbArray($table['polllist'],$_WHERE,'*',$sort,$orderby,$recnum,$p);
$NUM = getDbRows($table['polllist'],$_WHERE);
$TPG = getTotalPage($NUM,$recnum);

$colspan = 9;
?>

<div id="bskradm">
	<div class="local_ov01 local_ov">
		<h2>전자투표 | 온라인 투표를 위한 공간입니다.</h2>
	</div>
	<!-- 탭 메뉴 상단 시작 -->
	<ul class="tabs">
		<li class="tab-link current" data-tab="tab-1">투표리스트</li>
		<!--li class="tab-link" data-tab="tab-2">메뉴_둘</li-->
		<li class="tab-link" data-tab="tab-2">관리자메뉴</li>
	</ul>
	<!-- 탭 메뉴 상단 끝 -->
	<!-- 탭 메뉴 내용 시작 -->
		<div id="tab-1" class="tab-content current">
			<?php include 'list.php'; ?>
		</div>
		<div id="tab-2" class="tab-content">		
			<?php include 'adminlist.php'; ?>
		</div>
	<!-- 탭 메뉴 내용 끝 -->
</div>
<div class="container">

</div>

<script>
$(document).ready(function(){
	
	$('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	})

});
</script>


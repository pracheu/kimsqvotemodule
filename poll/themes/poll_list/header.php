<?php
include_once $g['path_module'].'poll/mod/_func.php';
include_once $g['path_module'].'poll/plugin/jquery-ui/datepicker.php';

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


$mod = $mod ? $mod : 'list';
$smod = $smod ? $smod : '';

?>
<form id="hiddenform" name="hiddenform" action="<?php echo $g['s']?>/">
	<input type="hidden" name="r" value="<?php echo $r?>" />
	<input type="hidden" name="c" value="<?php echo $c?>" />
	<input type="hidden" name="m" value="<?php echo $m?>" />
	<input type="hidden" id="mod" name="mod" value="<?php echo $mod?>">
</form>
<div id="bskrlist">
	<div class="local_ov01 local_ov">
		<h2>전자투표 | 온라인 투표를 위한 공간입니다.</h2>
	</div>
	<!-- 탭 메뉴 상단 시작 -->
	<ul class="tabs">
		<li class="tab-link <?php if($mod == "list") :?>current <?php endif?>" data-tab="list">투표리스트</li>
		<li class="tab-link <?php if($mod == "adminpolllist") :?>current <?php endif?>" data-tab="adminpolllist">관리자메뉴</li>
	</ul>
	<!-- 탭 메뉴 상단 끝 -->
	<!-- 탭 메뉴 내용 시작 -->
	<?php if($mod == "list") :?>
	<div id="list" class="tab-content current ">
		<?php 
		if($smod == ''){
			include 'list.php'; 
		}elseif($smod == 'result'){
			include 'result.php'; 
		}elseif($smod == 'step1'){
			include 'personcheck.php'; 
		}elseif($smod == 'step2'){
			include 'voteing.php'; 
		}
		?>
	</div>
	<?php endif?>
	<?php if($mod == "adminpolllist") :?> 
		<div id="adminpolllist" class="tab-content current">		
			<?php 
			if($smod == ''){
				include 'adminpolllist.php'; 
			}elseif($smod == 'update' || $smod == 'insert'){
				include 'adminpollupdate.php'; 
			}
			?>
		</div>
	<?php endif?>
	<!-- 탭 메뉴 내용 끝 -->
</div>
<div class="container">

</div>

<script>
$(document).ready(function(){
	
	$('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');
		
		if(tab_id == 'list'){
			document.hiddenform.mod.value = 'list';
		}else if(tab_id == 'adminpolllist'){
			document.hiddenform.mod.value = 'adminpolllist';
		}
		document.hiddenform.submit();
	})

});
</script>


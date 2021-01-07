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
$orderby= $orderby ? $orderby : 'desc';
$recnum	= $recnum && $recnum < 200 ? $recnum : 20;

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';
$_WHERE = 'po_site="'.$s.'"';
$RCD = getDbArray($table['polllist'],$_WHERE,'*',$sort,$orderby,$recnum,$p);
$NUM = getDbRows($table['polllist'],$_WHERE);
$TPG = getTotalPage($NUM,$recnum);

$colspan = 9;
?>
<div id="bskrlist">
	<div class="local_ov01 local_ov">
		<h2>투표수 <?php echo number_format($NUM) ?>개</h2>
	</div>
</div>
<div id="bskrlist">
	<form name="listForm" id="listForm" action="<?php echo $g['s']?>/">
		<input type="hidden" name="r" value="<?php echo $r?>" />
		<input type="hidden" name="m" value="<?php echo $m?>" />
		<input type="hidden" name="a" value="" />	
		<input type="hidden" id="mod" name="mod" value="<?php echo $mod?>">	
		<input type="hidden" id="smod" name="smod" value="<?php echo $smod?>">	
		<input type="hidden" id="pid" name="pid" value="">
		<div class="table-responsive">
			<table class="table table-striped table-admin">
			<thead>
			<tr>
				<th scope="col" class="side1">등록일</th>
				<th scope="col">제목</th>
				<th scope="col">기간</th>
				<th scope="col">상태</th>
				<th scope="col" class="side2">결과</th>
			</tr>
			</thead>
			<tbody>
			<?php while($R=db_fetch_array($RCD)):?>
			
				<tr>
					<td class="td_num"><?php echo $R['po_date'] ?></td>
					<td><?php echo $R['po_subject'] ?></td>
					<td><?php echo $R['start'] ?> ~ <?php echo $R['end'] ?></td>
					<td class="td_num" style="text-align:center">
						<?php
							$today = strtotime(date('Y-m-d'));
							$startdate = strtotime($R['start']);
							$enddate = strtotime($R['end']);
						?>
						<?php if($today < $startdate) : ?>
							예정
						<?php elseif($today <= $enddate) : ?>
							<input class="btn btn-sm btn-primary" type="submit" value="투표하기" onclick="startVote(<?php echo "'".$R['po_id']."'"?>)">
						<?php else :?>
							종료
						<?php endif ?>
					</td>
					<td class="td_mngsmall">
						<?php if($today < $startdate) : ?>
							-
						<?php elseif($today <= $enddate) : ?>
							-
						<?php else :?>
							<input class="btn btn-sm btn-primary" type="submit" value="결과" onclick="resultOpen(<?php echo "'".$R['po_id']."'"?>)">
						<?php endif ?>
					</td>
				</tr>
			
			<?php endwhile; ?>
			<?php

			if ($NUM==0)
				echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
			?>
			</tbody>
			</table>
		</div>
		<div class="pagebox01">
			<script type="text/javascript">getPageLink(10,<?php echo $p?>,<?php echo $TPG?>,'<?php echo $g['img_core']?>/page/default');</script>
		</div>
	</form>
</div>
<script>
function resultOpen(v){
	document.listForm.smod.value = "result";
	document.listForm.pid.value = v;
}
function startVote(v){
	document.listForm.smod.value = "step1";
	document.listForm.pid.value = v;
}

</script>

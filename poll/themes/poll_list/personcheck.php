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


$colspan = 9;
?>
<div id="bskrlist">
	<div class="local_ov01 local_ov">
		<h2>투표본인확인 (선거인명부확인)</h2>
	</div>
</div>
<div id="bskrlist">
	<form name="listForm" id="listForm"  method="post" action="<?php echo $g['s']?>/" target="_action_frame_<?php echo $m?>" onsubmit="return inputCheck(this);">
		<input type="hidden" name="r" value="<?php echo $r?>" />
		<input type="hidden" name="c" value="<?php echo $c?>" />
		<input type="hidden" name="m" value="<?php echo $m?>" />
		<input type="hidden" name="a" value="personcheck" />	
		<input type="hidden" id="mod" name="mod" value="<?php echo $mod?>">	
		<input type="hidden" id="smod" name="smod" value="step2">	
		<input type="hidden" id="pid" name="pid" value="<?php echo $pid?>">
		<div class="table-responsive">
			<table class="table table-striped table-admin">
			<thead>
			</thead>
			<tbody>			
				<tr>
					<th class="tleft">이름</th>
					<td class="tleft">
						<input type="text" name="name" id="name" value="" size="13">
					</td>
				</tr>	
				<tr>
					<th class="tleft">생년월일</th>
					<td class="tleft">
						<input type="text" name="birth" value="" id="birth" class="frm_input" size="13" maxlength="10">
					</td>
				</tr>
				<tr>
					<th class="tleft">동</th>
					<td class="tleft">
						<input type="text" name="dong" value="" id="dong" class="frm_input" size="13" maxlength="10">
					</td>
				</tr>
				<tr>
					<th class="tleft">호수</th>
					<td class="tleft">
						<input type="text" name="hosu" value="" id="hosu" class="frm_input" size="13" maxlength="10">
					</td>
				</tr>
			</tbody>
			</table>
		</div>
		<div style="text-align:center">
			<button type="submit" class="btn btn-sm btn-primary">확인</button>
			<button type="button" class="btn btn-sm btn-danger" onclick="returnList()">목록으로</button>
		</div>
	</form>
</div>

<form id="hiddenupdateform" name="hiddenupdateform" action="<?php echo $g['s']?>/">
	<input type="hidden" name="r" value="<?php echo $r?>" />
	<input type="hidden" name="c" value="<?php echo $c?>" />
	<input type="hidden" name="m" value="<?php echo $m?>" />
	<input type="hidden" id="mod" name="mod" value="<?php echo $mod?>">
</form>
<script>
function returnList(){
	document.hiddenupdateform.submit();
}

function inputCheck(f){
	if (f.name.value == '')
	{
		alert("이름을 입력해주세요.");
		return false;
	}
	if (f.birth.value == '')
	{
		alert("생일을 입력해주세요.");
		return false;
	}
	if (f.dong.value == '')
	{
		alert("동을 입력해주세요.");
		return false;
	}
	if (f.hosu.value == '')
	{
		alert("호수를 입력해주세요.");
		return false;
	}
}

$(document).ready(function()
{
    $("#birth").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});

</script>

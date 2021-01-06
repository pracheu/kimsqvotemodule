<?php
$year1 = $year1 ? $year1 : substr($date['today'], 0, 4);
$month1 = $month1 ? $month1 : substr($date['today'], 4, 2);
$day1 = $day1 ? $day1 : 1; //substr($date['today'],6,2);
$year2 = $year2 ? $year2 : substr($date['today'], 0, 4);
$month2 = $month2 ? $month2 : substr($date['today'], 4, 2);
$day2 = $day2 ? $day2 : substr($date['today'], 6, 2);
//echo $smod;

$where = "po_id='" . $pid . "'";
$P = getDbData($table['polllist'], $where, '*');
$where = "po_id='" . $pid . "'";
$sort = $sort ? $sort : 'idx';
$orderby = $orderby ? $orderby : 'asc';
$recnum = $recnum && $recnum < 200 ? $recnum : 20;

$UCD = getDbArray($table['polluser'], $where, '*', $sort, $orderby, $recnum, $p);
?>

<div id="bskrlist">
	<div class="local_ov01 local_ov">
		<?php if($smod == 'update') : ?>
			<h2>투표 수정</h2> <strong><span style="color:red">*</span>은 필수 항목입니다.</strong>
		<?php elseif($smod == 'insert') : ?>
			<h2>신규투표 등록</h2> <strong><span style="color:red">*</span>은 필수 항목입니다.</strong>
		<?php endif ?>
	</div>


	<form name="fpoll" id="fpoll" action="./poll_form_update2.php" onsubmit="return poll_submit(this);" method="post" enctype="multipart/form-data">
	<input type="hidden" name="po_id" value="<?php echo $po_id ?>">
	<input type="hidden" name="w" value="<?php echo $w ?>">
	<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php echo $stx ?>">
	<input type="hidden" name="sst" value="<?php echo $sst ?>">
	<input type="hidden" name="sod" value="<?php echo $sod ?>">
	<input type="hidden" name="page" value="<?php echo $page ?>">
	<input type="hidden" name="token" value="">

	<div class="table-responsive">
		<table class="table table-striped table-admin">
		<tbody>
		<tr>
			<th class="tleft" scope="row"><label for="po_subject">투표 제목<strong style="color:red">*</strong></label></th>
			<td class="tleft"><input type="text" name="po_subject" value="<?php echo $P['po_subject'] ?>" id="po_subject" required class="required frm_input" size="80" maxlength="125"></td>
		</tr>
		<?php for($i = 1; $i < 10; $i++) : ?>
		<?php
			$required = '';
			$sound_only = '';
			if ($i==1 || $i==2) {
				$required = 'required';
				$sound_only = '<strong style="color:red">*</strong>';
			}
			$po_poll = $P['po_poll'.$i];
		?>
		<tr>
        <th class="tleft" scope="row"><label for="po_poll<?php echo $i ?>">항목 <?php echo $i ?><?php echo $sound_only ?></label></th>
        <td class="tleft">
            <input type="text" name="po_poll<?php echo $i ?>" value="<?php echo $po_poll ?>" id="po_poll<?php echo $i ?>" <?php echo $required ?> class="frm_input <?php echo $required ?>" maxlength="125">
            <label for="po_cnt<?php echo $i ?>">항목 <?php echo $i ?> 투표수</label>
			<?php if($smod == 'update') :?>
				<?php if($P['start'] > date("Y-m-d")) : ?>

					<input type="text" disabled name="po_cnt<?php echo $i ?>" value="-" id="po_cnt<?php echo $i ?>" class="frm_input" size="3">

				<?php elseif($P['end'] >= date("Y-m-d")) : ?>
				
					<input type="text" disabled name="po_cnt<?php echo $i ?>" value="-" id="po_cnt<?php echo $i ?>" class="frm_input" size="3">
					
				<?php else : ?>
				
					<input type="text" disabled name="po_cnt<?php echo $i ?>" value="<?php echo $P['po_cnt'.$i] ?>" id="po_cnt<?php echo $i ?>" class="frm_input" size="3">
					
				<?php endif ?>
			<?php else : ?>
			<?php endif ?>
		</td>
		</tr>
		<?php endfor ?>
		<!--tr>
			<th scope="row"><label for="po_level">투표 가능 회원레벨</label></th>
			<td>
				<?php //echo help("레벨을 1로 설정하면 손님도 투표할 수 있습니다.") ?>
				<?php //echo get_member_level_select('po_level', 1, 10, $po['po_level']) ?> 이상 투표할 수 있음
			</td>
		</tr-->
		<?php if ($smod == 'update') : ?>
		<tr>
			<th class="tleft" scope="row">투표 등록일</th>
			<td class="tleft"><?php echo $P['po_date']; ?></td>
		</tr>
		<?php endif ?>
		<tr>
			<th class="tleft" scope="row">투표 시작일<?php if($smod == 'update') : ?> <strong style="color:red">수정불가능합니다.</strong> <?php endif ?></th>
			<td class="tleft"> <input type="text" name="start" value="<?php echo $P['start'] ?>" id="start" class="frm_input" size="11" maxlength="10" <?php if($smod == 'update') : ?> disabled <?php endif ?>></td>
		</tr>

		<tr>
			<th class="tleft" scope="row">투표 종료일<?php if($smod == 'update') : ?> <strong style="color:red">수정불가능합니다.</strong> <?php endif ?></th>
			<td class="tleft"> <input type="text" name="end" value="<?php echo $P['end'] ?>" id="end" class="frm_input" size="11" maxlength="10" <?php if($smod == 'update') : ?> disabled <?php endif ?>></td>
		</tr>
		<tr>
			<th class="tleft" scope="row"><label for="content">선거정보/투표내용</label></th>
			<td class="tleft"><textarea name="content" id="content" rows="10" style="width:90%"><?php echo $P['content']; ?></textarea></td>
		</tr>
		<?php if ($smod == 'update') : ?>
		<tr>
			<th class="tleft" scope="row"><label for="po_ips">투표 참가 IP</label></th>
			<td class="tleft"><textarea name="po_ips" id="po_ips" readonly rows="3" style="width:90%"><?php echo preg_replace("/\n/", " / ", $P['po_ips']) ?></textarea></td>
		</tr>
		<?php endif ?>
		<tr id="printArea">
			<th class="tleft" scope="row"><label for="mb_ids">선거 인명부 리스트</label></th>
			<td>
				<table id="parea" style="border:1px;width:100%;height:200px;overflow-y:scroll;border:1px solid;">
				<tr>
					<th>번호</th>
					<th>투표유무</th>
					<th>동</th>
					<th>호</th>
					<th>성명</th>
					<th>생년월일</th>
					<th>서명</th>
				</tr>
				<?php $i = 0; ?>
				<?php while($U=db_fetch_array($UCD)):?>
					<tr>
						<td><?php echo ++$i; ?></td>
						<td>
							<?php if($U['puse']==1) : ?>
								<font color="#0000ff">투표함</font>
							<?php else : ?>
								<font color="#ff0000">투표안됨</font>
							<?php endif ?>
						</td>
						<td><?php echo $U['dong'] ?></td>
						<td><?php echo $U['hosu'] ?></td>
						<td><?php echo $U['name'] ?></td>
						<td><?php echo $U['birth'] ?></td>
						<td>
						
						<?
						?>
						
						</td>
					</tr>
				<?php endwhile ?>
					
				</table>

			</td>
		</tr>
		</tbody>
		</table>

	</div>

	<div class="btn_confirm01 btn_confirm">
		
		<?php if($smod == 'update') : ?>
		<button type="submit" class="btn btn-sm btn-primary">수정</button>
		<?php elseif($smod == 'insert') : ?>
		<button type="submit" class="btn btn-sm btn-primary">저장</button>
		<?php endif ?>
		<button type="submit" class="btn btn-sm btn-primary">목록으로</button>
		<button type="submit" class="btn btn-sm btn-primary">결과출력</button>
		<a href="./poll_list2.php?<?php echo $qstr ?>">목록</a>
		<a href="./poll_print.php?po_id=<?php echo $po_id ?>" target=_blank>결과출력</a>
	<br><br><font color=#ff0000>※ 결과출력(새창에서 오른쪽 마우스 버튼을 누르고 인쇄하기를 하시면 됩니다)</font>
	</div>

	</form>

</div>

<script>


function poll_submit(f)
{
	if( $("#start").val() =="" || $("#start").val()=="0000-00-00")
	{
		alert('기간이 잘못 설정되었습니다.');
		return false;
	}
	if( $("#end").val() =="" || $("#end").val()=="0000-00-00")
	{
		alert('기간이 잘못 설정되었습니다.');
		return false;
	}

	if( $("#start").val() > $("#end").val() )
	{
		alert('기간이 잘못 설정되었습니다.');
		return false;
	}
    return true;
}

$(document).ready(function()
{
    $("#start, #end").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+130d" });
});

</script>

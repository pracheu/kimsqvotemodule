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
<div id="bskrlist">
	<div class="local_ov01 local_ov">
		<h2>투표수 <?php echo number_format($NUM) ?>개
		&nbsp;<font color=#ff0000>최초 등록후 투표일자는 수정할 수 없습니다.</font></h2>
	</div>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
<div class="sch_last">
    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl">
        <option value="po_subject">제목</option>
		<option value="po_site">사이트</option>
    </select>
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
    <input type="submit" class="btn_submit" value="검색">
</div>
</form>

<div style="text-align:right">
    <a href="./poll_form2.php" id="poll_add" class="btn btn-primary">신규 투표 등록</a>
	<br>
	<br>
</div>
<div id="bskrlist">
	<form name="alistForm" action="<?php echo $g['s']?>/">
		<input type="hidden" name="r" value="<?php echo $r?>" />
		<input type="hidden" name="m" value="<?php echo $m?>" />
		<input type="hidden" name="mod" value="<?php echo $mod?>" />
		<input type="hidden" name="smod" value="" />
		<input type="hidden" name="pid" value="" />
		<div class="table-responsive">
			<table class="table table-striped table-admin">
			<thead>
			<tr>
				<th scope="col" class="side1">
					<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
				</th>
				<th scope="col">번호</th>
				<th scope="col">제목</th>
				<th scope="col">투표권한</th>
				<th scope="col">투표수</th>
				<th scope="col">선거인명부</th>
				<th scope="col">시작일</th>
				<th scope="col">종료일</th>
				<th scope="col" class="side2">관리</th>
			</tr>
			</thead>
			<tbody>
			<?php while($R=db_fetch_array($RCD)):?>
			
				<tr>
					<td class="td_chk">
						<input type="checkbox" name="chk[]" value="<?php echo $row['po_id'] ?>" id="chk_<?php echo $i ?>">
					</td>
					<td class="td_num"><?php echo $R['po_id'] ?></td>
					<td><?php echo $R['po_subject'] ?></td>
					<td class="td_num"><?php echo $R['po_level'] ?></td>
					<td class="td_num"><?php echo $R['sum_po_cnt'] ?></td>
					<td class="td_num" style="text-align:center">
						<?php
							$puser = getDbRows($table['polluser'],'po_id="'.$R['po_id'].'"');
							 if($puser==0)
							 {
								 echo "<font color=#ff0000>없음</font><br>";
							 }
							 else
							 {
								 echo "<font color=#0000ff>".$puser."명</font><br>";
							 }
						?>
						<a href="<?php echo $g['dir_module_skin'] ?>pollexcel.php?po_id=<?=$R['po_id']?>" onclick="return excelform(this.href);" target="_blank">등록</a>


					   <a href="./poll_user_remove.php?po_id=<?=$R['po_id']?>" onclick="return complate_confirm();">삭제</a>

						
					</td>
					<td class="td_num"><?php echo $R['start'] ?></td>
					<td class="td_num"><?php echo $R['end'] ?></td>
					<td class="td_mngsmall"><input class="btn btn-sm btn-primary" type="submit" value="수정" onclick="updateOpen('update',<?php echo "'".$R['po_id']."'"?>)"></td>
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

		<input class="btn btn-sm btn-success" type="submit" value="선택삭제">
	</form>
</div>
<script>
function excelform(url)
{
    var opt = "width=600,height=450,left=10,top=10";
    window.open(url, "win_excel", opt);
    return false;
}

function updateOpen(cmd, v){
	document.alistForm.smod.value = cmd;
	document.alistForm.pid.value = v;
}

function complate_confirm()
{
	if(confirm("현재 해당 투표에 등록된 선거인명부를 모두 삭제하시겠습니까?\r\n삭제하시면 선거투표수의 오류가 생길수 있습니다.")) {
        var token = get_ajax_token();
        var href = el.href.replace(/&token=.+$/g, "");
        if(!token) {
            alert("토큰 정보가 올바르지 않습니다.");
            return false;
        }
        el.href = href+"&token="+token;
        return true;
    } else {
        return false;
    }
}



$(function() {
    $('#fpolllist').submit(function() {
        if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
            if (!is_checked("chk[]")) {
                alert("선택삭제 하실 항목을 하나 이상 선택하세요.");
                return false;
            }

            return true;
        } else {
            return false;
        }
    });
});
</script>

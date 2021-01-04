<?php
// 댓글링크
function getCommentLink($table, $parent, $cid) {
	$post = str_replace('bbs', '', $parent);		// 'bbs...' 에서 문자열 bbs 제거 
	$R = getDbData($table, "uid='".$post."'", "uid, site, bbs, bbsid");
	$link = RW('m=bskrbbs&bid='.$R['bbsid'].'&uid='.$R['uid'].($GLOBALS['s']!=$R['site']?'&s='.$R['site']:''));
	$link .= '&pos=cmt-'.$cid;
	$link .= '&cp=Y';
	return $link;
}

$SITES = getDbArray($table['s_site'],'','*','gid','asc',0,1);
$year1	= $year1  ? $year1  : substr($date['today'],0,4);
$month1	= $month1 ? $month1 : substr($date['today'],4,2);
$day1	= $day1   ? $day1   : 1;//substr($date['today'],6,2);
$year2	= $year2  ? $year2  : substr($date['today'],0,4);
$month2	= $month2 ? $month2 : substr($date['today'],4,2);
$day2	= $day2   ? $day2   : substr($date['today'],6,2);


$sort	= $sort ? $sort : 'uid';
$orderby= $orderby ? $orderby : 'asc';
$recnum	= $recnum && $recnum < 200 ? $recnum : 20;

$accountQue = $account ? 'site='.$account.' and ':'';

$_WHERE = $accountQue.'d_regis > '.$year1.sprintf('%02d',$month1).sprintf('%02d',$day1).'000000 and d_regis < '.$year2.sprintf('%02d',$month2).sprintf('%02d',$day2).'240000';

if ($where && $keyw)
{
	if (strstr('[name][nic][id][ip]',$where)) $_WHERE .= " and ".$where."='".$keyw."'";
	else $_WHERE .= getSearchSql($where,$keyw,$ikeyword,'or');
}
$RCD = getDbArray($table[$module.'comment'],$_WHERE,'*',$sort,$orderby,$recnum,$p);
$NUM = getDbRows($table[$module.'comment'],$_WHERE);
$TPG = getTotalPage($NUM,$recnum);
?>


<div id="bskrlist">
	<div class="sbox">
		<form name="procForm" action="<?php echo $g['s']?>/" method="get">
		<input type="hidden" name="r" value="<?php echo $r?>" />
		<input type="hidden" name="m" value="<?php echo $m?>" />
		<input type="hidden" name="module" value="<?php echo $module?>" />
		<input type="hidden" name="front" value="<?php echo $front?>" />

		<select name="account" class="account" onchange="this.form.submit();">
		<option value="">&nbsp;+ 전체사이트</option>
		<option value="">---------------------------</option>
		<?php while($S = db_fetch_array($SITES)):?>
		<option value="<?php echo $S['uid']?>"<?php if($account==$S['uid']):?> selected="selected"<?php endif?>>ㆍ<?php echo $S['name']?></option>
		<?php endwhile?>
		<?php if(!db_num_rows($SITES)):?>
		<option value="">등록된 사이트가 없습니다.</option>
		<?php endif?>
		</select>

		<div>
		<select name="year1">
		<?php for($i=$date['year'];$i>2000;$i--):?><option value="<?php echo $i?>"<?php if($year1==$i):?> selected="selected"<?php endif?>><?php echo $i?>년</option><?php endfor?>
		</select>
		<select name="month1">
		<?php for($i=1;$i<13;$i++):?><option value="<?php echo sprintf('%02d',$i)?>"<?php if($month1==$i):?> selected="selected"<?php endif?>><?php echo sprintf('%02d',$i)?>월</option><?php endfor?>
		</select>
		<select name="day1">
		<?php for($i=1;$i<32;$i++):?><option value="<?php echo sprintf('%02d',$i)?>"<?php if($day1==$i):?> selected="selected"<?php endif?>><?php echo sprintf('%02d',$i)?>일(<?php echo getWeekday(date('w',mktime(0,0,0,$month1,$i,$year1)))?>)</option><?php endfor?>
		</select> ~
		<select name="year2">
		<?php for($i=$date['year'];$i>2000;$i--):?><option value="<?php echo $i?>"<?php if($year2==$i):?> selected="selected"<?php endif?>><?php echo $i?>년</option><?php endfor?>
		</select>
		<select name="month2">
		<?php for($i=1;$i<13;$i++):?><option value="<?php echo sprintf('%02d',$i)?>"<?php if($month2==$i):?> selected="selected"<?php endif?>><?php echo sprintf('%02d',$i)?>월</option><?php endfor?>
		</select>
		<select name="day2">
		<?php for($i=1;$i<32;$i++):?><option value="<?php echo sprintf('%02d',$i)?>"<?php if($day2==$i):?> selected="selected"<?php endif?>><?php echo sprintf('%02d',$i)?>일(<?php echo getWeekday(date('w',mktime(0,0,0,$month2,$i,$year2)))?>)</option><?php endfor?>
		</select>

		<input type="button" class="btn btn-xs btn-default" value="기간적용" onclick="this.form.submit();" />
		<input type="button" class="btn btn-xs btn-default" value="어제" onclick="dropDate('<?php echo date('Ymd',mktime(0,0,0,substr($date['today'],4,2),substr($date['today'],6,2)-1,substr($date['today'],0,4)))?>','<?php echo date('Ymd',mktime(0,0,0,substr($date['today'],4,2),substr($date['today'],6,2)-1,substr($date['today'],0,4)))?>');" />
		<input type="button" class="btn btn-xs btn-default" value="오늘" onclick="dropDate('<?php echo $date['today']?>','<?php echo $date['today']?>');" />
		<input type="button" class="btn btn-xs btn-default" value="일주" onclick="dropDate('<?php echo date('Ymd',mktime(0,0,0,substr($date['today'],4,2),substr($date['today'],6,2)-7,substr($date['today'],0,4)))?>','<?php echo $date['today']?>');" />
		<input type="button" class="btn btn-xs btn-default" value="한달" onclick="dropDate('<?php echo date('Ymd',mktime(0,0,0,substr($date['today'],4,2)-1,substr($date['today'],6,2),substr($date['today'],0,4)))?>','<?php echo $date['today']?>');" />
		<input type="button" class="btn btn-xs btn-default" value="당월" onclick="dropDate('<?php echo substr($date['today'],0,6)?>01','<?php echo $date['today']?>');" />
		<input type="button" class="btn btn-xs btn-default" value="전월" onclick="dropDate('<?php echo date('Ym',mktime(0,0,0,substr($date['today'],4,2)-1,substr($date['today'],6,2),substr($date['today'],0,4)))?>01','<?php echo date('Ym',mktime(0,0,0,substr($date['today'],4,2)-1,substr($date['today'],6,2),substr($date['today'],0,4)))?>31');" />
		<input type="button" class="btn btn-xs btn-default" value="전체" onclick="dropDate('20090101','<?php echo $date['today']?>');" />
		</div>

		<div>

		<select name="sort" onchange="this.form.submit();">
		<option value="uid"<?php if($sort=='uid'):?> selected="selected"<?php endif?>>등록일</option>
		<option value="hit"<?php if($sort=='hit'):?> selected="selected"<?php endif?>>조회</option>
		<option value="oneline"<?php if($sort=='oneline'):?> selected="selected"<?php endif?>>한줄의견</option>
		<option value="score1"<?php if($sort=='score1'):?> selected="selected"<?php endif?>>점수1</option>
		<option value="score2"<?php if($sort=='score2'):?> selected="selected"<?php endif?>>점수2</option>
		<option value="singo"<?php if($sort=='singo'):?> selected="selected"<?php endif?>>신고</option>
		</select>
		<select name="orderby" onchange="this.form.submit();">
		<option value="desc"<?php if($orderby=='desc'):?> selected="selected"<?php endif?>>역순</option>
		<option value="asc"<?php if($orderby=='asc'):?> selected="selected"<?php endif?>>정순</option>
		</select>

		<select name="recnum" onchange="this.form.submit();">
		<option value="20"<?php if($recnum==20):?> selected="selected"<?php endif?>>20개</option>
		<option value="35"<?php if($recnum==35):?> selected="selected"<?php endif?>>35개</option>
		<option value="50"<?php if($recnum==50):?> selected="selected"<?php endif?>>50개</option>
		<option value="75"<?php if($recnum==75):?> selected="selected"<?php endif?>>75개</option>
		<option value="90"<?php if($recnum==90):?> selected="selected"<?php endif?>>90개</option>
		</select>

		<select name="where">
		<option value="subject"<?php if($where=='subject'):?> selected="selected"<?php endif?>>제목</option>
		<option value="content"<?php if($where=='content'):?> selected="selected"<?php endif?>>본문</option>
		<option value="name|nic"<?php if($where=='name|nic'):?> selected="selected"<?php endif?>>이름</option>
		<option value="id"<?php if($where=='id'):?> selected="selected"<?php endif?>>아이디</option>
		<option value="ip"<?php if($where=='ip'):?> selected="selected"<?php endif?>>아이피</option>
		</select>

		<input type="text" name="keyw" value="<?php echo stripslashes($keyw)?>" class="input" />

		<input type="submit" value="검색" class="btn btn-xs btn-info" />
		<input type="button" value="리셋" class="btn btn-xs btn-default" onclick="location.href='<?php echo $g['adm_href']?>';" />
		</div>

		</form>
	</div>

	
	<form name="listForm" action="<?php echo $g['s']?>/" method="post" target="_action_frame_<?php echo $m?>">
	<input type="hidden" name="r" value="<?php echo $r?>" />
	<input type="hidden" name="m" value="<?php echo $module?>" />
	<input type="hidden" name="a" value="" />

	<div class="info">
		<div class="article">
			<?php echo number_format($NUM)?>개(<?php echo $p?>/<?php echo $TPG?>페이지)
		</div>
		
		<div class="category">
		</div>
		<div class="clear"></div>
	</div>

	<div class="table-responsive">
		<table class="table table-striped table-admin">
		<colgroup> 
		<col width="30"> 
		<col width="50"> 
		<col width="380"> 
		<col width="80"> 
		<col width="50"> 
		<col width="50"> 
		<col width="50"> 
		<col width="120">
		<col>
		</colgroup> 
		<thead>
		<tr>
		<th scope="col" class="side1"><img src="<?php echo $g['path_module'].$module?>/admin/img/ico_check_01.gif" alt="선택/반전" class="hand" onclick="chkFlag('comment_members[]');" /></th>
		<th scope="col">번호</th>
		<th scope="col">제목</th>
		<th scope="col">이름</th>
		<th scope="col" class="center">점수1</th>
		<th scope="col" class="center">점수2</th>
		<th scope="col" class="center">신고</th>
		<th scope="col" class="center">날짜</th>
		<th scope="col" class="side2"></th>
		</tr>
		</thead>
		<tbody>
		<?php while($R=db_fetch_array($RCD)):?>
		<?php $R['mobile']=isMobileConnect($R['agent'])?>
		<tr>
		<td><input type="checkbox" name="comment_members[]" value="<?php echo $R['uid']?>" /></td>
		<td><?php echo $NUM-((($p-1)*$recnum)+$_rec++)?></td>
		<td class="sbj">
			<?php if($R['notice']):?><i class="glyphicon glyphicon-pushpin"></i><?php endif?>
			<?php if($R['mobile']):?><img src="<?php echo $g['path_module'].$module?>/admin/img/ico_mobile.gif" alt="모바일" title="모바일(<?php echo $R['mobile']?>)로 등록된 글입니다" /><?php endif?>
			<a href="<?php echo getCommentLink($table[$module.'data'], $R['parent'], $R['uid'])?>" target="_blank"><?php echo $R['subject']?></a>	
			<?php if(strstr($R['content'],'.jpg')):?><img src="<?php echo $g['path_module'].$module?>/admin/img/ico_pic.gif" alt="사진" title="사진" /><?php endif?>
			<?php if($R['upload']):?><img src="<?php echo $g['path_module'].$module?>/admin/img/ico_file.gif" alt="첨부파일" title="첨부파일" /><?php endif?>
			<?php if($R['hidden']):?><img src="<?php echo $g['path_module'].$module?>/admin/img/ico_hidden.gif" alt="비밀글" title="비밀글" /><?php endif?>
			<?php if($R['oneline']):?><span class="comment">[<?php echo $R['oneline']?>]</span><?php endif?>
			<?php if(getNew($R['d_regis'],24)):?><span class="new">new</span><?php endif?>
		</td>
		<td class="name"><?php echo $R[$_HS['nametype']]?></td>
		<td class="center"><?php echo $R['score1']?></td>
		<td class="center"><?php echo $R['score2']?></td>
		<td class="center"><?php echo $R['singo']?></td>
		<td class="center"><?php echo getDateFormat($R['d_regis'],'Y.m.d H:i')?></td>
		<td></td>
		</tr> 
		<?php endwhile?> 

		<?php if(!$NUM):?>
		<tr>
		<td><input type="checkbox" disabled="disabled" /></td>
		<td>1</td>
		<td class="sbj1">댓글이 없습니다.</td>
		<td class="">-</td>
		<td class="center">-</td>
		<td class="center">-</td>
		<td class="center">-</td>
		<td class="center">-</td>
		<td></td>
		</tr> 
		<?php endif?>

		</tbody>
		</table>
	</div>


	<div class="pagebox01">
	<script type="text/javascript">getPageLink(10,<?php echo $p?>,<?php echo $TPG?>,'<?php echo $g['img_core']?>/page/default');</script>
	</div>


	<div class="prebox">
		<input type="button" class="btn btn-sm btn-success" value="선택/해제" onclick="chkFlag('comment_members[]');" />
		<input type="button" class="btn btn-sm btn-danger" value="삭제" onclick="actQue('multi_delete_comment');" />
	</div>
	</form>

</div>

<div id="qTilePopDiv"></div>
<script type="text/javascript">
//<![CDATA[
function dropDate(date1,date2)
{
	var f = document.procForm;
	f.year1.value = date1.substring(0,4);
	f.month1.value = date1.substring(4,6);
	f.day1.value = date1.substring(6,8);
	
	f.year2.value = date2.substring(0,4);
	f.month2.value = date2.substring(4,6);
	f.day2.value = date2.substring(6,8);

	f.submit();
}
function actQue(flag)
{
	var f = document.listForm;
    var l = document.getElementsByName('comment_members[]');
    var n = l.length;
    var i;
	var j=0;
	var s='';

	for	(i = 0; i < n; i++)
	{
		if (l[i].checked == true)
		{
			j++;
			s += l[i].value +',';
		}
	}
	if (!j)
	{
		alert('댓글을 선택해 주세요.     ');
		return false;
	}
	
	
	if (flag == 'multi_delete_comment')
	{
		if (!confirm('정말로 삭제하시겠습니까?     '))
		{
			return false;
		}
	}
	f.a.value = flag;
	f.submit();
}
//]]>
</script>

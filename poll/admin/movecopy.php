<?php
include_once $g['path_module'].'bskrbbs/mod/_func.php';

$postarray1 = array();
$postarray2 = array();

$postarray1 = getArrayString($postuid);
foreach($postarray1['data'] as $val)
{
	if (!strstr($_SESSION['BbsPost'.$type],'['.$val.']'))
	{
		$_SESSION['BbsPost'.$type] .= '['.$val.']';
	}
}
$postarray2 = getArrayString($_SESSION['BbsPost'.$type]);
rsort($postarray2['data']);
reset($postarray2['data']);
?>


<form name="procForm" action="<?php echo $g['s']?>/" method="post" target="_action_frame_<?php echo $m?>">
<input type="hidden" name="r" value="<?php echo $r?>" />
<input type="hidden" name="m" value="<?php echo $module?>" />
<input type="hidden" name="type" value="<?php echo $type?>" />
<input type="hidden" name="a" value="" />


<div id="toolbox">	
	<div class="header">
		<div class="xleft">
			<ul>
			<li<?php if($type=='multi_move'):?> class="selected"<?php endif?>><a href="<?php echo $g['adm_href']?>&amp;iframe=<?php echo $iframe?>&amp;type=multi_move">게시물이동</a></li>
			<li<?php if($type=='multi_copy'):?> class="selected"<?php endif?>><a href="<?php echo $g['adm_href']?>&amp;iframe=<?php echo $iframe?>&amp;type=multi_copy">게시물복사</a></li>
			</ul>
		</div>
		<div class="xright">
			<img src="<?php echo $g['path_module'].$module?>/admin/img/ico_notice2.gif" alt="" style="position:relative; top:-2px;" /><a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $module?>&amp;a=multi_empty&amp;type=<?php echo $type?>" target="_action_frame_<?php echo $m?>" onclick="return confirm('정말로 대기리스트를 비우시겠습니까?       ');">비우기</a>
		</div>
		<div class="clear"></div>
	</div>

	
	<div class="xbody">
		<div class="table-responsive">
		<table class="table">
		<colgroup> 
		<col width="30">
		<col width="80">
		<col> 
		<col width="50">
		<col width="90"> 
		</colgroup> 
		<thead>
		<tr>
		<th scope="col" class="side1"><img src="<?php echo $g['path_module'].$module?>/admin/img/ico_check_01.gif" class="hand" alt="" onclick="chkFlag('post_members[]');" /></th>
		<th scope="col">게시판</th>
		<th scope="col">제목</th>
		<th scope="col">조회</th>
		<th scope="col" class="side2">날짜</th>
		</tr>
		</thead>
		<tbody>

		<?php foreach($postarray2['data'] as $val):?>
		<?php $R=getUidData($table[$module.'data'],$val)?>
		<?php $R['mobile']=isMobileConnect($R['agent'])?>
		<tr>
		<td><input type="checkbox" name="post_members[]" value="<?php echo $R['uid']?>" checked="checked" /></td>
		<td class="bbsid"><?php echo $R['bbsid']?></td>
		<td class="sbj">
			<?php if($R['notice']):?><i class="glyphicon glyphicon-pushpin"></i><?php endif?>
			<?php if($R['mobile']):?><img src="<?php echo $g['path_module'].$module?>/admin/img/ico_mobile.gif" alt="모바일" title="모바일(<?php echo $R['mobile']?>)로 등록된 글입니다" /><?php endif?>
			<?php if($R['category']):?><span class="cat">[<?php echo $R['category']?>]</span><?php endif?>
			<a href="<?php echo getPostLink($R)?>" target="_blank"><?php echo $R['subject']?></a>
			<?php if(strstr($R['content'],'.jpg')):?><img src="<?php echo $g['path_module'].$module?>/admin/img/ico_pic.gif" alt="사진" title="사진" /><?php endif?>
			<?php if($R['upload']):?><img src="<?php echo $g['path_module'].$module?>/admin/img/ico_file.gif" alt="첨부파일" title="첨부파일" /><?php endif?>
			<?php if($R['hidden']):?><img src="<?php echo $g['path_module'].$module?>/admin/img/ico_hidden.gif" alt="비밀글" title="비밀글" /><?php endif?>
			<?php if($R['comment']):?><span class="comment">[<?php echo $R['comment'].($R['oneline']? '+'.$R['oneline']:'')?>]</span><?php endif?>
			<?php if($R['trackback']):?><span class="trackback">[<?php echo $R['trackback']?>]</span><?php endif?>
			<?php if(getNew($R['d_regis'],24)):?><span class="new">new</span><?php endif?>
		</td>
		<td class="hit b"><?php echo $R['hit']?></td>
		<td><?php echo getDateFormat($R['d_regis'],'Y.m.d H:i')?></td>
		</tr> 
		<?php endforeach?> 

		<?php if(!$postarray2['count']):?>
		<tr>
		<td><input type="checkbox" disabled="disabled" /></td>
		<td></td>
		<td class="sbj1">게시물이 없습니다.</td>
		<td class="hit b">-</td>
		<td><?php echo getDateFormat($date['totime'],'Y.m.d H:i')?></td>
		</tr> 
		<?php endif?>

		</tbody>
		</table>
		</div>
	</div>

	<div class="footer">
		<?php if($type == 'multi_copy'):?>
		<table>
		<tr>
		<td class="td1">게시판 선택</td>
		<td class="td2">:</td>
		<td class="td3">
		
			<select name="bid">
			<option value="">&nbsp;+ 선택하세요</option>
			<option value="">---------------------------</option>
			<?php $_BBSLIST = getDbArray($table[$module.'list'],'','*','gid','asc',0,1)?>
			<?php while($_B=db_fetch_array($_BBSLIST)):?>
			<option value="<?php echo $_B['uid']?>"<?php if($_B['uid']==$bid):?> selected="selected"<?php endif?>>ㆍ<?php echo $_B['name']?>(<?php echo $_B['id']?> - <?php echo number_format($_B['num_r'])?>)</option>
			<?php endwhile?>
			<?php if(!db_num_rows($_BBSLIST)):?>
			<option value="">등록된 게시판이 없습니다.</option>
			<?php endif?>
			</select>
		
		</td>
		</tr>
		<tr>
		<td class="td1">복사옵션</td>
		<td class="td2">:</td>
		<td class="td3 shift">
			<div class="shift">
			<input type="checkbox" name="inc_upload" value="1" checked="checked" />첨부파일포함
			<input type="checkbox" name="inc_comment" value="1" checked="checked" />댓글/한줄의견포함
			
			<input type="button" value="복사" class="btn btn-sm btn-primary" onclick="actQue('multi_copy');" />
			<input type="button" value="닫기" class="btn btn-sm btn-default" onclick="top.close();" />
			</div>
		</td>
		</tr>
		</table>
		<?php else:?>
		
		<table>
		<tr>
		<td class="td1">게시판 선택</td>
		<td class="td2">:</td>
		<td class="td3">
		
			<select name="bid">
			<option value="">&nbsp;+ 선택하세요</option>
			<option value="">---------------------------</option>
			<?php $_BBSLIST = getDbArray($table[$module.'list'],'','*','gid','asc',0,1)?>
			<?php while($_B=db_fetch_array($_BBSLIST)):?>
			<option value="<?php echo $_B['uid']?>"<?php if($_B['uid']==$bid):?> selected="selected"<?php endif?>>ㆍ<?php echo $_B['name']?>(<?php echo $_B['id']?> - <?php echo number_format($_B['num_r'])?>)</option>
			<?php endwhile?>
			<?php if(!db_num_rows($_BBSLIST)):?>
			<option value="">등록된 게시판이 없습니다.</option>
			<?php endif?>
			</select>
			<span class="s1">(동일게시판의 게시물은 제외됨)</span>
		
		</td>
		</tr>
		<tr>
		<td class="td1"></td>
		<td class="td2"></td>
		<td class="td3">
			<input type="button" value="이동" class="btn btn-sm btn-primary" onclick="actQue('multi_move');" />
			<input type="button" value="닫기" class="btn btn-sm btn-default" onclick="top.close();" />
		</td>
		</tr>
		</table>
		<?php endif?>
	</div>

</div>

</form>














<script type="text/javascript">
//<![CDATA[
function actQue(act)
{
	var f = document.procForm;
    var l = document.getElementsByName('post_members[]');
    var n = l.length;
	var j = 0;
    var i;

    for (i = 0; i < n; i++)
	{
		if(l[i].checked == true)
		{
			j++;
		}
	}
	if (!j)
	{
		alert('선택된 게시물이 없습니다.      ');
		return false;
	}
	
	if (f.bid.value == '')
	{
		alert('게시판을 선택해 주세요.       ');
		f.bid.focus();
		return false;
	}
	if (confirm('정말로 실행하시겠습니까?    '))
	{
		f.a.value = act;
		f.submit();
	}
	return false;
}


document.title = "게시물 <?php echo $type=='multi_move'?'이동':'복사'?>";
self.resizeTo(650,650);
//]]>
</script>

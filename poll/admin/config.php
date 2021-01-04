<?php
include_once $g['path_module'].$module.'/var/var.php';
?>
<?php if( $g['mobile'] && $_SESSION['pcmode']!='Y' ):?>
<style>#bskradm {font-size:14px;}</style>
<?php endif?>

<div id="bskradm">
	<form name="procForm" action="<?php echo $g['s']?>/" method="post" target="_action_frame_<?php echo $m?>" onsubmit="return saveCheck(this);">
	<input type="hidden" name="r" value="<?php echo $r?>" />
	<input type="hidden" name="m" value="<?php echo $module?>" />
	<input type="hidden" name="a" value="config" />

	<h2>게시판 기본환경</h2>
	<div class="table-responsive">
	<table class="table">
		<tr>
			<td class="td1">
				대표테마 <i class="glyphicon glyphicon-question-sign hand" onclick="$('#guide_skin').toggle()"></i>
			</td>
			<td class="td2">
				<select name="skin_main" class="select1">
				<option value="">&nbsp;+ 선택하세요</option>
				<option value="">--------------------------------</option>
				<?php $tdir = $g['path_module'].$module.'/themes/'?>
				<?php $dirs = opendir($tdir)?>
				<?php while(false !== ($skin = readdir($dirs))):?>
				<?php if($skin=='.' || $skin == '..' || is_file($tdir.$skin))continue?>
				<option value="<?php echo $skin?>" title="<?php echo $skin?>"<?php if($d['bbs']['skin_main']==$skin):?> selected="selected"<?php endif?>>ㆍ<?php echo getFolderName($tdir.$skin)?>(<?php echo $skin?>)</option>
				<?php endwhile?>
				<?php closedir($dirs)?>
				</select>
				<div id="guide_skin" class="guide hide2">
				지정된 대표테마는 게시판설정시 별도의 테마지정없이 자동으로 적용됩니다.<br />
				가장 많이 사용하는 테마를 지정해 주세요.
				</div>
			</td>
		</tr>
		<tr>
			<td class="td1 m">
				(모바일테마) <i class="glyphicon glyphicon-question-sign hand" onclick="$('#guide_skin_mobile').toggle()"></i>
			</td>
			<td class="td2">
				<select name="skin_mobile" class="select1">
				<option value="">&nbsp;+ 선택하세요</option>
				<option value="">--------------------------------</option>
				<?php $tdir = $g['path_module'].$module.'/themes/'?>
				<?php $dirs = opendir($tdir)?>
				<?php while(false !== ($skin = readdir($dirs))):?>
				<?php if($skin=='.' || $skin == '..' || is_file($tdir.$skin))continue?>
				<option value="<?php echo $skin?>" title="<?php echo $skin?>"<?php if($d['bbs']['skin_mobile']==$skin):?> selected="selected"<?php endif?>>ㆍ<?php echo getFolderName($tdir.$skin)?>(<?php echo $skin?>)</option>
				<?php endwhile?>
				<?php closedir($dirs)?>
				</select>
				<div id="guide_skin_mobile" class="guide hide2">
				필요한 경우, 모바일 기기에서만 호출되는 전용 게시판 테마를 설정하여 사용할 수 있습니다.<br />
				단, 반응형 테마는 기본적으로 모바일에 대응하도록 개발되므로 대부분의 경우 필요없을 수 있습니다. 
				</div>				
			</td>
		</tr>
		<tr>
			<td class="td1">
				통합보드테마 <i class="glyphicon glyphicon-question-sign hand" onclick="$('#guide_total').toggle()"></i>
			</td>
			<td class="td2">
				
				<select name="skin_total" class="select1">
				<option value="">&nbsp;+ 통합보드 사용안함</option>
				<option value="">--------------------------------</option>
				<?php $tdir = $g['path_module'].$module.'/themes/'?>
				<?php $dirs = opendir($tdir)?>
				<?php while(false !== ($skin = readdir($dirs))):?>
				<?php if($skin=='.' || $skin == '..' || is_file($tdir.$skin))continue?>
				<option value="<?php echo $skin?>" title="<?php echo $skin?>"<?php if($d['bbs']['skin_total']==$skin):?> selected="selected"<?php endif?>>ㆍ<?php echo getFolderName($tdir.$skin)?>(<?php echo $skin?>)</option>
				<?php endwhile?>
				<?php closedir($dirs)?>
				</select>
				<div id="guide_total" class="guide hide2">
				통합보드란 모든 게시판의 전체 게시물을 하나의 게시판으로 출력해 주는 서비스입니다.<br />
				사용하시려면 통합보드용 테마를 지정해 주세요.<br />
				통합보드의 호출은 <a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $module?>" target="_blank" class="b u"><?php echo $g['r']?>/?m=<?php echo $module?></a> 입니다.
				</div>
			</td>
		</tr>
		<tr>
			<td class="td1">
				위지윅 에디터 <i class="glyphicon glyphicon-question-sign hand" onclick="$('#guide_editor').toggle()"></i>
			</td>
			<td class="td2">
				<select name="editor" class="select1">
				<option value="">&nbsp;+ 에디터 사용안함</option>
				<option value="">--------------------------------</option>
				<?php $tdir = $g['path_module'].$module.'/editor/'?>
				<?php $dirs = opendir($tdir)?>
				<?php while(false !== ($editor = readdir($dirs))):?>
				<?php if($editor=='.' || $editor == '..' || is_file($tdir.$editor))continue?>
				<option value="<?php echo $editor?>" title="<?php echo $editor?>"<?php if($d['bbs']['editor']==$editor):?> selected="selected"<?php endif?>>ㆍ<?php echo getFolderName($tdir.$editor)?>(<?php echo $editor?>)</option>
				<?php endwhile?>
				<?php closedir($dirs)?>
				</select>
				<div id="guide_editor" class="guide hide2">
				BSKR 게시판에 내장되어 있는 위지윅 에디터를 선택하여 보다 강화된 사용자 환경을 제공할 수 있습니다.<br />
				위지윅 에디터는 게시물을 작성할 때 HTML 요소들을 손쉽게 추가하고 적용할 수 있도록 해주는 프로그램 요소입니다.
				</div>
			</td>
		</tr>
		<tr>
			<td class="td1">첨부파일 최대개수</td>
			<td class="td2">
				<input type="text" name="max_upload" value="<?php echo $d['bbs']['max_upload']?$d['bbs']['max_upload']:5?>" size="5" class="input" />개 (한 게시물에 첨부할 수 있는 최대 파일/이미지 개수)
			</td>
		</tr>
		<tr>
			<td class="td1">첨부파일 최대크기</td>
			<td class="td2">
				<?php echo ini_get('upload_max_filesize')?> (파일 당 허용되는 최대 크기로, 서버의 upload_max_filesize 값임. 또한, post_max_size 값은 <?php echo ini_get('post_max_size')?>로 이보다 크거나 같아야 함)
			</td>
		</tr>
		<tr>
			<td class="td1">썸네일 가로폭</td>
			<td class="td2">
				<input type="text" name="thumb_width" value="<?php echo $d['bbs']['thumb_width']?$d['bbs']['thumb_width']:350?>" size="5" class="input" />px (이미지 형식의 첨부파일에 대한 썸네일 이미지 크기)
			</td>
		</tr>
		<tr>
			<td class="td1"><span class="">RSS발행</span></td>
			<td class="td2">
				<label><input type="checkbox" name="rss" value="1"<?php if($d['bbs']['rss']):?> checked="checked"<?php endif?> /> RSS발행을 허용합니다.(개별게시판별 RSS발행은 개별게시판 설정을 따름)</label><br />
			</td>
		</tr>
		<tr>
			<td class="td1">게시물출력</td>
			<td class="td2">
				<input type="text" name="recnum" value="<?php echo $d['bbs']['recnum']?$d['bbs']['recnum']:20?>" size="5" class="input" />개 (한페이지에 출력할 게시물의 수)
			</td>
		</tr>
		<tr>
			<td class="td1">제목끊기</td>
			<td class="td2">
				<input type="text" name="sbjcut" value="<?php echo $d['bbs']['sbjcut']?$d['bbs']['sbjcut']:40?>" size="5" class="input" />자 (제목이 길 경우 자르기)
			</td>
		</tr>
		<tr>
			<td class="td1">새글유지시간</td>
			<td class="td2">
				<input type="text" name="newtime" value="<?php echo $d['bbs']['newtime']?$d['bbs']['newtime']:24?>" size="5" class="input" />시간 (새글로 인식되는 시간)
			</td>
		</tr>
		<tr>
			<td class="td1">답글인식문자</td>
			<td class="td2">
				<input type="text" name="restr" value="<?php echo $d['bbs']['restr']?>" size="5" class="input" />
			</td>
		</tr>
		<tr>
			<td class="td1">삭제제한</td>
			<td class="td2 shift">
				<label><input type="checkbox" name="replydel" value="1"<?php if($d['bbs']['replydel']):?> checked="checked"<?php endif?> /> 답변글이 있는 원본글의 삭제를 제한합니다.</label><br />
				<label><input type="checkbox" name="commentdel" value="1"<?php if($d['bbs']['commentdel']):?> checked="checked"<?php endif?> /> 댓글이 있는 원본글의 삭제를 제한합니다.</label>
			</td>
		</tr>
		<tr>
			<td class="td1">불량글 처리</td>
			<td class="td2">
				<label><input type="checkbox" name="singo_del" value="1"<?php if($d['bbs']['singo_del']):?> checked="checked"<?php endif?> /> 신고수가 </label>
				<input type="text" name="singo_del_num" value="<?php echo $d['bbs']['singo_del_num']?>" size="5" class="input" />건 이상일 경우 
				<select name="singo_del_act">
				<option value="1"<?php if($d['bbs']['singo_del_act']==1):?> selected="selected"<?php endif?>>자동삭제</option>
				<option value="2"<?php if($d['bbs']['singo_del_act']==2):?> selected="selected"<?php endif?>>비밀처리</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="td1">제한단어</td>
			<td class="td2">
				<textarea name="badword" rows="5" cols="70" onfocus="this.style.color='#000000';" onblur="this.style.color='#ffffff';"><?php echo $d['bbs']['badword']?></textarea>
			
			</td>
		</tr>
		<tr>
			<td class="td1">제한단어 처리</td>
			<td class="td2">
				<label><input type="radio" name="badword_action" value="0"<?php if($d['bbs']['badword_action']==0):?> checked="checked"<?php endif?> /> 제한단어 체크하지 않음</label><br />
				<label><input type="radio" name="badword_action" value="1"<?php if($d['bbs']['badword_action']==1):?> checked="checked"<?php endif?> /> 등록을 차단함</label><br />
				<label><input type="radio" name="badword_action" value="2"<?php if($d['bbs']['badword_action']==2):?> checked="checked"<?php endif?> /> 제한단어를 다음의 문자로 치환하여 등록함</label>
				<input type="text" name="badword_escape" value="<?php echo $d['bbs']['badword_escape']?>" size="1" maxlength="1" class="input" />
			</td>
		</tr>
	</table>
	</div>


	<h2>댓글 기본환경</h2>	
	<div class="table-responsive">
	<table class="table">
		<tr>
			<td class="td1">댓글출력</td>
			<td class="td2">
				<input type="text" name="c_recnum" value="<?php echo $d['bbs']['c_recnum']? $d['bbs']['c_recnum']: '10'?>" size="5" class="input">개 (페이지 당 출력 댓글수. 기본값으로 게시판별 설정 가능)
			</td>
		</tr>	
		<tr>
			<td class="td1">삭제제한</td>
			<td class="td2">
				<label><input type="checkbox" name="c_onelinedel" value="1"<?php if($d['bbs']['c_onelinedel']):?> checked="checked"<?php endif?>> 한줄의견이 있는 댓글의 삭제를 제한</label>
			</td>
		</tr>			
		<tr>
			<td class="td1">댓글정렬</td>
			<td class="td2">
				<label><input type="radio" name="c_orderby" value="asc"<?php if($d['bbs']['c_orderby']!='desc'):?> checked="checked"<?php endif?>> 최근댓글이 위로</label>
				&nbsp;&nbsp;
				<label><input type="radio" name="c_orderby" value="desc"<?php if($d['bbs']['c_orderby']=='desc'):?> checked="checked"<?php endif?>> 최근댓글이 아래로</label>
			</td>
		</tr>
		<tr>
			<td class="td1">한줄의견정렬</td>
			<td class="td2">
				<label><input type="radio" name="o_orderby" value="desc"<?php if($d['bbs']['o_orderby']=='desc'):?> checked="checked"<?php endif?>> 최근한줄의견이 위로</label>
				&nbsp;&nbsp;
				<label><input type="radio" name="o_orderby" value="asc"<?php if($d['bbs']['o_orderby']!='desc'):?> checked="checked"<?php endif?>> 최근한줄의견이 아래로</label>
			</td>
		</tr>		
	</table>
	</div>
	
	<div class="submitbox">
		<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> 확인</button>
	</div>
	</form>
	
</div>



<script type="text/javascript">
//<![CDATA[
function saveCheck(f)
{
	if (f.skin_main.value == '')
	{
		alert('대표테마를 선택해 주세요.       ');
		f.skin_main.focus();
		return false;
	}
	/***
	if (f.skin_mobile.value == '')
	{
		alert('모바일테마를 선택해 주세요.       ');
		f.skin_mobile.focus();
		return false;
	}
	***/

	return confirm('정말로 실행하시겠습니까?         ');
}
//]]>
</script>



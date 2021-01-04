<?php
if( $iframe )
	$context = 'window.opener.';
	
if( !$isEditor ) {
	$isEditor = $d['bbs']['editor'];
	if( $my['level']<$d['theme']['edit_html'] )
		$isEditor = false;
}

// php.ini의 upload_max_filesize 설정값(10M 등)을 bytes 값으로 되돌린다.
function upload_max_filesize_bytes() {
    $val = ini_get('upload_max_filesize');
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }
    return $val;
}
?>


<div id="upload-container"<?php if( !$iframe ):?> class="hide"<?php endif?>>
	<div class="form-wrap">
		<form id="uploadForm" method="post" action="<?php echo $g['s']?>/" target="_action_frame_<?php echo $m?>" enctype="multipart/form-data">
			<input type="hidden" name="r" value="<?php echo $r?>" />
			<input type="hidden" name="a" value="write_upload" />
			<input type="hidden" name="m" value="<?php echo $m?>" />
			<input type="hidden" name="bid" value="<?php echo $R['bbsid']?$R['bbsid']:$bid?>" />
			<input type="hidden" name="uid" value="<?php echo $R['uid']?>" />
			<input type="file" name="upfile[]" id="upload-file">
		</form>
		
		<?php if( !$iframe ):?>
		<form id="deleteForm" method="post" action="<?php echo $g['s']?>/" target="_action_frame_<?php echo $m?>">
			<input type="hidden" name="r" value="<?php echo $r?>" />
			<input type="hidden" name="a" value="delete_upload" />
			<input type="hidden" name="m" value="<?php echo $m?>" />
			<input type="hidden" name="bid" value="<?php echo $R['bbsid']?$R['bbsid']:$bid?>" />
			<input type="hidden" name="uid" value="<?php echo $R['uid']?>" />
			<input type="hidden" name="upid" value="" />
		</form>
		<?php endif?>
	</div>
	<div class="dsc">※ 최대 <b style="color:#31708f"><?php echo  ini_get('upload_max_filesize')?> </b> 크기의 파일을 등록할 수 있습니다.</div>
	<div class="dsc">※ <b style="color:#31708f">MS IE10 이상</b> 또는 <b style="color:#31708f">구글 크롬(Chrome)</b> 등의 최신 웹브라우저를 이용하시면, 더 편리한 파일첨부가 가능합니다.</div>
</div>	

<script src="<?php echo $g['dir_module']?>plugin/jquery-form/jquery.form.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#uploadForm').ajaxForm({
		beforeSend: function() {
			$('#upload-browse').prop('disabled', true);
			$('#upload-clear').prop('disabled', true);		
		},		
		uploadProgress: function(event, position, total, percentComplete) {
		},
		success: function() {
		},
		complete: function(xhr) {
			try {
				var res = jQuery.parseJSON(xhr.responseText);
				<?php echo $context?>$('#upload-tmp').remove();
				
				if( res.code > 0 ) {
					var imgput = '';
					<?php if( $isEditor ):?>
					if( res.type == 2 )
						imgput = '<a href="javascript:imgPut(' + res.upid + ', \'' + res.url + '\', \'' + res.name + '\', true)" class="pull-right"><span class="badge" title="이 이미지의 HTML 코드를 에디터에 추가합니다."><i class="glyphicon glyphicon-picture"></i></span></a>';
					<?php endif?>
					
					var remove = '<a href="javascript:deleteUpload(' + res.upid + ')" class="pull-right" style="margin-left:5px;"><span class="badge" title="이 파일을 삭제합니다"><i class="glyphicon glyphicon-trash"></i></span></a>';
					<?php echo $context?>$('#upload-list ul').append('<li id="' + res.upid + '" class="list-group-item">' + remove + imgput + res.name + ' (' + getFilesizeStr(res.size) + ')</li>');
					<?php echo $context?>$('#upfilesValue').val( <?php echo $context?>$('#upfilesValue').val() + '[' + res.upid + ']');
					<?php echo $context?>$('#upload-clear').show();
				
					<?php if( $isEditor ):?>
					imgPut(res.upid, res.url, res.name, false);
					<?php endif?>
				}
				
				if( res.msg )
					alert(res.msg);
				
				<?php echo $context?>$('#upload-browse').prop('disabled', false);
				<?php echo $context?>$('#upload-clear').prop('disabled', false);
		
				<?php if( $iframe ):?>
				self.close();
				<?php endif?>
			} catch(e) {
				alert('예외가 발생했습니다.\n' + e);
			}
		}
	});

	$('#upload-file').change(function() {
		var nUploaded = $('#upload-list ul').children().length;
		if( nUploaded >= <?php echo $d['bbs']['max_upload']?> ) {
			alert('최대 <?php echo $d['bbs']['max_upload']?>개의 파일까지 첨부가 가능합니다.');
			return;
		}
	
		if( window.FormData == undefined ) {		
			var name = $('#upload-file').val();
			<?php echo $context?>$('#upload-list ul').append('<li id="upload-tmp" class="list-group-item"><span style="float:right"><img src="<?php echo $g['img_module_skin']?>/ajax-loader.gif"></span>' + name + '</li>');
		}
		else {
			var file = this.files[0];
			if( !file ) return;
			
			var name = file.name;
			var size = file.size;
			var type = file.type;

			var maxSize = <?php echo upload_max_filesize_bytes()?>;
			if( size >= maxSize ) {
				alert('파일의 크기가 너무 큽니다. 최대 <?php echo  ini_get('upload_max_filesize')?>까지 첨부가 가능합니다.');
				return;
			}
				
			$('#upload-list ul').append('<li id="upload-tmp" class="list-group-item"><span style="float:right"><img src="<?php echo $g['img_module_skin']?>/ajax-loader.gif"></span>' + name + ' (' + getFilesizeStr(size) + ')</li>');
		}
		<?php echo $context?>$('#upload-list').show();
		$('#uploadForm').submit();
	});
	
	<?php if( !$iframe ):?>
	$('#upload-browse').click(function() {
		if( window.FormData == undefined )
			window.open('<?php echo $g['s']?>/?r=<?php echo $r?>&m=<?php echo $m?>&bid=<?php echo $R['bbsid']?$R['bbsid']:$bid?>&mod=upload_popup&iframe=Y', 'upload','width=395px,height=255px,status=no,scrollbars=no,toolbar=no');
		else
			$('#upload-file').click();
	});	
	
	$('#upload-clear').click(function() {
		if( confirm('정말 전체 첨부파일을 삭제하시겠습니까?') == false )
			return;
	
		$('#upload-list ul li').each(function(i, li) {
			$(li).find('a').remove();
			$(li).append('<span style="float:right"> <img src="<?php echo $g['img_module_skin']?>/ajax-loader.gif"> </span>');		
		
			$('#deleteForm [name="upid"]').val($(li).attr('id'));
			$('#deleteForm').submit();
		});
	});			
	
	$('#deleteForm').ajaxForm({
		beforeSend: function() {
			$('#upload-browse').prop('disabled', true);
			$('#upload-clear').prop('disabled', true);		
		},		
		complete: function(xhr) {
			try {
				var res = jQuery.parseJSON(xhr.responseText);
				if( res.code > 0 ) {
					if( $('#upload-list ul #' + res.upid) )
						$('#upload-list ul #' + res.upid).remove();
						
					if( !$('#upload-list ul').children().length )
						$('#upload-clear').hide();
					
					// upfiles 로부터 upid 제거
					var upfiles = $('#upfilesValue').val();
					upfiles = upfiles.replace('[' + res.upid + ']', '');
					$('#upfilesValue').val(upfiles);				
					
					// 결과 메시지 출력
					var msg = '[' + res.name + '] 파일이 삭제되었습니다.';
					<?php if( $isEditor ):?>
					if( res.type == 2 )
						msg += '\n이미지 파일의 경우, 에디터에서 직접 HTML 코드를 제거해 주셔야 합니다.';
					<?php endif?>
					alert(msg);
				}

				if( res.msg )
					alert(res.msg);
					
				$('#upload-browse').prop('disabled', false);
				$('#upload-clear').prop('disabled', false);				
			} catch(e) {
				alert('예외가 발생했습니다\n' + e);
			}
		}		
	});	
	<?php endif // End of (!$iframe)?>
	
	<?php if( $uid and $R['upload'] ):?>
	initUpfiles();
	<?php endif?>	
});


<?php if( $isEditor ):?>
function imgPut(upid, url, name, check) {
	if( check && confirm('이 이미지의 HTML코드를 에디터에 추가 하시겠습니까?') == false )
		return;
		
	var sHTML = '<div class="bskr-img" data-upid="' + upid + '"><img src="' + url + '" class="img-responsive" alt="" title="' + name + '"></div><br>';
	
	<?php if( $isEditor=='SmartEditor2' ):?>
	<?php echo $context?>oEditors.getById["ir1"].exec("PASTE_HTML", [sHTML]);
	<?php elseif( $isEditor=='Summernote' ):?>
	<?php echo $context?>$('#summernote').code(<?php echo $context?>$('#summernote').code() + sHTML);
	<?php endif?>
}
<?php endif?>


<?php if( $uid and $R['upload'] ):	// 편집 시, 첨부목록 초기화?>
function initUpfiles() {
	var remove, imgput = "";

	<?php 
	$arrUpfiles = getArrayString($R['upload']);
	foreach($arrUpfiles['data'] as $_val)
	{
		$U = getUidData($table['s_upload'],$_val);
		if (!$U['uid']) continue;
	?>

	<?php if( $isEditor and $U['type']==2  ):?>
	imgput = '<a href="javascript:imgPut(<?php echo $U['uid']?>, \'<?php echo $U['url'].$U['folder'].'/'.$U['tmpname']?>\', \'<?php echo $U['name']?>\', true)" class="pull-right"><span class="badge" title="이 이미지의 HTML 코드를 에디터에 추가합니다."><i class="glyphicon glyphicon-picture"></i></span></a>';
	<?php endif?>
	remove = '<a href="javascript:deleteUpload(<?php echo $U['uid']?>)" class="pull-right" style="margin-left:5px;"><span class="badge" title="이 파일을 삭제합니다"><i class="glyphicon glyphicon-trash"></i></span></a>';
	$('#upload-list ul').append('<li id="<?php echo $U['uid']?>" class="list-group-item">' + remove + imgput + '<?php echo $U['name']?>' + ' (' + getFilesizeStr(<?php echo $U['size']?>) + ')</li>');
		
	<?php
	}	
	?>

	$('#upload-list').show();
	$('#upload-clear').show();
}
<?php endif?>

function deleteUpload(upid) {
	if( confirm('이 첨부파일을 삭제하시겠습니까?') == false )
		return;
		
	$('#' + upid + ' a').remove();
	$('#' + upid).append('<span style="float:right"> <img src="<?php echo $g['img_module_skin']?>/ajax-loader.gif"> </span>');
	
	$('#deleteForm [name="upid"]').val(upid);
	$('#deleteForm').submit();
}

function getFilesizeStr(bytes) {
	var exp = Math.log(bytes) / Math.log(1024) | 0;
	var result = (bytes / Math.pow(1024, exp)).toFixed(2);
	return result + ' ' + (exp == 0 ? 'bytes': 'KMGTPEZY'[exp - 1] + 'B');
}


var submitFlag = false;
function writeCheck(f)
{
	if( f==null )
		f = document.getElementById('writeForm');
	
	if (submitFlag == true)
	{
		alert('게시물을 등록하고 있습니다. 잠시만 기다려 주세요.');
		return false;
	}
	if (f.name && f.name.value == '')
	{
		alert('이름을 입력해 주세요. ');
		f.name.focus();
		return false;
	}
	if (f.pw && f.pw.value == '')
	{
		alert('비밀번호를 입력해 주세요. ');
		f.pw.focus();
		return false;
	}
	if (f.category && f.category.value == '' )
	{
		if( !(f.notice && f.notice.checked==true) )
		{
			alert('분류를 선택해 주세요. ');
			f.category.focus();
			return false;
		}
	}
	if( jQuery.trim(f.subject.value) == '' )
	{
		alert('제목을 입력해 주세요.      ');
		f.subject.focus();
		return false;
	}
	if (f.notice && f.hidden)
	{
		if (f.notice.checked == true && f.hidden.checked == true)
		{
			alert('공지글은 비밀글로 등록할 수 없습니다.  ');
			f.hidden.focus();
			return false;
		}
	}
	
	<?php if( $isEditor=='SmartEditor2' ):?>
	f.content.value = oEditors.getById["ir1"].getIR();
	<?php elseif( $isEditor=='Summernote' ):?>
	f.content.value = $('#summernote').code();
	<?php endif?>
	if( jQuery.trim(f.content.value) == '' )
	{
		alert('내용을 입력해 주세요.       ');
		<?php if( !$isEditor ):?>
		f.content.focus();
		<?php endif?>
		return false;
	}

	submitFlag = true;
	return true;
}
</script>

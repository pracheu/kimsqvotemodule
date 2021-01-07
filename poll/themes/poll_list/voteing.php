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

$R = getDbData($table[ $m . 'list' ], 'po_id=' . $pid, '*');
$U = getDbData($table[ $m. 'user' ], 'idx='.$idx, '*');

$save_file = $s.$U['idx'].$U['dong'].$U['hosu'].".png";
?>
<!-- jQuery UI -->
<script type="text/javascript" src="<?php echo $g['path_module'] ?>/poll/plugin/inc/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?php echo $g['path_module'] ?>/poll/plugin/inc/jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="<?php echo $g['path_module'] ?>/poll/plugin/inc/jquery.ui.mouse.min.js"></script>
<script type="text/javascript" src="<?php echo $g['path_module'] ?>/poll/plugin/inc/jquery.ui.draggable.min.js"></script>

<!-- wColorPicker -->
<link rel="Stylesheet" type="text/css" href="<?php echo $g['path_module'] ?>/poll/plugin/inc/wColorPicker.css" />
<script type="text/javascript" src="<?php echo $g['path_module'] ?>/poll/plugin/inc/wColorPicker.js"></script>

<!-- wPaint -->
<link rel="Stylesheet" type="text/css" href="<?php echo $g['path_module'] ?>/poll/plugin/wPaint/wPaint.css" />
<script type="text/javascript" src="<?php echo $g['path_module'] ?>/poll/plugin/wPaint/wPaint.js"></script>

<style>
	.page-content { line-height:22px; word-break: keep-all; word-wrap: break-word; }
	.page-content p { margin:0 0 15px; padding:0; }
	.page-content .slogan { font-size:25px; letter-spacing:-1px; margin-bottom:15px; line-height:34px; }
	.page-content .slogan i { font-size:17px; vertical-align:top; margin-top:6px; }
</style>


<style>
	#image-tests {
		margin: 10px 0;
	}
	#image-tests a {
		font-size: 10px;
		font-family: verdana;
	}
	#image-data {
		font-size: 10px;
	}
	#image-data input {
		width: 70px;
		margin-right: 10px;
		font-size: 8px;
		font-family: verdana;
	}
</style>
<div id="bskrlist">
	<div class="local_ov01 local_ov">
		<h2>투표</h2>
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
					<th><?php echo $R['po_subject'] ?></th>
				</tr>	
				<?php for ($i=0; $i < 9 ; $i++) : ?>
				<?php if($R["po_poll".($i+1)]!="") : ?>
				<tr>
					<td class="tleft">
						<input type="radio" name="po_poll" id="po_poll" value="<?php echo $i+1 ?>"><span style="padding-left:10px"><?php echo $R["po_poll".($i+1)]?></span>
					</td>
				</tr>
				<?php endif ?>
				<?php endfor ?>
			</tbody>
			</table>
		</div>
		<div class="text-center">
			<div class="btn-group">
			<h5>※ 서명을 반드시 확인 해주세요. 서명되지 않은 투표는 무효가 될 수 있습니다.</h5>

			<div id="wPaint" style="margin:0 auto;position:relative; width:250px; height:150px; background:#FFFFFF; border:solid black 2px;"></div>
			<br>
			<center>
			<button type="button" id="sign" class="btn btn-blue btn-sm" onclick="upload_image();"><i class="fa fa-pencil"></i> 서명 확인</button>
			</center>
			</div>
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
<script type="text/javascript">
	var wp = $("#wPaint").wPaint({
		drawDown: function(e, mode){ $("#canvasDown").val(this.settings.mode + ": " + e.pageX + ',' + e.pageY); },
		drawMove: function(e, mode){ $("#canvasMove").val(this.settings.mode + ": " + e.pageX + ',' + e.pageY); },
		drawUp: function(e, mode){ $("#canvasUp").val(this.settings.mode + ": " + e.pageX + ',' + e.pageY); }
	}).data('_wPaint');
	

	$("._wPaint_menu").hide();
	
	function loadImage_png()
	{
		$("#wPaint").wPaint("image", "images/demo/wPaint.png");
	}

	function loadImage_jpg()
	{
		$("#wPaint").wPaint("image", "images/demo/wPaint.jpg");
	}

	function saveImage()
	{
		var imageData = $("#wPaint").wPaint("image");
		
		$("#canvasImage").attr('src', imageData);
		$("#canvasImageData").val(imageData);
	}

	function clearCanvas()
	{
		$("#wPaint").wPaint("clear");
	}
	
	function upload_image()
	{
		$.ajax({
			url: <?php echo "'".$g['path_module']."'" ?> + 'poll/themes/poll_list/upload.php',
			data: {
				file: <?php echo '"'.$save_file.'"' ?>,
				site: <?php echo '"'.$r.'"' ?>,
				image: $('#wPaint').wPaint('image')
			},
			type: 'POST',
			success: function(resp)
			{
				
				console.log(resp);
				//alert('서명이미지가 등록되었습니다.');
			}
		});
	}
</script>
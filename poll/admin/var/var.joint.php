<?php
$recnum = 10;
$catque = 'uid';
if ($_keyw) $catque .= " and ".$where." like '".$_keyw."%'";
$PAGES = getDbArray($table[$smodule.'list'],$catque,'*','gid','asc',$recnum,$p);
$NUM = getDbRows($table[$smodule.'list'],$catque);
$TPG = getTotalPage($NUM,$recnum);
$tdir = $g['path_module'].$smodule.'/theme/';
?>


<div id="mjointbox">

	<input type="button" value="연결" class="btnblue" onclick="dropJoint('<?php echo $g['s']?>/?r=<?php echo $r?>&m=<?php echo $smodule?>');" />

</div>


<style type="text/css">
#mjointbox {}
#mjointbox .title {border-bottom:#dfdfdf dashed 1px;padding:0 0 10px 0;margin:0 0 20px 0;}
#mjointbox .title .cat {width:120px;}
#mjointbox table {width:98%;}
#mjointbox table .name {width:160px;}
#mjointbox table .name span {font-size:11px;font-family:arial;color:#c0c0c0;padding:0 0 0 3px;}
#mjointbox table .cat {text-align:right;}
#mjointbox table .cat select {width:115px;}
#mjointbox table .aply {text-align:right;}
#mjointbox table .aply .btngray {width:25px;}
#mjointbox table .aply .btnblue {width:45px;}
#mjointbox .pagebox01 {text-align:center;padding:15px 0 15px 0;margin:15px 0 0 0;border-top:#efefef solid 1px;}
#mjointbox .nonebbs {padding:20px 0 20px 0;font-size:12px;color:#888;}
#mjointbox .nonebbs img {position:relative;top:2px;}
#mjointbox .category1 {width:160px;}
#mjointbox .madetr td {background:#efefef;}
#mjointbox .td1 {padding:14px 0 5px 0;width:100px;vertical-align:top;}
#mjointbox .td2 {padding:10px 0 5px 0;color:#666666;}
#mjointbox .td2 .sname {width:154px;}
#mjointbox .td2 .sname1 {width:287px;}
#mjointbox .td2 .sname2 {width:82px;}
#mjointbox .td2 .select1 {width:180px;letter-spacing:-1px;}
#mjointbox .td2 .guide {font-size:11px;font-family:dotum;color:#555;line-height:150%;padding:10px 0 0 0;}
#mjointbox .td2 .dn {margin-bottom:-5px;}
#mjointbox .td2 .dm {position:relative;top:2px;padding:5px;margin:0 3px 0 0;border:#dfdfdf solid 1px;background:#f9f9f9;cursor:pointer;}
#mjointbox .td2 .add {height:40px;}
#mjointbox .td2 textarea {padding:5px;margin:0;width:330px;height:100px;overflow-x:hidden;overflow-y:auto;line-height:150%;color:#000000;font-family:Courier new, arial, dotum;font-size:9pt;text-align:left;}
#mjointbox .sfont1 {font:normal 11px dotum;color:#c0c0c0;}
#mjointbox .notice {padding:15px 0 10px 15px;margin:0 0 20px 0;font-size:11px;font-family:dotum;color:#02B6D6;border-bottom:#dfdfdf dashed 1px;line-height:150%;}
#mjointbox .submitbox {margin:20px 0 20px 0;padding:15px 0 20px 107px;border-top:#dfdfdf dashed 1px;}
#mjointbox .submitbox a {font-size:11px;font-family:dotum;text-decoration:underline;color:#c0c0c0;padding:0 0 0 10px;}

</style>

<script type="text/javascript">
//<![CDATA[
function thisReset()
{
	var f = document.bbsSform;
	f.newboard.value = '';
	f.p.value = 1;
	f._keyw.value = '';
	f.submit();
}
function saveCheck(f)
{
	if (f.name.value == '')
	{
		alert('게시판이름을 입력해 주세요.     ');
		f.name.focus();
		return false;
	}
	if (f.bid.value == '')
	{
		if (f.id.value == '')
		{
			alert('게시판아이디를 입력해 주세요.      ');
			f.id.focus();
			return false;
		}
		if (!chkFnameValue(f.id.value))
		{
			alert('게시판아이디는 영문 대소문자/숫자/_ 만 사용가능합니다.      ');
			f.id.value = '';
			f.id.focus();
			return false;
		}
	}
	return confirm('정말로 새 게시판을 만드시겠습니까?         ');
}
//]]>
</script>
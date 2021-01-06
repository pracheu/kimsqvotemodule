<?php
$g5['title'] = '엑셀파일로 선거인명부 일괄 등록';
?>

<link rel="stylesheet" href="./pollexcel.css">
<link href="/plugins/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">

<div class="new_win">
    <h1><?php echo $g5['title']; ?></h1>

    <div class="local_desc01 local_desc">
        <p>
            엑셀파일을 이용하여 선거인명부를 일괄등록할 수 있습니다.<br>
            형식은 <strong>선거인명부 엑셀파일</strong>을 확인후에 선거인명부 정보를 입력하시면 됩니다.<br>
            수정 완료 후 엑셀파일을 업로드하시면 선거인명부가 일괄등록됩니다.<br>
            엑셀파일을 저장하실 때는 <strong>Excel 97 - 2003 통합문서 (*.xls)</strong> 로 저장하셔야 합니다.
        </p>
    </div>

    <form name="fitemexcel" method="post" action="./pollexcelupdate.php" enctype="MULTIPART/FORM-DATA" autocomplete="off">
	<input type="hidden" id="po_id" name="po_id" value="<?=$_GET['po_id']?>">
    <div id="excelfile_upload">
        <label for="excelfile">파일선택</label>
        <input type="file" name="excelfile" id="excelfile" >
    </div>

    <div class="btn_confirm01 btn_confirm">
        <input type="submit" value="선거인명부 엑셀파일 등록(※ 등록후 반드시 기다려주세요...)"  class="btn btn-sm btn-success">
        <button type="button" onclick="window.close();"  class="btn btn-sm btn-danger">닫기</button>
    </div>

    </form>

</div>
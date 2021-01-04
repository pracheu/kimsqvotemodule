<?php if( $g['mobile'] && $_SESSION['pcmode']!='Y' ):?>
<style>#bskradm {font-size:14px;}</style>
<?php endif?>

<div id="bskradm">
	<h2>모듈 기본정보</h2>
	<table>
	<tr>
		<td class="td1">모듈명</td>
		<td>:</td>
		<td class="td2"><?php echo $MD['name']?></td>
	</tr>
	<tr>
		<td class="td1" valign="top">모듈설명</td>
		<td valign="top">:</td>
		<td class="td2">본 모듈은 KimsQ Rb1.2의 게시판(bbs) 모듈을 기반으로 KimsQ Rb2.0 용으로 변환, 최적화 및 기능강화(개작)를 수행한 모듈입니다.</td>
	</tr>	
	<tr>
		<td class="td1" valign="top"></td>
		<td valign="top"></td>
		<td class="td2">BSKR은 부트스트랩커(BootstrapKR)의 약자이며, "대한민국의 부트스트랩" 또는 "부트스트랩 메이커"을 의미하는 중의적인 표현입니다.</td>
	</tr>	
	<tr>
		<td class="td1">모듈아이디</td>
		<td>:</td>
		<td class="td2"><?php echo $MD['id']?></td>
	</tr>
	<tr>
		<td class="td1">모듈의위치</td>
		<td>:</td>
		<td class="td2"><?php echo $g['path_module'].$module?>/</td>
	</tr>
	<tr>
		<td class="td1">테이블생성</td>
		<td>:</td>
		<td class="td2">
			<?php if($MD['tblnum']):?>
			<?php echo $MD['tblnum']?>개
			<?php else:?>
			없음
			<?php endif?>
		</td>
	</tr>
	<tr>
		<td class="td1">모듈등록일</td>
		<td>:</td>
		<td class="td2">
			<?php echo getDateFormat($MD['d_regis'],'Y/m/d')?>
		</td>
	</tr>
	<tr>
		<td class="td1">버젼</td>
		<td>:</td>
		<td class="td2">
			<?php echo $d['moduleinfo']['ver']?>
		</td>
	</tr>
	<tr>
		<td class="td1">최근업데이트</td>
		<td>:</td>
		<td class="td2">
			<?php echo $d['moduleinfo']['d_update']?>
		</td>
	</tr>
	</table>

	
	<h2>제작자 정보</h2>
	<table>
	<tr>
		<td class="td1" valign="top">제작사</td>
		<td valign="top">:</td>
		<td class="td2">스마툴즈</td>
	</tr>
	<tr>
		<td class="td1"></td>
		<td></td>
		<td class="td2">Copyright © 2014-<?php echo date("Y")?> by SmarTools Co.</td>
	</tr>	
	<tr>
		<td class="td1">브랜드명</td>
		<td>:</td>
		<td class="td2">부트스트랩커 (BootstrapKR)</td>
	</tr>
	<tr>
		<td class="td1">이메일</td>
		<td>:</td>
		<td class="td2"><a href="mailto:contact@bootstrapkr.com">contact@bootstrapkr.com</a></td>
	</tr>
	<tr>
		<td class="td1">홈페이지</td>
		<td>:</td>
		<td class="td2">
			<a href="http://www.bootstrapkr.com" target="_blank">www.bootstrapkr.com</a>
		</td>
	</tr>
	<tr>
		<td class="td1" valign="top">라이선스</td>
		<td valign="top">:</td>
		<td class="td2">
			LGPL
			<br><br>※ 라이브러리에 해당되는 게시판 모듈 자체에는 GPLv2 가 적용됩니다.
			<br>※ 단, 라이브러리를 사용하는 게시판 테마는 해당 제작자의 독립(점)적인 라이선스 설정이 가능합니다.
			<br>※ 본 프로그램은 있는 그대로 사용자에게 무상으로 양도되며, 어떠한 형태의 보증도 제공되지 않습니다.
		</td>
	</tr>	
	</table>


	<h2>원작자 정보</h2>
	<table>
	<tr>
		<td class="td1">제작사</td>
		<td>:</td>
		<td class="td2">레드블럭</td>
	</tr>
	<tr>
		<td class="td1">회원아이디</td>
		<td>:</td>
		<td class="td2">세븐고(kims)</td>
	</tr>
	<tr>
		<td class="td1">이메일</td>
		<td>:</td>
		<td class="td2"><a href="mailto:admin@kimsq.com">admin@kimsq.com</a></td>
	</tr>
	<tr>
		<td class="td1">홈페이지</td>
		<td>:</td>
		<td class="td2">
			<a href="http://www.kimsq.co.kr" target="_blank">www.kimsq.co.kr</a>
		</td>
	</tr>
	<tr>
		<td class="td1">라이선스</td>
		<td>:</td>
		<td class="td2">
			LGPL
		</td>
	</tr>
	</table>
	
	<h2>오픈소스 라이선스 고지</h2>
	<table>
	<tr>
		<td class="td1" valign="top">개요</td>
		<td valign="top">:</td>
		<td class="td2">본 모듈에는 아래와 같은 오픈소스 기술이 사용 및 포함되어 있으며, 각 요소의 저작권은 해당 저작권자에게 있습니다.</td>
	</tr>
	<tr>
		<td class="td1" valign="top">항목</td>
		<td valign="top">:</td>
		<td class="td2">
		- Bootstrap / MIT license / <a href="http://getbootstrap.com/" target="_blank">http://getbootstrap.com/</a><br>
		- jQuery / MIT license / <a href="http://jquery.com/" target="_blank">http://jquery.com/</a><br>		
		- jQuery Form Plugin / MIT license, GPL / <a href="https://github.com/malsup/form/" target="_blank">https://github.com/malsup/form/</a><br>		
		- 스마트 에디터2 / LGPL / <a href="http://dev.naver.com/projects/smarteditor" target="_blank">http://dev.naver.com/projects/smarteditor</a><br>
		- 썸머노트 / MIT license / <a href="http://summernote.org/" target="_blank">http://summernote.org/</a><br>
		</td>
	</tr>
	</table>	
</div>

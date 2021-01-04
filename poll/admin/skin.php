<?php if( $g['mobile'] && $_SESSION['pcmode']!='Y' ):?>
<style>#bskradm {font-size:14px;}</style>
<?php endif?>

<div id="bskradm" class="row">
	<div class="col-md-3 col-sm-4" id="tab-content-list">
		<div class="panel-group" id="accordion">
			<div class="panel panel-default">
				<div class="panel-heading rb-icon">
					<div class="icon">
						<i class="fa fa-cubes fa-2x"></i>
					</div>
					<h4 class="dropdown panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapmetane">테마 목록</a>
						<span class="pull-right" style="position:relative;left:-15px;top:3px;">
							<button type="button" class="btn btn-default btn-xs<?php if(!$_SESSION['sh_site_page_search']):?> collapsed<?php endif?>" data-toggle="collapse" data-target="#panel-search" data-tooltip="tooltip" title="<?php echo _LANG('a0002','module')?>" onclick="sessionSetting('sh_module_search','1','','1');getSearchFocus();"><i class="glyphicon glyphicon-search"></i></button>
						</span>
					</h4>
				</div>
				<div id="panel-search" class="collapse<?php if($_SESSION['sh_module_search']):?> in<?php endif?>">
					<form role="form" action="<?php echo $g['s']?>/" method="get">
					<input type="hidden" name="r" value="<?php echo $r?>">
					<input type="hidden" name="m" value="<?php echo $m?>">
					<input type="hidden" name="module" value="<?php echo $module?>">
					<input type="hidden" name="front" value="<?php echo $front?>">
					<input type="hidden" name="id" value="<?php echo $id?>">
						<div class="panel-heading rb-search-box">
							<div class="input-group">
								<div class="input-group-addon"><small><?php echo _LANG('a0003','module')?></small></div>
								<div class="input-group-btn">
									<select class="form-control" name="recnum" onchange="this.form.submit();">
									<option value="15"<?php if($recnum==15):?> selected<?php endif?>>15</option>
									<option value="30"<?php if($recnum==30):?> selected<?php endif?>>30</option>
									<option value="60"<?php if($recnum==60):?> selected<?php endif?>>60</option>
									<option value="100"<?php if($recnum==100):?> selected<?php endif?>>100</option>
									</select>
								</div>
							</div>
						</div>
						<div class="rb-keyword-search">
							<input type="text" name="keyw" class="form-control" value="<?php echo $keyw?>" placeholder="<?php echo _LANG('a0004','module')?>">
						</div>
					</form>
				</div>

				<div class="panel-collapse collapse in" id="collapmetane">
					<table id="module-list" class="table">
						<tbody>						
							<?php $i=0?>
							<?php $tdir = $g['path_module'].$module.'/themes/'?>
							<?php $dirs = opendir($tdir)?>
							<?php while(false !== ($skin = readdir($dirs))):?>
							<?php if($skin=='.' || $skin == '..' || is_file($tdir.$skin))continue?>
							<?php $i++?>
							<tr<?php if($theme==$skin):?> class="active1"<?php endif?> onclick="goHref('<?php echo $g['adm_href']?>&amp;recnum=<?php echo $recnum?>&amp;p=<?php echo $p?>&amp;theme=<?php echo $skin?>');">
								<td class="rb-name">
									<?php echo getFolderName($tdir.$skin)?> (<?php echo $skin?>)
								</td>
							</tr>
							<?php endwhile?>
							<?php closedir($dirs)?>
							<?php if(!$i):?>
							<tr>
								<td class="rb-name">테마가 없습니다.</td>							
							</tr> 
							<?php endif?>
						</tbody>
					</table>
				
					<?php if($TPG>1):?>
					<div class="panel-footer rb-panel-footer">
						<ul class="pagination">
						<script>getPageLink(5,<?php echo $p?>,<?php echo $TPG?>,'');</script>
						<?php //echo getPageLink(5,$p,$TPG,'')?>
						</ul>
					</div>
					<?php endif?>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-9 col-sm-8" id="tab-content-view">
		<div class="page-header">
			<h4>테마 세부설정 변수</h4>
		</div>

		<div class="panel panel-default">
			<div class="panel-body">
				<?php if($theme):?>
				<div class="notice">
					<b><?php echo getFolderName($tdir.$theme)?></b> 테마가 선택되었습니다.<br />
					이 테마를 사용하는 모든 게시판에 아래의 설정값이 적용됩니다.
				</div>
				
				<form name="procForm" action="<?php echo $g['s']?>/" method="post" target="_action_frame_<?php echo $m?>" onsubmit="return saveCheck(this);">
				<input type="hidden" name="r" value="<?php echo $r?>" />
				<input type="hidden" name="m" value="<?php echo $module?>" />
				<input type="hidden" name="a" value="theme_config" />
				<input type="hidden" name="theme" value="<?php echo $theme?>" />				
				<textarea name="theme_var" rows="10" cols="70"><?php echo implode('',file($g['path_module'].$module.'/themes/'.$theme.'/_var.php'))?></textarea>
				
				<div class="submitbox">
					<a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $module?>&amp;a=theme_delete&amp;theme=<?php echo $theme?>" target="_action_frame_<?php echo $m?>" class="btn btn-danger" onclick="return confirm('정말로 이 테마를 삭제하시겠습니까?       ');"><i class="glyphicon glyphicon-remove"></i> 테마삭제</a>
					<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> 테마속성 변경</button>
				</div>
				</form>	
				<?php else:?>
				<div class="notice">
					테마가 선택되지 않았습니다. 테마를 선택해 주세요.<br>
					테마설정은 해당 테마를 사용하는 모든 게시판에 적용됩니다.
				</div>
				
				<ul>
					<li>테마는 게시판의 외형을 변경할 수 있는 요소입니다.</li>
					<li>테마설정은 게시판의 외형만 제어하며 게시판의 내부시스템에는 영향을 주지 않습니다.</li>
					<li>테마의 속성을 변경하면 해당테마를 사용하는 모든 게시판에 적용됩니다.</li>
				</ul>		
				<?php endif?>														
			</div>
		</div>
	</div>
	<hr>
	</div>
</div>


<script type="text/javascript">
//<![CDATA[
function saveCheck(f)
{
	return confirm('정말로 실행하시겠습니까?         ');
}
//]]>
</script>

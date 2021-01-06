<?php



$R = getDbData($table['polllist'],'po_id='.$pid,'*');

$colorArray = array("blue","green","red","yellow");

$count = 0;
$totalvote = 0;
for($i = 0; $i < 9; $i++){
	if($R['po_poll'.$i] != ''){
		$count++;
		$totalvote = $totalvote + $R['po_cnt'.$i];
	}
}

?>

<div>
	<form id="hiddencharform" name="hiddencharform" action="<?php echo $g['s']?>/">
		<input type="hidden" name="r" value="<?php echo $r?>" />
		<input type="hidden" name="m" value="<?php echo $m?>" />
		<input type="hidden" name="a" value="" />	
		<input type="hidden" id="mod" name="mod" value="<?php echo $mod?>">	
		<input type="hidden" id="smod" name="smod" value="">	
		<input type="hidden" id="pid" name="pid" value="">

		<table class="table table-stripe default-table">
			<tbody>
			<tr>
				<th><?php echo $R['po_subject']?>&nbsp;&nbsp;&nbsp;총 <?php echo $totalvote ?>표</th>
			</tr>
			<tr>
				<td>				
					<div class="charts">
						<?php for($i = 0; $i < 9; $i++) :?>
							<?php if($R['po_poll'.$i] != '') : ?>
							<div style="text-align:left;">
							<span class="justspan1"><b><?php echo $R['po_poll'.$i]?></b></span>
							<span class="justspan2"><b>총 : <?php echo $R['po_cnt'.$i]?>표</b></span>
							<?php
								if($R['po_cnt'.$i] != 0){
									$rvote = sprintf('%0.2f', $R['po_cnt'.$i] / $totalvote * 100);
								}else{
									$rvote = 0;
								}
								$vvote = round($rvote);
							?>
								<div style="padding:0.8em;">
									<div class="wrapdiv">
										<div class="charts__chart chart--p<?php echo $vvote?> chart--<?php echo $colorArray[$i%4]?>">
										</div>
										<div class="wrapdivtext"><?php echo $R['po_cnt'.$i] ?>표 (<?php echo $rvote ?>%)</div>
									</div>								
								</div>
							</div>
							<?php endif ?>
						<?php endfor ?>
					</div>
				</td>
			</tr>
			</tbody>
		</table>
		<div style="text-align:center">
		<button type="submit" class="btn btn-sm btn-primary">투표리스트로 돌아가기</button>
		</div>
	</form>
</div>



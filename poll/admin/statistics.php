<?php
include_once $g['path_module'] . 'bskrbbs/mod/_func.php';

$SITES = getDbArray( $table['s_site'], '', '*', 'gid', 'asc', 0, 1 );

$year1  = $year1 ? $year1 : substr( $date['today'], 0, 4 );
$month1 = $month1 ? $month1 : substr( $date['today'], 4, 2 );
$day1   = $day1 ? $day1 : 1;//substr($date['today'],6,2);

$year2  = $year2 ? $year2 : substr( $date['today'], 0, 4 );
$month2 = $month2 ? $month2 : substr( $date['today'], 4, 2 );
$day2   = $day2 ? $day2 : substr( $date['today'], 6, 2 );


$sort = 'tfend'; //$sort ? $sort : '';

$orderby = 'desc'; //$orderby ? $orderby : 'asc';

$recnum = $recnum && $recnum < 200 ? $recnum : 20;

$accountQue = $account ? 'site=' . $account . ' and ' : '';

$_WHERE = $accountQue . 'd_regis > ' . $year1 . sprintf( '%02d', $month1 ) . sprintf( '%02d', $day1 ) . '000000 and d_regis < ' . $year2 . sprintf( '%02d', $month2 ) . sprintf( '%02d', $day2 ) . '240000';

if ( $bid ) {
	$_WHERE .= " and bid='$bid'";
}

if ( $notice ) {
	$_WHERE .= ' and notice=1';
}

if ( $hidden ) {
	$_WHERE .= ' and hidden=1';
}


if($keyword){
	$_WHERE .= ' and bid LIKE "%'.$keyword.'%"';
}
if ( $where && $keyw ) {
	if ( strstr( '[name][nic][id][ip]', $where ) ) {
		$_WHERE .= " and " . $where . "='" . $keyw . "'";
	} else {
		$_WHERE .= getSearchSql( $where, $keyw, $ikeyword, 'or' );
	}
}


$RCD = getDbArray( 'rb_bskrbbs_stop', $_WHERE, '*', $sort, $orderby, $recnum, $p );

//$NUM = getDbRows( 'rb_bskrbbs_stop', $_WHERE );
$I   = getDbData( 'rb_bskrbbs_stop', $_WHERE, 'count(*) AS count, sum(timespan) AS total_timespan, sum(tflen) AS total_tflen' );
$NUM = $I['count'];
$TPG = getTotalPage( $NUM, $recnum );
?>


<div id="bskrlist">
    <div class="sbox">
        <form name="procForm" action="<?php echo $g['s'] ?>/" method="get">
            <input type="hidden" name="r" value="<?php echo $r ?>"/>
            <input type="hidden" name="m" value="<?php echo $m ?>"/>
            <input type="hidden" name="module" value="<?php echo $module ?>"/>
            <input type="hidden" name="front" value="<?php echo $front ?>"/>

            <select name="account" class="account hide" onchange="this.form.submit();">
                <option value="">&nbsp;+ 전체사이트</option>
                <option value="">---------------------------</option>
				<?php while ( $S = db_fetch_array( $SITES ) ): ?>
                    <option value="<?php echo $S['uid'] ?>"<?php if ( $account == $S['uid'] ): ?> selected="selected"<?php endif ?>>ㆍ<?php echo $S['name'] ?></option>
				<?php endwhile ?>
				<?php if ( ! db_num_rows( $SITES ) ): ?>
                    <option value="">등록된 사이트가 없습니다.</option>
				<?php endif ?>
            </select>
			
			<div>

				<select name="bid" onchange="this.form.submit();">
					<option value="">&nbsp;+ 전체게시판</option>
					<option value="">---------------------------</option>
					<?php $_BBSLIST = getDbArray( $table[ $module . 'list' ], '', '*', 'gid', 'asc', 0, 1 ) ?>
					<?php while ( $_B = db_fetch_array( $_BBSLIST ) ): ?>
						<option value="<?php echo $_B['id'] ?>"<?php if ( $_B['id'] == $bid ): ?> selected="selected"<?php endif ?>>ㆍ<?php echo $_B['name'] ?>(<?php echo $_B['id'] ?> - <?php echo number_format( $_B['num_r'] ) ?>)</option>
					<?php endwhile ?>
					<?php if ( ! db_num_rows( $_BBSLIST ) ): ?>
						<option value="">등록된 게시판이 없습니다.</option>
					<?php endif ?>
				</select>
			
			
				<input type="text" name="keyword" id="keyword" value="" style="width:200px;">
				<button type="submit" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-search"></i>검색</button>
	
			
			</div>

            <div>
                <select name="year1">
					<?php for ( $i = $date['year']; $i > 2000; $i -- ): ?>
                        <option value="<?php echo $i ?>"<?php if ( $year1 == $i ): ?> selected="selected"<?php endif ?>><?php echo $i ?>년</option><?php endfor ?>
                </select>
                <select name="month1">
					<?php for ( $i = 1; $i < 13; $i ++ ): ?>
                        <option value="<?php echo sprintf( '%02d', $i ) ?>"<?php if ( $month1 == $i ): ?> selected="selected"<?php endif ?>><?php echo sprintf( '%02d', $i ) ?>월</option><?php endfor ?>
                </select>
                <select name="day1">
					<?php for ( $i = 1; $i < 32; $i ++ ): ?>
                        <option value="<?php echo sprintf( '%02d', $i ) ?>"<?php if ( $day1 == $i ): ?> selected="selected"<?php endif ?>><?php echo sprintf( '%02d', $i ) ?>일(<?php echo getWeekday( date( 'w', mktime( 0, 0, 0, $month1, $i, $year1 ) ) ) ?>)</option><?php endfor ?>
                </select> ~
                <select name="year2">
					<?php for ( $i = $date['year']; $i > 2000; $i -- ): ?>
                        <option value="<?php echo $i ?>"<?php if ( $year2 == $i ): ?> selected="selected"<?php endif ?>><?php echo $i ?>년</option><?php endfor ?>
                </select>
                <select name="month2">
					<?php for ( $i = 1; $i < 13; $i ++ ): ?>
                        <option value="<?php echo sprintf( '%02d', $i ) ?>"<?php if ( $month2 == $i ): ?> selected="selected"<?php endif ?>><?php echo sprintf( '%02d', $i ) ?>월</option><?php endfor ?>
                </select>
                <select name="day2">
					<?php for ( $i = 1; $i < 32; $i ++ ): ?>
                        <option value="<?php echo sprintf( '%02d', $i ) ?>"<?php if ( $day2 == $i ): ?> selected="selected"<?php endif ?>><?php echo sprintf( '%02d', $i ) ?>일(<?php echo getWeekday( date( 'w', mktime( 0, 0, 0, $month2, $i, $year2 ) ) ) ?>)</option><?php endfor ?>
                </select>

                <input type="button" class="btn btn-xs btn-default" value="기간적용" onclick="this.form.submit();"/>
                <input type="button" class="btn btn-xs btn-default" value="어제"
                       onclick="dropDate('<?php echo date( 'Ymd', mktime( 0, 0, 0, substr( $date['today'], 4, 2 ), substr( $date['today'], 6, 2 ) - 1, substr( $date['today'], 0, 4 ) ) ) ?>','<?php echo date( 'Ymd', mktime( 0, 0, 0, substr( $date['today'], 4, 2 ), substr( $date['today'], 6, 2 ) - 1, substr( $date['today'], 0, 4 ) ) ) ?>');"/>
                <input type="button" class="btn btn-xs btn-default" value="오늘" onclick="dropDate('<?php echo $date['today'] ?>','<?php echo $date['today'] ?>');"/>
                <input type="button" class="btn btn-xs btn-default" value="일주" onclick="dropDate('<?php echo date( 'Ymd', mktime( 0, 0, 0, substr( $date['today'], 4, 2 ), substr( $date['today'], 6, 2 ) - 7, substr( $date['today'], 0, 4 ) ) ) ?>','<?php echo $date['today'] ?>');"/>
                <input type="button" class="btn btn-xs btn-default" value="한달" onclick="dropDate('<?php echo date( 'Ymd', mktime( 0, 0, 0, substr( $date['today'], 4, 2 ) - 1, substr( $date['today'], 6, 2 ), substr( $date['today'], 0, 4 ) ) ) ?>','<?php echo $date['today'] ?>');"/>
                <input type="button" class="btn btn-xs btn-default" value="당월" onclick="dropDate('<?php echo substr( $date['today'], 0, 6 ) ?>01','<?php echo $date['today'] ?>');"/>
                <input type="button" class="btn btn-xs btn-default" value="전월"
                       onclick="dropDate('<?php echo date( 'Ym', mktime( 0, 0, 0, substr( $date['today'], 4, 2 ) - 1, substr( $date['today'], 6, 2 ), substr( $date['today'], 0, 4 ) ) ) ?>01','<?php echo date( 'Ym', mktime( 0, 0, 0, substr( $date['today'], 4, 2 ) - 1, substr( $date['today'], 6, 2 ), substr( $date['today'], 0, 4 ) ) ) ?>31');"/>
                <input type="button" class="btn btn-xs btn-default" value="전체" onclick="dropDate('20090101','<?php echo $date['today'] ?>');"/>
            </div>

            <div class="hide">
                <select name="sort" onchange="this.form.submit();">
                    <option value="gid"<?php if ( $sort == 'gid' ): ?> selected="selected"<?php endif ?>>등록일</option>
                    <option value="hit"<?php if ( $sort == 'hit' ): ?> selected="selected"<?php endif ?>>조회</option>
                    <option value="down"<?php if ( $sort == 'down' ): ?> selected="selected"<?php endif ?>>다운</option>
                    <option value="comment"<?php if ( $sort == 'comment' ): ?> selected="selected"<?php endif ?>>댓글</option>
                    <option value="oneline"<?php if ( $sort == 'oneline' ): ?> selected="selected"<?php endif ?>>한줄의견</option>
                    <option value="trackback"<?php if ( $sort == 'trackback' ): ?> selected="selected"<?php endif ?>>트랙백</option>
                    <option value="score1"<?php if ( $sort == 'score1' ): ?> selected="selected"<?php endif ?>>점수1</option>
                    <option value="score2"<?php if ( $sort == 'score2' ): ?> selected="selected"<?php endif ?>>점수2</option>
                    <option value="singo"<?php if ( $sort == 'singo' ): ?> selected="selected"<?php endif ?>>신고</option>
                </select>
                <select name="orderby" onchange="this.form.submit();">
                    <option value="desc"<?php if ( $orderby == 'desc' ): ?> selected="selected"<?php endif ?>>역순</option>
                    <option value="asc"<?php if ( $orderby == 'asc' ): ?> selected="selected"<?php endif ?>>정순</option>
                </select>

                <select name="recnum" onchange="this.form.submit();">
                    <option value="20"<?php if ( $recnum == 20 ): ?> selected="selected"<?php endif ?>>20개</option>
                    <option value="35"<?php if ( $recnum == 35 ): ?> selected="selected"<?php endif ?>>35개</option>
                    <option value="50"<?php if ( $recnum == 50 ): ?> selected="selected"<?php endif ?>>50개</option>
                    <option value="75"<?php if ( $recnum == 75 ): ?> selected="selected"<?php endif ?>>75개</option>
                    <option value="90"<?php if ( $recnum == 90 ): ?> selected="selected"<?php endif ?>>90개</option>
                </select>
                <select name="where">
                    <option value="subject|tag"<?php if ( $where == 'subject|tag' ): ?> selected="selected"<?php endif ?>>제목+태그</option>
                    <option value="content"<?php if ( $where == 'content' ): ?> selected="selected"<?php endif ?>>본문</option>
                    <option value="name"<?php if ( $where == 'name' ): ?> selected="selected"<?php endif ?>>이름</option>
                    <option value="nic"<?php if ( $where == 'nic' ): ?> selected="selected"<?php endif ?>>닉네임</option>
                    <option value="id"<?php if ( $where == 'id' ): ?> selected="selected"<?php endif ?>>아이디</option>
                    <option value="ip"<?php if ( $where == 'ip' ): ?> selected="selected"<?php endif ?>>아이피</option>
                </select>

                <input type="text" name="keyw" value="<?php echo stripslashes( $keyw ) ?>" class="input"/>
                <input type="submit" value="검색" class="btn btn-xs btn-info"/>
                <input type="button" value="리셋" class="btn btn-xs btn-default" onclick="location.href='<?php echo $g['adm_href'] ?>';"/>
                &nbsp;
                <label><input type="checkbox" name="notice" id="notice" value="Y"<?php if ( $notice == 'Y' ): ?> checked="checked"<?php endif ?> onclick="this.form.submit();"/> 공지글</label>
                &nbsp;
                <label><input type="checkbox" name="hidden" id="hidden" value="Y"<?php if ( $hidden == 'Y' ): ?> checked="checked"<?php endif ?> onclick="this.form.submit();"/> 비밀글</label>
            </div>
        </form>
    </div>

    <div class="info">
        <div class="article">
			<?php echo number_format( $NUM ) ?>개(<?php echo $p ?>/<?php echo $TPG ?>페이지) 총재생시간: <?= number_format( $I['total_timespan'] ) ?> 총트래픽: <?= number_format( $I['total_tflen'] ) ?>
        </div>
        <div class="category"></div>
        <div class="clear"></div>
    </div>

    <form name="listForm" action="<?php echo $g['s'] ?>/" method="post" target="_action_frame_<?php echo $m ?>">
        <input type="hidden" name="r" value="<?php echo $r ?>"/>
        <input type="hidden" name="m" value="<?php echo $module ?>"/>
        <input type="hidden" name="a" value=""/>
		<?php
		$dateTimeZoneUTC   = new DateTimeZone( "UTC" );
		$dateTimeZoneLocal = new DateTimeZone( date_default_timezone_get() );

		// Create two DateTime objects that will contain the same Unix timestamp, but
		// have different timezones attached to them.
		$dateTimeUTC   = new DateTime( "now", $dateTimeZoneUTC );
		$dateTimeLocal = new DateTime( "now", $dateTimeZoneLocal );

		// Calculate the GMT offset for the date/time contained in the $dateTimeTaipei
		// object, but using the timezone rules as defined for Tokyo
		// ($dateTimeZoneJapan).
		$timeOffset = $dateTimeZoneLocal->getOffset( $dateTimeUTC );

		// Should show int(32400) (for dates after Sat Sep 8 01:00:00 1951 JST).
		//var_dump($timeOffset);

		?>
        <div class="table-responsive">
            <table class="table table-striped table-admin">
                <colgroup>
                    <col width="30"></col> <!--check-->
                    <col width="50"></col> <!--번호-->
                    <col width="100"></col> <!--게시판 ID-->
                    <col width="150"></col><!--재생시작일시-->
                    <!--col width="150"></col><!--재생종료일시-->
                    <col width="130"></col><!--IP 주소-->
                    <col></col><!--Path-->
                    <col width="100"></col> <!--재생시간-->
                    <col width="100"></col> <!--트래픽(Byte)-->
                    <col width="100"></col> <!--dev-->
                    <col width="100"></col> <!--호출파라미터-->
                    <!--col width="140"></col><!--날짜-->
                    <!--<col></col>-->
                </colgroup>
                <thead>
                <tr>
                    <th scope="col" class="side1"><img src="<?php echo $g['path_module'] . $module ?>/admin/img/ico_check_01.gif" class="hand" alt="" onclick="chkFlag('statistics_members[]');"/></th>
                    <th scope="col">번호</th>
                    <th scope="col">게시판 ID</th>
                    <th scope="col">재생시작</th>
                    <!--th scope="col">재생종료</th-->
                    <th scope="col">IP 주소</th>
                    <th scope="col">Path</th>
                    <th scope="col" class="center">재생시간</th>
                    <th scope="col" class="center">트래픽(Byte)</th>
                    <th scope="col" class="center">dev</th>
                    <th scope="col" class="center">호출파라미터</th>
                    <!--th scope="col" class="center">날짜</th-->
                    <!--<th scope="col" class="side2"></th>-->
                </tr>
                </thead>

                <tbody>
				<?php while ( $R = db_fetch_array( $RCD ) ):
					$total_timespan += $R['timespan'];
					$total_tflen += $R['tflen'];
					?>
					<?php $R['mobile'] = isMobileConnect( $R['agent'] ) ?>
                    <tr>
                        <td><input type="checkbox" name="statistics_members[]" value="<?php echo $R['uuid'] ?>"/></td>
                        <td>
							<?php if ( $R['uid'] != $uid ): ?>
								<?php echo $NUM - ( ( ( $p - 1 ) * $recnum ) + $_rec ++ ) ?>
							<?php else:$_rec ++ ?>
                                <span class="now">&gt;&gt;</span>
							<?php endif ?>
                        </td>
                        <td><a href="<?php echo RW( 'm=' . $module . '&bid=' . $R['bbsid'] ) ?>" target="_blank"><?php echo $R['bid'] ?></a></td>
                        <td><?= date( 'Y.m.d H:i:s', strtotime( $R['tfstart'] . " " . $timeOffset . " second" ) ) ?></td>
                        <!--td><!--?= date( 'Y.m.d H:i:s', strtotime( $timeOffset . ' second ' . $R['tfend'] ) ) ?></td-->
                        <td><?= $R['userhost'] ?></td>
                        <td style="max-width: 200px; overflow-x: scroll"><?= $R['path'] ?></td>
                        <td class="right"><?= $R['timespan'] ? number_format( $R['timespan'] ) : "" ?></td>
                        <td class="right"><?= $R['tflen'] ? number_format( $R['tflen'] ) : "" ?></td>
                        <td class="center"><?= $R['dev'] ?></td>
                        <td class="center"><?= $R['uid'] ?></td>
                        <!--td class="center"><!--?= getDateFormat( $R['d_regis'], 'Y.m.d H:i' ) ?></td-->
                        <!--<td></td>-->
                    </tr>
				<?php endwhile ?>

				<?php if ( ! $NUM ): ?>
                    <tr>
                        <td><input type="checkbox" disabled="disabled"/></td>
                        <td>1</td>
                        <td>-</td>
                        <td class="sbj1">-</td>
                        <!--td class="sbj1">-</td-->
                        <td class="hit b">-</td>
                        <td>-</td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                        <!--td><!--?= getDateFormat( $date['totime'], 'Y.m.d H:i' ) ?></td-->
                        <!--<td></td>-->
                    </tr>
				<?php endif ?>
                </tbody>

                <tfoot>
                <tr>
                    <th colspan="6">합계</th>
                    <th class="right"><?= number_format( $total_timespan ) ?></th>
                    <th class="right"><?= number_format( $total_tflen ) ?></th>
                    <th colspan="2"></th>
                </tr>
                <tr>
                    <th colspan="6">총합계</th>
                    <th class="right"><?= number_format( $I["total_timespan"] ) ?></th>
                    <th class="right"><?= number_format( $I["total_tflen"] ) ?></th>
                    <th colspan="2"></th>
                </tr>
                </tfoot>
            </table>
        </div>

        <div class="pagebox01">
            <script type="text/javascript">getPageLink(10,<?php echo $p?>,<?php echo $TPG?>, '<?php echo $g['img_core']?>/page/default');</script>
        </div>

        <input type="button" value="선택/해제" class="btn btn-sm btn-success" onclick="chkFlag('statistics_members[]');"/>
         <input type="button" value="삭제" class="btn btn-sm btn-danger" onclick="actCheck('multi_delete_statistics');" /> 
        <!-- <input type="button" value="복사" class="btn btn-sm btn-default" onclick="actCheck('multi_copy');" /> -->
        <!-- <input type="button" value="이동" class="btn btn-sm btn-default" onclick="actCheck('multi_move');" /> -->
    </form>
</div>


<script type="text/javascript">
    //<![CDATA[
    function dropDate(date1, date2) {
        var f = document.procForm;
        f.year1.value = date1.substring(0, 4);
        f.month1.value = date1.substring(4, 6);
        f.day1.value = date1.substring(6, 8);

        f.year2.value = date2.substring(0, 4);
        f.month2.value = date2.substring(4, 6);
        f.day2.value = date2.substring(6, 8);

        f.submit();
    }

    function actCheck(act) {
        var f = document.listForm;
        var l = document.getElementsByName('statistics_members[]');
        var n = l.length;
        var j = 0;
        var i;
        var s = '';

        for (i = 0; i < n; i++) {
            if (l[i].checked == true) {
                j++;
                s += '[' + l[i].value + ']';
            }
        }
        if (!j) {
            alert('선택된 게시물이 없습니다.      ');
            return false;
        }

        if (act == 'multi_delete_statistics') {
            if (confirm('정말로 삭제하시겠습니까?    ')) {
                f.a.value = act;
                f.submit();
            }
        } else {
            OpenWindow('<?php echo $g['s']?>/?r=<?php echo $r?>&iframe=Y&m=<?php echo $m?>&module=<?php echo $module?>&front=movecopy&type=' + act + '&postuid=' + s);
        }
        return false;
    }

    //]]>
</script>

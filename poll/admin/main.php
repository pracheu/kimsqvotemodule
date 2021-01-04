<?php
$sort    = $sort ? $sort : 'gid';
$orderby = $orderby ? $orderby : 'asc';
$recnum  = $recnum && $recnum < 301 ? $recnum : 30;
$bbsque  = 'uid';

if ( $where && $keyw ) {
	if ( strstr( '[id]', $where ) ) {
		$bbsque .= " and " . $where . "='" . $keyw . "'";
	} else {
		$bbsque .= getSearchSql( $where, $keyw, $ikeyword, 'or' );
	}
}else if($keyw){
	$bbsque = 'name LIKE "%'.$keyw. '%"';
}
$BBS = array();
$RCD = getDbArray( $table[ $module . 'list' ], $bbsque, '*', $sort, $orderby, $recnum, $p );
while ( $_R = db_fetch_array( $RCD ) ) {
	$BBS[] = $_R;
}

$NUM = getDbRows( $table[ $module . 'list' ], $bbsque );
$TPG = getTotalPage( $NUM, $recnum );

$_LEVELNAME = array( 'l0' => '전체허용' );
$_LEVELDATA = getDbArray( $table['s_mbrlevel'], '', '*', 'uid', 'asc', 0, 1 );
while ( $_L = db_fetch_array( $_LEVELDATA ) ) {
	$_LEVELNAME[ 'l' . $_L['uid'] ] = $_L['name'] . ' 이상';
}

// 기본값 로딩
if ( ! $uid ) {
	include_once $g['path_module'] . $module . '/var/var.php';
}
?>
<?php if ( $g['mobile'] && $_SESSION['pcmode'] != 'Y' ): ?>
    <style>
        #bskradm {
            font-size: 14px;
        }

        #bskradm .bskr-input {
            font-size: 14px;
        }
    </style>
<?php endif ?>

<div id="bskradm" class="row">
    <div class="col-md-3 col-sm-4" id="tab-content-list">
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading rb-icon">
                    <div class="icon">
                        <i class="fa fa-book fa-2x"></i>
                    </div>
                    <h4 class="dropdown panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"
                           href="#collapmetane">게시판 목록</a>
                        <span class="pull-right" style="position:relative;left:-15px;top:3px;">
							<button type="button"
                                    class="btn btn-default btn-xs<?php if ( ! $_SESSION['sh_site_page_search'] ): ?> collapsed<?php endif ?>"
                                    data-toggle="collapse" data-target="#panel-search" data-tooltip="tooltip"
                                    title="<?php echo _LANG( 'a0002', 'module' ) ?>"
                                    onclick="sessionSetting('sh_module_search','1','','1');getSearchFocus();"><i
                                        class="glyphicon glyphicon-search"></i></button>
						</span>
                    </h4>
                </div>
                <div id="panel-search" class="collapse<?php if ( $_SESSION['sh_module_search'] ): ?> in<?php endif ?>">
                    <form role="form" action="<?php echo $g['s'] ?>/" method="get">
                        <input type="hidden" name="r" value="<?php echo $r ?>">
                        <input type="hidden" name="m" value="<?php echo $m ?>">
                        <input type="hidden" name="module" value="<?php echo $module ?>">
                        <input type="hidden" name="front" value="<?php echo $front ?>">
                        <input type="hidden" name="id" value="<?php echo $id ?>">
                        <div class="panel-heading rb-search-box">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <small><?php echo '라인수' ?></small>
                                </div>
                                <div class="input-group-btn">
                                    <select class="form-control" name="recnum" onchange="this.form.submit();">
                                        <option value="15"<?php if ( $recnum == 15 ): ?> selected<?php endif ?>>15
                                        </option>
                                        <option value="30"<?php if ( $recnum == 30 ): ?> selected<?php endif ?>>30
                                        </option>
                                        <option value="60"<?php if ( $recnum == 60 ): ?> selected<?php endif ?>>60
                                        </option>
                                        <option value="100"<?php if ( $recnum == 100 ): ?> selected<?php endif ?>>100
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="rb-keyword-search">
                            <input type="text" name="keyw" class="form-control" value="<?php echo $keyw ?>"
                                   placeholder="검색할 게시판명을 입력해 주세요">
                        </div>
                    </form>
                </div>

                <div class="panel-collapse collapse in" id="collapmetane">
                    <table id="module-list" class="table table-bbslist">
                        <thead>
                        <tr>
                            <td class="rb-id"><span>게시판명</span></td>
                            <td class="rb-time"><span>게시물</span></td>
                        </tr>
                        </thead>
                        <tbody>
						<?php foreach ( $BBS as $_R ): ?>
                            <tr<?php if ( $uid == $_R['uid'] ): ?> class="active1"<?php endif ?>
                                    onclick="goHref('<?php echo $g['adm_href'] ?>&amp;recnum=<?php echo $recnum ?>&amp;p=<?php echo $p ?>&amp;uid=<?php echo $_R['uid'] ?>');">
                                <td class="rb-name">
									<?php echo $_R['name'] ?> (<?php echo $_R['id'] ?>)
                                </td>
                                <td class="rb-time"><?php echo number_format( $_R['num_r'] ) ?>개</td>
                            </tr>
						<?php endforeach ?>
						<?php if ( ! $NUM ): ?>
                            <tr>
                                <td class="rb-name" colspan="2">게시판이 없습니다.</td>
                            </tr>
						<?php endif ?>
                        </tbody>
                    </table>

					<?php if ( $TPG > 1 ): ?>
                        <div class="panel-footer rb-panel-footer">
                            <ul class="pagination">
                                <script>getPageLink(5,<?php echo $p?>,<?php echo $TPG?>, '');</script>
								<?php //echo getPageLink(5,$p,$TPG,'')?>
                            </ul>
                        </div>
					<?php endif ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading rb-icon">
                    <div class="icon">
                        <i class="fa fa-retweet fa-2x"></i>
                    </div>
                    <h4 class="panel-title">
                        <a class="accordion-toggle collapsed" data-parent="#accordion" data-toggle="collapse"
                           href="#collapseTwo">
                            순서조정
                        </a>
                    </h4>
                </div>
                <div class="panel-collapse collapse" id="collapseTwo">
                    <form name="form" action="<?php echo $g['s'] ?>/" method="post"
                          target="_action_frame_<?php echo $m ?>"
                    <input type="hidden" name="r" value="<?php echo $r ?>"/>
                    <input type="hidden" name="m" value="<?php echo $module ?>"/>
                    <input type="hidden" name="a" value="bbsorder_update"/>
                    <div class="panel-body" style="border-top:#ccc solid 1px;">
                        <div class="dd" id="nestable-menu">
                            <ol class="dd-list">
								<?php foreach ( $BBS as $_R ): ?>
                                    <li class="dd-item" data-id="1">
                                        <div class="dd-handle">
                                            <input type="checkbox" name="bbsmembers[]" value="<?php echo $_R['uid'] ?>"
                                                   checked class="hidden">
                                            <i class="fa fa-arrows fa-fw"></i>
											<?php echo $_R['name'] ?> (<?php echo $_R['id'] ?>)
                                        </div>
                                    </li>
								<?php endforeach ?>
                            </ol>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-9 col-sm-8" id="tab-content-view">
        <div class="page-header clearfix">
            <h4 class="pull-left"><?php if ( $uid ): ?>게시판 속성<?php else: ?>새 게시판 만들기<?php endif ?></h4>
			<?php if ( $uid ): ?><a href="<?php echo $g['adm_href'] ?>" class="pull-right"><i
                        class="glyphicon glyphicon-plus"></i> 새 게시판 만들기</a><?php endif ?>
        </div>

		<?php
		if ( $uid ) {
			$R = getUidData( $table[ $module . 'list' ], $uid );
			if ( $R['uid'] ) {
				include_once $g['path_module'] . $module . '/var/var.' . $R['id'] . '.php';

				$comment = getDbData( $table[ $module . 'data' ], "bbs='" . $R['uid'] . "'", 'SUM(comment)' );
				$oneline = getDbData( $table[ $module . 'data' ], "bbs='" . $R['uid'] . "'", 'SUM(oneline)' );

				$L        = getOverTime( $date['totime'], $R['d_last'] );
				$timeunit = array( '초', '분', '시간', '일', '달', '년' );
			}
			?>
            <div class="panel panel-default">
                <div class="panel-heading"><b><?php echo $R['name'] . ' (' . $R['id'] . ')' ?></b> 요약정보</div>
                <div class="panel-body">
                    <table class="table table-bordered table-condensed" style="margin-bottom:0">
                        <tr>
                            <td class="title">게시물</td>
                            <td><?php echo number_format( $R['num_r'] ) ?>개</td>
                            <td class="title">댓글</td>
                            <td><?php echo number_format( $comment[0] ) ?>개</td>
                            <td class="title">한줄의견</td>
                            <td><?php echo number_format( $oneline[0] ) ?>개</td>
                        </tr>
                        <tr>
                            <td class="title">최근 게시물</td>
                            <td><?php echo $R['d_last'] ? ( $L[1] < 3 ? $L[0] . $timeunit[ $L[1] ] . ' 전' : getDateFormat( $R['d_last'], 'Y.m.d' ) ) : '-' ?><?php if ( getNew( $R['d_last'], $d['bbs']['newtime'] ) ): ?>
                                    <span class="new">new</span><?php endif ?></td>
                            <td class="title">분류/연결</td>
                            <td><?php echo $R['category'] ? '<span>Y</span>' : 'N' ?>
                                / <?php echo $d['bbs']['sosokmenu'] ? '<span>Y</span>' : 'N' ?></td>
                            <td class="title">헤더/푸터</td>
                            <td><?php echo $R['imghead'] || is_file( $g['path_module'] . $module . '/var/code/' . $R['id'] . '.header.php' ) ? '<span>Y</span>' : 'N' ?>
                                / <?php echo $R['imgfoot'] || is_file( $g['path_module'] . $module . '/var/code/' . $R['id'] . '.footer.php' ) ? '<span>Y</span>' : 'N' ?></td>
                        </tr>
                        <tr>
                            <td class="title">레이아웃</td>
                            <td><?php echo $d['bbs']['layout'] ? '<i>Y</i>' : 'N' ?>
                                / <?php echo $d['bbs']['skin'] ? '<i>Y</i>' : 'N' ?>
                                / <?php echo $d['bbs']['c_skin'] ? '<i>Y</i>' : 'N' ?></td>
                            <td class="title">접근권한</td>
                            <td><?php echo $d['bbs']['perm_l_list'] ?> / <?php echo $d['bbs']['perm_l_view'] ?>
                                / <?php echo $d['bbs']['perm_l_write'] ?> / <?php echo $d['bbs']['perm_l_down'] ?></td>
                            <td class="title">포인트</td>
                            <td><?php echo number_format( $d['bbs']['point1'] ? $d['bbs']['point1'] : 0 ) ?>
                                / <?php echo number_format( $d['bbs']['point2'] ? $d['bbs']['point2'] : 0 ) ?>
                                / <?php echo number_format( $d['bbs']['point3'] ? $d['bbs']['point3'] : 0 ) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
			<?php
		}
		?>

        <div class="panel panel-default">
			<?php if ( $uid ): ?>
                <div class="panel-heading"><b><?php echo $R['name'] . ' (' . $R['id'] . ')' ?></b> 속성
                </div><?php endif ?>
            <div class="panel-body">
                <form name="procForm" action="<?php echo $g['s'] ?>/" method="post" enctype="multipart/form-data"
                      target="_action_frame_<?php echo $m ?>" onsubmit="return saveCheck(this);">
                    <input type="hidden" name="r" value="<?php echo $r ?>"/>
                    <input type="hidden" name="m" value="<?php echo $module ?>"/>
                    <input type="hidden" name="a" value="makebbs"/>
                    <input type="hidden" name="bid" value="<?php echo $R['id'] ?>"/>
                    <input type="hidden" name="perm_g_list" value="<?php echo $d['bbs']['perm_g_list'] ?>"/>
                    <input type="hidden" name="perm_g_view" value="<?php echo $d['bbs']['perm_g_view'] ?>"/>
                    <input type="hidden" name="perm_g_write" value="<?php echo $d['bbs']['perm_g_write'] ?>"/>
                    <input type="hidden" name="perm_g_down" value="<?php echo $d['bbs']['perm_g_down'] ?>"/>
                    <input type="hidden" name="perm_g_upload" value="<?php echo $d['bbs']['perm_g_upload'] ?>"/>
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <td class="td1">
                                    게시판이름 <i class="glyphicon glyphicon-question-sign hand"
                                             onclick="$('#guide_bbsidname').toggle()"></i>
                                </td>
                                <td class="td2">
                                    <input type="text" name="name" value="<?php echo $R['name'] ?>"
                                           class="input sname"/>
									<?php if ( $R['id'] ): ?>
                                        <span class="btn01"><a
                                                    href="<?php echo RW( 'm=' . $module . '&bid=' . $R['id'] ) ?>"
                                                    target="_blank" class="btn btn-default btn-xs bskr-input">게시판보기</a></span>
									<?php else: ?>
                                        아이디 <input type="text" name="id" value="" class="input sname2"/>
									<?php endif ?>

                                    <div id="guide_bbsidname" class="guide hide2">
                                        <span class="b">게시판이름</span> : 게시판제목에 해당되며 한글,영문등 자유롭게 등록할 수 있습니다.<br/>
                                        <span class="b">아이디</span> : 영문 대소문자+숫자+_ 조합으로 만듭니다.<br/>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">
                                    카 테 고 리 <i class="glyphicon glyphicon-question-sign hand"
                                               onclick="$('#guide_category').toggle()"></i>
                                </td>
                                <td class="td2">
                                    <input type="text" name="category" value="<?php echo $R['category'] ?>"
                                           class="input sname1"/>
                                    <div id="guide_category" class="guide hide2">
                                        분류를 <b>콤마(,)</b>로 구분해 주세요. 첫 항목은 전체 분류를 나타내는 목적(분류제목 등)으로 사용됩니다.<br/>
                                        예) <span class="b">전체</span>,유머,공포,엽기,무협,기타
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">공개여부</td>
                                <td class="td2">
                                    <select name="boardopen" class="select1">
                                        <option value="0"<?php if ( $d['bbs']['boardopen'] == '0' ): ?> selected="selected"<?php endif ?>>ㆍ공개</option>
                                        <option value="1"<?php if ( $d['bbs']['boardopen'] == '1' ): ?> selected="selected"<?php endif ?>>ㆍ비공개</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">레 이 아 웃</td>
                                <td class="td2">
                                    <select name="layout" class="select1">
                                        <option value="">&nbsp;+ 사이트 대표레이아웃</option>
										<?php $dirs = opendir( $g['path_layout'] ) ?>
										<?php while ( false !== ( $tpl = readdir( $dirs ) ) ): ?>
											<?php if ( $tpl == '.' || $tpl == '..' || $tpl == '_blank' || is_file( $g['path_layout'] . $tpl ) )
												continue ?>
											<?php $dirs1 = opendir( $g['path_layout'] . $tpl ) ?>
                                            <option value="">--------------------------------</option>
											<?php while ( false !== ( $tpl1 = readdir( $dirs1 ) ) ): ?>
												<?php if ( ! strstr( $tpl1, '.php' ) || $tpl1 == '_main.php' )
													continue ?>
                                                <option value="<?php echo $tpl ?>/<?php echo $tpl1 ?>"<?php if ( $d['bbs']['layout'] == $tpl . '/' . $tpl1 ): ?> selected="selected"<?php endif ?>>
                                                    ㆍ<?php echo getFolderName( $g['path_layout'] . $tpl ) ?>
                                                    (<?php echo str_replace( '.php', '', $tpl1 ) ?>)
                                                </option>
											<?php endwhile ?>
											<?php closedir( $dirs1 ) ?>
										<?php endwhile ?>
										<?php closedir( $dirs ) ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">게시판테마</td>
                                <td class="td2">
                                    <select name="skin" class="select1">
                                        <option value="">&nbsp;+ 게시판 대표테마</option>
                                        <option value="">--------------------------------</option>
										<?php $tdir = $g['path_module'] . $module . '/themes/' ?>
										<?php $dirs = opendir( $tdir ) ?>
										<?php while ( false !== ( $skin = readdir( $dirs ) ) ): ?>
											<?php if ( $skin == '.' || $skin == '..' || is_file( $tdir . $skin ) )
												continue ?>
                                            <option value="<?php echo $skin ?>"
                                                    title="<?php echo $skin ?>"<?php if ( $d['bbs']['skin'] == $skin ): ?> selected="selected"<?php endif ?>>
                                                ㆍ<?php echo getFolderName( $tdir . $skin ) ?>(<?php echo $skin ?>)
                                            </option>
										<?php endwhile ?>
										<?php closedir( $dirs ) ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1 sfont1">(모바일접속)</td>
                                <td class="td2">
                                    <select name="m_skin" class="select1">
                                        <option value="">&nbsp;+ 모바일 대표테마</option>
                                        <option value="">--------------------------------</option>
										<?php $tdir = $g['path_module'] . $module . '/themes/' ?>
										<?php $dirs = opendir( $tdir ) ?>
										<?php while ( false !== ( $skin = readdir( $dirs ) ) ): ?>
											<?php if ( $skin == '.' || $skin == '..' || is_file( $tdir . $skin ) )
												continue ?>
                                            <option value="<?php echo $skin ?>"
                                                    title="<?php echo $skin ?>"<?php if ( $d['bbs']['m_skin'] == $skin ): ?> selected="selected"<?php endif ?>>
                                                ㆍ<?php echo getFolderName( $tdir . $skin ) ?>(<?php echo $skin ?>)
                                            </option>
										<?php endwhile ?>
										<?php closedir( $dirs ) ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">EnjClient</td>
                                <td class="td2">
                                    <select name="enjclient" class="select1">
                                        <option value="">&nbsp;+ Default(EnjClient.js)</option>
                                        <option value="">--------------------------------</option>
										<?php
										$path_plugin = $g['path_module'] . $module . "/plugin/";
										foreach ( scandir( $path_plugin ) as $file ):
											if ( is_dir( $path_plugin . $file ) || strpos( $file, 'EnjClient' ) !== 0 || strstr( $file, ".js" ) != '.js' ) {
												continue;
											}
											?>
                                            <option value="<?= $file ?>" <?php if ( $d['bbs']['enjclient'] == $file ): ?>selected="selected"<?php endif;
											?>><?= $file ?></option>
										<?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">Url Encrypt</td>
                                <td class="td2">
                                    <input type="checkbox" name="url_encrypt"
									       <?php if ( $d['bbs']['url_encrypt'] ): ?>checked<?php endif; ?>>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">메시지 PUSH 서버
                                    <i class="glyphicon glyphicon-question-sign hand"
                                       onclick="$('#guide_push_server').toggle()"></i>
                                </td>
                                <td class="td2">
                                    <input type="text" name="push_server" value="<?= $d['bbs']['push_server'] ?>"
                                           size="50" class="input">
                                    <div id="guide_push_server" class="guide hide2">
                                        <span class="b">PUSH 서버</span> : PUSH 서버 주소<br/>
                                        <span class="b">PUSH 서버</span> : 마지막 /는 있어야 함, default : http://board.enjsoft.com/push/<br/>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">댓글 PUSH 발송</td>
                                <td class="td2">
                                    <input type="checkbox" name="push_comment"
									       <?php if ( $d['bbs']['push_comment'] ): ?>checked<?php endif; ?>>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">동영상 첨부</td>
                                <td class="td2">
                                    <input type="checkbox" name="video_upload"
									       <?php if ( $d['bbs']['video_upload'] ): ?>checked<?php endif; ?>>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">동영상 업로드 서버
                                    <i class="glyphicon glyphicon-question-sign hand"
                                       onclick="$('#guide_video_upload').toggle()"></i>
                                </td>
                                <td class="td2">
                                    <input type="text" name="video_server" value="<?= $d['bbs']['video_server'] ?>"
                                           size="50" class="input">
                                    <div id="guide_video_upload" class="guide hide2">
                                        <span class="b">동영상 업로드 서버</span> : 동영상 업로드 서버 주소<br/>
                                        <span class="b">자동 녹확 재생 서버</span> : 마지막 /는 없어야 함, 값이 없으면 apt.movieupservice.net:1935 로 재생 정보 저장<br/>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">동영상 upstoragename</td>
                                <td class="td2">
                                    <input type="text" name="video_storage" value="<?= $d['bbs']['video_storage'] ?>"
                                           size="50" class="input">
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">DID단말기_1</td>
                                <td class="td2">
                                    <input type="text" name="device_id_1" value="<?= $d['bbs']['device_id_1'] ?>" size="50" class="input">
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">DID단말기_1_url</td>
                                <td class="td2">
                                    <input type="text" name="device_id_1_url" value="<?= $d['bbs']['device_id_1_url'] ?>" size="50" class="input">
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">DID단말기_2</td>
                                <td class="td2">
                                    <input type="text" name="device_id_2" value="<?= $d['bbs']['device_id_2'] ?>" size="50" class="input">
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">DID단말기_2_url</td>
                                <td class="td2">
                                    <input type="text" name="device_id_2_url" value="<?= $d['bbs']['device_id_2_url'] ?>" size="50" class="input">
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">DID단말기_3</td>
                                <td class="td2">
                                    <input type="text" name="device_id_3" value="<?= $d['bbs']['device_id_3'] ?>" size="50" class="input">
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">DID단말기_3_url</td>
                                <td class="td2">
                                    <input type="text" name="device_id_3_url" value="<?= $d['bbs']['device_id_3_url'] ?>" size="50" class="input">
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">DID 유효기간</td>
                                <td class="td2">
                                    <input type="text" name="device_time" value="<?= $d['bbs']['device_time'] ?>" size="50" class="input">
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">Live 방송 주소</td>
                                <td class="td2">
                                    <input type="text" name="live_path" value="<?= $d['bbs']['live_path'] ?>" size="50" class="input">
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">그룹 대화 주소</td>
                                <td class="td2">
                                    <input type="text" name="group_path" value="<?= $d['bbs']['group_path'] ?>" size="50" class="input">
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">그룹 대화 허용</td>
                                <td class="td2">
                                    <select name="group_chat" class="select1">
                                        <option value="0"<?php if ( $d['bbs']['group_chat'] == '0' ): ?> selected="selected"<?php endif ?>>ㆍ사용안함</option>
                                        <option value="1"<?php if ( $d['bbs']['group_chat'] == '1' ): ?> selected="selected"<?php endif ?>>ㆍ상시허용</option>
										<option value="2"<?php if ( $d['bbs']['group_chat'] == '2' ): ?> selected="selected"<?php endif ?>>ㆍ방송중허용</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1 text-muted">버튼 이미지</td>
                                <td class="td2">
                                    <input type="hidden" id="button_use" name="button_use" value="<?php echo $d['bbs']['button_use'] ?>"/>
                                    <input type="file" id="button_use_file" name="button_use_file" class="upfile" style="padding-bottom:10px"/>
									<?php if ( $d['bbs']['button_use'] ): ?>
											<img class="button_img" src="<?php echo $g['s']?>/modules/<?php echo $module ?>/var/button/<?php echo $d['bbs']['button_use'] ?>" style="width:42px;height:42px;"/>
											<input class="btn btn-default btn-xs bskr-input" type="button" onclick="buttonimagedelete()" value="삭제"/>
									<?php endif ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1 text-muted">버튼 링크</td>
                                <td class="td2">
                                    <input type="text" name="button_path" value="<?= $d['bbs']['button_path'] ?>" size="50" class="input">
                                </td>
                            </tr>
							<tr id="menu_button">
                                <td class="td1">버튼 위치</td>
                                <td class="td2">
									<input type="hidden" id="button_postion" name="button_postion" value="<?php if ( $d['bbs']['button_postion'] != "" && $d['bbs']['button_use'] != 0 ): ?><?php echo $d['bbs']['button_postion'] ?><?php else : ?>10<?php endif ?>">
                                    <table>
										<tr>
											<td>
												<input class="btn btn-default btn-xs bskr-input" type="button" name="btnp" onclick="buttondisabled(this)" value="↖" data-value="0" <?php if (($d['bbs']['button_use'] && $d['bbs']['button_postion'] != 0 && $d['bbs']['button_postion'] != 10) || ($d['bbs']['button_postion'] != 0 && !$d['bbs']['button_use'])): ?> disabled="true" <?php elseif ($d['bbs']['button_postion'] == 0 && $d['bbs']['button_postion'] != "") : ?> style="background:red" <?php endif ?>></button>
											</td>
											<td>
												<input class="btn btn-default btn-xs bskr-input" type="button" name="btnp" onclick="buttondisabled(this)" value="↑" data-value="1"  <?php if (($d['bbs']['button_use'] && $d['bbs']['button_postion'] != 1 && $d['bbs']['button_postion'] != 10) || ($d['bbs']['button_postion'] != 1 && !$d['bbs']['button_use'])): ?> disabled="true" <?php elseif ($d['bbs']['button_postion'] == 1 && $d['bbs']['button_postion'] != "") : ?> style="background:red" <?php endif ?>></button>
											</td>
											<td>
												<input class="btn btn-default btn-xs bskr-input" type="button" name="btnp" onclick="buttondisabled(this)" value="↗" data-value="2"  <?php if (($d['bbs']['button_use'] && $d['bbs']['button_postion'] != 2 && $d['bbs']['button_postion'] != 10) || ($d['bbs']['button_postion'] != 2 && !$d['bbs']['button_use'])): ?> disabled="true" <?php elseif ($d['bbs']['button_postion'] == 2 && $d['bbs']['button_postion'] != "") : ?> style="background:red" <?php endif ?>></button>
											</td>
										</tr>
										<tr>
											<td>
												<input class="btn btn-default btn-xs bskr-input" type="button" name="btnp" onclick="buttondisabled(this)" value="←" data-value="3"  <?php if (($d['bbs']['button_use'] && $d['bbs']['button_postion'] != 3 && $d['bbs']['button_postion'] != 10) || ($d['bbs']['button_postion'] != 3 && !$d['bbs']['button_use'])): ?> disabled="true" <?php elseif ($d['bbs']['button_postion'] == 3 && $d['bbs']['button_postion'] != "") : ?> style="background:red" <?php endif ?>></button>
											</td>
											<td>
												<input class="btn btn-default btn-xs bskr-input" type="button" name="btnp" onclick="buttondisabled(this)" value="■" data-value="4"  <?php if (($d['bbs']['button_use'] && $d['bbs']['button_postion'] != 4 && $d['bbs']['button_postion'] != 10) || ($d['bbs']['button_postion'] != 4 && !$d['bbs']['button_use'])): ?> disabled="true" <?php elseif ($d['bbs']['button_postion'] == 4 && $d['bbs']['button_postion'] != "") : ?> style="background:red" <?php endif ?>></button>
											</td>
											<td>
												<input class="btn btn-default btn-xs bskr-input" type="button" name="btnp" onclick="buttondisabled(this)" value="→" data-value="5"  <?php if (($d['bbs']['button_use'] && $d['bbs']['button_postion'] != 5 && $d['bbs']['button_postion'] != 10) || ($d['bbs']['button_postion'] != 5 && !$d['bbs']['button_use'])): ?> disabled="true" <?php elseif ($d['bbs']['button_postion'] == 5 && $d['bbs']['button_postion'] != "") : ?> style="background:red" <?php endif ?>></button>
											</td>
										</tr>
										<tr>
											<td>
												<input class="btn btn-default btn-xs bskr-input" type="button" name="btnp" onclick="buttondisabled(this)" value="↙" data-value="6"  <?php if (($d['bbs']['button_use'] && $d['bbs']['button_postion'] != 6 && $d['bbs']['button_postion'] != 10) || ($d['bbs']['button_postion'] != 6 && !$d['bbs']['button_use'])): ?> disabled="true" <?php elseif ($d['bbs']['button_postion'] == 6 && $d['bbs']['button_postion'] != "") : ?> style="background:red" <?php endif ?>></button>
											</td>
											<td>
												<input class="btn btn-default btn-xs bskr-input" type="button" name="btnp" onclick="buttondisabled(this)" value="↓" data-value="7"  <?php if (($d['bbs']['button_use'] && $d['bbs']['button_postion'] != 7 && $d['bbs']['button_postion'] != 10) || ($d['bbs']['button_postion'] != 7 && !$d['bbs']['button_use'])): ?> disabled="true" <?php elseif ($d['bbs']['button_postion'] == 7 && $d['bbs']['button_postion'] != "") : ?> style="background:red" <?php endif ?>></button>
											</td>
											<td>
												<input class="btn btn-default btn-xs bskr-input" type="button" name="btnp" onclick="buttondisabled(this)" value="↘" data-value="8"  <?php if (($d['bbs']['button_use'] && $d['bbs']['button_postion'] != 8 && $d['bbs']['button_postion'] != 10) || ($d['bbs']['button_postion'] != 8 && !$d['bbs']['button_use'])): ?> disabled="true" <?php elseif ($d['bbs']['button_postion'] == 8 && $d['bbs']['button_postion'] != "") : ?> style="background:red" <?php endif ?>></button>
											</td>
										</tr>
									</table>
                                </td>
							</tr>
                            <tr>
                                <td class="td1">Download Path</td>
                                <td class="td2">
                                    <input type="text" name="download_path" value="<?= $d['bbs']['download_path'] ?>" size="50" class="input">
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">Download ID</td>
                                <td class="td2">
                                    <input type="text" name="download_id" value="<?= $d['bbs']['download_id'] ?>" size="50" class="input">
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">Download PW</td>
                                <td class="td2">
                                    <input type="text" name="download_pw" value="<?= $d['bbs']['download_pw'] ?>" size="50" class="input">
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">첨 부 파 일</td>
                                <td class="td2">
                                    파일 당 최대 <?php echo ini_get( 'upload_max_filesize' ) ?> 크기 및 <input type="text"
                                                                                                       name="max_upload"
                                                                                                       value="<?php echo $d['bbs']['max_upload'] ? $d['bbs']['max_upload'] : '5' ?>"
                                                                                                       size="5"
                                                                                                       class="input">개 까지
                                    첨부 가능
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">댓 글 옵 션</td>
                                <td class="td2">
                                    <select name="c_use" class="select1">
                                        <option value="">ㆍ댓글 사용 안 함</option>
                                        <option value="1"<?php if ( $d['bbs']['c_use'] || ! $uid ): ?> selected<?php endif ?>>
                                            ㆍ댓글 사용
                                        </option>
                                    </select>
                                    <div style="margin-top:5px;">
                                        <div class="inblk subtitle">댓글출력:</div>
                                        <div class="inblk"><input type="text" name="c_recnum"
                                                                  value="<?php echo $d['bbs']['c_recnum'] ? $d['bbs']['c_recnum'] : '10' ?>"
                                                                  size="5" class="input">개 (페이지 당 출력 댓글수)
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">
                                    연 결 메 뉴 <i class="glyphicon glyphicon-question-sign hand"
                                               onclick="$('#guide_sosokmenu').toggle()"></i>
                                </td>
                                <td class="td2">
                                    <select name="sosokmenu" class="select1">
                                        <option value="">&nbsp;+ 사용안함</option>
                                        <option value="">--------------------------------</option>
										<?php include_once $g['path_core'] . 'function/menu1.func.php' ?>
										<?php $cat = $d['bbs']['sosokmenu'] ?>
										<?php getMenuShowSelect( $s, $table['s_menu'], 0, 0, 0, 0, 0, '' ) ?>
                                    </select>
                                    <div id="guide_sosokmenu" class="guide hide2">
                                        이 게시판을 메뉴에 연결하였을 경우 해당메뉴를 지정해 주세요.<br/>
                                        연결메뉴를 지정하면 게시물수,로케이션이 동기화됩니다.<br/>
                                    </div>
                                </td>
                            </tr>
							<?php /***
							 * <tr>
							 * <td class="td1">
							 * 소 셜 연 동
							 * <img src="<?php echo $g['img_core']?>/_public/ico_q.gif" alt="도움말" title="도움말" class="hand" onclick="layerShowHide('guide_snsconnect','block','none');" />
							 * </td>
							 * <td class="td2">
							 * <select name="snsconnect" class="select1">
							 * <option value="0">&nbsp;+ 연동안함</option>
							 * <option value="0">--------------------------------</option>
							 * <?php $tdir = $g['path_module'].'social/inc/'?>
							 * <?php if(is_dir($tdir)):?>
							 * <?php $dirs = opendir($tdir)?>
							 * <?php while(false !== ($skin = readdir($dirs))):?>
							 * <?php if($skin=='.' || $skin == '..')continue?>
							 * <option value="social/inc/<?php echo $skin?>"<?php if($d['bbs']['snsconnect']=='social/inc/'.$skin):?> selected="selected"<?php endif?>>ㆍ<?php echo str_replace('.php','',$skin)?></option>
							 * <?php endwhile?>
							 * <?php closedir($dirs)?>
							 * <?php endif?>
							 * </select>
							 * <div id="guide_snsconnect" class="guide hide">
							 * 게시물 등록시 SNS에 동시등록을 가능하게 합니다.<br />
							 * 이 서비스를 위해서는 소셜연동 모듈을 설치해야 합니다.<br />
							 * </div>
							 * </td>
							 * </tr>
							 ***/ ?>
                            <tr>
                                <td class="td1">
                                    추 가 설 정 <i class="glyphicon glyphicon-question-sign hand"
                                               onclick="$('#guide_addconfig').toggle()"></i>
                                </td>
                                <td class="td2 shift">
                                    <label><input type="checkbox" checked="checked" disabled="disabled"/> 권한설정</label>
                                    <img src="<?php echo $g['path_module'] . $module ?>/admin/img/ico_under.gif"
                                         alt="접기/펼치기" title="접기/펼치기" id="dm_img_addinfo" class="dm"
                                         onclick="codShowHide('menu_addinfo','block','none',this);"/>&nbsp;&nbsp;&nbsp;
                                    <label><input type="checkbox" checked="checked" disabled="disabled"/> 고급설정</label>
                                    <img src="<?php echo $g['path_module'] . $module ?>/admin/img/ico_under.gif"
                                         alt="접기/펼치기" title="접기/펼치기" id="dm_img_config" class="dm"
                                         onclick="codShowHide('menu_config','block','none',this);"/><br/>
									<?php /*** 코어 미지워. 단, 메뉴의 헤더.푸터 사용 가능
									 * <label><input type="checkbox" <?php if($R['imghead']||is_file($g['path_module'].$module.'/var/code/'.$R['id'].'.header.php')):?> checked="checked"<?php endif?> disabled="disabled" /> 헤더삽입</label>
									 * <img src="<?php echo $g['path_module'].$module?>/admin/img/ico_under.gif" alt="접기/펼치기" title="접기/펼치기" id="dm_img_header" class="dm" onclick="codShowHide('menu_header','block','none',this);" />&nbsp;&nbsp;&nbsp;
									 * <label><input type="checkbox" <?php if($R['imgfoot']||is_file($g['path_module'].$module.'/var/code/'.$R['id'].'.footer.php')):?> checked="checked"<?php endif?> disabled="disabled" /> 풋터삽입</label>
									 * <img src="<?php echo $g['path_module'].$module?>/admin/img/ico_under.gif" alt="접기/펼치기" title="접기/펼치기" id="dm_img_footer" class="dm" onclick="codShowHide('menu_footer','block','none',this);" />
									 ***/ ?>

                                    <div id="guide_addconfig" class="guide hide2">
                                        게시판에 더 많은 세부설정이 필요할 경우 사용합니다.<br/>
                                        추가설정 뷰는 각각의 모드별로 마지막 접기/펼치기 값을 기억합니다.<br/>
                                        더 세부적인 설정은 기초환경 및 테마별 설정을 이용하세요.
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div id="menu_addinfo" class="hide2 table-responsive">
                        <table class="table">
                            <tr>
                                <td class="td1"></td>
                                <td class="td2">
                                    <div class="guide">
                                        각각의 모드에 대한 회원등급/그룹별 접근권한을 설정합니다.<br/>
                                        복수의 그룹을 선택하려면 드래그하거나 Ctrl키를 누른다음 클릭해 주세요.
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1"></td>
                                <td class="td2 b">
                                    [목록접근]
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">허용등급</td>
                                <td class="td2">
                                    <select name="perm_l_list" class="select1">
                                        <option value="0">&nbsp;+ 전체허용</option>
                                        <option value="0">--------------------------------</option>
										<?php $_LEVEL = getDbArray( $table['s_mbrlevel'], '', '*', 'uid', 'asc', 0, 1 ) ?>
										<?php while ( $_L = db_fetch_array( $_LEVEL ) ): ?>
                                            <option value="<?php echo $_L['uid'] ?>"<?php if ( $_L['uid'] == $d['bbs']['perm_l_list'] ): ?> selected="selected"<?php endif ?>>
                                                ㆍ<?php echo $_L['name'] ?>(<?php echo number_format( $_L['num'] ) ?>) 이상
                                            </option>
											<?php if ( $_L['gid'] ) {
												break;
											} endwhile ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">
                                    차단그룹
                                </td>
                                <td class="td2">
                                    <select name="_perm_g_list" class="select1" multiple="multiple" size="5">
                                        <option value=""<?php if ( ! $d['bbs']['perm_g_list'] ): ?> selected="selected"<?php endif ?>>
                                            ㆍ차단안함
                                        </option>
										<?php $_SOSOK = getDbArray( $table['s_mbrgroup'], '', '*', 'gid', 'asc', 0, 1 ) ?>
										<?php while ( $_S = db_fetch_array( $_SOSOK ) ): ?>
                                            <option value="<?php echo $_S['uid'] ?>"<?php if ( strstr( $d['bbs']['perm_g_list'], '[' . $_S['uid'] . ']' ) ): ?> selected="selected"<?php endif ?>>
                                                ㆍ<?php echo $_S['name'] ?>(<?php echo number_format( $_S['num'] ) ?>)
                                            </option>
										<?php endwhile ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td class="td1"></td>
                                <td class="td2 b">
                                    [본문열람]
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">허용등급</td>
                                <td class="td2">
                                    <select name="perm_l_view" class="select1">
                                        <option value="0">&nbsp;+ 전체허용</option>
                                        <option value="0">--------------------------------</option>
										<?php $_LEVEL = getDbArray( $table['s_mbrlevel'], '', '*', 'uid', 'asc', 0, 1 ) ?>
										<?php while ( $_L = db_fetch_array( $_LEVEL ) ): ?>
                                            <option value="<?php echo $_L['uid'] ?>"<?php if ( $_L['uid'] == $d['bbs']['perm_l_view'] ): ?> selected="selected"<?php endif ?>>
                                                ㆍ<?php echo $_L['name'] ?>(<?php echo number_format( $_L['num'] ) ?>) 이상
                                            </option>
											<?php if ( $_L['gid'] ) {
												break;
											} endwhile ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">
                                    차단그룹
                                </td>
                                <td class="td2">
                                    <select name="_perm_g_view" class="select1" multiple="multiple" size="5">
                                        <option value=""<?php if ( ! $d['bbs']['perm_g_view'] ): ?> selected="selected"<?php endif ?>>
                                            ㆍ차단안함
                                        </option>
										<?php $_SOSOK = getDbArray( $table['s_mbrgroup'], '', '*', 'gid', 'asc', 0, 1 ) ?>
										<?php while ( $_S = db_fetch_array( $_SOSOK ) ): ?>
                                            <option value="<?php echo $_S['uid'] ?>"<?php if ( strstr( $d['bbs']['perm_g_view'], '[' . $_S['uid'] . ']' ) ): ?> selected="selected"<?php endif ?>>
                                                ㆍ<?php echo $_S['name'] ?>(<?php echo number_format( $_S['num'] ) ?>)
                                            </option>
										<?php endwhile ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td class="td1"></td>
                                <td class="td2 b">
                                    [글쓰기]
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">허용등급</td>
                                <td class="td2">
                                    <select name="perm_l_write" class="select1">
                                        <option value="0">&nbsp;+ 전체허용</option>
                                        <option value="0">--------------------------------</option>
										<?php $d['bbs']['perm_l_write'] = $d['bbs']['perm_l_write'] == '' ? 1 : $d['bbs']['perm_l_write'] ?>
										<?php $_LEVEL = getDbArray( $table['s_mbrlevel'], '', '*', 'uid', 'asc', 0, 1 ) ?>
										<?php while ( $_L = db_fetch_array( $_LEVEL ) ): ?>
                                            <option value="<?php echo $_L['uid'] ?>"<?php if ( $_L['uid'] == $d['bbs']['perm_l_write'] ): ?> selected="selected"<?php endif ?>>
                                                ㆍ<?php echo $_L['name'] ?>(<?php echo number_format( $_L['num'] ) ?>) 이상
                                            </option>
											<?php if ( $_L['gid'] ) {
												break;
											} endwhile ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">
                                    차단그룹
                                </td>
                                <td class="td2">
                                    <select name="_perm_g_write" class="select1" multiple="multiple" size="5">
                                        <option value=""<?php if ( ! $d['bbs']['perm_g_write'] ): ?> selected="selected"<?php endif ?>>
                                            ㆍ차단안함
                                        </option>
										<?php $_SOSOK = getDbArray( $table['s_mbrgroup'], '', '*', 'gid', 'asc', 0, 1 ) ?>
										<?php while ( $_S = db_fetch_array( $_SOSOK ) ): ?>
                                            <option value="<?php echo $_S['uid'] ?>"<?php if ( strstr( $d['bbs']['perm_g_write'], '[' . $_S['uid'] . ']' ) ): ?> selected="selected"<?php endif ?>>
                                                ㆍ<?php echo $_S['name'] ?>(<?php echo number_format( $_S['num'] ) ?>)
                                            </option>
										<?php endwhile ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td class="td1"></td>
                                <td class="td2 b">
                                    [업로드]
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">허용등급</td>
                                <td class="td2">
                                    <select name="perm_l_upload" class="select1">
                                        <option value="0">&nbsp;+ 전체허용</option>
                                        <option value="0">--------------------------------</option>
										<?php $d['bbs']['perm_l_upload'] = $d['bbs']['perm_l_upload'] == '' ? 1 : $d['bbs']['perm_l_upload'] ?>
										<?php $_LEVEL = getDbArray( $table['s_mbrlevel'], '', '*', 'uid', 'asc', 0, 1 ) ?>
										<?php while ( $_L = db_fetch_array( $_LEVEL ) ): ?>
                                            <option value="<?php echo $_L['uid'] ?>"<?php if ( $_L['uid'] == $d['bbs']['perm_l_upload'] ): ?> selected="selected"<?php endif ?>>
                                                ㆍ<?php echo $_L['name'] ?>(<?php echo number_format( $_L['num'] ) ?>) 이상
                                            </option>
											<?php if ( $_L['gid'] ) {
												break;
											} endwhile ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">
                                    차단그룹
                                </td>
                                <td class="td2">
                                    <select name="_perm_g_upload" class="select1" multiple="multiple" size="5">
                                        <option value=""<?php if ( ! $d['bbs']['perm_g_upload'] ): ?> selected="selected"<?php endif ?>>
                                            ㆍ차단안함
                                        </option>
										<?php $_SOSOK = getDbArray( $table['s_mbrgroup'], '', '*', 'gid', 'asc', 0, 1 ) ?>
										<?php while ( $_S = db_fetch_array( $_SOSOK ) ): ?>
                                            <option value="<?php echo $_S['uid'] ?>"<?php if ( strstr( $d['bbs']['perm_g_upload'], '[' . $_S['uid'] . ']' ) ): ?> selected="selected"<?php endif ?>>
                                                ㆍ<?php echo $_S['name'] ?>(<?php echo number_format( $_S['num'] ) ?>)
                                            </option>
										<?php endwhile ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td class="td1"></td>
                                <td class="td2 b">
                                    [다운로드]
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">허용등급</td>
                                <td class="td2">
                                    <select name="perm_l_down" class="select1">
                                        <option value="0">&nbsp;+ 전체허용</option>
                                        <option value="0">--------------------------------</option>
										<?php $d['bbs']['perm_l_down'] = $d['bbs']['perm_l_down'] == '' ? 1 : $d['bbs']['perm_l_down'] ?>
										<?php $_LEVEL = getDbArray( $table['s_mbrlevel'], '', '*', 'uid', 'asc', 0, 1 ) ?>
										<?php while ( $_L = db_fetch_array( $_LEVEL ) ): ?>
                                            <option value="<?php echo $_L['uid'] ?>"<?php if ( $_L['uid'] == $d['bbs']['perm_l_down'] ): ?> selected="selected"<?php endif ?>>
                                                ㆍ<?php echo $_L['name'] ?>(<?php echo number_format( $_L['num'] ) ?>) 이상
                                            </option>
											<?php if ( $_L['gid'] ) {
												break;
											} endwhile ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">
                                    차단그룹
                                </td>
                                <td class="td2">
                                    <select name="_perm_g_down" class="select1" multiple="multiple" size="5">
                                        <option value=""<?php if ( ! $d['bbs']['perm_g_down'] ): ?> selected="selected"<?php endif ?>>
                                            ㆍ차단안함
                                        </option>
										<?php $_SOSOK = getDbArray( $table['s_mbrgroup'], '', '*', 'gid', 'asc', 0, 1 ) ?>
										<?php while ( $_S = db_fetch_array( $_SOSOK ) ): ?>
                                            <option value="<?php echo $_S['uid'] ?>"<?php if ( strstr( $d['bbs']['perm_g_down'], '[' . $_S['uid'] . ']' ) ): ?> selected="selected"<?php endif ?>>
                                                ㆍ<?php echo $_S['name'] ?>(<?php echo number_format( $_S['num'] ) ?>)
                                            </option>
										<?php endwhile ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div id="menu_config" class="hide2 table-responsive">
                        <table class="table">
                            <tr>
                                <td class="td1">
                                    게시물정렬 <i class="glyphicon glyphicon-question-sign hand"
                                             onclick="$('#guide_sort').toggle()"></i>
                                </td>
                                <td class="td2 shift">
                                    <div class="shift">
                                        <label><input type="radio" name="sort"
                                                      value="gid"<?php if ( ! $d['bbs']['sort'] || $d['bbs']['sort'] != 'uid' ): ?> checked="checked"<?php endif ?>>
                                            최근 게시물이 위로</label>
                                        &nbsp;&nbsp;
                                        <label><input type="radio" name="sort"
                                                      value="uid"<?php if ( $d['bbs']['sort'] == 'uid' ): ?> checked="checked"<?php endif ?>>
                                            최근 게시물이 아래로</label>
                                    </div>
                                    <div id="guide_sort" class="guide hide2">
                                        필요에 따라 게시물 목록의 정렬 방식을 변경할 수 있습니다.<br>
                                        예를 들어, FAQ 게시판을 구성한다면 최근 게시물이 아래로 출력되도록 설정해 주는 것이 보다 효과적일 수 있습니다.
                                        단, 이러한 경우 답변 기능은 사용이 불가하며 테마에 따라 해당 UI 출력이 제한될 수 있습니다.
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">
                                    최근글제외 <i class="glyphicon glyphicon-question-sign hand"
                                             onclick="$('#guide_display').toggle()"></i>
                                </td>
                                <td class="td2 shift">
                                    <div class="shift">
                                        <label><input type="checkbox" name="display"
                                                      value="1"<?php if ( $d['bbs']['display'] ): ?> checked="checked"<?php endif ?> />
                                            최근글 추출에서 제외합니다.</label>
                                    </div>
                                    <div id="guide_display" class="guide hide2">
                                        최근글 추출제외는 게시물등록시에 이 설정값을 따르므로<br/>
                                        설정값을 중간에 변경하면 이전 게시물에 대해서는 적용되지 않습니다.<br/>
                                        최근글 제외설정은 게시판 서비스전에 확정하여 주세요.<br/>
                                        최근글에서 제외하면 통합검색에서도 제외됩니다.
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">
                                    비밀글숨김 <i class="glyphicon glyphicon-question-sign hand"
                                             onclick="$('#guide_hide_hidden').toggle()"></i>
                                </td>
                                <td class="td2 shift">
                                    <div class="shift">
                                        <label><input type="checkbox" name="hide_hidden"
                                                      value="1"<?php if ( $d['bbs']['hide_hidden'] ): ?> checked="checked"<?php endif ?> />
                                            목록에서 비밀글을 제외합니다.</label>
                                    </div>
                                    <div id="guide_hide_hidden" class="guide hide2">
                                        비밀글 설정된 게시물을 목록에 표시하지 않도록 합니다. (관리자만 확인 가능)<br>예를 들어, 관리자만 작성이 가능한 게시판(블로그/공지전용
                                        등)에 글을 작성중일 때 등을 위해 사용할 수 있습니다.
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">
                                    쿼 리 생 략 <i class="glyphicon glyphicon-question-sign hand"
                                               onclick="$('#guide_list').toggle()"></i>
                                </td>
                                <td class="td2 shift">
                                    <div class="shift">
                                        <label><input type="checkbox" name="hidelist"
                                                      value="1"<?php if ( $d['bbs']['hidelist'] ): ?> checked="checked"<?php endif ?> />
                                            게시물가공 기본쿼리를 생략합니다.</label>
                                    </div>
                                    <div id="guide_list" class="guide hide2">
                                        종종 기본쿼리가 아닌 테마자체에서 데이터를 가공해야 하는 경우가 있습니다.<br/>
                                        1:1상담게시판,일정관리 등 특수한 테마의 경우 쿼리생략이 요구되기도 합니다.<br/>
                                        쿼리생략이 요구되는 테마를 사용할 경우 체크해 주세요.<br/>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">RSS발행</td>
                                <td class="td2 shift">
                                    <div class="shift">
                                        <label><input type="checkbox" name="rss"
                                                      value="1"<?php if ( $d['bbs']['rss'] ): ?> checked="checked"<?php endif ?> />
                                            RSS발행을 허용합니다.</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">조회수증가</td>
                                <td class="td2 shift">
                                    <div class="shift">
                                        <label><input type="radio" name="hitcount"
                                                      value="1"<?php if ( $d['bbs']['hitcount'] ): ?> checked="checked"<?php endif ?> />
                                            무조건증가</label>&nbsp;
                                        <label><input type="radio" name="hitcount"
                                                      value="0"<?php if ( ! $d['bbs']['hitcount'] ): ?> checked="checked"<?php endif ?> />
                                            1회만증가</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">게시물출력</td>
                                <td class="td2">
                                    <input type="text" name="recnum"
                                           value="<?php echo $d['bbs']['recnum'] ? $d['bbs']['recnum'] : 20 ?>" size="5"
                                           class="input"/>개 (한페이지에 출력할 게시물의 수)
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">제목끊기</td>
                                <td class="td2">
                                    <input type="text" name="sbjcut"
                                           value="<?php echo $d['bbs']['sbjcut'] ? $d['bbs']['sbjcut'] : 40 ?>" size="5"
                                           class="input"/>자 (제목이 길 경우 자르기)
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">새글유지시간</td>
                                <td class="td2">
                                    <input type="text" name="newtime"
                                           value="<?php echo $d['bbs']['newtime'] ? $d['bbs']['newtime'] : 24 ?>"
                                           size="5" class="input"/>시간 (새글로 인식되는 시간)
                                </td>
                            </tr>

                            <tr>
                                <td class="td1">등록포인트</td>
                                <td class="td2">
                                    <input type="text" name="point1"
                                           value="<?php echo $d['bbs']['point1'] ? $d['bbs']['point1'] : 0 ?>" size="5"
                                           class="input"/>포인트지급 (게시물 삭제시 환원됩니다)
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">열람포인트</td>
                                <td class="td2">
                                    <input type="text" name="point2"
                                           value="<?php echo $d['bbs']['point2'] ? $d['bbs']['point2'] : 0 ?>" size="5"
                                           class="input"/>포인트차감
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">다운포인트</td>
                                <td class="td2">
                                    <input type="text" name="point3"
                                           value="<?php echo $d['bbs']['point3'] ? $d['bbs']['point3'] : 0 ?>" size="5"
                                           class="input"/>포인트차감
                                </td>
                            </tr>

                            <tr>
                                <td class="td1">
                                    추가관리자 <i class="glyphicon glyphicon-question-sign hand"
                                             onclick="$('#guide_bbsadmin').toggle()"></i>
                                </td>
                                <td class="td2">
                                    <input type="text" name="admin" value="<?php echo $d['bbs']['admin'] ?>"
                                           class="input sname"/>
                                    <div id="guide_bbsadmin" class="guide hide2">
                                        이 게시판에 대해서 관리자권한을 별도로 부여할 회원이 있을경우<br/>
                                        회원아이디를 콤마(,)로 구분해서 등록해 주세요.<br/>
                                        관리자로 지정될 경우 열람/수정/삭제등의 모든권한을 얻게 됩니다.
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">
                                    부 가 필 드 <i class="glyphicon glyphicon-question-sign hand"
                                               onclick="$('#guide_addinfo').toggle()"></i>
                                </td>
                                <td class="td2">
                                    <textarea name="addinfo"
                                              class="add"><?php echo htmlspecialchars( $R['addinfo'] ) ?></textarea>
                                    <div id="guide_addinfo" class="guide hide2">
                                        이 게시판에 대해서 추가적인 정보가 필요할 경우 사용합니다.<br/>
                                        필드명은 <span class="b">[adddata]</span> 입니다.
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1">기본양식</td>
                                <td class="td2">
                                    <textarea name="writecode" class="form-control"
                                              id="summernote-basic-form"><?php echo htmlspecialchars( $R['writecode'] ) ?></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>


                    <div id="menu_header" class=" table-responsive">
                        <table class="table">
                            <tr>
                                <td class="td1 text-muted">헤더파일</td>
                                <td class="td2">
                                    <input type="file" name="imghead" class="upfile"/>
									<?php if ( $R['imghead'] ): ?>
                                        <a href="<?php echo $g['s'] ?>/?r=<?php echo $r ?>&m=admin&module=filemanager&front=main&editmode=Y&pwd=./modules/<?php echo $module ?>/var/files/&file=<?php echo $R['imghead'] ?>"
                                           target="_blank" title="<?php echo $R['imghead'] ?>" class="u hide">파일수정</a><a
                                                href="<?php echo $g['r'] ?>/?m=<?php echo $module ?>&amp;a=bbs_file_delete&amp;bid=<?php echo $R['id'] ?>&amp;dtype=head"
                                                target="_action_frame_<?php echo $m ?>" class="u"
                                                onclick="return confirm('정말로 삭제하시겠습니까?     ');">삭제</a>
									<?php else: ?>
                                        <span>(gif/jpg/png/swf 가능)</span>
									<?php endif ?>
                                </td>
                            </tr>
                            <tr class="hide2">
                                <td class="td1 text-muted">
                                    헤더코드
                                    <img src="<?php echo $g['path_module'] . $module ?>/admin/img/btn_code.gif"
                                         class="dn hand" alt="편집기" title=""
                                         onclick="editWindow('<?php echo $g['s'] ?>/?r=<?php echo $r ?>&system=edit.editor&iframe=Y&droparea=codheadArea');"/>
                                </td>
                                <td class="td2">
                                    <textarea name="codhead"
                                              id="codheadArea"><?php if ( is_file( $g['path_module'] . $module . '/var/code/' . $R['id'] . '.header.php' ) )
		                                    echo htmlspecialchars( implode( '', file( $g['path_module'] . $module . '/var/code/' . $R['id'] . '.header.php' ) ) ) ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1 text-muted">
                                    노출위치
                                </td>
                                <td class="td2">
                                    <label><input type="checkbox" name="inc_head_list"
                                                  value="[l]"<?php if ( strstr( $R['puthead'], '[l]' ) ): ?> checked="checked"<?php endif ?> />
                                        목록</label>&nbsp;
                                    <label><input type="checkbox" name="inc_head_view"
                                                  value="[v]"<?php if ( strstr( $R['puthead'], '[v]' ) ): ?> checked="checked"<?php endif ?> />
                                        본문</label>&nbsp;
                                    <label><input type="checkbox" name="inc_head_write"
                                                  value="[w]"<?php if ( strstr( $R['puthead'], '[w]' ) ): ?> checked="checked"<?php endif ?> />
                                        쓰기</label>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div id="menu_footer" class="hide2 table-responsive">
                        <table class="table">
                            <tr>
                                <td class="td1 text-muted">풋터파일</td>
                                <td class="td2">
                                    <input type="file" name="imgfoot" class="upfile" disabled/>
									<?php if ( $R['imgfoot'] ): ?>
                                        <a href="<?php echo $g['s'] ?>/?r=<?php echo $r ?>&m=admin&module=filemanager&front=main&editmode=Y&pwd=./modules/<?php echo $module ?>/var/files/&file=<?php echo $R['imgfoot'] ?>"
                                           target="_blank" title="<?php echo $R['imgfoot'] ?>" class="u">파일수정</a> <a
                                                href="<?php echo $g['s'] ?>/?r=<?php echo $r ?>&amp;m=<?php echo $module ?>&amp;a=bbs_file_delete&amp;bid=<?php echo $R['id'] ?>&amp;dtype=foot"
                                                target="_action_frame_<?php echo $m ?>" class="u"
                                                onclick="return confirm('정말로 삭제하시겠습니까?     ');">삭제</a>
									<?php else: ?>
                                        <span>(gif/jpg/png/swf 가능)</span>
									<?php endif ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1 text-muted">
                                    풋터코드
                                    <img src="<?php echo $g['path_module'] . $module ?>/admin/img/btn_code.gif"
                                         class="dn hand" alt="편집기" title=""
                                         onclick="editWindow('<?php echo $g['s'] ?>/?r=<?php echo $r ?>&system=edit.editor&iframe=Y&droparea=codfootArea');"/>
                                </td>
                                <td class="td2">
                                    <textarea name="codfoot" id="codfootArea"
                                              disabled><?php if ( is_file( $g['path_module'] . $module . '/var/code/' . $R['id'] . '.footer.php' ) )
		                                    echo htmlspecialchars( implode( '', file( $g['path_module'] . $module . '/var/code/' . $R['id'] . '.footer.php' ) ) ) ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class="td1 text-muted">
                                    노출위치
                                </td>
                                <td class="td2 shift">
                                    <label><input type="checkbox" name="inc_foot_list"
                                                  value="[l]"<?php if ( strstr( $R['putfoot'], '[l]' ) ): ?> checked="checked"<?php endif ?>
                                                  disabled/> 목록</label>&nbsp;
                                    <label><input type="checkbox" name="inc_foot_view"
                                                  value="[v]"<?php if ( strstr( $R['putfoot'], '[v]' ) ): ?> checked="checked"<?php endif ?>
                                                  disabled/> 본문</label>&nbsp;
                                    <label><input type="checkbox" name="inc_foot_write"
                                                  value="[w]"<?php if ( strstr( $R['putfoot'], '[w]' ) ): ?> checked="checked"<?php endif ?>
                                                  disabled/> 쓰기</label>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="submitbox">
						<?php if ( $uid ): ?>
                            <a href="<?php echo $g['s'] ?>/?r=<?php echo $r ?>&amp;m=<?php echo $module ?>&amp;a=deletebbs&amp;uid=<?php echo $uid ?>"
                               onclick="return hrefCheck(this,true,'삭제하시면 모든 게시물이 지워지며 복구할 수 없습니다.\n정말로 삭제하시겠습니까?');"
                               class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> 게시판 삭제</a>
						<?php endif ?>
                        <button type="submit" class="btn btn-primary"><i
                                    class="glyphicon glyphicon-ok"></i> <?php echo $R['uid'] ? '게시판 속성 변경' : '새 게시판 만들기' ?>
                        </button>
                    </div>
                </form>
            </div> <!-- End of panel-body -->
        </div>
    </div>
    <hr>
</div>
</div>


<!-- nestable : https://github.com/dbushell/Nestable -->
<?php getImport( 'nestable', 'jquery.nestable', false, 'js' ) ?>
<script>
    $('#nestable-menu').nestable();
    $('.dd').on('change', function () {
        var f = document.forms[1];
        getIframeForAction(f);
        f.submit();
    });

    function iconDrop(val) {
        var f = document.procForm;
        f.icon.value = val;
        iconDropAply();
    }

    function iconDropAply() {
        var f = document.procForm;
        f.iconaction.value = '1';
        getIframeForAction(f);
        f.submit();
        $('#modal_window').modal('hide');
    }
	function buttondisabled(obj){
		var idx = -1;
		var button_postion = document.getElementById('button_postion');
		var b = document.getElementsByName('btnp');
		var n = b.length;
		if(obj.name == 'btnp'){
			idx = obj.getAttribute( 'data-value' ) 
		}
		if(button_postion.value == '10'){
			button_postion.value = idx;
			for(i = 0; i < n; i++){
				if(i != idx){
					b[i].disabled = 'true';
				}else{
					b[idx].style = "background:red";
				}
			}
		}else{			
			button_postion.value = '10';
			for(i = 0; i < n; i++){
				if(i != idx){
					b[i].disabled = '';
				}else{
					b[idx].style = "background:white";
				}
			}
		}
	}
    function changeButton() {
		var buttonSelect = document.getElementById("button_use");
		var selectValue = buttonSelect.options[buttonSelect.selectedIndex].value;
        var button_postion = document.getElementById('button_postion');
		var b = document.getElementsByName('btnp');
		var n = b.length;
        if(selectValue == 0){
			button_postion.value = '10';
			for(i = 0; i < n; i++){
				b[i].disabled = 'true';
				b[i].style = "background:white";
			}
		}else{
			button_postion.value = '10';
			for(i = 0; i < n; i++){
				b[i].disabled = '';
			}
		}
    }
	function buttonimagedelete(){
		var buttonuse = document.getElementById('button_use');
		var test = confirm('정말로 실행하시겠습니까?');
		if(test){
			buttonuse.value = "";
			$( 'img.button_img' ).hide();
		}
	}
</script>


<script type="text/javascript">
    //<![CDATA[
    function codShowHide(layer, show, hide, img) {
        if (getId(layer).style.display != show) {
            getId(layer).style.display = show;
            img.src = img.src.replace('ico_under', 'ico_over');
            setCookie('ck_' + layer, show, 1);
        } else {
            getId(layer).style.display = hide;
            img.src = img.src.replace('ico_over', 'ico_under');
            setCookie('ck_' + layer, hide, 1);
        }
    }
    /*function codShowHide(layer, value) {
        if (getId(layer).style.display != show) {
			alert(1);
            getId(layer).style.display = show;
        } else {
			alert(2);
            getId(layer).style.display = hide;
        }
    }*/

    function saveCheck(f) {
        var l1 = f._perm_g_list;
        var n1 = l1.length;
        var l2 = f._perm_g_view;
        var n2 = l2.length;
        var l3 = f._perm_g_write;
        var n3 = l3.length;
        var l4 = f._perm_g_down;
        var n4 = l4.length;
        var l5 = f._perm_g_upload;
        var n5 = l5.length;
        var i;
        var s1 = '';
        var s2 = '';
        var s3 = '';
        var s4 = '';
        var s5 = '';

        for (i = 0; i < n1; i++) {
            if (l1[i].selected == true && l1[i].value != '') {
                s1 += '[' + l1[i].value + ']';
            }
        }
        for (i = 0; i < n2; i++) {
            if (l2[i].selected == true && l2[i].value != '') {
                s2 += '[' + l2[i].value + ']';
            }
        }
        for (i = 0; i < n3; i++) {
            if (l3[i].selected == true && l3[i].value != '') {
                s3 += '[' + l3[i].value + ']';
            }
        }
        for (i = 0; i < n4; i++) {
            if (l4[i].selected == true && l4[i].value != '') {
                s4 += '[' + l4[i].value + ']';
            }
        }
        for (i = 0; i < n5; i++) {
            if (l5[i].selected == true && l5[i].value != '') {
                s5 += '[' + l5[i].value + ']';
            }
        }
        f.perm_g_list.value = s1;
        f.perm_g_view.value = s2;
        f.perm_g_write.value = s3;
        f.perm_g_down.value = s4;
        f.perm_g_upload.value = s5;

        if (f.name.value == '') {
            alert('게시판이름을 입력해 주세요.     ');
            f.name.focus();
            return false;
        }
        if (f.bid.value == '') {
            if (f.id.value == '') {
                alert('게시판아이디를 입력해 주세요.      ');
                f.id.focus();
                return false;
            }
            if (!chkFnameValue(f.id.value)) {
                alert('게시판아이디는 영문 대소문자/숫자/_ 만 사용가능합니다.      ');
                f.id.value = '';
                f.id.focus();
                return false;
            }
        }
        return confirm('정말로 실행하시겠습니까?         ');
    }

    function slideshowOpen() {
        var cc = getCookie('ck_menu_config');
        var ch = getCookie('ck_menu_header');
        var cf = getCookie('ck_menu_footer');
        var ca = getCookie('ck_menu_addinfo');

        if (cc == 'block') {
            getId('menu_config').style.display = cc;
            getId('dm_img_config').src = getId('dm_img_config').src.replace('ico_under', 'ico_over');
        }
        if (ch == 'block') {
            getId('menu_header').style.display = ch;
            getId('dm_img_header').src = getId('dm_img_header').src.replace('ico_under', 'ico_over');
        }
        if (cf == 'block') {
            getId('menu_footer').style.display = cf;
            getId('dm_img_footer').src = getId('dm_img_footer').src.replace('ico_under', 'ico_over');
        }
        if (ca == 'block') {
            getId('menu_addinfo').style.display = ca;
            getId('dm_img_addinfo').src = getId('dm_img_addinfo').src.replace('ico_under', 'ico_over');
        }
    }

    slideshowOpen();
    //]]>
</script>


<?php if ( $d['admin']['codeeidt'] ): ?>
    <!-- codemirror -->
    <style>
        .CodeMirror {
            font-size: 13px;
            font-family: Menlo, Monaco, Consolas, "Courier New", monospace !important;
        }

        .note-group-select-from-files {
            display: none;
        }
    </style>
	<?php getImport( 'codemirror', 'codemirror', false, 'css' ) ?>
	<?php getImport( 'codemirror', 'codemirror', false, 'js' ) ?>
	<?php getImport( 'codemirror', 'theme/' . $d['admin']['codeeidt'], false, 'css' ) ?>
	<?php getImport( 'codemirror', 'mode/htmlmixed/htmlmixed', false, 'js' ) ?>
	<?php getImport( 'codemirror', 'mode/xml/xml', false, 'js' ) ?>
<?php endif ?>

<?php $lang['site']['a4027'] = ( $lang['site']['a4027'] ) ? $lang['site']['a4027'] : 'ko-KR'; ?>
<?php getImport( 'summernote', 'dist/summernote.min', false, 'js' ) ?>
<?php if ( $lang['site']['a4027'] )
	getImport( 'summernote', 'lang/summernote-' . $lang['site']['a4027'], false, 'js' ) ?>
<?php getImport( 'summernote', 'dist/summernote', false, 'css' ) ?>

<script>
    $('#summernote-basic-form').summernote({
        height: 250,
        lang: '<?php echo $lang['site']['a4027']?>',
		<?php if($d['admin']['codeeidt']):?>
        codemirror: {
            mode: "text/html",
            indentUnit: 4,
            lineNumbers: true,
            matchBrackets: true,
            indentWithTabs: true,
            theme: '<?php echo $d['admin']['codeeidt']?>'
        },
		<?php endif?>
        minHeight: null,
        maxHeight: null,
        focus: true,
		<?php if($lang['site']['a4027']):?>lang: '<?php echo $lang['site']['a4027']?>'<?php endif?>
    });
</script>

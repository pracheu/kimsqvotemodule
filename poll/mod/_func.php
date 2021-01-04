<?php
// 게시물링크
function getPostLink($arr)
{
	return RW('m=bskrbbs&bid='.$arr['bbsid'].'&uid='.$arr['uid'].($GLOBALS['s']!=$arr['site']?'&s='.$arr['site']:''));
}
// 게시물 시간
function getPostTime($d_regis, $d_now)
{
	$now['year']	= substr($d_now, 0, 4);
	$now['mon']	= substr($d_now, 4, 2);
	$now['date']  	= substr($d_now, 6, 2);
		
	$regis['year']	= substr($d_regis, 0, 4);
	$regis['mon']	= substr($d_regis, 4, 2);
	$regis['date'] 	= substr($d_regis, 6, 2);
	$regis['hour']	= substr($d_regis, 8, 2);
	
	if( $now['year']==$regis['year'] && $now['mon']==$regis['mon'] && $now['date']==$regis['date'] )
		return (($regis['hour']>=12)? '오후 ': '오전 ').getDateFormat($d_regis, 'g:i');
	return getDateFormat($d_regis, 'Y.m.d');
}
// 게시물 페이징
function getPageLinkBSKR($lnum,$p,$tpage,$img)
{
	$_N = $GLOBALS['g']['pagelink'].'&amp;';
	$g_p1 = '<img src="'.$img.'/p1.gif" alt="이전 '.$lnum.' 페이지" />';
	$g_p2 = '<img src="'.$img.'/p2.gif" alt="이전 '.$lnum.' 페이지" />';
	$g_n1 = '<img src="'.$img.'/n1.gif" alt="다음 '.$lnum.' 페이지" />';
	$g_n2 = '<img src="'.$img.'/n2.gif" alt="다음 '.$lnum.' 페이지" />';
	$g_cn = '<img src="'.$img.'/l.gif" class="split" alt="" />';
	$g_q  = $p > 1 ? '<a href="'.$_N.'p=1"><img src="'.$img.'/fp.gif" alt="처음페이지" /></a>' : '<img src="'.$img.'/fp1.gif" alt="처음페이지" />';
	if($p < $lnum+1) { $g_q .= $g_p1; }
	else{ $pp = (int)(($p-1)/$lnum)*$lnum; $g_q .= '<a href="'.$_N.'p='.$pp.'">'.$g_p2.'</a>';} $g_q .= $g_cn;
	$st1 = (int)(($p-1)/$lnum)*$lnum + 1;
	$st2 = $st1 + $lnum;
	for($jn = $st1; $jn < $st2; $jn++)
	if ( $jn <= $tpage)
	($jn == $p)? $g_q .= '<span class="selected" title="'.$jn.' 페이지">'.$jn.'</span>'.$g_cn : $g_q .= '<a href="'.$_N.'p='.$jn.'" class="notselected" title="'.$jn.' 페이지">'.$jn.'</a>'.$g_cn;
	if($tpage < $lnum || $tpage < $jn) { $g_q .= $g_n1; }
	else{$np = $jn; $g_q .= '<a href="'.$_N.'p='.$np.'">'.$g_n2.'</a>'; }
	$g_q  .= $tpage > $p ? '<a href="'.$_N.'p='.$tpage.'"><img src="'.$img.'/lp.gif" alt="마지막페이지" /></a>' : '<img src="'.$img.'/lp1.gif" alt="마지막페이지" />';
	return $g_q;
}
// 댓글 페이징
function getPageLinkBSKRCmt($lnum,$m,$parent,$p,$tpage,$img)
{
	$_N = $GLOBALS['g']['pagelink'].'&amp;m='.$m.'&amp;parent='.$parent.'&amp;mod=comment&amp;pos=bskr-ctop&amp;';
	$g_p1 = '<img src="'.$img.'/p1.gif" alt="이전 '.$lnum.' 페이지" />';
	$g_p2 = '<img src="'.$img.'/p2.gif" alt="이전 '.$lnum.' 페이지" />';
	$g_n1 = '<img src="'.$img.'/n1.gif" alt="다음 '.$lnum.' 페이지" />';
	$g_n2 = '<img src="'.$img.'/n2.gif" alt="다음 '.$lnum.' 페이지" />';
	$g_cn = '<img src="'.$img.'/l.gif" class="split" alt="" />';
	$g_q  = $p > 1 ? '<a href="'.$_N.'p=1"><img src="'.$img.'/fp.gif" alt="처음페이지" /></a>' : '<img src="'.$img.'/fp1.gif" alt="처음페이지" />';
	if($p < $lnum+1) { $g_q .= $g_p1; }
	else{ $pp = (int)(($p-1)/$lnum)*$lnum; $g_q .= '<a href="'.$_N.'p='.$pp.'">'.$g_p2.'</a>';} $g_q .= $g_cn;
	$st1 = (int)(($p-1)/$lnum)*$lnum + 1;
	$st2 = $st1 + $lnum;
	for($jn = $st1; $jn < $st2; $jn++)
	if ( $jn <= $tpage)
	($jn == $p)? $g_q .= '<span class="selected" title="'.$jn.' 페이지">'.$jn.'</span>'.$g_cn : $g_q .= '<a href="'.$_N.'p='.$jn.'" class="notselected" title="'.$jn.' 페이지">'.$jn.'</a>'.$g_cn;
	if($tpage < $lnum || $tpage < $jn) { $g_q .= $g_n1; }
	else{$np = $jn; $g_q .= '<a href="'.$_N.'p='.$np.'">'.$g_n2.'</a>'; }
	$g_q  .= $tpage > $p ? '<a href="'.$_N.'p='.$tpage.'"><img src="'.$img.'/lp.gif" alt="마지막페이지" /></a>' : '<img src="'.$img.'/lp1.gif" alt="마지막페이지" />';
	return $g_q;
}
?>
<?php
if (!defined('__KIMS__')) {
    exit;
}

include_once $g['dir_module'] . 'var/var.php';

header('Content-Type: application/json;charset=utf-8');

$table = "rb_bskrbbs_data";
$where = " TRUE";
//$where .= " AND bbs = 1";
$where .= " AND bbsid = '{$bid}'";
$where = $where . " order by notice desc, uid desc";
if ($limit) {
    $where = $where . " limit {$limit}";
}
if ($offset) {
    $where = $where . " offset {$offset}";
}
$data = " * ";
$data = " uid, site, bbs, bbsid, depth, display, hidden, notice, name, nic, mbruid, id, pw, subject, html, hit, d_regis, d_modify ";
$data .= " , (select count(*) from rb_bskrbbs_comment where parent = concat('bbs', rb_bskrbbs_data.uid)) as comment ";

$query_result = getDbSelect($table, $where, $data);

//$result;
while ($R = db_fetch_assoc($query_result)) {

    $R['d_regis'] = date('Y.m.d H:i', strtotime($R['d_regis']));
    $result[] = $R;
}
 
exit(json_encode($result, JSON_UNESCAPED_UNICODE));

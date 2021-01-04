<?php
if(!defined('__KIMS__')) exit;

//투표 리스트
$_tmp = db_query( "select count(*) from ".$table[$module.'poll_list'], $DB_CONNECT );
if ( !$_tmp ) {
$_tmp = ("

CREATE TABLE ".$table[$module.'poll_list']." (
	po_id int(11) NOT NULL AUTO_INCREMENT,
	po_subject varchar(255) NOT NULL DEFAULT '',
	po_poll1 varchar(255) NOT NULL DEFAULT '',
	po_poll2 varchar(255) NOT NULL DEFAULT '',
	po_poll3 varchar(255) NOT NULL DEFAULT '',
	po_poll4 varchar(255) NOT NULL DEFAULT '',
	po_poll5 varchar(255) NOT NULL DEFAULT '',
	po_poll6 varchar(255) NOT NULL DEFAULT '',
	po_poll7 varchar(255) NOT NULL DEFAULT '',
	po_poll8 varchar(255) NOT NULL DEFAULT '',
	po_poll9 varchar(255) NOT NULL DEFAULT '',
	po_cnt1 int(11) NOT NULL DEFAULT '0',
	po_cnt2 int(11) NOT NULL DEFAULT '0',
	po_cnt3 int(11) NOT NULL DEFAULT '0',
	po_cnt4 int(11) NOT NULL DEFAULT '0',
	po_cnt5 int(11) NOT NULL DEFAULT '0',
	po_cnt6 int(11) NOT NULL DEFAULT '0',
	po_cnt7 int(11) NOT NULL DEFAULT '0',
	po_cnt8 int(11) NOT NULL DEFAULT '0',
	po_cnt9 int(11) NOT NULL DEFAULT '0',
	po_etc varchar(255) NOT NULL DEFAULT '',
	po_level tinyint(4) NOT NULL DEFAULT '0',
	po_point int(11) NOT NULL DEFAULT '0',
	po_date date NOT NULL DEFAULT '0000-00-00',
	po_ips mediumtext NOT NULL,
	mb_ids text NOT NULL,
	start date NOT NULL,
	end date NOT NULL,
	content text NOT NULL,
	PRIMARY KEY (po_id)) ENGINE=".$DB['type']." CHARSET=UTF8");                            
db_query($_tmp, $DB_CONNECT);
db_query("OPTIMIZE TABLE ".$table['s_oneline'],$DB_CONNECT); 
}

//투표 참여자
$_tmp = db_query( "select count(*) from ".$table[$module.'poll_user'], $DB_CONNECT );
if ( !$_tmp ) {
$_tmp = ("

CREATE TABLE ".$table[$module.'poll_user']." (
	idx int(11) NOT NULL AUTO_INCREMENT,
	dong varchar(10) NOT NULL,
	hosu varchar(10) NOT NULL,
	name varchar(128) NOT NULL,
	birth date NOT NULL,
	type tinyint(4) NOT NULL,
	po_id int(11) NOT NULL,
	puse tinyint(4) NOT NULL DEFAULT '0',
	PRIMARY KEY (idx),
	KEY idx (idx),
	KEY dong (dong),
	KEY hosu (hosu)) ENGINE=".$DB['type']." CHARSET=UTF8");                            
db_query($_tmp, $DB_CONNECT);
db_query("OPTIMIZE TABLE ".$table['s_oneline'],$DB_CONNECT); 
}
?>
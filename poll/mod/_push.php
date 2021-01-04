<?php
//if ( ! defined( '__KIMS__' ) ) {
//	exit;
//}

function enjPush( $method, $url, $params, $timeout = 5 ) {

	// POST Method $set_params should be string
	$opts = array(
		CURLOPT_CUSTOMREQUEST  => $method,
		CURLOPT_URL            => $url,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT        => $timeout
	);

//$fmode = "w"; // 새로 쓰기
//$fmode = "a"; // 추가 쓰기
//$myfile = fopen($g['dir_module'] . 'push_log.txt', $fmode) or die("Unable to open file!");
//fwrite($myfile, $params);
//fclose($myfile);

	switch ( strtoupper( $method ) ) {
		case 'GET':
			break;
		case 'POST':
			$opts[ CURLOPT_POST ]       = 1;
			$params                     = json_encode( $params, JSON_UNESCAPED_UNICODE );
			$opts[ CURLOPT_POSTFIELDS ] = $params;
			$header                     = array( 'Content-Type: application/json', 'Content-Length: ' . strlen( $params ) );
			$opts[ CURLOPT_HTTPHEADER ] = $header;
			break;
		case 'PUT':
			$opts[ CURLOPT_PUT ]        = 0;
			$params                     = json_encode( $params, JSON_UNESCAPED_UNICODE );
			$opts[ CURLOPT_POSTFIELDS ] = $params;
			$header                     = array( 'Content-Type: application/json', 'Content-Length: ' . strlen( $params ) );
			$opts[ CURLOPT_HTTPHEADER ] = $header;
			break;
		case 'DELETE':
			$opts[ CURLOPT_POST ]       = 1;
			$params                     = json_encode( $params, JSON_UNESCAPED_UNICODE );
			$opts[ CURLOPT_POSTFIELDS ] = $params;
			$header                     = array( 'Content-Type: application/json', 'Content-Length: ' . strlen( $params ) );
			$opts[ CURLOPT_HTTPHEADER ] = array( "X-HTTP-Method-Override: DELETE" );
			break;
		default:
			throw new Exception( '지원하지 않은 Method！' );
	}

	/* curl */
	$ch = curl_init();
	curl_setopt_array( $ch, $opts );
	$data  = curl_exec( $ch );
	$error = curl_error( $ch );

	curl_close( $ch );

	return $data;
}

//$method = "POST";
//$url    = "http://board.enjsoft.com/push/api/push";
//$params = array( 'bbs' => 'enjsoft', 'bid' => '2', 'cid' => '3' );
//
//echo http( $method, $url, $params );
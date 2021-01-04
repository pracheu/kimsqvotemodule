<?php

if ( ! defined( '__KIMS__' ) ) {
	exit;
}

//if ( $download == "video" ) {
//	header( "Content-type:video/mp4;" );
//} else if ( $download == "audio" ) {
//	header( "Content-type:audio/mp4;" );
//} else {
//	header( "Content-type:text/plain;charset=utf-8" );
//}

function GetMp4File( $file ) {

	header( "Cache-Control: no-cache, no-store, must-revalidate" ); // HTTP 1.1.
	header( "Pragma: no-cache" ); // HTTP 1.0.
	header( "Expires: 0" ); // Proxies.

	$size = filesize( $file );

	//header( "Content-type: video/mp4" );
	header( "Content-type: application/octet-stream" );
	//header( "Accept-Ranges: bytes" );

	if ( isset( $_SERVER['HTTP_RANGE'] ) ) {

		header( "HTTP/1.1 206 Partial Content" );

		list( $name, $range ) = explode( "=", $_SERVER['HTTP_RANGE'] );

		list( $begin, $end ) = explode( "-", $range );

		if ( $end == 0 ) {

			$end = $size - 1;

		}

	} else {

		$begin = 0;
		$end   = $size - 1;

	}

	header( "Content-Disposition: filename=" . basename( $file ) );
	header( "Content-Transfer-Encoding: binary" );
	header( "Content-Length: " . ( $end - $begin + 1 ) );

	//header( "Content-Range: bytes " . $begin . "-" . $end . "/" . $size );

	ob_clean();
	flush();

	$fp = fopen( $file, 'rb' );

	//fseek( $fp, $begin );

	while ( ! feof( $fp ) ) {
		
		set_time_limit(30);

		$p = min( 1024 * 10, $end - $begin + 1 );

		$begin += $p;

		echo fread( $fp, $p );

	}

	fclose( $fp );

}
if ( $R["html"] == "HTML" ) {

	$dom  = new DOMDocument();
	$meta = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
	$dom->loadHTML( $meta . $R['content'] );

//                $dom = $dom->importNode( $dom->documentElement, true );

//                var_dump($dom);

//			$elements = $dom->getElementById($jobid);
	$elements = $dom->getElementsByTagName( "a" );

	foreach ( $elements as $element ) {
//                for ($i = 0; $i < $elements->length; $i++) {
//                    $element = $elements->item($i);

		$href = $element->getAttribute( "href" );
		if ( strpos( $href, "mvup" ) == 0 ) {

			$vindex ++;
			$videoId = "video" . $vindex;

			$url_origin_args   = parse_url( $href );
			$url_origin_scheme = $url_origin_args["scheme"];
			$url_origin_host   = $url_origin_args["host"];
			$url_origin_port   = $url_origin_args["port"];
			$url_origin_path   = $url_origin_args["path"];
			$url_origin_query  = htmlspecialchars_decode( $url_origin_args["query"] );

			$url_origin_no_query = $url_origin_scheme . "://" . $url_origin_host . ( $url_origin_port ? ":" . $url_origin_port : "" ) . $url_origin_path;

			$ssl = $url_origin_args["scheme"] == "mvups" ? "true" : "false";

			$max_height = '555px';
			$width      = '100%';
			$height     = 'calc(56.26vw)';
//						$width      = '90%';
//						$height     = 'calc(50.625vw)';
			if ( $url_origin_query ) {

				parse_str( $url_origin_query, $querys );
//                                        var_dump($querys);
				if ( $querys["width"] ) {
					$width = $querys["width"] . "px";
				}
				if ( $querys["height"] ) {
					$height = $querys["height"] . "px";
				}
			}

			$class = $element->getAttribute( "class" );
			if ( strstr( $class, "convert" ) ) { // 업로드 동영상 변환

				$jobid = $class = $element->getAttribute( "id" );
				$J     = getDbData( "rb_bskrbbs_convert", "jobid = '$jobid'", "*" );
				if ( $J["jobid"] ) {

					switch ( $J["result"] ) {
						case "start":

							break;
						case "converting":

							break;
						case "success":

							$url_origin_no_query = $d['bbs']['video_server'] . "/" . $J["upstoragename"] . $J["path"];

							if ( $d['bbs']['url_encrypt'] ) {

								$url_origin_no_query = str_replace( " ", "%20", $url_origin_no_query );

								$cnt = 0;
								while ( $cnt < 3 && ( $url_crypt_path = @file_get_contents( str_replace( "mvup", "http", $url_origin_no_query ) . $fix, false, stream_context_create( $opts ) ) ) === false ) {
									$cnt ++;
								}

								$host = str_replace( "http://", "", $d['bbs']['video_server'] ); // "board.enjsoft.com:1935";
								$src  = $host . $url_crypt_path;
							} else {
								$src = $url_origin_no_query;
							}

							$src = str_replace( "http://", "", $src );

							$newelement = $dom->createElement( "div", "" );

							// class
							$newelement->setAttribute( "class", "video" );

							// id
							$newelement->setAttribute( "id", $videoId );

							// style
							$newelement->setAttribute( "style", "width:$width; height:$height; max-height: $max_height;" );

							// script
							$src .= "?key=" . session_id();
							$src .= "&uid=" . $my['uid'];

							$scriptValue      = 'enj_add_player("' . $videoId . '", "EnjVideoPlayer.swf", ' . $ssl . ', "' . $src . '"' . $extra . ')';
							$newscriptelement = $dom->createElement( "script", htmlentities( $scriptValue ) );

//                                        $newscriptelement = $dom->createTextNode("<script>");

							$newelement->appendChild( $newscriptelement );

							if ( $pw ) {
								$hover = $dom->createElement( "div", "" );
								$hover->setAttribute( "class", 'hover' );

								$hover_input = $dom->createElement( "input", '' );
//							$hover_input->setAttribute( "type", "text" );
								$hover_input->setAttribute( "class", "play_pw" );
								$hover_input->setAttribute( "id", "play_pw" . $vindex );
								$hover_input->setAttribute( "placeholder", "시청암호" );
								$hover->appendChild( $hover_input );

								$hover_input = $dom->createElement( "button", '확인' );
								$hover_input->setAttribute( "class", "play_play" );
								$hover_input->setAttribute( "data-index", $vindex );
								$hover->appendChild( $hover_input );

								$newelement->appendChild( $hover );
							}

//                                        $element->parentNode->insertBefore($newelement, $element);
//                                        $element->setAttribute("class", "hide");

							$ddd["org"] = $element;
							$ddd["new"] = $newelement;
							$delete[]   = $ddd;
//                                        $element->parentNode->removeChild($element);

//                                        if($vindex>1)
//                                        $element->parentNode->replaceChild($newelement, $element); // tag 교체

//                                        if($vindex>1)
//                                        $element->parentNode->appendChild($newelement); // tag 추가


							break;
						case "fail":

							break;
						default:

							break;
					}

				}
			} else {

				if ( strpos( $url_origin_path, "." ) ) {

//					echo $url_origin_path;

					if ( $d['bbs']['url_encrypt'] ) {

						$url_origin_no_query = str_replace( " ", "%20", $url_origin_no_query );

						$cnt = 0;
						while ( $cnt < 3 && ( $url_crypt_path = @file_get_contents( str_replace( "mvup", "http", $url_origin_no_query ) . $fix, false, stream_context_create( $opts ) ) ) === false ) {
							$cnt ++;
						}

						if ( $url_crypt_path ) {
							$src = $url_origin_args["host"] . ( $url_origin_args["port"] ? ":" . $url_origin_args["port"] : "" ) . $url_crypt_path;
						} else {
							continue;
						}
					} else {
						$src = str_replace( "mvup", "http", $url_origin_no_query );
					}

					$src = str_replace( "http://", "", $src );

				} else {

					$src = $url_origin_args["host"] . ( $url_origin_args["port"] ? ":" . $url_origin_args["port"] : "" ) . $url_origin_args["path"];
				}

				$newelement = $dom->createElement( "div", "" );

				// class
				$newelement->setAttribute( "class", "video" );

				// id
				$newelement->setAttribute( "id", $videoId );

				// style
				$newelement->setAttribute( "style", "width:$width; height:$height; max-height: $max_height;" );

				// script
				$src              .= "?key=" . session_id();
				$src              .= "&uid=" . $my['uid'];
				$scriptValue      = 'enj_add_player("' . $videoId . '", "EnjVideoPlayer.swf", ' . $ssl . ', "' . $src . '"' . $extra . ')';
				$newscriptelement = $dom->createElement( "script", htmlentities( $scriptValue ) );
				$newelement->appendChild( $newscriptelement );

				if ( $pw ) {
					$hover = $dom->createElement( "div", "" );
					$hover->setAttribute( "class", 'hover' );

					$hover_input = $dom->createElement( "input", '' );
//							$hover_input->setAttribute( "type", "text" );
					$hover_input->setAttribute( "class", "play_pw" );
					$hover_input->setAttribute( "id", "play_pw" . $vindex );
					$hover_input->setAttribute( "placeholder", "시청암호" );
					$hover->appendChild( $hover_input );

					$hover_input = $dom->createElement( "button", '확인' );
					$hover_input->setAttribute( "class", "play_play" );
					$hover_input->setAttribute( "data-index", $vindex );
					$hover->appendChild( $hover_input );

					$newelement->appendChild( $hover );
				}

//                            $element->parentNode->replaceChild($newelement, $element); // tag 교체

				$ddd["org"] = $element;
				$ddd["new"] = $newelement;
				$delete[]   = $ddd;

			}


		}
	}

//                var_dump($delete);
	if ( $delete ) {
		foreach ( $delete as $val ) {
//                    var_dump($val);
			$val["org"]->parentNode->replaceChild( $val["new"], $val["org"] );
		}
	}

	$R['content'] = $dom->saveHTML();
}

$downloadPath = $d['bbs']['download_path'];
$downloadID = $d['bbs']['download_id'];
$downloadPW = $d['bbs']['download_pw'];

		//echo '<script>alert("'.$downloadPath.'")</script>';
if ( $downloadPath ) {

	//exec("Net Use {$downloadPath} /user:$downloadID {$downloadPW}",$output,$temp);
	exec("Net Use {$downloadPath} /user:$downloadID {$downloadPW}");

	$downloadPath = str_replace( "\\", "/", $downloadPath );
	$file         = $downloadPath . $url_origin_path;
	
	$testtextfile = fopen("testlog.txt", "w");
	$testtxt = $file."\n".$downloadPW;
	fwrite($testtextfile, $testtxt);
	fclose($testtextfile);

	if ( $download == "video" ) {

	} else if ( $download == "audio" ) {
		$file = str_replace( ".mp4", ".aac", $file );
	} else {
		exit( "ERROR" );
	}

	if ( file_exists( $file ) ) {
		GetMp4File( $file );
	} else {
		echo '<script>alert("파일이 존재하지 않습니다.")</script>';
//	die();
	}
} else {
	echo '<script>alert("정상적인 접근이 아닙니다.")</script>';
}

echo '<script>history.back();</script>';

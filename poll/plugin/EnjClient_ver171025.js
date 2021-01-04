

var msg_fail_connection = 'Failed to connect to ';

var Base64 = {

	// private property
	_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

	// public method for encoding
	encode : function (input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;

		input = Base64._utf8_encode(input);

		while (i < input.length) {

			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);

			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;

			if (isNaN(chr2)) {
				enc3 = enc4 = 64;
			} else if (isNaN(chr3)) {
				enc4 = 64;
			}

			output = output +
			this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
			this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

		}

		return output;
	},

	// public method for decoding
	decode : function (input) {
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;

		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

		while (i < input.length) {

			enc1 = this._keyStr.indexOf(input.charAt(i++));
			enc2 = this._keyStr.indexOf(input.charAt(i++));
			enc3 = this._keyStr.indexOf(input.charAt(i++));
			enc4 = this._keyStr.indexOf(input.charAt(i++));

			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;

			output = output + String.fromCharCode(chr1);

			if (enc3 != 64) {
				output = output + String.fromCharCode(chr2);
			}
			if (enc4 != 64) {
				output = output + String.fromCharCode(chr3);
			}

		}

		output = Base64._utf8_decode(output);

		return output;

	},

	// private method for UTF-8 encoding
	_utf8_encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";

		for (var n = 0; n < string.length; n++) {

			var c = string.charCodeAt(n);

			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}

		}

		return utftext;
	},

	// private method for UTF-8 decoding
	_utf8_decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;

		while ( i < utftext.length ) {

			c = utftext.charCodeAt(i);

			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}

		}

		return string;
	}

}





function JSONtoString(object) 
{     
    var results = "{";  
    var count = 0;
    for (var property in object) 
    {         
        var value = object[property];    
         
         
        if (value != null)             
            {
            try
            {  
            
                if( typeof value == "string" )
                {
                     results = results + property.toString() + ': "' + value + '",';   
                
                }
                else
                {
                     results = results + property.toString() + ': ' + value + ',';    
                }
count ++;
            
            }
            catch(e)
            {
            
            } 
            }     
    }    
if( count > 0 )                                   
    results = results + " lastobj: true }";
else
    results = "";
    return results;
    
}



var bbforupload = false;
var load_result = 0;
var checkcount = 0;
var player_container = null;
var iframeid;


function enj_add_player(parent, swf, bonssl, streamurlarray, titleimageurl, action, watermark, logo, ad_pre, bforupload) {

    load_result = 0;
    checkcount = 0;
    player_container = null;

    if (bforupload == null)
    {
        bforupload = false;
    }
    bbforupload = bforupload;
    if (streamurlarray.join == null ) {
        streamurlarray = [streamurlarray];
    }
    var streamurl = streamurlarray[0];
    var qpos = streamurl.indexOf('?');
    var query = "";
    var url = streamurl;
    if (qpos > 0) {
        query = streamurl.substring(qpos + 1);
        url = streamurl.substring(0, qpos);
    }

    var params = "";

    if (query.length > 0) {
        params = params + "**enj**query:" + query;
    }

    if (titleimageurl != null) {
        params = params + "**enj**titleimg:" + titleimageurl;
    }

    if (ad_pre != null) {
        params = params + "**enj**adurl:" + ad_pre;
    }

    if (watermark != null) {
        params = params + "**enj**watermark:" + JSONtoString(watermark);
    }


    if (action != null) {
        params = params + "**enj**action:" + JSONtoString(action);
    }
    if (logo != null) {
        params = params + "**enj**logo:" + JSONtoString(logo);
    }
    if (bforupload)
    {
        params = params + "**enj**forupload:true";
    }


    var streamurllist = "";

    for (i = 1; i < streamurlarray.length ; i++) {
        streamurllist = streamurllist + "**enj**streamlist" + i + ":" + streamurlarray[i];
    }

    if (streamurllist.length > 0) {
        params = params + streamurllist;
    }


    params = Base64.encode(params);


    var plurl = (bonssl ? "https" : "http") + "://" + url + "/player.htm?info=" + params;
    
    player_container = document.getElementById(parent);
    
    
    
    
    if( window.addEventListener )
    {        
        window.addEventListener('message', handle_activemsg   );
    }
    else if(window.attachEvent)
    {     
        window.attachEvent('onmessage', handle_activemsg);
    }
    else {     
        window.onmessage = handle_activemsg;
    }
    iframeid = parent + "_if";
    player_container.innerHTML = "<div id='playerloadmsg' style='position: absolute: left:0; top:0;width:100%; height:100%; '></div><iframe name='playername' src='" + plurl + "' id='" + iframeid + "' scrolling='no' style='left= 0; top = 0;width: 100%; height: 100%; background-color: #000000; display: none;' frameborder='0' allowfullscreen onload='handle_iframeloaed()'></iframe>";
    
}
function handle_iframeloaed() {
    if (load_result) {
        playerframe_loaed();
    }
    else {
        var checksuccestimerid = setInterval(function () {
            checkcount++;
            if (load_result) {
                checkcount = 10000;
            }
            if (checkcount > 100) {
                clearInterval(checksuccestimerid);
                playerframe_loaed();
            }
            else if (checkcount == 10) {
                var msgele = document.getElementById('playerloadmsg');
                msgele.innerHTML = "Loading the player...";

            }

        }, 100);


    }
}
function playerframe_loaed() {
    var objiframe = document.getElementById(iframeid);
    objiframe.style.display = "block";
    var msgele = document.getElementById('playerloadmsg');
    msgele.style.display = "none";
    if (load_result) {

    }
    else {
        var newhtml = "<div  id='enjperrarea' style='position: absolute; left: 0; top: 0;width: 100%; height: 100%; " + (bbforupload ? "background: #ffffff; color: #000000;'>" : "background: #000000; color: #ffffff;'>") +
                "<div id='enjperrarea_msg' style='position: absolute; top: 50%; width: 100%; text-align: center;'>" +
            "<p>Failed to connect to Stream Server</p>" +
            "<p>" + streamurl + "</p>" +
            "</div>" +
                "</div>";
        player_container.innerHTML = newhtml;

        objiframe.style.display = 'none';

        var enjperraread = document.getElementById('enjperrarea');
        var enjperrarea_msg = document.getElementById('enjperrarea_msg');


        enjperrarea_msg.style.top = (enjperraread.clientHeight - enjperrarea_msg.clientHeight) / 2;
    }
}

function handle_activemsg(e) {
    
    var pp = e.data.indexOf('#');
    var kind = e.data;
    var result = null;
    if (pp > 0)
    {
        kind = e.data.substring(0, pp);
        result = e.data.substring(pp + 1);
    }
    
    
    if (kind == 'enjstreamxactive') {
        load_result = 1;
    }
    else if (kind == 'lifeeidtcomplete') {

        completeLiveEdit(result);
    }
    else if (kind == 'uploadfile') {
        completeUploadFile(result);
    }
   
}

function enj_upload_file(parent , bonssl,  stgname)
{        
    var upurl = location.host + '/?' + stgname;
    
    enj_add_player(parent, null, location.host.protocol == 'https', upurl, null, null, null, null, null, true);
}
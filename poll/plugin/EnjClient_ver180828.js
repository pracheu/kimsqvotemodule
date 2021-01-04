
var enjplcount = 0;


var Base64 = {


    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",


    encode: function (input) {
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


    decode: function (input) {
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


    _utf8_encode: function (string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if ((c > 127) && (c < 2048)) {
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


    _utf8_decode: function (utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while (i < utftext.length) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }

};









function JSONtoString(object) {
    var results = "{";
    var count = 0;
    for (var property in object) {
        var value = object[property];


        if (value != null) {
            try {

                if (typeof value == "string") {
                    results = results + property.toString() + ': "' + value + '",';

                }
                else {
                    results = results + property.toString() + ': ' + value + ',';
                }
                count++;

            }
            catch (e) {

            }
        }
    }
    if (count > 0)
        results = results + " lastobj: true }";
    else
        results = "";
    return results;

};


function popmessage(parent, msg) {

    var elepopmsgcontainer = (parent.popmsgcon == null && parent.popmsgcon == undefined) ? null : parent.popmsgcon;
    if (msg == null) {

        if (elepopmsgcontainer != null) {

            if (elepopmsgcontainer.parentNode != null && elepopmsgcontainer.parentNode != undefined)
                elepopmsgcontainer.parentNode.removeChild(elepopmsgcontainer);
            parent.popmsgcon = null;
        }
        return;
    }

    if (parent.handleresize == null || parent.handleresize == undefined) {

        parent.handleresize = function (e) {

            if (parent.popmsgcon) {
                parent.popmsgcon.style.left = (parent.clientWidth - parent.popmsgcon.clientWidth) / 2 + 'px';
                parent.popmsgcon.style.top = (parent.clientHeight - parent.popmsgcon.clientHeight) / 2 + 'px';
            }
        };


    }

    if (elepopmsgcontainer == null) {
        elepopmsgcontainer = document.createElement('div');
        elepopmsgcontainer.style.position = 'absolute';
        elepopmsgcontainer.style.color = 'white';
        elepopmsgcontainer.style.backgroundColor = 'black';  /*'blanchedalmond'*/;
        elepopmsgcontainer.style.textAlign = 'center';
        elepopmsgcontainer.style.padding = '20px';
        elepopmsgcontainer.style.borderRadius = '5px';
        elepopmsgcontainer.style.fontWeight = 'bold';
        elepopmsgcontainer.style.fontSize = '3.5mm';
        elepopmsgcontainer.style.fontFamily = "sans-serif";

        elepopmsgcontainer.elemsg = document.createElement('label');

        elepopmsgcontainer.appendChild(elepopmsgcontainer.elemsg);


        parent.appendChild(elepopmsgcontainer);
        parent.popmsgcon = elepopmsgcontainer;


    }
    elepopmsgcontainer.elemsg.innerHTML = msg;
    parent.handleresize();

    window.removeEventListener('resize', parent.handleresize);
    window.addEventListener('resize', parent.handleresize);

}


function enj_add_player(parent, swf, bonssl, streamurlarray, titleimageurl, action, watermark, logo, ad_pre, bforupload) {


    var curdate = new Date();
    var subid = curdate.getTime() + "_" + enjplcount; enjplcount++;
    var bbforupload = false;
    var load_result = 0;
    var checkcount = 0;
    var player_container = null;
    var iframeid = 'iframe' + subid;
    var formid = 'idform' + subid;
    var loadmsgid = 'loadmsg' + subid;
    var iframename = 'nm' + iframeid;
    var eleiframe = null;

    load_result = 0;
    checkcount = 0;
    player_container = null;




    if (bforupload == null) {
        bforupload = false;
    }
    bbforupload = bforupload;


    if (streamurlarray.join == null) {
        streamurlarray = [streamurlarray];
    }
    var streamurl = streamurlarray[0];
    var qpos = streamurl.indexOf('?');
    var query = "";
    var url = streamurl;


    ;

    if (qpos > 0) {
        query = streamurl.substring(qpos + 1);
        url = streamurl.substring(0, qpos);
    }

    var params = "**enj**ifid:" + iframeid;

    if (query.length > 0) {
        params = params + "**enj**query:" + query;
    }

    if (titleimageurl) {
        params = params + "**enj**titleimg:" + titleimageurl;
    }

    if (ad_pre) {
        params = params + "**enj**adurl:" + ad_pre;
    }

    if (watermark) {
        params = params + "**enj**watermark:" + JSONtoString(watermark);
    }


    if (action) {
        params = params + "**enj**action:" + JSONtoString(action);
    }
    if (logo) {
        params = params + "**enj**logo:" + JSONtoString(logo);
    }
    if (bforupload) {
        params = params + "**enj**forupload:true";
    }


    var streamurllist = "";

    for (i = 1; i < streamurlarray.length; i++) {
        streamurllist = streamurllist + "**enj**streamlist" + i + ":" + streamurlarray[i];

    }

    if (streamurllist.length > 0) {
        params = params + streamurllist;
    }

    

    params = Base64.encode(params);


    var ni = url.indexOf('/');
    url = url.substring(0, ni + 1) + encodeURIComponent(url.substring(ni + 1));
   
    var plurl = (bonssl ? "https" : "http") + "://" + url + "/player.htm";




    player_container = document.getElementById(parent);

    player_container.style.backgroundColor = 'black';
    player_container.style.color = 'white';
    popmessage(player_container, null);

    if (window.addEventListener) {
        window.addEventListener('message', handle_activemsg);
    }
    else if (window.attachEvent) {
        window.attachEvent('onmessage', handle_activemsg);
    }
    else {
        window.onmessage = handle_activemsg;
    }


    

    player_container.innerHTML = "<form id='" + formid + "' target='" + iframename + "' action='" +  plurl + "' method='POST' style='display:none' ><input type='text' name='pinfo' value='" + params + "' /></form><iframe name='" + iframename + "' id='" + iframeid + "' scrolling='no' style='left= 0; top = 0;width: 100%; height: 100%; background-color: #000000; display: none;' frameborder='0' allowfullscreen ></iframe>";

    eleiframe = document.getElementById(iframeid);


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
            

            popmessage(player_container, "Loading the player...");
        }
    }, 100);



    var eleform = document.getElementById(formid);


    eleform.submit();
    eleform.style.display = 'none';


    function getLang() {
        var userLang = navigator.language || navigator.userLanguage;
        return userLang.substring(0, 2);
    }
    function playerframe_loaed() {



        var failmsg = getLang() == "ko" ? "스트리밍 서버에 연결할 수 없거나 유효한 컨텐츠가 아닙니다." : "Connecting to Stream-Server is failed, or it is a invalid content. ";

        
        if (load_result) {
            popmessage(player_container, null);
            eleiframe.style.display = "block";
        }
        else {
            eleiframe.style.display = 'none';
            popmessage(player_container, failmsg);
        }
    };

    function handle_activemsg(e) {


        var pp = e.data.indexOf('#');
        var kind = e.data;
        var result = null;

        if (pp > 0) {
            kind = e.data.substring(0, pp);
            result = e.data.substring(pp + 1);
        }

        if (kind == 'enjstreamxactive') {
            if (result == iframeid)
                load_result = 1;
        }
        else if (kind == 'enjstreamxnosrc') {
            if (result == iframeid)
                checkcount = 10000;
        }
        else if (kind == 'lifeeidtcomplete') {

            completeLiveEdit(result);
        }
        else if (kind == 'uploadfile') {

            completeUploadFile(result);
        }
        else if (kind == 'fullsc') {
            if (result == iframeid) {
                console.log(eleiframe);
                if (eleiframe.setAttribute)
                    eleiframe.setAttribute('style', 'position: fixed;left:0;top:0;width:100%;height:100%;');
                else
                    eleiframe.style = 'position: fiexed;left:0;top:0;width:100%;height:100%;';

            }
        }
    };


    return eleiframe;
};


function enj_upload_file(parent, bonssl, stgname, pmaxlen) {
    var upurl = location.host + '/?' + stgname;
    
    enj_add_player(parent, null, location.host.protocol == 'https', upurl, null, {maxlen:pmaxlen,skin:2}, null, null, null, true);
};

function popup_uploadwindow(upurl, pmsghandler) {

    var popwidth = 450;
    var popheight = 500;
    var scleft = window.screenLeft ? window.screenLeft : window.screenX;
    var sctop = window.screenTop ? window.screenTop : window.screenY;
    var left = scleft + window.innerWidth / 2 - popwidth / 2;
    var top = sctop + window.innerHeight / 2 - popheight / 2;
    var ctime = new Date();
    var popcontent = "<html><body scroll='no'><iframe src='" + upurl + "' style='position: absolute;left:0; top:0; width:100%; height:100%; margin:0;' frameborder=0 scrolling=no border=0 cellpadding=0 cellspacing=0 ></iframe></body></html>";
    popwin = window.open('', "uploader", "width=" + popwidth + ", height=" + popheight + ", left=" + left + ', top=' + top + ',scrollbars=no');
    
    popwin.document.open();
    popwin.document.write(popcontent);
    popwin.document.close();
    popwin.focus();

    addEventHandler(popwin, 'message', pmsghandler);
    popwin.checktimerid = setInterval(function () {
        if (popwin.closed)
        {
            clearInterval(popwin.checktimerid);
            if (pmsghandler)
                pmsghandler('closed');
        }
    }, 500);
    /*
    if (popwin.addEventListener) {
        popwin.addEventListener('message', pmsghandler);
    }
    else if (popwin.attachEvent) {
        popwin.attachEvent('onmessage', pmsghandler);
    }
    else {
        popwin.onmessage = pmsghandler;
    }*/

    return popwin;
}

function popup_uploader(upurl, handler_get_results) {
    var upresults = null;
    var callback = handler_get_results;
    var popwidth = 450;
    var popheight = 500;
    var scleft = window.screenLeft ? window.screenLeft : window.screenX;
    var sctop = window.screenTop ? window.screenTop : window.screenY;
    var left = scleft + window.innerWidth / 2 - popwidth / 2;
    var top = sctop + window.innerHeight / 2 - popheight / 2;
    var popcontent = "<html><body scroll='no'><iframe src='" + upurl + "' style='position: absolute;left:0; top:0; width:100%; height:100%; margin:0;' frameborder=0 scrolling=no border=0 cellpadding=0 cellspacing=0 ></iframe></body></html>";

    popwin = window.open('', "uploader", "width=" + popwidth + ", height=" + popheight + ", left=" + left + ', top=' + top + ',scrollbars=no,location=no,menubar=no,toobar=no,status=no');

    popwin.document.open();
    popwin.document.write(popcontent);
    popwin.document.close();
    popwin.focus();

    popwin.onbeforeunload = function () {
        if (callback) {

            setTimeout(function () {
                callback(upresults);
                callback = null;
            }, 100);

        }
    };
    if (popwin.addEventListener) {
        popwin.addEventListener('message', handle_msg);
    }
    else if (popwin.attachEvent) {
        popwin.attachEvent('onmessage', handle_msg);
    }
    else {
        popwin.onmessage = handle_msg;
    }

    function handle_msg(e) {


        var pp = e.data.indexOf('#');
        var kind = e.data;
        var result = null;
        if (pp > 0) {
            kind = e.data.substring(0, pp);
            upresults = e.data.substring(pp + 1);
        }
        if (kind == 'uploadfile') {

            if (upresults.indexOf('startcvt') > 0) {

            }
            else {
                popwin.close();
            }
        }

    }

}
<?php

//add_stylesheet('<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css" type="text/css">', 0);
//<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

//add_stylesheet('<link rel="stylesheet" href="'.G5_PLUGIN_URL.'/jquery-ui/jquery-ui.css" type="text/css">', 0);
//add_stylesheet('<link rel="stylesheet" href="'.G5_PLUGIN_URL.'/jquery-ui/style.css" type="text/css">', 0);
?>
<link rel="stylesheet" href="<?php echo $g['path_module'].'poll/plugin' ?>/jquery-ui/jquery-ui.css" type="text/css">
<link rel="stylesheet" href="<?php echo $g['path_module'].'poll/plugin' ?>/jquery-ui/style.css" type="text/css">
<script src="<?php echo $g['path_module'].'poll/plugin' ?>/jquery-ui/jquery-ui.min.js"></script>
<script>
jQuery(function($){
    $.datepicker.regional["ko"] = {
        closeText: "닫기",
        prevText: "이전달",
        nextText: "다음달",
        currentText: "오늘",
        monthNames: ["1월(JAN)","2월(FEB)","3월(MAR)","4월(APR)","5월(MAY)","6월(JUN)", "7월(JUL)","8월(AUG)","9월(SEP)","10월(OCT)","11월(NOV)","12월(DEC)"],
        monthNamesShort: ["1월","2월","3월","4월","5월","6월", "7월","8월","9월","10월","11월","12월"],
        dayNames: ["일","월","화","수","목","금","토"],
        dayNamesShort: ["일","월","화","수","목","금","토"],
        dayNamesMin: ["일","월","화","수","목","금","토"],
        weekHeader: "Wk",
        dateFormat: "yymmdd",
        firstDay: 0,
        isRTL: false,
        showMonthAfterYear: true,
        yearSuffix: ""
    };
	$.datepicker.setDefaults($.datepicker.regional["ko"]);
});
</script>
var validator;function save_success(a){displayMessageEx("ok_1")}function save_error(a){displayMessageEx(a.split(";"),true)}function errorBoxControl(){try{var b=false;for(key in validator.items){if(typeof(validator.items[key])!="function"){b=validator.items[key].error.is_error?true:b}}if(!b){displayMessageEx(false,true);validator.setOnCheck()}}catch(a){alert("errorBoxControl: "+a.message)}}function myPanelDestructor(){validator=null}function myPanelOnLoad(){validator=new FormValidator();temp_vars_set=getTempVarsSet();validator.add("login_remember_me");validator.add("login_block_message","check_max_len(val,1024)");validator.items.login_block_message.checkOnEvent("keyup");validator.add("login_try_capcha","check_int_limit(val,32768,1)");validator.items.login_try_capcha.checkOnEvent("keyup");validator.add("login_try_block_ip","check_int_limit(val,32768,1)");validator.items.login_try_block_ip.checkOnEvent("keyup");validator.add("ip_block_timeout","check_int_limit(val,32768,60)");validator.items.ip_block_timeout.checkOnEvent("keyup");validator.add("ip_block_selected_period","check_int_limit(val,32768,60)");validator.items.ip_block_selected_period.checkOnEvent("keyup");validator.add("captcha_char_min","check_int_limit(val,5,2)&&check_captcha_mm()");validator.items.captcha_char_min.checkOnEvent("keyup");validator.add("captcha_char_max","check_int_limit(val,5,2)&&check_captcha_mm()");validator.items.captcha_char_max.checkOnEvent("keyup");validator.add("autoban_count","check_int_limit(val,32768,2)");validator.items.autoban_count.checkOnEvent("keyup");validator.add("autoban_timeout","check_int_limit(val,32768,60)");validator.items.autoban_timeout.checkOnEvent("keyup");validator.add("session_expiration","check_int_limit(val,32768,60)");validator.items.session_expiration.checkOnEvent("keyup");validator.checkAll()}function check_captcha_mm(){var a=parseInt($("#captcha_char_min").val());var c=parseInt($("#captcha_char_max").val());var b=(a<=c)?true:false;return b}function secure_save(c,d){try{if(d=="reload"){if(confirm(temp_vars_set.cancelText)){var g={action:""};load_panel(c,g,{"0":base_url+"js/admin/init.js"})}}else{displayMessageEx();var b=validator.checkAll();if(!b){displayMessageEx(temp_vars_set.ValidationError,true);validator.setOnCheck(errorBoxControl)}else{var a=validator.getItems();var g={login_remember_me:a.login_remember_me,login_try_capcha:a.login_try_capcha,login_try_block_ip:a.login_try_block_ip,ip_block_timeout:a.ip_block_timeout,ip_block_selected_period:a.ip_block_selected_period,login_block_message:a.login_block_message,captcha_char_min:a.captcha_char_min,captcha_char_max:a.captcha_char_max,autoban_count:a.autoban_count,autoban_timeout:a.autoban_timeout,session_expiration:a.session_expiration,action:d};on_post_success=save_success;on_post_error=save_error;load_panel(c,g,{"0":base_url+"js/admin/init.js"})}}}catch(f){_sys_error("Error:","FAIL",f)}};

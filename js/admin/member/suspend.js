function after_member_suspend_success(c){msg=c;after_panel_load=after_panel_loaded;try{current_page=1;var b={cpage:current_page,ppage:per_page,ord:sort_by,ord_type:sort_how};moveSlideTab(2,5,"unsuspend_delete");load_panel(base_url+"member/unsuspend",b)}catch(a){_sys_error("after_member_suspend_success","Loading suspended members list after success",a)}}function after_member_suspend_error(a){if(a!=""){_error_panel_show(a)}}function suspend_member_list(){try{var b="";var f=[];var d=$("input[id^='mbr_id-']");for(i=0;i<d.length;i++){if($(d[i]).attr("checked")){f=$(d[i]).attr("id").split("-");if(f[1]>0){var a=$("select[id^='sreason_id-"+f[1]+"']").val();a=_get_valid_reason(a);b=b+"!"+f[1]+"-"+a}}}b=b.substr(1);if(b==""){$("#jsvalid_error_mbr_notchecked").show();$("#error_panel").show();return false}var c=$("#suspend_question").text();if(confirm(c)){var h={mbrlist:b};on_post_success=after_member_suspend_success;on_post_error=after_member_suspend_error;load_panel(base_url+"member/suspend_user",h);return}}catch(g){_sys_error("suspend_member_list","select checked members id",g)}}function suspend_member(d){try{d=parseInt(d);if(d>0){var c=$("#suspend_question").text();if(confirm(c)){var a=$("select[id^='sreason_id-"+d+"']").val();a=_get_valid_reason(a);var b=d+"-"+a+"!";var g={mbrlist:b};on_post_success=after_member_suspend_success;on_post_error=after_member_suspend_error;load_panel(base_url+"member/suspend_user",g);return}}}catch(f){_sys_error("suspend_member","prepare params",f)}};

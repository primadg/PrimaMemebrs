function load_unconfirmed_mbr_list(b){try{if(b){$.each(b,function(d,e){if(d=="ppage"&&!isNaN(e)){per_page=e;current_page=1}else{if(d=="cpage"){current_page=(e>0)?e:1}else{if(d=="ord"){sort_by=e;sort_how=(sort_how=="asc")?"desc":"asc"}}}return})}var c={cpage:current_page,ppage:per_page,ord:sort_by,ord_type:sort_how};moveSlideTab(2,4,"activate_suspend");load_panel(base_url+"member/confirmation",c)}catch(a){_sys_error("confirm:load_unconfirmed_list","Generates not confirmed members list",a)}}function after_member_confirm_success(c){msg=c;after_panel_load=after_panel_loaded;try{var b="";load_panel(base_url+"member/member_list",b)}catch(a){_sys_error("confirm:after_member_confirm_success","Loading member list after success",a)}}function after_member_confirm_error(a){if(a!=""){_error_panel_show(a)}}function confirm_member_list(){try{var b=_get_selected_members();if(b==""){$("#jsvalid_error_mbr_notchecked").show();$("#error_panel").show();return false}var a=$("#confirm_question").text();if(confirm(a)){var d={mbrlist:b};on_post_success=after_member_confirm_success;on_post_error=after_member_confirm_error;load_panel(base_url+"member/confirm_user/",d);return}}catch(c){_sys_error("confirm:confirm_member_list","select checked members id",c)}}function confirm_member(b){try{if(b>0){var a=$("#confirm_question").text();if(confirm(a)){var d={mbrlist:b+"!"};on_post_success=after_member_confirm_success;on_post_error=after_member_confirm_error;load_panel(base_url+"member/confirm_user",d);return}}}catch(c){_sys_error("confirm:confirm_member","Prepare member id",c)}};

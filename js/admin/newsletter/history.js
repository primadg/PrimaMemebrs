var h_is_search=false;var h_search_date_from=null;var h_search_date_to=null;function email_history_list(c){try{if(c){$.each(c,function(e,f){if(e=="ppage"&&!isNaN(f)){per_page=f;current_page=1}else{if(e=="cpage"){current_page=(f>0)?f:1}else{if(e=="ord"){sort_by=f;sort_how=(sort_how=="asc")?"desc":"asc"}else{if(e=="h_is_search"){h_is_search=c.h_is_search}}}}return})}if(h_is_search){if($("#date_from").length){if($("#date_from").val()!=""){if($("#date_to").val()==""||!compare_dates($("#date_from").val(),$("#date_to").val())){h_is_search=false;return false}h_search_date_from=$("#date_from").val();h_search_date_to=$("#date_to").val();h_search_date_period=null}else{if($("#date_period").val()!="all_time"){h_search_date_period=$("#date_period option:selected").val();h_search_date_from=null;h_search_date_to=null}else{h_search_date_from=null;h_search_date_to=null;h_search_date_period=null}}}if(!h_search_date_from&&!h_search_date_period){if(c&&c.h_is_search==true){h_is_search=false;return false}}else{var a={is_search:true,date_from:h_search_date_from,date_to:h_search_date_to,date_period:h_search_date_period}}}var d={cpage:current_page,ppage:per_page,ord:sort_by,ord_type:sort_how};if(a){d=$.extend(d,a)}moveSlideTab(4,3,"email_history");load_panel(base_url+"newsletter/history",d)}catch(b){_sys_error("history:history_list","Prepares params for getting email history list",b)}}function after_email_history_delete_success(c){_error_panel_hide();msg=c;after_panel_load=after_panel_loaded;try{var b={cpage:current_page,ppage:per_page,ord:sort_by,ord_type:sort_how};load_panel(base_url+"newsletter/history",b);redesign()}catch(a){_sys_error("history:after_email_history_delete_success","Loading email history list after delete",a)}}function after_email_history_delete_error(a){_msg_panel_hide();if(a!=""){_error_panel_show(a)}}function email_history_delete(a){_msg_panel_hide();_error_panel_hide();try{if(parseInt(a)>0){var b=$("#delete_question").text();if(confirm(b)){var d={id:a};on_post_success=after_email_history_delete_success;on_post_error=after_email_history_delete_error;load_panel(base_url+"newsletter/history_remove",d);return}}}catch(c){_sys_error("history:email_history_delete","Checks email history id before delete it",c)}}function email_history_info(a){try{if(parseInt(a)>0){var c={id:a};document.location.hash=document.location.hash+"/"+a+"/info";load_panel(base_url+"newsletter/history_view",c);return}}catch(b){_sys_error("history:email_history_info","Prepares email history id for history info displaying",b)}};

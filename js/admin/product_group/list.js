var sort_by="";var order_by=false;var page=0;var per_page=0;var prev_tab=null;function click_group_sort(a){sort_by=a;order_by=!order_by;if(prev_tab){prev_tab.removeClass("sort");prev_tab=$("#glist_tab_"+a);prev_tab.addClass("sort")}else{prev_tab=$("#glist_tab_name");prev_tab.removeClass("sort")}load_group_list()}function load_group_list(){var a={order_by:order_by,sort_by:sort_by,page:page,per_page:per_page};setPagerParams({order_by:order_by,sort_by:sort_by,page:page,per_page:per_page});load_panel(base_url+"product_group/group_list",a)}function group_list_prepare_pager(a){if(!a){a=new Object}if(a.cpage){page=a.cpage}if(a.ppage){per_page=a.ppage;page=1}else{per_page=a.ppage=$("#pp_select-1").val()}load_group_list()}function list_click_add_group(){load_panel(base_url+"product_group/add")}function group_added_success(){show_form_msg(1)}function group_saved_success(){show_form_msg(3)}function click_delete_group(b,a){if(a){alert($("#group_not_empty_mess").html());return}on_post_success=group_delete_response_success;on_post_error=group_delete_response_error;load_panel(base_url+"product_group/delete_group",{id:b})}function group_delete_response_success(){after_panel_load=after_group_delete;load_group_list()}function group_delete_response_error(a){hide_form_errors();show_form_errors(parseInt(a),a)}function after_group_delete(){show_form_msg(2)}function click_edit_group(a){a=parseInt(a);load_panel(base_url+"product_group/edit/"+a)};

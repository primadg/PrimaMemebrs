function after_product_add(){show_form_msg(1)}function after_product_save(){show_form_msg(3)}var order_by=false;var sort_by="name";var page=1;var per_page=0;var prev_tab=null;var group_id=0;var member_group=0;var member_groups_available=0;var working_product=0;function tab_sort_click(a){try{sort_by=a;order_by=!order_by;product_list_prepare_pager({});if(prev_tab){prev_tab.removeClass("sort");prev_tab=$("#plist_tab_"+a);prev_tab.addClass("sort")}else{prev_tab=$("#plist_tab_name");prev_tab.removeClass("sort")}}catch(b){_sys_error("product/product_list.js::tab_sort_click","...",b)}}function product_list_prepare_pager(a){if(!a){a=new Object}if(a.cpage){page=a.cpage}if(a.ppage){per_page=a.ppage;page=1}else{per_page=a.ppage=$("#pp_select-1").val()}get_product_list()}function get_product_list(){var a={order_by:order_by,sort_by:sort_by,page:page,per_page:per_page,group:group_id,member_group:member_group,member_groups_available:member_groups_available};setPagerParams({order_by:order_by,sort_by:sort_by,page:page,per_page:per_page,group:group_id,member_group:member_group,member_groups_available:member_groups_available});document.location.hash="#products_list";load_panel(base_url+"product/product_list",a)}function product_list_group_search(){group_id=$("#product_groups").val();member_group=$("#member_group_selector").val();member_groups_available=$("#member_groups_available:checked").length;page=1;product_list_prepare_pager({})}function block_product(a){working_product=a;on_post_success=block_success;on_post_error=block_error;load_panel(base_url+"product/block_product",{id:a})}function special_offers_product(a){working_product=a;displayMessageEx(false);on_post_success=special_offers_success;on_post_error=special_offers_error;load_panel(base_url+"product/special_offers_product",{id:a})}function special_offers_success(){try{var c=$($("#product_special_offers"+working_product).children()[0]);var f=base_url+"img/favorite_off.png";var b=base_url+"img/favorite.png";if(/MSIE (5\.5|6).+Win/.test(navigator.userAgent)){if(c.css("filter")){if(c.css("filter").match(/favorite.png/)){c.css("filter","progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+b+"',sizingMethod='scale')")}else{c.css("filter","progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+f+"',sizingMethod='scale')")}}}else{var a=c.attr("src");if(a==f){c.attr("src",b);$("#product_special_offers"+working_product).attr("title",$("#unspecial_product").html())}else{c.attr("src",f);$("#product_special_offers"+working_product).attr("title",$("#special_product").html())}}return 0}catch(d){_sys_error("product/product_list.js::special_offers_product","block_unblock_product",d)}}function special_offers_error(a){show_form_errors(parseInt(a),a)}function block_success(){try{var c=$($("#product_locked"+working_product).children()[0]);var d=base_url+"img/ico_locked.png";var b=base_url+"img/ico_unlocked.png";if(/MSIE (5\.5|6).+Win/.test(navigator.userAgent)){if(c.css("filter")){if(c.css("filter").match(/ico_locked.png/)){c.css("filter","progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+b+"',sizingMethod='scale')")}else{c.css("filter","progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+d+"',sizingMethod='scale')")}}}else{var a=c.attr("src");c.attr("src",(a==d?b:d))}return 0}catch(f){_sys_error("product/product_list.js::block_success","block_unblock_product",f)}}function block_error(a){show_form_errors(parseInt(a),a)}function show_list_errors(b){var a=$("#list_errors").children();$("#list_errors").show();$(a[b]).show()}function hide_list_errors(){var a=$("#list_errors").children();$("#list_errors").hide();$.each(a,function(b,c){if(b>0&&b<10){$(c).hide()}})}function click_delete_product(a){if(!confirm($("#product_delete_ask").html())){return}working_product=a;displayMessageEx(false);on_post_success=delete_success;on_post_error=delete_error;load_panel(base_url+"product/delete_product",{id:a})}function delete_success(){after_panel_load=after_delete;get_product_list()}function delete_error(a){show_form_errors(parseInt(a),a)}function after_delete(){show_form_msg(2)}function add_product_click(){try{document.location.hash=document.location.hash+"/product_add";load_panel(base_url+"product/add")}catch(a){alert(a.message)}}function click_edit(b){try{b=parseInt(b);document.location.hash=document.location.hash+"/"+b+"/product_edit";load_panel(base_url+"product/edit/"+b,{},{a:base_url+"js/admin/product/edit.js"})}catch(a){alert(a.message)}};
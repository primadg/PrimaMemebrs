function after_add_template_success(c){_error_panel_hide();msg=c;after_panel_load=after_panel_loaded;try{var b={cpage:current_page,ppage:per_page,ord:sort_by,ord_type:sort_how};_set_menu_cursor("4","1");load_panel(base_url+"newsletter/template_list",b)}catch(a){_sys_error("template_add:after_add_template_success","Getting pager params for email templates list",a)}}function after_add_template_error(a){if(a!=""){_error_panel_show(a)}}function add_template(){_msg_panel_hide();_error_panel_hide();try{var a=$("#tpl_name").val();var f=$("#tpl_subject").val();var b=$("#tpl_message").val();var d={name:a,subject:f,message:b,action:"add"};if(_validate_template_fields(d)){on_post_success=after_add_template_success;on_post_error=after_add_template_error;moveSlideTab(4,1,"email_templates");load_panel(base_url+"newsletter/template_add",d)}}catch(c){_sys_error("template_add:add_template","Getting form fields values",c)}};

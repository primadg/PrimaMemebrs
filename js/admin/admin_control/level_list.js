function myPanelOnLoad(){on_post_error=save_error;additional_accept=after_save}function myPanelDestructor(){}function after_save(b){var a=getTempVarsSet(false,b);displayMessageEx(a)}function save_error(b){var a=getTempVarsSet(false,b);displayMessageEx(a,true)}function myOnAdd(){var a={action:""};document.location.hash=document.location.hash+"/add";load_panel(base_url+"admin_control/levels_edit/",a,{"0":base_url+"js/admin/init.js"})}function levelEdit(b){var a={action:""};a.id=b;document.location.hash=document.location.hash+"/"+b+"/edit";load_panel(base_url+"admin_control/levels_edit/",a,{"0":base_url+"js/admin/init.js"})}function levelDelete(b){if(confirm(temp_vars_set.are_you_sure)){var a={action:"delete"};a.id=b;load_panel(base_url+"admin_control/levels_edit/",a,{"0":base_url+"js/admin/init.js"})}};

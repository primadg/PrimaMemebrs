var validator;function myPanelOnLoad(){try{}catch(a){alert("myTabOnLoad: "+a.message);_sys_error("Error:","FAIL",a)}}function myPanelDestructor(){validator=null}function memberPageSave(a){var b={action:"save"};$("table#field_list tr:has(div.options)").each(function(){var c=new Array();$(this).find(":input").each(function(d,e){if($(e).is("select")){c.push($(e).attr("name")+"="+($(e).val()))}else{if($(e).is(":text")){c.push($(e).attr("name")+"="+$(e).val())}else{c.push($(e).attr("name")+"="+($(e).is(":checked")?"1":"0"))}}});b["option_"+$(this).find(".options").attr("name")]=c.join("&")});load_panel(base_url+"config/member_pages/"+a,b,{"0":base_url+"js/admin/init.js"})}function memberPageBack(a){load_panel(a,"",{"0":base_url+"js/admin/init.js"})}function memberPageOptionsToggle(a){if(a==".options_toggler"){var b=$(".all_options_toggler");if(b.is(".all_options_toggler_show")){b.html("&#9658;");b.removeClass("all_options_toggler_show");$(a).siblings(".options").slideUp(500);$(a).find(".arrow").html("&#9658;")}else{b.html("&#9660;");b.addClass("all_options_toggler_show");$(a).siblings(".options").slideDown(500);$(a).find(".arrow").html("&#9660;")}return}if($(a).siblings(".options:visible").length>0){$(a).siblings(".options").slideUp(500);$(a).find(".arrow").html("&#9658;")}else{$(a).siblings(".options").slideDown(500);$(a).find(".arrow").html("&#9660;")}}function memberPageLoadPreset(a){if(confirm(temp_vars_set.confirm_load)){var b={action:"preset"};b.preset=$("#page_presets").val();load_panel(base_url+"config/member_pages/"+a,b,{"0":base_url+"js/admin/init.js"})}}function memberPageOptionsOrder(b,a,c){load_panel(base_url+"config/member_pages/"+b,{action:a,id:c},{"0":base_url+"js/admin/init.js"})};

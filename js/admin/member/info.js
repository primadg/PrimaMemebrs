function load_member_info(b,a){try{if(parseInt(b)>0){if(a==""){a="load_member_list"}var d={id:b,back_link:a};document.location.hash=document.location.hash+"/"+b+"/info";load_panel(base_url+"member/info",d);return}}catch(c){_sys_error("common:load_member_info","prepare member id for member info",c)}};

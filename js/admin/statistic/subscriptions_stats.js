var search_by;var search_word;var date_from;var date_to;function myPanelOnLoad(){try{validator=new FormValidator();validator.add("search_by");validator.add("search_word");validator.add("date_from","check_date(val)||!val");validator.items.date_from.checkOnEvent("keyup");validator.items.date_from.checkOnEvent("focus");validator.add("date_to","check_date(val)||!val");validator.items.date_to.checkOnEvent("keyup");validator.items.date_to.checkOnEvent("focus");validator.checkAll();validator.setOnCheck(errorBoxControl)}catch(a){alert("myTabOnLoad: "+a.message);_sys_error("Error:","FAIL",a)}on_post_error=save_error;additional_accept=after_save}function myPanelDestructor(){validator=null}function myPagerHandler(b){var a=new Array();if(search_by){a.push(search_by);a.push(search_word);a.push(date_from);a.push(date_to)}b["filter[]"]=a;load_panel(base_url+"statistic/subscriptions_stats/",b,{"0":base_url+"js/admin/init.js"})}function NewSearchParamsSet(){if(!validator.checkAll()){displayMessageEx("date_from",true)}else{displayMessageEx(false,true);var a=validator.getItems();search_by=a.search_by;search_word=a.search_word;date_from=a.date_from;date_to=a.date_to;var b=getPagerParams();myPagerHandler(b)}}function after_save(b){var a=getTempVarsSet(false,b);displayMessageEx(a)}function save_error(b){var a=getTempVarsSet(false,b);displayMessageEx(a,true)}function errorBoxControl(){try{var b=false;for(key in validator.items){if(typeof(validator.items[key])!="function"){b=validator.items[key].error.is_error?true:b}}if(!b){displayMessageEx(false,true);validator.setOnCheck()}}catch(a){alert("errorBoxControl: "+a.message)}}function ShowTransactions(b){document.location.hash=document.location.hash+"/"+b+"/transactions";var a={action:""};load_panel(base_url+"statistic/transactions_stats/"+b+"/",a,{"0":base_url+"js/admin/init.js"})};
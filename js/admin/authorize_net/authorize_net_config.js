var validator;function myPanelOnLoad(){try{validator=new FormValidator();validator.add("api_login","val");validator.items.api_login.checkOnEvent("keyup");validator.add("transaction_key","val");validator.items.transaction_key.checkOnEvent("keyup");validator.add("md5hash","check_max_len(val, 254)");validator.items.md5hash.checkOnEvent("keyup");validator.add("test");validator.items.test.checkOnEvent("click",isTestMode);validator.checkAll();isTestMode()}catch(a){alert("myTabOnLoad: "+a.message);_sys_error("Error:","FAIL",a)}}function isTestMode(){if(validator.items.test.value){$("#"+validator.items.md5hash.id).parent().parent().show()}else{$("#"+validator.items.md5hash.id).parent().parent().hide()}}function myPanelDestructor(){validator=null};
jQuery.fn.corner=function(f){function k(n){var n=parseInt(n).toString(16);return(n.length<2)?"0"+n:n}function d(o){for(;o&&o.nodeName.toLowerCase()!="html";o=o.parentNode){var n=jQuery.css(o,"backgroundColor");if(n.indexOf("rgb")>=0){rgb=n.match(/\d+/g);return"#"+k(rgb[0])+k(rgb[1])+k(rgb[2])}if(n&&n!="transparent"){return n}}return"#ffffff"}function m(n){switch(h){case"round":return Math.round(e*(1-Math.cos(Math.asin(n/e))));case"cool":return Math.round(e*(1+Math.cos(Math.asin(n/e))));case"sharp":return Math.round(e*(1-Math.cos(Math.acos(n/e))));case"bite":return Math.round(e*(Math.cos(Math.asin((e-n-1)/e))));case"slide":return Math.round(e*(Math.atan2(n,e/n)));case"jut":return Math.round(e*(Math.atan2(e,(e-n-1))));case"curl":return Math.round(e*(Math.atan(n)));case"tear":return Math.round(e*(Math.cos(n)));case"wicked":return Math.round(e*(Math.tan(n)));case"long":return Math.round(e*(Math.sqrt(n)));case"sculpt":return Math.round(e*(Math.log((e-n-1),e)));case"dog":return(n&1)?(n+1):e;case"dog2":return(n&2)?(n+1):e;case"dog3":return(n&3)?(n+1):e;case"fray":return(n%2)*e;case"notch":return e;case"bevel":return n+1}}f=(f||"").toLowerCase();var b=/keep/.test(f);var g=((f.match(/cc:(#[0-9a-f]+)/)||[])[1]);var j=((f.match(/sc:(#[0-9a-f]+)/)||[])[1]);var e=parseInt((f.match(/(\d+)px/)||[])[1])||10;var l=/round|bevel|notch|bite|cool|sharp|slide|jut|curl|tear|fray|wicked|sculpt|long|dog3|dog2|dog/;var h=((f.match(l)||["round"])[0]);var i={T:0,B:1};var a={TL:/top|tl/.test(f),TR:/top|tr/.test(f),BL:/bottom|bl/.test(f),BR:/bottom|br/.test(f)};if(!a.TL&&!a.TR&&!a.BL&&!a.BR){a={TL:1,TR:1,BL:1,BR:1}}var c=document.createElement("div");c.style.overflow="hidden";c.style.height="1px";c.style.backgroundColor=j||"transparent";c.style.borderStyle="solid";return this.each(function(r){var o={T:parseInt(jQuery.css(this,"paddingTop"))||0,R:parseInt(jQuery.css(this,"paddingRight"))||0,B:parseInt(jQuery.css(this,"paddingBottom"))||0,L:parseInt(jQuery.css(this,"paddingLeft"))||0};if(jQuery.browser.msie){this.style.zoom=1}if(!b){this.style.border="none"}c.style.borderColor=g||d(this.parentNode);var t=jQuery.curCSS(this,"height");for(var p in i){var u=i[p];c.style.borderStyle="none "+(a[p+"R"]?"solid":"none")+" none "+(a[p+"L"]?"solid":"none");var v=document.createElement("div");var n=v.style;u?this.appendChild(v):this.insertBefore(v,this.firstChild);if(u&&t!="auto"){if(jQuery.css(this,"position")=="static"){this.style.position="relative"}n.position="absolute";n.bottom=n.left=n.padding=n.margin="0";if(jQuery.browser.msie){n.setExpression("width","this.parentNode.offsetWidth")}else{n.width="100%"}}else{n.margin=!u?"-"+o.T+"px -"+o.R+"px "+(o.T-e)+"px -"+o.L+"px":(o.B-e)+"px -"+o.R+"px -"+o.B+"px -"+o.L+"px"}for(var q=0;q<e;q++){var x=Math.max(0,m(q));var s=c.cloneNode(false);s.style.borderWidth="0 "+(a[p+"R"]?x:0)+"px 0 "+(a[p+"L"]?x:0)+"px";u?v.appendChild(s):v.insertBefore(s,v.firstChild)}}})};

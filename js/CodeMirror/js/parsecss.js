var CSSParser=Editor.Parser=(function(){var b=(function(){function g(i,j){var h=i.next();if(h=="@"){i.nextWhile(matcher(/\w/));return"css-at"}else{if(h=="/"&&i.equals("*")){j(e);return null}else{if(h=="<"&&i.equals("!")){j(d);return null}else{if(h=="="){return"css-compare"}else{if(i.equals("=")&&(h=="~"||h=="|")){i.next();return"css-compare"}else{if(h=='"'||h=="'"){j(f(h));return null}else{if(h=="#"){i.nextWhile(matcher(/\w/));return"css-hash"}else{if(h=="!"){i.nextWhile(matcher(/[ \t]/));i.nextWhile(matcher(/\w/));return"css-important"}else{if(/\d/.test(h)){i.nextWhile(matcher(/[\w.%]/));return"css-unit"}else{if(/[,.+>*\/]/.test(h)){return"css-select-op"}else{if(/[;{}:\[\]]/.test(h)){return"css-punctuation"}else{i.nextWhile(matcher(/[\w\\\-_]/));return"css-identifier"}}}}}}}}}}}}function e(j,k){var h=false;while(!j.endOfLine()){var i=j.next();if(h&&i=="/"){k(g);break}h=(i=="*")}return"css-comment"}function d(j,k){var i=0;while(!j.endOfLine()){var h=j.next();if(i>=2&&h==">"){k(g);break}i=(h=="-")?i+1:0}return"css-comment"}function f(h){return function(j,l){var k=false;while(!j.endOfLine()){var i=j.next();if(i==h&&!k){break}k=!k&&i=="\\"}if(!k){l(g)}return"css-string"}}return function(i,h){return tokenizer(i,h||g)}})();function c(e,d,f){return function(g){if(!e||/^\}/.test(g)){return f}else{if(d){return f+indentUnit*2}else{return f+indentUnit}}}}function a(h,g){g=g||0;var i=b(h);var f=false,e=false;var d={next:function(){var j=i.next(),k=j.style,l=j.content;if(k=="css-identifier"&&e){j.style="css-value"}if(k=="css-hash"){j.style=e?"css-colorcode":"css-identifier"}if(l=="\n"){j.indentation=c(f,e,g)}if(l=="{"){f=true}else{if(l=="}"){f=e=false}else{if(f&&l==";"){e=false}else{if(f&&k!="css-comment"&&k!="whitespace"){e=true}}}}return j},copy:function(){var k=f,l=e,j=i.state;return function(m){i=b(m,j);f=k;e=l;return d}}};return d}return{make:a,electricChars:"}"}})();

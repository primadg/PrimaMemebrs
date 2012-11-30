<html>
<head>
<script type='text/javascript' src='<?php echo base_url();?>js/jquery/jquery-1.2.6.js'></script>
<script type='text/javascript' src='<?php echo base_url();?>js/admin/global.js'></script>
<script type='text/javascript' src='<?php echo base_url();?>js/CodeMirror/js/codemirror.js'></script>
<style>
.CodeMirror-line-numbers {
background-color:yellow;
color:#AAAAAA;
font-family:monospace;
padding-right:0.3em;
padding-top:0;
text-align:right;
width:2.2em;
}
.selectedIntely{
background-color:yellow;
}
</style>
<script type='text/javascript'>
base_url="<?php echo base_url();?>";
current_controller="<?php echo isset($current_controller) ? $current_controller : "";?>";
editor=null;
$(document).ready(function () {            
	
	/* $('#php_source').keypress( function(event) {
		if(event.keyCode==13 && event.ctrlKey)
		{
			php_test();            
		}
	} ); */
	
	editor = CodeMirror.fromTextArea('php_source', {
height: "350px", 
parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js", "../../../"+current_controller+"/debug_console/tokenizerphp_js/"+Math.random(), "../contrib/php/js/parsephp.js", "../contrib/php/js/parsephphtmlmixed.js"], stylesheet: ["../js/CodeMirror/css/xmlcolors.css", "../js/CodeMirror/css/jscolors.css", "../js/CodeMirror/css/csscolors.css", "../js/CodeMirror/contrib/php/css/phpcolors.css"], 
textWrapping:false,
lineNumbers:true,
autoMatchParens:true,
cursorActivity:cursorActivity,
activeTokens:activeTokens,
path: "../js/CodeMirror/js/", 
continuousScanning: 500,
initCallback:initEditor,

});
$('#php_source').next().css('height','100%');	
});
		function initEditor()
		{
			editor.grabKeys(function(event){
				if(event.keyCode==13)
				{
					if(event.ctrlKey)
					{
						php_test();            
					}
					else if($('#intely:visible').length>0)
					{
					var s = $('#intely').find('span.selectedIntely');
					var text=s.text();
					intelySet(text);
					
					// var pos=editor.cursorPosition();
					// editor.selectLines(pos.line, (pos.character-intelyStr.length), pos.line, pos.character);
					// editor.replaceSelection(text);
					// pos=editor.cursorPosition();
					// editor.selectLines(pos.line, (pos.character+text.length), pos.line, pos.character);
					//console.dir(editor.cursorPosition());
					//editor.insertIntoLine(pos.line, pos.character, s.text());						
					}
					else
					{
						var pos=editor.cursorPosition();
						editor.insertIntoLine(pos.line, pos.character, '\n');
						editor.selectLines(editor.nextLine(pos.line),0);
						editor.reindent();
					}
				}
				if(event.keyCode==37 || event.keyCode==39)
				{
					var pos=editor.cursorPosition();
					editor.selectLines(pos.line,pos.character+(event.keyCode-38));
					$('#intely').hide();
				}
				if(event.keyCode==38)
				{
					var s = $('#intely').find('span.selectedIntely');
					if(s.length>0)
					{
						var i=$('#intely span').index(s);
						s.removeClass('selectedIntely');
						s=$('#intely span').eq(i-1);
						s.addClass('selectedIntely');
						//$('#intely').scrollTop(s.offset().top);	
					}
					else
					{
						$('#intely').find('span:last').addClass('selectedIntely');
						$('#intely').scrollTop($('#intely').find('span:last').offset().top);
					}
					//$('#intely').scrollTop(30);				
				}
				if(event.keyCode==40)
				{
					var s = $('#intely').find('span.selectedIntely');
					if(s.length>0)
					{
						var i=$('#intely span').index(s);
						s.removeClass('selectedIntely');
						s=$('#intely span').eq(i+1);
						s.addClass('selectedIntely');
						//$('#intely').scrollTop(s.offset().top);	
					}
					else
					{
						$('#intely').find('span:first').addClass('selectedIntely');
						$('#intely').scrollTop(0);	
					}
					//$('#intely').scrollTop(30);					
				}
				
				
				//console.log(event.keyCode);
			},function(c){
				//console.log(c);
				if(c==13)
				{
					return true;
				}
				if($('#intely:visible').length>0 && (c==37||c==38||c==39||c==40))
				{
					return true;
				}				
			});		
			editor.insertIntoLine(editor.nthLine(1), 'end', '\n\n');			
			editor.focus();	
			editor.selectLines(editor.nthLine(2), 0);
			var s=new Array();
			for(key in window.phpDefKeywords)
			{
				s[key]=new Array();
			}
			phpDefKeywordsArray=s;			
			//console.log(s);
		}
		
		function iKey(a, key){
			for(k in a)
			{
				if(k.toLowerCase()==key.toLowerCase())
				{
					return k;
				}			
			}
			return false;
		}
		
		function intelyGetObjectProps(path)
		{
			//console.dir(path);
			if(typeof(path)=='object'  && path.length>0)
			{
				var a=phpDefKeywordsArray;
				var p=[];
				for(k in path)
				{
					p.push(path[k]);
					var tkey=iKey(a,path[k]);
					//console.log(tkey);
					if(tkey==false || a[tkey]===false)
					{
						tkey=path[k];
						a[tkey]=false;
						var tp=p.join("->");
						php_test("echo '"+tp+"|'; echo @php_test_get_object_props_js("+tp+");",function(data){				
							var s=data.split('|');
							var p=s.shift().split('->');
							var pr=[];
							//console.log(s.shift());
							s=s.sort();
							for(i in s)
							{
								if(s[i]!='')
								{
									pr[s[i]]=false;
								}
							}
							var ts="phpDefKeywordsArray['"+p.join("']['")+"']=pr;";
							//console.log(ts);
							eval(ts);
						});
					}
					a=a[tkey];
				}
				//console.dir(a);
				return a;
			}
			else
			{
				return phpDefKeywordsArray;
			}
		}
		
		//phpObjectsDefKeywords=new Array();
		
		//function normalizeObjectPath(); 
		
		function intelyList(text)
		{
			var pos=editor.cursorPosition();
			var line=editor.lineContent(pos.line);
			line=line.substr(0,pos.character);
			//console.log(text);
			var tempPhpDefKeywords=phpDefKeywordsArray;
			if(text=='->')
			{
				var f=line.lastIndexOf('$');
				if(f>=0)
				{
					line=line.substr(f);
					if((myArray = /([\s\S]+)[-]+[>]+/.exec(line)) && typeof(myArray[1])!='undefined')
					{
						//console.log('+s.length:',s.length);
						//return intelyGetObjectProps(myArray[1].split('->'));
						var s=new Array();
						for(var k in intelyGetObjectProps(myArray[1].split('->')))
						{
							s.push(k);
						}
						s=s.sort();
						return s;
					}
				}
			}
			else
			{
				if(text.indexOf('->')==0)
				{
					var re=new RegExp("(\\$[A-Za-z0-9\\$_>-]+)"+text+"$","gi");
					text=text.substr(2);
				}
				else
				{
					var re=new RegExp("(\\$[A-Za-z0-9\\$_>-]+)->"+text+"$","gi");
				}
				
				if((myArray = re.exec(line)) && typeof(myArray[1])!='undefined')
				{
					tempPhpDefKeywords=intelyGetObjectProps(myArray[1].split('->'));			
				}
				else if(text.length<2)
				{
					return new Array();
				}
			}			
			
			var s=new Array();
			for(var k in tempPhpDefKeywords)
			{
				if(k.toLowerCase().indexOf(text.toLowerCase())==0)
				{
					s.push(k);
				}
			}
			s=s.sort();
			return s;
		}		
		
		function intelySet(text)
		{
			var pos=editor.cursorPosition();
			if(intelyStr=='->')
			{
				editor.selectLines(pos.line, pos.character);
			}
			else
			{
				editor.selectLines(pos.line, (pos.character-intelyStr.length), pos.line, pos.character);
			}
			editor.replaceSelection(text);
			pos=editor.cursorPosition();
			editor.selectLines(pos.line, (pos.character+text.length), pos.line, pos.character);		
		}
		
		function cursorActivity(p)
		{
			try{
				editorPosition=typeof(editorPosition)=='undefined' ? ($('#php_source').parent().find('iframe').offset()) : editorPosition;
				var pos=$(p).offset();
				
				var top=editorPosition.top+pos.top+parseInt($(p).height());
				var left=editorPosition.left+pos.left;
				var text=$(p).text();
				
				if(text.length>0)
				{
					if((typeof(intelyStr)=='undefined' || text!=intelyStr) && text.indexOf('(')<0 && text.indexOf(')')<0)
					{
						intelyStr=text;
						var s=intelyList(text);
						if(s.length>0)
						{
							$('#intely').css({display:'block',top:top,left:left,overflow:'visible'});
							if(s.length==1 && s[0]==text)
							{
								$('#intely').hide();
							}
							else
							{
								$('#intely').html('<span>'+s.join('</span><br/><span>')+'</span>');
								$('#intely').css('overflow','auto');
								$('#intely').height(parseInt($('#intely>span:first').height())*((s.length>5)?5:s.length)+5);
								$('#intely span').click(function(){
									intelySet($(this).text());									$('#intely').hide();
									});
							}
						}
						else
						{
							$('#intely').hide();
						}
					}
				}
				else
				{
					$('#intely').hide();
				}
				
			}catch(e){
			//console.log("ERROR:");console.dir(e);
			}
			
		}
		
		function activeTokens(p)
		{
			//console.log("activeTokens:"+$(p).html());
		}
		
		function insertModel(m)
		{
			$('#object_header').html(activateObjectPath((m=='CI')? '$CI' : '$CI->'+m));
			//$('input.loaded_models[value='+m+']').attr('checked',true);
			$('#model_members').html($('#members_'+m).html());
			//insertAtCaret($('#php_source').get(0),'$'+'CI->'+m+'->');
			//$('#php_source').focus();
		}
		
		function activateObjectPath(obj)
		{
			var res=new Array();
			obj=obj.split('->');
			for(k in obj)
			{
				res.push("<a href='javascript:loadObjectProps(\""+obj.slice(0,parseInt(k)+1).join('->')+"\")'>"+obj[k]+"</a>");			
				
			}
			return res.join(' -> ');
		}
		
		function insertModelMember(text)
		{
			var pos=editor.cursorPosition();
			editor.insertIntoLine(pos.line, pos.character, text);
			editor.focus();	
			editor.selectLines(pos.line, pos.character+text.length);			
			//insertAtCaret($('#php_source').get(0),text);
			//$('#php_source').focus();
		}
		
		function clearInput()
		{
			editor.setCode('<'+'?php\n\n');
			editor.focus();	
			editor.selectLines(editor.nthLine(2), 0);	
			//$('#php_source').val('');
		}
        
        function loadCodeHistory(par)
        {
            if(typeof(par)!='undefined')
            {
                if(par=='next')
                {
                    var cur=$('#code_history>option:selected');
                    cur.attr('selected',false);
                    cur.next('option').attr('selected',true);
                    $('#code_history').change();
                }
                if(par=='prev')
                {
                    var cur=$('#code_history>option:selected');
                    cur.attr('selected',false);
                    var prev=cur.is(':first-child') ? $('#code_history>option:last') : cur.prev('option');
                    prev.attr('selected',true);
                    $('#code_history').change();
                }
                return;
            }
            current_code=editor.getCode();
            var file=$('#code_history').val();
            if(file!='')
            {
                var code='$file=absolute_path()."/_protect/temp/'+file+'.php"; echo file_exists($file)?file_get_contents($file):"";';
                setProgressBar($('#code_history'),true);
                php_test(code,function(data){
                    editor.setCode(data);
                    setProgressBar($('#code_history'),false);
                }); 
            }
        }
        
        function setProgressBar(elem,flag)
        {
            var id=elem.attr('id');
            if(typeof(flag)!='indefined' && flag==false)
            {
                $('#progress_img_'+id).remove();
                elem.show();
            }
            else
            {
                elem.hide();
                elem.before("<img id='progress_img_"+id+"' src='"+base_url+"img/ajax-loader.gif'/>");
                $('#progress_img_'+id).height(elem.height());
                $('#progress_img_'+id).width(elem.width());
                
            }
        }
        
		function loadObjectProps(obj)
		{
			$('#object_header').html(activateObjectPath(obj));
			var text="echo php_test_print_object("+obj+",'"+obj+"->');";		
			php_test(text,function(data){				
				$('#model_members').html(data);
			});
		}
		
        function executeSelected()
        {
            var text=editor.selection();
            if(text!=='')
            {
                php_test(text);
            }
        }
        
		function php_test(text,callback)
		{
			var internalCallback=function(data){
					
					//console.log(data);
					var r=(/<benchmark>([\s\S]+)<\/benchmark>([\s\S]*)/ig).exec(data);
					//console.dir(r);
					if(r && typeof(r[2])!='undefined')
					{
						data=r[2];
					}
					
					var benchmark=false;
					if(r && typeof(r[1])!='undefined')
					{
						try{
						eval("benchmark="+r[1]+";");
						//console.dir(benchmark);
						var b="<span style='color:blue;'>Executed: "+
						benchmark['elapsed_time']+" sec / "+
						benchmark['memory_usage']+" bytes ("+
						benchmark['execution_time']+
						")</span><br/>";
						$('#exec_time_span').html(b);
						}catch(e){}
					}
					else
					{
					$('#exec_time_span').html('<span style="color:red;">[UNNORMAL OUTPUT]</span><br/>');
					}
					
					$('#php_result').html('<span style="color:grey;">< Result in new window! ></span>');
					
					if($('#in_new_window:checked').length>0 || (!r && (/[\s\S]*<html>[\s\S]+<\/html>[\s\S]*/ig).exec(data)))
					{
						
						if(window.php_console_win==undefined||window.php_console_win.closed)
						{
							window.php_console_win = window.open();
						}
						window.php_console_win.document.write (data);
						if(r && benchmark)
						{
							window.php_console_win.document.title=benchmark['execution_time'];
						}
						else
						{
							window.php_console_win.document.title="UNNORMAL";
						}
						window.php_console_win.document.close();
					}
					else
					{
						$('#php_result').html(data);					
					}					
				}
			
			
			//text=(typeof(text)=='undefined') ? $('#php_source').val() : text;
			text=(typeof(text)=='undefined') ? editor.getCode() : text;
			var is_callback = (typeof(callback)=='function') ? 1 : 0;			
			callback = (typeof(callback)!='function') ?  internalCallback : callback;		
			var m=new Array();
			$('input.loaded_models:checked').each(function(){
				m.push($(this).val());		
			});
			var models=m.join('&');
			//console.log(models);
			//return;
			$.post(base_url+current_controller+'/debug_console', 
			{'is_callback':is_callback,'test': text,'models':models},callback);
		}
</script>

</head>
<body>
<table style='width:100%;height:400px;'>
<tr>
<td style='border:1px solid orange;'>
<!-- Controllers list -->
<?php  foreach($controllers as $c){ 
if(strtolower($current_controller)==strtolower($c)){ ?>
<a style='color:red;' href='<?php echo base_url().$c;?>/debug_console'>[<?php echo $c?>]</a>&nbsp;
<?php  }else{ ?>
<a href='<?php echo base_url().$c;?>/debug_console'><?php echo $c?></a>&nbsp;
<?php  } } ?>
</td>
<!-- Object path -->
<td id='object_header' style='vertical-align:top;border:1px solid orange;' colspan=2>
<a href="javascript:loadObjectProps('$CI')">$CI</a>
</td>
</tr>

<tr>
<td style='border:1px solid orange;'>
<!-- Input textarea -->		
<textarea style='width:100%;height:100%;' id='php_source'><?php echo '<?php'?></textarea>
</td>
<td width='200px'>
<!-- Current controller string -->
<div style='width:200px;height:400px;overflow:auto;border:1px solid orange;'>
&nbsp;<a href="javascript:insertModel('CI')">CI(<?php echo $current_controller?>)</a>
<span style='display:none;' id='members_CI' ><?php echo $controller_props;?></span>
<br/>
<!-- Models string -->
<?php  foreach($all_models as $m) { ?>
<input class="loaded_models" type="checkbox"  value="<?php echo $m?>" 
<?php echo in_iarray($m,$permanent_models) ? "disabled" : "";?> 
<?php echo (in_iarray($m,$permanent_models) || in_iarray($m,$loaded_models)) ? "checked" : "";?> 
/>&nbsp;
<a href="javascript:insertModel('<?php echo $m?>')"><?php echo $m?></a>
<span style="display:none;" id="members_<?php echo $m?>" >
<?php echo php_test_print_object($CI->$m,'$CI->'.$m.'->')?>
</span>
<br/>
<?php  } ?>
</div>

</td>
<!-- Property box -->
<td width='200px'>
<div style='width:200px;height:400px;overflow:auto;border:1px solid orange;' id='model_members'><?php echo $controller_props;?>
</div>
</td>
</tr>
</table>
<br/>
<!-- Buttons -->
&nbsp;&nbsp;<a href="javascript:loadCodeHistory('prev')"><<</a>&nbsp;&nbsp;<select onchange="loadCodeHistory()" id="code_history" style="width:150px;">
<option value="">CODE HISTORY</option>
<?php  foreach($code_history as $v){ ?>
<option value="<?php echo $v?>"><?php echo $v?></option>
<?php  } ?>
</select>&nbsp;&nbsp;
<a href="javascript:loadCodeHistory('next')">>></a>&nbsp;&nbsp;
<input type='button' value='Clear input' onclick='clearInput();'/>&nbsp;&nbsp;
<input type='button' value='Execute' onclick='$("#code_history>option:first").attr("selected",true); php_test()'/>&nbsp;&nbsp;
<input type='button' value='Execute selected' onclick='executeSelected();'/>&nbsp;&nbsp;
<input type='checkbox' id="in_new_window" value='1' />&nbsp;New window&nbsp;&nbsp;
<span id='exec_time_span'></span>
<br/>
<!-- Output box -->
<div style='width:100%;height:50%;' id='php_result'>
</div>
<div id="intely" style="padding-left:2px;padding-right:2px;display:none;position:absolute;z-index:99;border:1px solid grey;background-color:white;overflow-y:auto;">fffffff</div>
</body>
</html>

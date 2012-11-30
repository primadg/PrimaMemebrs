<?php
function tokenizerphp_js()
{
	?>
	/*
Copyright (c) 2008-2009 Yahoo! Inc.  All rights reserved.
The copyrights embodied in the content of this file are licensed by
Yahoo! Inc. under the BSD (revised) open source license

@author Vlad Dan Dascalescu <dandv@yahoo-inc.com>


Tokenizer for PHP code

References:
+ http://php.net/manual/en/reserved.php
+ http://php.net/tokens
+ get_defined_constants(), get_defined_functions(), get_declared_classes()
	executed on a realistic (not vanilla) PHP installation with typical LAMP modules.
	Specifically, the PHP bundled with the Uniform Web Server (www.uniformserver.com).

*/


	// add the forEach method for JS engines that don't support it (e.g. IE)
	// code from https://developer.mozilla.org/En/Core_JavaScript_1.5_Reference:Objects:Array:forEach
	if (!Array.prototype.forEach)
	{
		Array.prototype.forEach = function(fun /*, thisp*/)
		{
			var len = this.length;
			if (typeof fun != "function")
			throw new TypeError();

			var thisp = arguments[1];
			for (var i = 0; i < len; i++)
			{
				if (i in this)
				fun.call(thisp, this[i], i, this);
			}
		};
	}


	var tokenizePHP = (function() {
		/* A map of PHP's reserved words (keywords, predefined classes, functions and
	constants. Each token has a type ('keyword', 'operator' etc.) and a style.
	The style corresponds to the CSS span class in phpcolors.css.

	Keywords can be of three types:
	a - takes an expression and forms a statement - e.g. if
	b - takes just a statement - e.g. else
	c - takes an optinoal expression, but no statement - e.g. return
	This distinction gives the parser enough information to parse
	correct code correctly (we don't care that much how we parse
	incorrect code).

	Reference: http://us.php.net/manual/en/reserved.php
*/
		var keywords = function(){
			function token(type, style){
				return {type: type, style: style};
			}
			var result = {};

			// for each(var element in ["...", "..."]) can pick up elements added to
			// Array.prototype, so we'll use the loop structure below. See also
			// http://developer.mozilla.org/en/Core_JavaScript_1.5_Reference/Statements/for_each...in

			// keywords that take an expression and form a statement
			["if", "elseif", "while", "declare"].forEach(function(element, index, array) {
				result[element] = token("keyword a", "php-keyword");
			});

			// keywords that take just a statement
			["do", "else", "try" ].forEach(function(element, index, array) {
				result[element] = token("keyword b", "php-keyword");
			});

			// keywords that take an optional expression, but no statement
			["return", "break", "continue",  // the expression is optional
			"new", "clone", "throw"  // the expression is mandatory
			].forEach(function(element, index, array) {
				result[element] = token("keyword c", "php-keyword");
			});

			["__CLASS__", "__DIR__", "__FILE__", "__FUNCTION__", "__METHOD__", "__NAMESPACE__"].forEach(function(element, index, array) {
				result[element] = token("atom", "php-compile-time-constant");
			});

			["true", "false", "null"].forEach(function(element, index, array) {
				result[element] = token("atom", "php-atom");
			});

			["and", "or", "xor", "instanceof"].forEach(function(element, index, array) {
				result[element] = token("operator", "php-keyword php-operator");
			});

			["class", "interface"].forEach(function(element, index, array) {
				result[element] = token("class", "php-keyword");
			});
			["namespace", "use", "extends", "implements"].forEach(function(element, index, array) {
				result[element] = token("namespace", "php-keyword");
			});

			// reserved "language constructs"... http://php.net/manual/en/reserved.php
			[ "die", "echo", "empty", "exit", "eval", "include", "include_once", "isset",
			"list", "require", "require_once", "return", "print", "unset",
			"array" // a keyword rather, but mandates a parenthesized parameter list
			].forEach(function(element, index, array) {
				result[element] = token("t_string", "php-reserved-language-construct");
			});

			result["switch"] = token("switch", "php-keyword");
			result["case"] = token("case", "php-keyword");
			result["default"] = token("default", "php-keyword");
			result["catch"] = token("catch", "php-keyword");
			result["function"] = token("function", "php-keyword");

			// http://php.net/manual/en/control-structures.alternative-syntax.php must be followed by a ':'
			["endif", "endwhile", "endfor", "endforeach", "endswitch", "enddeclare"].forEach(function(element, index, array) {
				result[element] = token("default", "php-keyword");
			});

			result["const"] = token("const", "php-keyword");

			["abstract", "final", "private", "protected", "public", "global", "static"].forEach(function(element, index, array) {
				result[element] = token("modifier", "php-keyword");
			});
			result["var"] = token("modifier", "php-keyword deprecated");

			result["foreach"] = token("foreach", "php-keyword");
			result["as"] = token("as", "php-keyword");
			result["for"] = token("for", "php-keyword");

			// PHP built-in functions - output of get_defined_functions()["internal"]
			['<?php 
			php_test_get_all_models();
	$defined_functions=get_defined_functions();
    $defined_functions['internal']=preg_grep("/^[A-Za-z0-9_-]+$/",$defined_functions['internal']);    
    echo implode("', '",$defined_functions['internal'])."', '".implode("', '",$defined_functions['user']);?>', '_' // alias for gettext()
			].forEach(function(element, index, array) {
				result[element] = token("t_string", "php-predefined-function");
			});

			// output of get_defined_constants(). Differs significantly from http://php.net/manual/en/reserved.constants.php
			['<?php echo implode("', '",array_keys(get_defined_constants()));?>'].forEach(function(element, index, array) {
				result[element] = token("atom", "php-predefined-constant");
			});

			// PHP declared classes - output of get_declared_classes(). Differs from http://php.net/manual/en/reserved.classes.php
			['<?php echo implode("', '",get_declared_classes());?>'].forEach(function(element, index, array) {
				result[element] = token("t_string", "php-predefined-class");
			});
			//adding php variables to intely
			window.parent.phpDefKeywords=result;
			return result;

		}();

		// Helper regexp matchers.
		var isOperatorChar = matcher(/[+*&%\/=<>!?.|-]/);
		var isDigit = matcher(/[0-9]/);
		var isHexDigit = matcher(/[0-9A-Fa-f]/);
		var isWordChar = matcher(/[\w\$_]/);

		// Wrapper around phpToken that helps maintain parser state (whether
		// we are inside of a multi-line comment)
		function phpTokenState(inside) {
			return function(source, setState) {
				var newInside = inside;
				var type = phpToken(inside, source, function(c) {newInside = c;});
				if (newInside != inside)
				setState(phpTokenState(newInside));
				return type;
			};
		}

		// The token reader, inteded to be used by the tokenizer from
		// tokenize.js (through phpTokenState). Advances the source stream
		// over a token, and returns an object containing the type and style
		// of that token.
		function phpToken(inside, source, setInside) {
			function readHexNumber(){
				source.next();  // skip the 'x'
				source.nextWhile(isHexDigit);
				return {type: "number", style: "php-atom"};
			}

			function readNumber() {
				source.nextWhile(isDigit);
				if (source.equals(".")){
					source.next();
					source.nextWhile(isDigit);
				}
				if (source.equals("e") || source.equals("E")){
					source.next();
					if (source.equals("-"))
					source.next();
					source.nextWhile(isDigit);
				}
				return {type: "number", style: "php-atom"};
			}
			// Read a word and look it up in the keywords array. If found, it's a
			// keyword of that type; otherwise it's a PHP T_STRING.
			function readWord() {
				source.nextWhile(isWordChar);
				var word = source.get();
				var known = keywords.hasOwnProperty(word) && keywords.propertyIsEnumerable(word) && keywords[word];
				// since we called get(), tokenize::take won't get() anything. Thus, we must set token.content
				return known ? {type: known.type, style: known.style, content: word} :
				{type: "t_string", style: "php-t_string", content: word};
			}
			function readVariable() {
				source.nextWhile(isWordChar);
				var word = source.get();
				// in PHP, '$this' is a reserved word, but 'this' isn't. You can have function this() {...}
				if (word == "$this")
				return {type: "variable", style: "php-keyword", content: word};
				else
				return {type: "variable", style: "php-variable", content: word};
			}

			// Advance the stream until the given character (not preceded by a
			// backslash) is encountered, or the end of the line is reached.
			function nextUntilUnescaped(source, end) {
				var escaped = false;
				var next;
				while(!source.endOfLine()){
					var next = source.next();
					if (next == end && !escaped)
					return false;
					escaped = next == "\\";
				}
				return escaped;
			}

			function readSingleLineComment() {
				// read until the end of the line or until ?>, which terminates single-line comments
				// `<?php echo 1; // comment ?> foo` will display "1 foo"
				while(!source.lookAhead("?>") && !source.endOfLine())
				source.next();
				return {type: "comment", style: "php-comment"};
			}
			/* For multi-line comments, we want to return a comment token for
	every line of the comment, but we also want to return the newlines
	in them as regular newline tokens. We therefore need to save a
	state variable ("inside") to indicate whether we are inside a
	multi-line comment.
	*/

			function readMultilineComment(start){
				var newInside = "/*";
				var maybeEnd = (start == "*");
				while (true) {
					if (source.endOfLine())
					break;
					var next = source.next();
					if (next == "/" && maybeEnd){
						newInside = null;
						break;
					}
					maybeEnd = (next == "*");
				}
				setInside(newInside);
				return {type: "comment", style: "php-comment"};
			}

			// similar to readMultilineComment and nextUntilUnescaped
			// unlike comments, strings are not stopped by ?>
			function readMultilineString(start){
				var newInside = start;
				var escaped = false;
				while (true) {
					if (source.endOfLine())
					break;
					var next = source.next();
					if (next == start && !escaped){
						newInside = null;  // we're outside of the string now
						break;
					}
					escaped = (next == "\\");
				}
				setInside(newInside);
				return {
type: newInside == null? "string" : "string_not_terminated",
style: (start == "'"? "php-string-single-quoted" : "php-string-double-quoted")
				};
			}

			// http://php.net/manual/en/language.types.string.php#language.types.string.syntax.heredoc
			// See also 'nowdoc' on the page. Heredocs are not interrupted by the '?>' token.
			function readHeredoc(identifier){
				var token = {};
				if (identifier == "<<<") {
					// on our first invocation after reading the <<<, we must determine the closing identifier
					if (source.equals("'")) {
						// nowdoc
						source.nextWhile(isWordChar);
						identifier = "'" + source.get() + "'";
						source.next();  // consume the closing "'"
					} else if (source.applies(matcher(/[A-Za-z_]/))) {
						// heredoc
						source.nextWhile(isWordChar);
						identifier = source.get();
					} else {
						// syntax error
						setInside(null);
						return { type: "error", style: "syntax-error" };
					}
					setInside(identifier);
					token.type = "string_not_terminated";
					token.style = identifier.charAt(0) == "'"? "php-string-single-quoted" : "php-string-double-quoted";
					token.content = identifier;
				} else {
					token.style = identifier.charAt(0) == "'"? "php-string-single-quoted" : "php-string-double-quoted";
					// consume a line of heredoc and check if it equals the closing identifier plus an optional semicolon
					if (source.lookAhead(identifier, true) && (source.lookAhead(";\n") || source.endOfLine())) {
						// the closing identifier can only appear at the beginning of the line
						// note that even whitespace after the ";" is forbidden by the PHP heredoc syntax
						token.type = "string";
						token.content = source.get();  // don't get the ";" if there is one
						setInside(null);
					} else {
						token.type = "string_not_terminated";
						source.nextWhile(matcher(/[^\n]/));
						token.content = source.get();
					}
				}
				return token;
			}

			function readOperator() {
				source.nextWhile(isOperatorChar);
				return {type: "operator", style: "php-operator"};
			}
			function readStringSingleQuoted() {
				var endBackSlash = nextUntilUnescaped(source, "'", false);
				setInside(endBackSlash ? "'" : null);
				return {type: "string", style: "php-string-single-quoted"};
			}
			function readStringDoubleQuoted() {
				var endBackSlash = nextUntilUnescaped(source, "\"", false);
				setInside(endBackSlash ? "\"": null);
				return {type: "string", style: "php-string-double-quoted"};
			}

			// Fetch the next token. Dispatches on first character in the
			// stream, or first two characters when the first is a slash.
			switch (inside) {
			case null:
			case false: break;
			case "'":
			case "\"": return readMultilineString(inside);
			case "/*": return readMultilineComment(source.next());
			default: return readHeredoc(inside);
			}
			var ch = source.next();
			if (ch == "'" || ch == "\"")
			return readMultilineString(ch)
			else if (ch == "#")
			return readSingleLineComment();
			else if (ch == "$")
			return readVariable();
			else if (ch == ":" && source.equals(":")) {
				source.next();
				// the T_DOUBLE_COLON can only follow a T_STRING (class name)
				return {type: "t_double_colon", style: "php-operator"}
			}
			// with punctuation, the type of the token is the symbol itself
			else if (/[\[\]{}\(\),;:]/.test(ch)) {
				return {type: ch, style: "php-punctuation"};
			}
			else if (ch == "0" && (source.equals("x") || source.equals("X")))
			return readHexNumber();
			else if (isDigit(ch))
			return readNumber();
			else if (ch == "/") {
				if (source.equals("*"))
				{ source.next(); return readMultilineComment(ch); }
				else if (source.equals("/"))
				return readSingleLineComment();
				else
				return readOperator();
			}
			else if (ch == "<") {
				if (source.lookAhead("<<", true)) {
					setInside("<<<");
					return {type: "<<<", style: "php-punctuation"};
				}
				else
				return readOperator();
			}
			else if (isOperatorChar(ch))
			return readOperator();
			else
			return readWord();
		}

		// The external interface to the tokenizer.
		return function(source, startState) {
			return tokenizer(source, startState || phpTokenState(false, true));
		};
	})();
	
	var phpObjectsDefKeywords=new Array();
	phpObjectsDefKeywords['$ci']=phpObjectsDefKeywords['$this']="<?php
	$CI=&get_instance();
	echo @php_test_get_object_props_js($CI);
	?>".split("|");
	window.parent.phpObjectsDefKeywords=phpObjectsDefKeywords;
	<?php
	echo "/*";
	

	
	
	
	echo "*/";
	
	
}

?>

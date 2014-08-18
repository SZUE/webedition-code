<?php
/**
 * webEdition CMS
 *
 * $Rev$
 * $Author$
 * $Date$
 *
 * This source is part of webEdition CMS. webEdition CMS is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the textfile
 * webEdition/licenses/webEditionCMS/License.txt
 *
 * @category   webEdition
 * @package none
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */
include_once(WE_SPELLCHECKER_MODULE_PATH . '/spellchecker.conf.inc.php');

we_html_tools::protect();
echo we_html_tools::getHtmlTop(g_l('modules_glossary', '[glossary_check]')) . STYLESHEET;

// Transaction
if(!($Transaction = we_base_request::_(we_base_request::TRANSACTION, 'we_cmd', 0, 2))){
	die('No Transaction');
}

//
// ---> Main Frame
//

$cmd3 = we_base_request::_(we_base_request::STRING, 'we_cmd', '', 3);

switch(we_base_request::_(we_base_request::STRING, 'we_cmd', 'frameset', 1)){
	default:
	case 'frameset':

		$ClassName = $_SESSION['weS']['we_data'][$Transaction][0]['ClassName'];

		$we_doc = new $ClassName();
		$we_doc->we_initSessDat($_SESSION['weS']['we_data'][$Transaction]);

		$Language = $we_doc->Language;

		$DictBase = getServerUrl() . WE_SPELLCHECKER_MODULE_DIR . 'dict/';

		$LanguageDict = null;
		if(isset($spellcheckerConf['lang']) && is_array($spellcheckerConf['lang'])){
			$LanguageDict = array_search($Language, $spellcheckerConf['lang']);
			$LanguageDict = in_array($LanguageDict, $spellcheckerConf['active']) ? $LanguageDict : null;
		}
		if(is_null($LanguageDict)){
			$LanguageDict = $spellcheckerConf['default'];
		}

		$UserDict = WE_SPELLCHECKER_MODULE_PATH . '/dict/' . $_SESSION['user']['Username'] . '@' . $_SERVER['SERVER_NAME'] . '.dict';

		$AppletCode = we_html_element::htmlApplet(array(
				'name' => "spellchecker",
				'code' => "LeSpellchecker.class",
				'archive' => "lespellchecker.jar",
				'codebase' => getServerUrl() . WE_SPELLCHECKER_MODULE_DIR,
				'width' => 2,
				'height' => 2,
				'id' => "applet",
				'style' => "visibility: hidden",
				), '
<param name="code" value="LeSpellchecker.class"/>
<param name="archive" value="lespellchecker.jar"/>
<param name="type" value="application/x-java-applet;version=1.1"/>
<param name="dictBase" value="' . $DictBase . '"/>
<param name="dictionary" value="' . $LanguageDict . '"/>
<param name="debug" value="off"/>
<param name="user" value="' . $_SESSION['user']['Username'] . '@' . $_SERVER['SERVER_NAME'] . '"/>
<param name="udSize" value="' . (is_file($UserDict) ? filesize($UserDict) : '0') . '"/>'
		);

		//
		// --> get the content
		//

	$SrcBody = "";
		foreach($we_doc->elements as $key => $name){
			switch($key){
				case 'data':
				case 'Title':
				case 'Description':
				case 'Keywords':
				case 'Charset':
				default:
					if(isset($we_doc->elements[$key]['type']) && (
						$we_doc->elements[$key]['type'] == "txt" || $we_doc->elements[$key]['type'] == "input"
						)
					){
						$SrcBody .= $we_doc->elements[$key]['dat'] . " ";
					}
			}
		}

		/*
		  This is the fastest variant
		 */
		// split the source into tag and non-tag pieces
		$Pieces = preg_split('!(<[^>]*>)!', $SrcBody, -1, PREG_SPLIT_DELIM_CAPTURE);

		// replace words in non-tag pieces
		$ReplBody = "";
		$Before = " ";
		foreach($Pieces as $Piece){
			if(strpos($Piece, '<') !== 0 && stripos($Before, '<script') === false){
				$ReplBody .= $Piece . " ";
			}
			$Before = $Piece;
		}

		$Text = preg_replace("=<br(>|([\s/][^>]*)>)\r?\n?=i", "\n", $ReplBody);
		$Text = str_replace(array("\r\n", "\n"), ' ', $Text);
		$Text = str_replace("\"", "\\\"", $ReplBody);
		$Text = preg_replace("=<br(>|([\s/][^>]*)>)\r?\n?=i", "\n", $Text);
		$Text = str_replace(array("\r\n", "\n"), ' ', $Text);
		$Text = str_replace("&nbsp;", " ", $Text);
		$Text = preg_replace(array("/[\t]+/", "/[ ]+/"), " ", $Text);

		$ExceptionListFilename = we_glossary_glossary::getExceptionFilename($Language);

		if(!file_exists($ExceptionListFilename)){
			we_glossary_glossary::editException($Language, "");
		}

		$ExceptionList = we_glossary_glossary::getException($Language);
		$PublishedEntries = we_glossary_glossary::getEntries($Language, 'published');
		foreach($PublishedEntries as $Key => $Value){
			$ExceptionList[] = $Value['Text'];
		}
		$UnpublishedEntries = we_glossary_glossary::getEntries($Language, 'unpublished');
		$List = array();
		foreach($UnpublishedEntries as $Key => $Value){
			if($UnpublishedEntries[$Key]['Type'] != we_glossary_glossary::TYPE_LINK){
				$List[] = $Value;
			}
		}

		echo we_html_element::jsScript(JS_DIR . 'keyListener.js');
		?>
		<script type="text/javascript"><!--

			function applyOnEnter() {
				top.frames.glossarycheck.checkForm();
				return true;

			}

			function closeOnEscape() {
				return true;

			}

			var orginal;
			var retryjava = 0;
			var retry = 0;
			var to;

			top.opener.top.toggleBusy();

			function customAdapter() {
				this.innerHTML;

				this.getSelectedText = function() {
				}

			}

			function setDialog() {

		<?php
		foreach($List as $Key => $Value){
			$Value['Text'] = str_replace(array("\r", "\n"), '', $Value['Text']);
			$TextReplaced = preg_replace('-(^|\s|[!"#$%&\'()*+,\-./:;=?@[\\]^_`{\|}~])(' . preg_quote($Value['Text'], '-') . ')(\s|[!"#$%&\'()*+,\-./:;=?@[\\]^_`{\|}~]|$)-', '${1}${3}', $Text);
			$Replaced = (trim($TextReplaced) != trim($Text));
			$Text = trim($TextReplaced);
			if($Replaced){
				echo "top.frames.glossarycheck.addPredefinedRow('" . $Value['Text'] . "',new Array(),'" . $Value['Type'] . "','" . $Value['Title'] . "','" . $Value['Lang'] . "');\n";
			}
		}

		foreach($ExceptionList as $Key => $Value){
			$Value = str_replace(array("\r", "\n"), '', $Value);
			$Text = preg_replace('-(^|\s|[!"#$%&\'()*+,\-./:;=?@[\\]^_`{\|}~])(' . preg_quote($Value, '-') . ')(\s|[!"#$%&\'()*+,\-./:;=?@[\\]^_`{\|}~]|$)-', '${1}${3}', $Text);
		}
		?>
				orginal = "<?php echo $Text; ?>";
				window.setTimeout("spellcheck()", 1000);

			}

			function spellcheck() {
				retry = 0;
				if (document.spellchecker.isReady()) {
					top.frames.glossarycheck.document.getElementById("statusText").innerHTML = "<?php print g_l('modules_glossary', '[checking]'); ?>...";
					var text = getTextOnly(orginal);
					document.spellchecker.check(text);
					window.setTimeout("findNext()", 2000);
				} else {
					if (retryjava < 5) {
						window.setTimeout("spellcheck()", 1000);
						retryjava++;
					} else {
						fadeout("spinner", 80, 10, 10);
						top.frames.glossarycheck.noJava();
					}
				}
			}


			function findNext() {
				if (document.spellchecker.isReady()) {
					if (document.spellchecker.isReady()) {
						if (document.spellchecker.nextSuggestion()) {
							temp = document.spellchecker.getMisspelledWord();
							var suggs = document.spellchecker.getSuggestions();
							suggs = suggs + "";
							var suggA = suggs.split("|");
							top.frames.glossarycheck.addRow(temp, suggA);

							clearTimeout(to);
							to = window.setTimeout("findNext()", 250);

						} else if (document.spellchecker.isWorking()) {
							clearTimeout(to);
							to = window.setTimeout("findNext()", 250);

						} else if (retry < 7) {
							clearTimeout(to);
							to = window.setTimeout("findNext()", 250);
							retry++;

						} else {
							if (top.frames.glossarycheck.document.getElementById("spinner").style.display != "none") {
								fadeout("spinner", 80, 10, 10);
								top.frames.glossarycheck.activateButtons();
							}
							retry = 0;
							clearTimeout(to);
						}

					}

				} else {
					window.setTimeout("spellcheck()", 250);

				}

			}

			function add() {
				document.spellchecker.addWords(top.frames.glossarycheck.AddWords);
			}

			function getTextOnly(text) {
				var newtext = text.replace(/(<([^>]+)>)/ig, " ");
				newtext = newtext.replace(/\&([^; ]+);/ig, " ");
				newtext = newtext.replace("&amp;", "&");

				return newtext;

			}

			function fade(id, opacity) {
				var styleObj = top.frames.glossarycheck.document.getElementById(id).style;
				styleObj.opacity = (opacity / 100);
				styleObj.MozOpacity = (opacity / 100);
				styleObj.KhtmlOpacity = (opacity / 100);
				styleObj.filter = "alpha(opacity=" + opacity + ")";
			}

			function fadeout(id, from, step, speed) {
				fade(id, from);
				if (from === 0) {
					top.frames.glossarycheck.document.getElementById(id).style.display = "none";
				} else {
					window.setTimeout("fadeout(\"" + id + "\"," + (from - step) + "," + step + "," + speed + ")", speed);
				}
			}
			//-->
		</script>
		<style type="text/css">
			#applet {
				top: 0px;
				left: 0px;
				z-index: -10;
			}
		</style>
		</head>

		<body style="margin:0px;padding:0px;">
			<form name="we_form" action="<?php echo WEBEDITION_DIR; ?>we_cmd.php" method="post">

				<?php
				if(($cnt = count($_REQUEST['we_cmd'])) > 3){
					for($i = 3; $i < $cnt; $i++){
						echo '<input type="hidden" name="we_cmd[' . ($i - 3) . ']" value="' . we_base_request::_(we_base_request::RAW, 'we_cmd', '', $i) . '">';
					}
				}
				?>

				<script type="text/javascript"><!--
			function we_save_document() {
						top.opener._showGlossaryCheck = 0;
						top.opener.we_save_document();
						top.close();
					}
					function we_reloadEditPage() {
						top.opener.top.we_cmd('switch_edit_page', <?php echo $we_doc->EditPageNr; ?>, '<?php echo $Transaction; ?>', 'save_document');
					}
					//-->
				</script>
				<?php
				echo '<iframe id="glossarycheck" name="glossarycheck" frameborder="0" src="' . WEBEDITION_DIR . 'we_cmd.php?we_cmd[0]=' . we_base_request::_(we_base_request::RAW, 'we_cmd', '',0). '&we_cmd[1]=prepare&we_cmd[2]=' . we_base_request::_(we_base_request::RAW, 'we_cmd', '',2) . (($cmd3=we_base_request::_(we_base_request::RAW, 'we_cmd', false,3))!==false ? '&we_cmd[3]=' . $cmd3 : '' ) . '" width="730px" height="400px" style="overflow: hidden;"></iframe>' .
				$AppletCode;

//
// ---> Form with all unidentified words
//
				break;
			case 'prepare':

				$configFile = WE_GLOSSARY_MODULE_PATH . we_glossary_replace::configFile;
				if(!file_exists($configFile) || !is_file($configFile)){
					we_glossary_settingControl::saveSettings(true);
				}
				include($configFile);

				//FIXME: these values should be obtained from global settings
				$Languages = array(
					'de' => 'de',
					'en' => 'en',
					'es' => 'es',
					'fi' => 'fi',
					'ru' => 'ru',
					'nl' => 'nl',
					'pl' => 'pl',
				);

				$Modes = array();
				if((
					isset($_SESSION['prefs']['force_glossary_action']) && $_SESSION['prefs']['force_glossary_action'] == 0
					) && $cmd3 != "checkOnly"
				){
					$Modes[''] = g_l('modules_glossary', '[please_choose]');
				}
				$Modes['ignore'] = g_l('modules_glossary', '[ignore]');
				if(permissionhandler::hasPerm("NEW_GLOSSARY")){
					$Modes[we_glossary_glossary::TYPE_ABBREVATION] = g_l('modules_glossary', '[abbreviation]');
					$Modes[we_glossary_glossary::TYPE_ACRONYM] = g_l('modules_glossary', '[acronym]');
					$Modes[we_glossary_glossary::TYPE_FOREIGNWORD] = g_l('modules_glossary', '[foreignword]');
					$Modes[we_glossary_glossary::TYPE_TEXTREPLACE] = g_l('modules_glossary', '[textreplacement]');
				}
				if(permissionhandler::hasPerm("EDIT_GLOSSARY_DICTIONARY")){
					$Modes['exception'] = g_l('modules_glossary', '[to_exceptionlist]');
				}
				$Modes['correct'] = g_l('modules_glossary', '[correct_word]');
				$Modes['dictionary'] = g_l('modules_glossary', '[to_dictionary]');
				?>
				<style type="text/css">

					#spinner {
						width: 330px;
						height: 20px;
						padding: 0px;
						z-index: 1;
						position: absolute;
						left:480px;
						top:15px;
					}

					#statusText {
						width: 300px;
						line-height: 15px;
						vertical-align: middle;
						height: 20px;
						text-align: left;
					}

					#statusImage {
						float: left;
						width: 20px;
						height: 20px;
						padding-right: 5px;
					}
				</style>
				<?php echo we_html_element::jsScript(JS_DIR . 'weCombobox.js'); ?>
				<script type="text/javascript"><!--

					var table;
					var counter = 0;
					var Combobox = new weCombobox();


					function init() {
						table = document.getElementById('unknown');
						top.setDialog();
					}


					function getTextColumn(text, colspan) {
						text = text + '';
						var td = document.createElement('td');
						td.setAttribute('style', 'overflow: hidden;');
						td.setAttribute('title', text);
						if (colspan > 1) {
							td.setAttribute("colspan", colspan);
							td.setAttribute("align", "center");
							td.setAttribute("valign", "middle");
							td.setAttribute("height", "220");
						}
						if (text !== "<?php echo g_l('modules_glossary', '[all_words_identified]'); ?>" && text !== "<?php echo g_l('modules_glossary', '[no_java]'); ?>") {
							text = shortenWord(text, 20);
						}

						td.appendChild(document.createTextNode(text));
						return td;
					}

					function shortenWord(text, chars) {
						var newText = "";
						var textlength = text.length;
						if (textlength > chars) {
							var showPointsFrom = Math.round(chars / 2) - 1;
							var showPointsTo = Math.round(chars / 2) + 1;
							for (var i = 0; i < chars; i++) {
								if (i < showPointsFrom) {
									newText += text.charAt(i);
								}
								if (i >= showPointsFrom && i <= showPointsTo) {
									newText += ".";
								}
								if (i > showPointsTo) {
									var pos = textlength - (chars - i);
									newText += text.charAt(pos);
								}
							}
						} else {
							newText = text;
						}

						return newText;
					}

					function getInnerColumn(html) {
						var td = document.createElement('td');
						td.innerHTML = html;
						return td;
					}

					function getImageColumn(src, width, height) {
						var td = document.createElement('td');
						td.innerHTML = '<img src="' + src + '" width="' + width + '" height="' + height + '" />';
						return td;
					}


					function getActionColumn(word, type) {
						var td = document.createElement('td');
						var html;

						html = '<select class="defaultfont" name="item[' + word + '][type]" size="1" id="type_' + counter + '" onchange="disableItem(' + counter + ', this.value);" style="width: 140px">'
		<?php
		foreach($Modes as $Key => $Value){
			echo "		+	'<option value=\"" . $Key . "\"' + (type == '" . $Key . "' ? ' selected=\"selected\"' : '') + '>" . $Value . "</option>'";
		}
		?>
						+ '</select>';

						td.innerHTML = html;
						return td;
					}


					function getTitleColumn(word, suggestions, title) {
						var td = document.createElement('td');
						var html;

						html = '<input class="wetextinput" type="text" name="item[' + word + '][title]" size="24" value="' + title + '" maxlength="100" id="title_' + counter + '" style="display: inline; width: 200px;" disabled=\"disabled\" " />'
										+ '<select class="defaultfont" name="suggest_' + counter + '" id="suggest_' + counter + '" size="1" onchange="document.getElementById(\'title_' + counter + '\').value=this.value;this.value=\'\';" disabled=\"disabled\" style="width: 200px; display: none;">'
										+ '<option value="' + word + '">' + word + '</option>'
										+ '<optgroup label="<?php echo g_l('modules_glossary', '[change_to]'); ?>">'
										+ '<option value="">-- <?php echo g_l('modules_glossary', '[input]'); ?> --</option>'
										+ '</optgroup>';
						if (suggestions.length > 1) {
							html += '<optgroup label="<?php echo g_l('modules_glossary', '[suggestions]'); ?>">';
							for (i = 0; i < suggestions.length; i++) {
								if (suggestions[i] !== '') {
									html += '<option value="' + suggestions[i] + '">' + suggestions[i] + '</option>';
								}
							}
							html + '</optgroup>';
						}
						html + '</select>';

						td.innerHTML = html;

						return td;
					}


					function getLanguageColumn(word, lang) {
						var td = document.createElement('td');
						td.innerHTML = '<select class="defaultfont" name="item[' + word + '][lang]" size="1" id="lang_' + counter + '" disabled=\"disabled\" style="width: 100px">'
										+ '<option value="' + lang + '">' + lang + '</option>'
										+ '<optgroup label="<?php echo g_l('modules_glossary', '[change_to]'); ?>">'
										+ '<option value="">-- <?php echo g_l('modules_glossary', '[input]'); ?> --</option>'
										+ '</optgroup>'
										+ '<optgroup label="<?php echo g_l('modules_glossary', '[languages]'); ?>">'

		<?php
		foreach($Languages as $Key => $Value){
			echo "		+	'<option value=\"" . $Key . "\">" . $Value . "</option>'";
		}
		?>
						+ '</optgroup>'
										+ '</select>';

						return td;
					}


					function getColumn(text) {
						var td = document.createElement('td');
						td.appendChild(document.createTextNode(text));
						return td;
					}


					function addRow(word, suggestions) {
						var tr = document.createElement('tr');

						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(1, 5); ?>'));
						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(1, 5); ?>'));
						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(1, 5); ?>'));
						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(1, 5); ?>'));
						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(1, 5); ?>'));
						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(1, 5); ?>'));
						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(1, 5); ?>'));
						table.appendChild(tr);

						tr = document.createElement('tr');
						tr.appendChild(getTextColumn(word, 1));
						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(20, 1); ?>'));
						tr.appendChild(getActionColumn(word, ''));
						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(20, 1); ?>'));
						tr.appendChild(getTitleColumn(word, suggestions, ''));
						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(20, 1); ?>'));
						tr.appendChild(getLanguageColumn(word, ''));
						table.appendChild(tr);

						Combobox.init('suggest_' + counter, 'wetextinput');
						Combobox.init('lang_' + counter, 'wetextinput');

						counter++;

					}


					function addPredefinedRow(word, suggestions, type, title, lang) {
						var tr = document.createElement('tr');

						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(1, 5); ?>'));
						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(1, 5); ?>'));
						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(1, 5); ?>'));
						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(1, 5); ?>'));
						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(1, 5); ?>'));
						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(1, 5); ?>'));
						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(1, 5); ?>'));
						table.appendChild(tr);

						tr = document.createElement('tr');
						tr.appendChild(getTextColumn(word, 1));
						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(20, 1); ?>'));
						tr.appendChild(getActionColumn(word, type));
						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(20, 1); ?>'));
						tr.appendChild(getTitleColumn(word, suggestions, title));
						tr.appendChild(getInnerColumn('<?php echo we_html_tools::getPixel(20, 1); ?>'));
						tr.appendChild(getLanguageColumn(word, lang));
						table.appendChild(tr);

						Combobox.init('suggest_' + counter, 'wetextinput');
						Combobox.init('lang_' + counter, 'wetextinput');

						disableItem(counter, type);

						counter++;

					}

					function activateButtons() {
						if (counter === 0) {
							var tr = document.createElement('tr');

							tr.appendChild(getTextColumn('<?php echo g_l('modules_glossary', '[all_words_identified]'); ?>', 7));
							table.appendChild(tr);
							weButton.hide('execute');
		<?php
		if($cmd3 != "checkOnly"){
			?>
								weButton.enable('publish');
								weButton.show('publish');
			<?php
		}
		?>

						} else {
							weButton.enable('execute');
						}

					}

					function noJava() {
						var tr = document.createElement('tr');

						tr.appendChild(getTextColumn('<?php echo g_l('modules_glossary', '[no_java]'); ?>', 7));
						table.appendChild(tr);
						weButton.hide('execute');
		<?php
		if($cmd3 != "checkOnly"){
			?>
							document.getElementById('execute').innerHTML = '<?php echo str_replace("'", "\'", we_html_button::create_button("publish", "javascript:top.we_save_document();", true, 120, 22, "", "", true, false)); ?>';
							weButton.enable('publish');
			<?php
		}
		?>

					}

					function disableItem(id, value) {
						switch (value) {
							case <?php echo we_glossary_glossary::TYPE_FOREIGNWORD; ?>:
								document.getElementById('title_' + id).disabled = true;
								document.getElementById('lang_' + id).disabled = false;
								document.getElementById('title_' + id).style.display = 'inline';
								document.getElementById('suggest_' + id).style.display = 'none';
								break;
							case 'ignore':
							case 'exception':
							case 'dictionary':
								document.getElementById('title_' + id).disabled = true;
								document.getElementById('lang_' + id).disabled = true;
								document.getElementById('suggest_' + id).style.display = 'none';
								document.getElementById('title_' + id).style.display = 'inline';
								break;
							case 'correct':
								document.getElementById('title_' + id).style.display = 'none';
								document.getElementById('lang_' + id).disabled = true;
								document.getElementById('suggest_' + id).disabled = false;
								document.getElementById('title_' + id).disabled = false;
								document.getElementById('suggest_' + id).style.display = 'inline';
								break;
							case "":
								document.getElementById('title_' + id).disabled = true;
								document.getElementById('lang_' + id).disabled = true;
								document.getElementById('suggest_' + id).style.display = 'none';
								document.getElementById('title_' + id).style.display = 'inline';
								break;
							default:
								document.getElementById('title_' + id).disabled = false;
								document.getElementById('lang_' + id).disabled = false;
								document.getElementById('suggest_' + id).style.display = 'none';
								document.getElementById('title_' + id).style.display = 'inline';
						}
					}

					function checkForm() {
						for (i = 0; i < counter; i++) {
							type = document.getElementById('type_' + i).value;
							title = document.getElementById('title_' + i).value;
							lang = document.getElementById('lang_' + i).value;
							switch (type) {
								case <?php echo we_glossary_glossary::TYPE_ABBREVATION; ?>:
								case <?php echo we_glossary_glossary::TYPE_ACRONYM; ?>:
									if (title === '') {
										document.getElementById('title_' + i).focus();
		<?php print we_message_reporting::getShowMessageCall(g_l('modules_glossary', '[please_insert_title]'), we_message_reporting::WE_MESSAGE_ERROR); ?>
										return false;
									}
									if (lang === '') {
										document.getElementById('lang_' + i).focus();
		<?php print we_message_reporting::getShowMessageCall(g_l('modules_glossary', '[please_insert_language]'), we_message_reporting::WE_MESSAGE_ERROR); ?>
										return false;
									}
									break;
								case <?php echo we_glossary_glossary::TYPE_FOREIGNWORD; ?>:
									if (lang === '') {
										document.getElementById('lang_' + i).focus();
		<?php print we_message_reporting::getShowMessageCall(g_l('modules_glossary', '[please_insert_language]'), we_message_reporting::WE_MESSAGE_ERROR); ?>
										return false;
									}
									break;
								case 'ignore':
								case 'exception':
								case 'dictionary':
									break;
								case 'correct':
									document.getElementById('title_' + i).value = document.getElementById('suggest_' + i).value;
									title = document.getElementById('title_' + i).value;
									if (title === '') {
										document.getElementById('title_' + i).focus();
		<?php print we_message_reporting::getShowMessageCall(g_l('modules_glossary', '[please_insert_correct_word]'), we_message_reporting::WE_MESSAGE_ERROR); ?>
										return false;
									}
									break;
								default:
									document.getElementById('type_' + i).focus();
		<?php print we_message_reporting::getShowMessageCall(g_l('modules_glossary', '[please_choose_action]'), we_message_reporting::WE_MESSAGE_ERROR); ?>
									return false;
									break;
							}
						}
						document.forms[0].submit();
					}
					//-->
				</script>

			</head>

			<body class="weDialogBody" onload="init();">

				<div id="spinner">
					<div id="statusImage"><img src="<?php echo IMAGE_DIR; ?>/spinner.gif"/></div>
					<div id="statusText" class="small" style="color: black;"><?php echo g_l('modules_glossary', '[download]'); ?></div>
				</div>


				<form name="we_form" action="<?php print WEBEDITION_DIR; ?>we_cmd.php" method="post" target="glossarycheck">
					<input type="hidden" name="ItemsToPublish" id="ItemsToPublish" value="" />
					<input type="hidden" name="we_cmd[0]" value="<?php echo we_base_request::_(we_base_request::STRING, 'we_cmd', '', 0); ?>" />
					<input type="hidden" name="we_cmd[1]" value="finish" />
					<input type="hidden" name="we_cmd[2]" value="<?php echo $Transaction; ?>" />
					<?php
					if($cmd3){
						echo "	<input type=\"hidden\" name=\"we_cmd[3]\" value=\"" . $cmd3 . "\" />";
					}


					$Content = '
	<table width="650" border="0" cellpadding="0" cellspacing="0" class="defaultfont">
	<tr>
		<td>' . we_html_tools::getPixel(150, 1) . '</td>
		<td>' . we_html_tools::getPixel(20, 1) . '</td>
		<td>' . we_html_tools::getPixel(140, 1) . '</td>
		<td>' . we_html_tools::getPixel(20, 1) . '</td>
		<td>' . we_html_tools::getPixel(200, 1) . '</td>
		<td>' . we_html_tools::getPixel(20, 1) . '</td>
		<td>' . we_html_tools::getPixel(100, 1) . '</td>
	</tr>
	<tr>
		<td colspan="7">' . g_l('modules_glossary', '[not_identified_words]') . '</td>
	</tr>
	<tr>
		<td colspan="7">' . we_html_tools::getPixel(2, 5) . '</td>
	</tr>
	<tr>
		<td><b>' . g_l('modules_glossary', '[not_known_word]') . '</b></td>
		<td>' . we_html_tools::getPixel(20, 1) . '</td>
		<td><b>' . g_l('modules_glossary', '[action]') . '</b></td>
		<td>' . we_html_tools::getPixel(20, 1) . '</td>
		<td><b>' . g_l('modules_glossary', '[announced_word]') . ' / ' . g_l('modules_glossary', '[suggestion]') . '</b></td>
		<td>' . we_html_tools::getPixel(20, 1) . '</td>
		<td><b>' . g_l('modules_glossary', '[language]') . '</b></td>
		<td>' . we_html_tools::getPixel(20, 1) . '</td>
	</tr>
	<tr>
		<td colspan="7">' . we_html_tools::getPixel(2, 5) . '</td>
	</tr>
	</table>
	<div style="height: 248px; width: 675px; overflow: auto;">
	<table width="650" border="0" cellpadding="0" cellspacing="0" class="defaultfont">
	<tbody id="unknown">
	<tr>
		<td>' . we_html_tools::getPixel(150, 1) . '</td>
		<td>' . we_html_tools::getPixel(20, 1) . '</td>
		<td>' . we_html_tools::getPixel(140, 1) . '</td>
		<td>' . we_html_tools::getPixel(20, 1) . '</td>
		<td>' . we_html_tools::getPixel(200, 1) . '</td>
		<td>' . we_html_tools::getPixel(20, 1) . '</td>
		<td>' . we_html_tools::getPixel(100, 1) . '</td>
	</tr>
	</tbody>
	</table>';


					// Only glossary check
					if($cmd3 == "checkOnly"){
						$CancelButton = we_html_button::create_button("close", "javascript:top.close();", true, 120, 22, "", "", false, false);
						$PublishButton = "";

						// glossary check and publishing
					} else {
						$CancelButton = we_html_button::create_button("cancel", "javascript:top.close();", true, 120, 22, "", "", false, false);
						$PublishButton = we_html_button::create_button("publish", "javascript:top.we_save_document();", true, 120, 22, "", "", true, false);
					}
					$ExecuteButton = we_html_button::create_button("execute", "javascript:checkForm();", true, 120, 22, "", "", true, false);


					$Buttons = we_html_button::position_yes_no_cancel($PublishButton . $ExecuteButton, "", $CancelButton);
					if($cmd3 != "checkOnly"){
						$Buttons .= we_html_element::jsElement("weButton.hide('publish');");
					}

					$Parts = array();
					$Part = array(
						"headline" => "",
						"html" => $Content,
						"space" => 0
					);
					$Parts[] = $Part;

					echo we_html_multiIconBox::getHTML('weMultibox', "100%", $Parts, 30, $Buttons, -1, '', '', false, g_l('modules_glossary', '[glossary_check]'));

//
// --> Finish Step
//
					break;
				case 'finish':
					$ClassName = $_SESSION['weS']['we_data'][$Transaction][0]['ClassName'];

					$we_doc = new $ClassName();
					$we_doc->we_initSessDat($_SESSION['weS']['we_data'][$Transaction]);

					$Language = $we_doc->Language;

					//
					// --> Insert or correct needed items
					//

	$AddJs = "";
					$items = we_base_request::_(we_base_request::STRING, 'item');
					if($items){

						foreach($items as $Key => $Entry){
							switch($Entry['type']){
								case 'exception':
									we_glossary_glossary::addToException($Language, $Key);
									break;
								case '':
								case 'ignore':
									break;
								case 'correct':
									foreach($we_doc->elements as &$val){
										if(isset($val['type']) && (
											$val['type'] == 'txt' || $val['type'] == 'input'
											)
										){
											$val['dat'] = preg_replace('-(^|\s|[!"#$%&\'()*+,\-./:;=?@[\\]^_`{\|}~])(' . preg_quote($Key, '-') . ')(\s|[!"#$%&\'()*+,\-./:;=?@[\\]^_`{\|}~]|$)-', '${1}' . $Entry['title'] . '${3}', $temp);
										}
									}
									unset($val);
									break;
								case "dictionary":
									$AddJs .= "AddWords += '" . addslashes($Key) . ",'\n";
									break;
								default:
									$Glossary = new we_glossary_glossary();
									$Glossary->Path = '/' . $Language . '/' . $Entry['type'] . '/' . $Key;
									$Glossary->IsFolder = 0;
									$Glossary->Icon = "";
									$Glossary->Text = $Key;
									$Glossary->Type = $Entry['type'];
									$Glossary->Language = $Language;
									$Glossary->Title = isset($Entry['title']) ? $Entry['title'] : '';
									$Glossary->setAttribute('lang', isset($Entry['lang']) ? $Entry['lang'] : '');
									$Glossary->Published = time();

									if($Glossary->pathExists($Glossary->Path)){
										$ID = $Glossary->getIDByPath($Glossary->Path);
										$Glossary->ID = $ID;
									}

									$Glossary->save();
									unset($Glossary);
							}
						}
					}

					$we_doc->saveinSession($_SESSION['weS']['we_data'][$Transaction]);

					//
					// --> Actualize to Cache
					//

	$Cache = new we_glossary_cache($Language);
					$Cache->write();
					unset($Cache);

					echo we_html_element::jsElement('
top.we_reloadEditPage();
var AddWords = "";
' . $AddJs . '
top.add();' .
						($cmd3 != 'checkOnly' ? "top.we_save_document();" : '') .
						we_message_reporting::getShowMessageCall(
							g_l('modules_glossary', ($cmd4 == 'checkOnly' ?
									'[check_successful]' :
									// glossary check with publishing
									'[check_successful_and_publish]')), we_message_reporting::WE_MESSAGE_NOTICE, false, true) .
						"top.close();");
					?>
					</head>
					<body class="weDialogBody">
						<form name="we_form" action="<?php print WEBEDITION_DIR; ?>we_cmd.php" method="post"><?php
					}
					?>
				</form>
				</center>
			</body>

			</html>
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
abstract class we_fileupload_base{
	protected $name = 'weFileSelect';
	protected $dimensions = array(
		'width' => 400,
		'dragHeight' => 30,
		'progressWidth' => 200,
		'alertBoxWidth' => 390,
		'marginTop' => 0,
		'marginBottom' => 0
	);
	protected $action = '';
	protected $typeConditionJson = '';
	protected $maxUploadSizeMBytes = 0;
	protected $maxUploadSizeBytes = 0;
	protected $maxChunkCount;
	public $useLegacy = false;

	const CHUNK_SIZE = 256;
	const ON_ERROR_RETURN = true;
	const ON_ERROR_DIE = true;

	abstract protected function _getSenderJS_additional();

	abstract protected function _getInitJS();

	abstract protected function _getSelectorJS();

	protected function __construct($name, $width = 400, $maxUploadSize = -1){
		$this->name = $name;
		$this->dimensions['width'] = $width;
		$this->maxUploadSizeMBytes = intval($maxUploadSize != -1 ? $maxUploadSize : (defined('FILE_UPLOAD_MAX_UPLOAD_SIZE') ? FILE_UPLOAD_MAX_UPLOAD_SIZE : 0));
		$this->maxUploadSizeBytes = $this->maxUploadSizeMBytes * 1048576;
		$this->maxChunkCount = $this->maxUploadSizeMBytes * 1024 / self::CHUNK_SIZE;
		$this->useLegacy = defined('FILE_UPLOAD_USE_LEGACY') ? FILE_UPLOAD_USE_LEGACY : false;
	}

	public function setAction($action){
		$this->action = $action;
	}

	public function setDimensions($args = array()){
		$this->dimensions = array(
			'width' => isset($args['width']) ? $args['width'] : $this->dimensions['width'],
			'dragHeight' => isset($args['dragHeight']) ? $args['dragHeight'] : $this->dimensions['dragHeight'],
			'progressWidth' => isset($args['progressWidth']) ? $args['progressWidth'] : $this->dimensions['progressWidth'],
			'alertBoxWidth' => isset($args['alertBoxWidth']) ? $args['alertBoxWidth'] : $this->dimensions['alertBoxWidth'],
			'marginTop' => isset($args['marginTop']) ? $args['marginTop'] : $this->dimensions['marginTop'],
			'marginBottom' => isset($args['marginBottom']) ? $args['marginBottom'] : $this->dimensions['marginBottom']
		);
	}

	public function setMaxUploadSize($sizeMB = 0){
		$this->maxUploadSizeMBytes = $sizeMB;
		$this->maxUploadSizeBytes = $this->maxUploadSizeMBytes * 1048576;
	}

	public function setUseLegacy($useLegacy = true){
		$this->useLegacy = $useLegacy;
	}

	public function getName(){
		return $this->name;
	}

	public function getMaxUploadSize(){
		return $this->useLegacy ? getUploadMaxFilesize(false) : $this->maxUploadSizeBytes;
	}

	public function getMaxUploadSizeText(){
		$field = $this->useLegacy ? '[max_possible_size]' : ($this->getMaxUploadSize() ? '[size_limit_set_to]' : '[no_size_limit]');
		return $field == '[no_size_limit]' ? g_l('newFile', $field) :
			sprintf(g_l('newFile', $field), we_base_file::getHumanFileSize($this->getMaxUploadSize(), we_base_file::SZ_MB));
	}

	public function getHtmlMaxUploadSizeAlert($width = -1){
		$width = $width == -1 ? $this->dimensions['alertBoxWidth'] : $width;
		$type = $this->useLegacy ? we_html_tools::TYPE_ALERT : we_html_tools::TYPE_INFO;

		return '
			<div id="div_' . $this->name . '_alert_legacy" style="display:none">' .
			we_html_tools::htmlAlertAttentionBox(sprintf(g_l('newFile', '[max_possible_size]'), we_base_file::getHumanFileSize(getUploadMaxFilesize(false), we_base_file::SZ_MB)), we_html_tools::TYPE_ALERT, $width) .
			'<div style="margin-top: 4px"></div>' .
			we_html_tools::htmlAlertAttentionBox(g_l('importFiles', '[fallback_text]'), we_html_tools::TYPE_ALERT, $width, false, 9) .
			'</div>
			<div id="div_' . $this->name . '_alert">' .
			we_html_tools::htmlAlertAttentionBox($this->getMaxUploadSizeText(), $type, $width) .
			'</div>';
	}

	public function getCss(){
		return $this->useLegacy ? '' : we_html_element::cssLink(CSS_DIR . 'we_fileupload.css') . we_html_element::cssElement('
			div.we_file_drag{
				padding-top: ' . (($this->dimensions['dragHeight'] - 10) / 2) . 'px;
				height: ' . $this->dimensions['dragHeight'] . 'px;
			}');
	}

	public function getJs($init = true, $selector = true, $sender = true){
		return implodeJS(($init ? $this->_getInitJS() : '') .
			($selector ? $this->_getSelectorJS() : '') .
			($sender ? $this->_getSenderJS_core() . $this->_getSenderJS_additional() : '')
		);
	}

	protected function _getSenderJS_core(){
		return we_html_element::jsElement('
weFU.sendNextFile = function(){
	var cur;
	if(cur = weFU.preparedFiles.shift()){
		weFU.currentFile = cur;
		if(cur.uploadConditionsOk){
			weFU.isUploading = true;
			weFU.repaintGUI({"what" : "startSendFile"});

			if(cur.file.size <= weFU.chunkSize){
				weFU.sendNextChunk(false);
			} else {
				//when all chunks of currentFile are done, we will submit the original form without file
				//FIXME: move this out of core functions
				if(weFU.elems.fileSelect && weFU.elems.fileSelect.value){
					weFU.elems.fileSelect.value = "";
				}

				var fr = new FileReader();
				fr.onload = function(e){
					var content = e.target.result;
					cur.dataArray = new Uint8Array(content);

					//prepare currentFile-data used for splitting
					cur.totalParts = Math.ceil(cur.dataArray.length / weFU.chunkSize);
					cur.lastChunkSize = cur.dataArray.length % weFU.chunkSize;
					cur.currentPos = 0;
					cur.partNum = 0;

					weFU.sendNextChunk(true);
				};
				fr.readAsArrayBuffer(cur.file);
			}
		} else {
			weFU.processError({"from" : "gui", "msg" : cur.error});
		}

	} else {
		//all uploads done
		weFU.isUploading = false;
		weFU.postProcess();
	}
};

weFU.sendNextChunk = function(split){
	if(weFU.isCancelled){
		weFU.isCancelled = false;
		return;
	}

	var cur = weFU.currentFile,
		resp = "";

	if(split){
		if(cur.partNum < cur.totalParts){
			var pos = cur.currentPos,
				file = cur.file,
				blob;

			cur.partNum++;
			cur.currentPos = pos + weFU.chunkSize;
			blob = new Blob([cur.dataArray.subarray(pos, cur.currentPos)]);

			weFU.sendChunk(
				blob,
				file.name,
				(cur.mimePHP !== "none" ? cur.mimePHP : file.type),
				(cur.partNum === cur.totalParts ? cur.lastChunkSize : weFU.chunkSize),
				cur.partNum,
				cur.totalParts,
				cur.fileNameTemp
			);
		}
	} else {
		weFU.sendChunk(cur.file, cur.file.name, cur.file.type, cur.file.size, 1, 1, "");
	}
};

weFU.sendChunk = function(part, fileName, fileCt, partSize, partNum, totalParts, fileNameTemp){
	var xhr = new XMLHttpRequest(),
		fd = new FormData(),
		cur = weFU.currentFile;

	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if(xhr.status === 200){
				weFU.processResponse(JSON.parse(xhr.responseText), {"partSize" : partSize, "partNum" : partNum, "totalParts" : totalParts});
			} else {
				weFU.processError({"type" : "request", "msg" : "http request failed",});
			}
		}
	}

	fileCt = fileCt ? fileCt : "text/plain";
	fd.append("uploadParts", 1);
	fd.append("wePartNum", partNum);
	fd.append("wePartCount", totalParts);
	fd.append("weFileNameTemp", fileNameTemp);
	fd.append("weFileName", fileName);
	fd.append("weFileCt", fileCt);
	fd.append("' . $this->name . '", part, fileName);
	fd.append("weIsUploading", 1);

	if(typeof weFU.appendMoreData === "function"){
		fd = weFU.appendMoreData(fd);
	}
	xhr.open("POST", weFU.action, true);
	xhr.send(fd);
};

weFU.processResponse = function(resp, args){
	if(!weFU.isCancelled){
		var cur = weFU.currentFile;

		cur.fileNameTemp = resp.fileNameTemp;
		cur.mimePHP = resp.mimePhp;
		cur.currentWeightFile += args.partSize;
		weFU.currentWeight += args.partSize;

		switch(resp.status){
			case "continue":
				weFU.repaintGUI({"what" : "chunkOK"});
				weFU.sendNextChunk(true);
				break;
			case "success":
				weFU.currentWeightTag = weFU.currentWeight;
				weFU.repaintGUI({"what" : "chunkOK"});
				weFU.repaintGUI({"what" : "fileOK"});
				if(weFU.preparedFiles.length !== 0){
					weFU.sendNextFile();
				} else {
					weFU.postProcess(resp);
				}
				break;
			case "failure":
				weFU.currentWeight = weFU.currentWeightTag + cur.file.size;
				weFU.currentWeightTag = weFU.currentWeight;
				weFU.repaintGUI({"what" : "chunkNOK", "message" : resp.message});
				if(weFU.preparedFiles.length !== 0){
					weFU.sendNextFile();
				} else {
					weFU.postProcess(resp);
				}
				break;
		}
	}
};
		');
	}

}

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
class we_doclist_search extends we_search_search{
	public $View;
	protected $whichSearch;

	public function __construct(we_modules_view $view = null){
		parent::__construct($view);
		$this->whichSearch = we_search_view::SEARCH_DOCLIST;
	}

	public function searchProperties($model, $table = ''){ // FIXME: handle model in like in other searches
		$DB_WE = new DB_WE();
		$foundItems = 0;
		$result = $saveArrayIds = $currentSearch = array();
		$_SESSION['weS']['weSearch']['foundItems'] = 0;

		$currentSearchFields = $model->getProperty('currentSearchFields');
		$currentSearch = $model->getProperty('currentSearch');
		$table = $table ? : (($t = $model->getProperty('currentSearchTables')) ? $t[0] : FILE_TABLE);
		$currentLocation = $model->getProperty('currentLocation');
		$currentOrder = $model->getProperty('currentOrder');
		//$view = $model->getProperty('currentSetView');
		$currentSearchstart = $model->getProperty('currentSearchstart');
		$currentAnzahl = $model->getProperty('currentAnzahl');
		$currentFolderID = $model->getProperty('currentFolderID');

		$where = array();
		$this->settable($table);

		if($currentFolderID){
			$this->createTempTable();

			foreach($currentSearchFields as $i => $searchField){
				if(isset($currentSearch[0])){
					$searchString = (isset($currentSearch[$i]) ? $currentSearch[$i] : $currentSearch[0]);
				}
				if(!empty($searchString)){

					switch($searchField){
						default:
						case 'Text':
							if(isset($searchField) && isset($currentLocation[$i])){
								$where[] = ($this->searchfor($searchString, $searchField, $currentLocation[$i], $table) ? : 'AND 0');
							}
						case 'Content':
						case 'Title':
						case 'Status':
						case 'Speicherart':
						case 'CreatorName':
						case 'WebUserName':
						case 'temp_category':
							break;
					}

					switch($searchField){
						case 'Content':
							$where[] = 'AND ' . ($this->searchContent($searchString, $table) ? : '0');
							break;

						case 'Title':
							break;
						/*
						  $w = $this->searchInTitle($searchString, $table);
						  $where[] = ($w ? $w : '0');
						 *
						 */
						case "Status":
						case "Speicherart":
							if($searchString != ""){
								if($table === FILE_TABLE){
									$where[] = $this->getStatusFiles($searchString, $table);
								}
							}
							break;
						case 'CreatorName':
						case 'WebUserName':
							if($searchString != ""){
								$where[] = $this->searchSpecial($searchString, $searchField, $currentLocation[$i]);
							}
							break;
						case 'temp_category':
							$where[] = $this->searchCategory($searchString, $table, $searchField);
							break;
					}
				}
			}

			$where[] = 'WETABLE.ParentID=' . intval($currentFolderID);
			switch($table){
				case FILE_TABLE:
					$where[] = '(WETABLE.RestrictOwners=0 OR WETABLE.CreatorID=' . intval($_SESSION['user']['ID']) . ' OR FIND_IN_SET(' . intval($_SESSION["user"]["ID"]) . ',WETABLE.Owners))';
					break;
				case TEMPLATES_TABLE:
					//$where[] = 'AND (RestrictUsers IN (0,' . intval($_SESSION['user']['ID']) . ') OR FIND_IN_SET(' . intval($_SESSION["user"]["ID"]) . ',Users))';
					break;
				case (defined('OBJECT_FILES_TABLE') ? OBJECT_FILES_TABLE : 'OBJECT_FILES_TABLE'):
					$where[] = '(WETABLE.RestrictOwners=0 OR WETABLE.CreatorID=' . intval($_SESSION['user']['ID']) . ' OR FIND_IN_SET(' . intval($_SESSION['user']['ID']) . ',WETABLE.Owners))';
					break;
				case (defined('OBJECT_TABLE') ? OBJECT_TABLE : 'OBJECT_TABLE'):
					$where[] = '(WETABLE.RestrictUsers=0 OR WETABLE.CreatorID=' . intval($_SESSION['user']['ID']) . ' OR FIND_IN_SET(' . intval($_SESSION['user']['ID']) . ',WETABLE.Users))';
					break;
			}
			$whereQuery = implode(' AND ', $where);
			$this->setwhere($whereQuery);
			$this->insertInTempTable($whereQuery, $table, id_to_path($currentFolderID) . '/');

			$foundItems = $this->countitems($whereQuery, $table);
			$_SESSION['weS']['weSearch']['foundItems'] = $this->founditems = $foundItems;

			$this->selectFromTempTable($currentSearchstart, $currentAnzahl, $currentOrder);

			while($this->next_record()){
				if(!isset($saveArrayIds[$this->Record['ContentType']][$this->Record['docID']])){
					$saveArrayIds[$this->Record['ContentType']][$this->Record['docID']] = $this->Record['docID'];
					$result[] = array_merge(array('Table' => $table), $this->Record);
				}
			}
		}

		if(!$this->founditems){
			return array();
		}
		$this->createTempTable();

		foreach($result as $k => $v){
			$result[$k]['Description'] = '';
			if($result[$k]['Table'] == FILE_TABLE && $result[$k]['Published'] >= $result[$k]['ModDate'] && $result[$k]['Published'] != 0){
				$result[$k]['Description'] = f('SELECT c.Dat FROM (' . FILE_TABLE . ' f LEFT JOIN ' . LINK_TABLE . ' l ON (f.ID=l.DID)) LEFT JOIN ' . CONTENT_TABLE . ' c ON (l.CID=c.ID) WHERE f.ID=' . intval($result[$k]['docID']) . ' AND l.nHash=x\'' . md5("Description") . '\' AND l.DocumentTable="' . stripTblPrefix(FILE_TABLE) . '"', '', $DB_WE);
			} else {
				if(($obj = f('SELECT DocumentObject FROM ' . TEMPORARY_DOC_TABLE . ' WHERE DocumentID=' . intval($result[$k]["docID"]) . ' AND DocTable="tblFile" AND Active=1', '', $DB_WE))){
					$tempDoc = we_unserialize($obj);
					if(isset($tempDoc[0]['elements']['Description']) && $tempDoc[0]['elements']['Description']['dat']){
						$result[$k]['Description'] = $tempDoc[0]['elements']['Description']['dat'];
					}
				}
			}
		}

		return $result;
	}

}

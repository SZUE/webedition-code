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
 * @package    webEdition_base
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */

    class validation{

        function validation(){
            die('static class do not use constructor');
        }

        function getAllCategories(){
            $cats = array(
                'xhtml'=>g_l('validation','[category_xhtml]'),
                'links'=>g_l('validation','[category_links]'),
                'css'=>g_l('validation','[category_css]'),
                'accessibility'=>g_l('validation','[category_accessibility]')
                );
            return $cats;
        }

        function saveService($validationService){
            // before saving check if another validationservice has this name
			$checkNameQuery = '
				SELECT *
				FROM ' . VALIDATION_SERVICES_TABLE . '
				WHERE name="' . mysql_real_escape_string($validationService->name) . '"
					AND PK_tblvalidationservices != ' . abs($validationService->id) . '
				';

			$GLOBALS['DB_WE']->query($checkNameQuery);
			if ($GLOBALS['DB_WE']->num_rows()) {

				$GLOBALS['errorMessage'] = g_l('validation','[edit_service][servicename_already_exists]');
				return false;
			}


            if($validationService->id != 0){
                $query = '
                    UPDATE ' . VALIDATION_SERVICES_TABLE . '
                        SET category="' . mysql_real_escape_string($validationService->category). '",name="' . mysql_real_escape_string($validationService->name) . '",host="' . mysql_real_escape_string($validationService->host) . '",path="' . mysql_real_escape_string($validationService->path) . '",method="' . mysql_real_escape_string($validationService->method) . '",varname="' . mysql_real_escape_string($validationService->varname) . '",checkvia="' . mysql_real_escape_string($validationService->checkvia) . '",additionalVars="' . mysql_real_escape_string($validationService->additionalVars) . '",ctype="' . mysql_real_escape_string($validationService->ctype) . '",fileEndings="' . mysql_real_escape_string($validationService->fileEndings) . '",active="' . mysql_real_escape_string($validationService->active) . '"
                        WHERE PK_tblvalidationservices = ' . abs($validationService->id);
            } else {

                $query = '
                    INSERT INTO ' . VALIDATION_SERVICES_TABLE . '
                        (category, name, host, path, method, varname, checkvia, additionalVars, ctype, fileEndings, active)
                        VALUES("' . mysql_real_escape_string($validationService->category) . '", "' . mysql_real_escape_string($validationService->name) . '", "' . mysql_real_escape_string($validationService->host) . '", "' . mysql_real_escape_string($validationService->path) . '", "' . mysql_real_escape_string($validationService->method) . '", "' . mysql_real_escape_string($validationService->varname) . '", "' . mysql_real_escape_string($validationService->checkvia) . '", "' . mysql_real_escape_string($validationService->additionalVars) . '", "' . mysql_real_escape_string($validationService->ctype) . '", "' . mysql_real_escape_string($validationService->fileEndings) . '", "' . mysql_real_escape_string($validationService->active) . '");
                ';
            }

            if($GLOBALS['DB_WE']->query($query)){
                if($validationService->id == 0){
                    $id = f("SELECT LAST_INSERT_ID() as LastID FROM " . VALIDATION_SERVICES_TABLE,"LastID",$GLOBALS['DB_WE']);
                    $validationService->id = $id;
                }
                return $validationService;
            } else {
                return false;
            }
        }

        function deleteService($validationService){
            if($validationService->id != 0){
                $query = '
                    DELETE FROM ' . VALIDATION_SERVICES_TABLE . '
                        WHERE PK_tblvalidationservices = ' . abs($validationService->id);

                if($GLOBALS['DB_WE']->query($query)){
                    return true;
                }
            } else {
                //  not saved entry - must not be deleted from db
                return true;
            }
            return false;
        }

        function getValidationServices($mode='edit'){
            $_ret = array();

            switch($mode){

                case 'edit':
                    $query = '
                        SELECT *
                        FROM ' . VALIDATION_SERVICES_TABLE;
                    break;
                case 'use':
                    $query = '
                        SELECT *
                        FROM ' . VALIDATION_SERVICES_TABLE . '
                        WHERE fileEndings LIKE "%' . mysql_real_escape_string($GLOBALS['we_doc']->Extension) . '%" AND active=1';
                    break;
            }

            $GLOBALS['DB_WE']->query($query);
            while($GLOBALS['DB_WE']->next_record()){
                $_ret[] = new validationService($GLOBALS['DB_WE']->f('PK_tblvalidationservices'),'custom',$GLOBALS['DB_WE']->f('category'),$GLOBALS['DB_WE']->f('name'),$GLOBALS['DB_WE']->f('host'),$GLOBALS['DB_WE']->f('path'),$GLOBALS['DB_WE']->f('method'),$GLOBALS['DB_WE']->f('varname'),$GLOBALS['DB_WE']->f('checkvia'),$GLOBALS['DB_WE']->f('ctype'),$GLOBALS['DB_WE']->f('additionalVars'),$GLOBALS['DB_WE']->f('fileEndings'),$GLOBALS['DB_WE']->f('active'));
            }
            return $_ret;
        }
    }

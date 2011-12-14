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



we_html_tools::protect();

if(isset($_REQUEST["ucmd"])){
    switch($_REQUEST["ucmd"]){
        case "new_group":
    	    if(!we_hasPerm("NEW_GROUP")){
                print we_htmlElement::jsElement(we_message_reporting::getShowMessageCall(g_l('alert',"[access_denied]"), we_message_reporting::WE_MESSAGE_ERROR));
                break;
            }

            $user_object=new we_user();

            if(isset($_REQUEST["cgroup"]) && $_REQUEST["cgroup"]){
                $user_group = new we_user();
                if($user_group->initFromDB($_REQUEST["cgroup"])){
                    $user_object->ParentID=$_REQUEST["cgroup"];
                }
            }

            $user_object->initType(1);

            $_SESSION["user_session_data"] = $user_object->getState();

            print we_htmlElement::jsElement('
                    top.content.user_resize.user_right.user_editor.user_edheader.location="' . WE_USERS_MODULE_PATH . 'edit_users_edheader.php";
                    top.content.user_resize.user_right.user_editor.user_properties.location="' . WE_USERS_MODULE_PATH . 'edit_users_properties.php";
                    top.content.user_resize.user_right.user_editor.user_edfooter.location="' . WE_USERS_MODULE_PATH . 'edit_users_edfooter.php";
                ');
    	    break;

        case "new_alias":
            if(!we_hasPerm("NEW_USER")){
                print we_htmlElement::jsElement(we_message_reporting::getShowMessageCall(g_l('alert',"[access_denied]"), we_message_reporting::WE_MESSAGE_ERROR));
                break;
            }

            $user_object=new we_user();

            if(isset($_REQUEST["cgroup"]) && $_REQUEST["cgroup"]){
                $user_group = new we_user();
                if($user_group->initFromDB($_REQUEST["cgroup"])){
                    $user_object->ParentID=$_REQUEST["cgroup"];
                }
            }

            $user_object->initType(2);

            $_SESSION["user_session_data"] = $user_object->getState();
            print we_htmlElement::jsElement('
                    top.content.user_resize.user_right.user_editor.user_edheader.location="' . WE_USERS_MODULE_PATH . 'edit_users_edheader.php";
                    top.content.user_resize.user_right.user_editor.user_properties.location="' . WE_USERS_MODULE_PATH . 'edit_users_properties.php";
                    top.content.user_resize.user_right.user_editor.user_edfooter.location="' . WE_USERS_MODULE_PATH . 'edit_users_edfooter.php";
                ');
    	    break;

        case "search":
            print we_htmlElement::jsElement('
                    top.content.user_resize.user_right.user_editor.user_properties.location="' . WE_USERS_MODULE_PATH . 'edit_users_sresults.php?kwd='.$_REQUEST["kwd"].'";
                ');
            break;

        case "display_alias":
            if($uid && $ctype && $ctable){
                print we_htmlElement::jsElement('
                        top.content.usetHot();
                        top.content.user_resize.user_right.user_editor.user_edheader.location="' . WE_USERS_MODULE_PATH . 'edit_users_edheader.php?uid=".$uid."&ctype=".ctype."&ctable=".$ctable;
                        top.content.user_resize.user_right.user_editor.user_properties.location="' . WE_USERS_MODULE_PATH . 'edit_users_properties.php?uid=".$uid."&ctype=".ctype."&ctable=".$ctable;
                        top.content.user_resize.user_right.user_editor.user_edfooter.location="' . WE_USERS_MODULE_PATH . 'edit_users_edfooter.php?uid=".$uid."&ctype=".ctype."&ctable=".$ctable;
                    ');
            }
            break;
    }
}

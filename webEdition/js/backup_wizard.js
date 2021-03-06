/* global WE, top */

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
function we_submitForm(target, url) {
	var f = self.document.we_form;
	if (!f.checkValidity()) {
		top.we_showMessage(WE().consts.g_l.main.save_error_fields_value_not_valid, WE().consts.message.WE_MESSAGE_ERROR, window);
		return false;
	}
	f.target = target;
	f.action = url;
	f.method = "post";
	f.submit();
	return true;
}

function doClicked(checked, opt) {
	if (checked) {

		switch (opt) {
			case 101:
				if (!document.we_form.handle_core.checked) {
					document.we_form.handle_core.value = 1;
					document.we_form.handle_core.checked = true;
					top.we_showMessage(WE().consts.g_l.backupWizard.temporary_dep, WE().consts.message.WE_MESSAGE_NOTICE, window);
				}
				break;
			case 12:
				if (!document.we_form.handle_core.checked || !document.we_form.handle_object.checked) {
					document.we_form.handle_core.value = 1;
					document.we_form.handle_core.checked = true;
					document.we_form.handle_object.value = 1;
					document.we_form.handle_object.checked = true;
					top.we_showMessage(WE().consts.g_l.backupWizard.versions_dep, WE().consts.message.WE_MESSAGE_NOTICE, window);
				}
				break;
			case 13:
				if (!document.we_form.handle_core.checked || !document.we_form.handle_object.checked || !document.we_form.handle_versions.checked) {
					document.we_form.handle_core.value = 1;
					document.we_form.handle_core.checked = true;
					document.we_form.handle_object.value = 1;
					document.we_form.handle_object.checked = true;
					document.we_form.handle_versions.value = 1;
					document.we_form.handle_versions.checked = true;
					top.we_showMessage(WE().consts.g_l.backupWizard.versions_binarys_dep, WE().consts.message.WE_MESSAGE_NOTICE, window);
				}
				break;
			case 14:
				if (!document.we_form.handle_core.checked) {
					document.we_form.handle_core.value = 1;
					document.we_form.handle_core.checked = true;
					top.we_showMessage(WE().consts.g_l.backupWizard.binary_dep, WE().consts.message.WE_MESSAGE_NOTICE, window);
				}
				break;
			case 55:
				if (!document.we_form.handle_core.checked || !document.we_form.handle_object.checked) {
					document.we_form.handle_core.value = 1;
					document.we_form.handle_core.checked = true;
					document.we_form.handle_object.value = 1;
					document.we_form.handle_object.checked = true;
					top.we_showMessage(WE().consts.g_l.backupWizard.schedule_dep, WE().consts.message.WE_MESSAGE_NOTICE, window);
				}
				break;
			case 30:
				if (WE().consts.tables.SHOP_TABLE && WE().consts.tables.CUSTOMER_TABLE && !document.we_form.handle_customer.checked) {
					document.we_form.handle_customer.value = 1;
					document.we_form.handle_customer.checked = true;
					top.we_showMessage(WE().consts.g_l.backupWizard.shop_dep, WE().consts.message.WE_MESSAGE_NOTICE, window);
				}
				break;
			case 35:
				if (WE().consts.tables.WORKFLOW_TABLE && !document.we_form.handle_user.checked || !document.we_form.handle_core.checked) {
					document.we_form.handle_core.value = 1;
					document.we_form.handle_core.checked = true;
					document.we_form.handle_user.value = 1;
					document.we_form.handle_user.checked = true;
					top.we_showMessage(WE().consts.g_l.backupWizard.workflow_dep, WE().consts.message.WE_MESSAGE_NOTICE, window);
				}
				break;
			case 40:
				if (WE().consts.tables.MESSAGES_TABLE && !document.we_form.handle_user.checked) {
					document.we_form.handle_user.value = 1;
					document.we_form.handle_user.checked = true;
					top.we_showMessage(WE().consts.g_l.backupWizard.todo_dep, WE().consts.message.WE_MESSAGE_NOTICE, window);
				}
				break;
			case 45:
				if (WE().consts.tables.NEWSLETTER_TABLE && WE().consts.tables.CUSTOMER_TABLE && !document.we_form.handle_customer.checked || !document.we_form.handle_core.checked || !document.we_form.handle_object.checked) {
					document.we_form.handle_core.value = 1;
					document.we_form.handle_core.checked = true;
					document.we_form.handle_object.value = 1;
					document.we_form.handle_object.checked = true;
					document.we_form.handle_customer.value = 1;
					document.we_form.handle_customer.checked = true;
					top.we_showMessage(WE().consts.g_l.backupWizard.newsletter_dep, WE().consts.message.WE_MESSAGE_NOTICE, window);
				}
				break;
			case 50:
				if (WE().consts.tables.BANNER_TABLE && !document.we_form.handle_core.checked) {
					document.we_form.handle_core.value = 1;
					document.we_form.handle_core.checked = true;
					top.we_showMessage(WE().consts.g_l.backupWizard.banner_dep, WE().consts.message.WE_MESSAGE_NOTICE, window);
				}
				break;

		}
		return;
	}
	var mess = "";
	switch (opt) {
		case 10:
			if (WE().consts.tables.WORKFLOW_TABLE && document.we_form.elements.handle_workflow.checked) {
				document.we_form.elements.handle_workflow.checked = false;
				mess += "\n-" + WE().consts.g_l.backupWizard.workflow_data;
			}
			if (WE().consts.tables.NEWSLETTER_TABLE && document.we_form.elements.handle_newsletter.checked) {
				document.we_form.elements.handle_newsletter.checked = false;
				mess += "\n-" + WE().consts.g_l.backupWizard.newsletter_data;
			}
			if (WE().consts.tables.BANNER_TABLE && document.we_form.elements.handle_banner.checked) {
				document.we_form.elements.handle_banner.checked = false;
				mess += "\n-" + WE().consts.g_l.backupWizard.newsletter_data;
			}
			if (WE().consts.tables.SCHEDULE_TABLE && document.we_form.elements.handle_schedule.checked) {
				document.we_form.elements.handle_schedule.checked = false;
				mess += "\n-" + WE().consts.g_l.backupWizard.schedule_data;
			}
			if (document.we_form.elements.handle_versions.checked) {
				document.we_form.elements.handle_versions.checked = false;
				mess += "\n-" + WE().consts.g_l.backupWizard.versions_data;
			}

			if (document.we_form.elements.handle_versions_binarys.checked) {
				document.we_form.elements.handle_versions_binarys.checked = false;
				mess += "\n-" + WE().consts.g_l.backupWizard.versions_binarys_data;
			}
			if (document.we_form.elements.handle_temporary.checked) {
				document.we_form.elements.handle_temporary.checked = false;
				mess += "\n-" + WE().consts.g_l.backupWizard.temporary_data;
			}
			if (document.we_form.elements.handle_history.checked) {
				document.we_form.elements.handle_history.checked = false;
				mess += "\n-" + WE().consts.g_l.backupWizard.history_data;
			}
			if (mess !== "") {
				tmpMess = WE().util.sprintf(WE().consts.g_l.backupWizard.unselect_dep2, WE().consts.g_l.backupWizard.core_data) + mess + "\n" + WE().consts.g_l.backupWizard.unselect_dep3;
				top.we_showMessage(tmpMess, WE().consts.message.WE_MESSAGE_NOTICE, window);
			}
			break;

		case 11:
			if (WE().consts.tables.OBJECT_TABLE !== "OBJECT_TABLE") {
				if (WE().consts.tables.SCHEDULE_TABLE && document.we_form.elements.handle_schedule.checked) {
					document.we_form.elements.handle_schedule.checked = false;
					mess += "\n-" + WE().consts.g_l.backupWizard.schedule_data;
				}
				if (document.we_form.elements.handle_versions.checked) {
					document.we_form.elements.handle_versions.checked = false;
					mess += "\n-" + WE().consts.g_l.backupWizard.versions_data;
				}
				if (document.we_form.elements.handle_versions_binarys.checked) {
					document.we_form.elements.handle_versions_binarys.checked = false;
					mess += "\n-" + WE().consts.g_l.backupWizard.versions_binarys_data;
				}
				if (mess !== "") {
					tmpMess = WE().util.sprintf(WE().consts.g_l.backupWizard.unselect_dep2, WE().consts.g_l.backupWizard.object_data) + mess + "\n" + WE().consts.g_l.backupWizard.unselect_dep3;
					top.we_showMessage(tmpMess, WE().consts.message.WE_MESSAGE_NOTICE, window);
				}
			}
			break;
		case 12:
			if (WE().consts.tables.OBJECT_TABLE !== "OBJECT_TABLE") {
				if (document.we_form.elements.handle_versions_binarys.checked) {
					document.we_form.elements.handle_versions_binarys.checked = false;
					mess += "\n-" + WE().consts.g_l.backupWizard.versions_binarys_data;
				}
				if (mess !== "") {
					tmpMess = WE().util.sprintf(WE().consts.g_l.backupWizard.unselect_dep2, WE().consts.g_l.backupWizard.versions_data) + mess + "\n" + WE().consts.g_l.backupWizard.unselect_dep3;
					top.we_showMessage(tmpMess, WE().consts.message.WE_MESSAGE_NOTICE, window);
				}
			}
			break;

		case 14:
			if (mess !== "") {
				tmpMess = WE().util.sprintf(WE().consts.g_l.backupWizard.unselect_dep2, WE().consts.g_l.backupWizard.binary_data) + mess + "\n" + WE().consts.g_l.backupWizard.unselect_dep3;
				top.we_showMessage(tmpMess, WE().consts.message.WE_MESSAGE_NOTICE, window);
			}
			break;
		case 20:
			if (WE().consts.tables.WORKFLOW_TABLE) {
				if (document.we_form.elements.handle_workflow.checked) {
					document.we_form.elements.handle_workflow.checked = false;
					mess += "\n-" + WE().consts.g_l.backupWizard.workflow_data;
				}
				if (WE().consts.tables.MESSAGES_TABLE && document.we_form.elements.handle_todo.checked) {
					document.we_form.elements.handle_todo.checked = false;
					mess += "\n-" + WE().consts.g_l.backupWizard.todo_data;
				}
				if (mess !== "") {
					tmpMess = WE().util.sprintf(WE().consts.g_l.backupWizard.unselect_dep2, WE().consts.g_l.backupWizard.user_data) + mess + "\n" + WE().consts.g_l.backupWizard.unselect_dep3;
					top.we_showMessage(tmpMess, WE().consts.message.WE_MESSAGE_NOTICE, window);
				}
			}
			break;
		case 25:
			if (WE().consts.tables.CUSTOMER_TABLE) {
				if (WE().consts.tables.SHOP_TABLE && document.we_form.elements.handle_shop.checked) {
					document.we_form.elements.handle_shop.checked = false;
					mess += "\n-" + WE().consts.g_l.backupWizard.shop_data;
				}
				if (WE().consts.tables.NEWSLETTER_TABLE && document.we_form.elements.handle_newsletter.checked) {
					document.we_form.elements.handle_newsletter.checked = false;
					mess += "\n-" + WE().consts.g_l.backupWizard.newsletter_data;
				}
				if (mess !== "") {
					tmpMess = WE().util.sprintf(WE().consts.g_l.backupWizard.unselect_dep2, WE().consts.g_l.backupWizard.customer_data) + mess + "\n" + WE().consts.g_l.backupWizard.unselect_dep3;
					top.we_showMessage(tmpMess, WE().consts.message.WE_MESSAGE_NOTICE, window);
				}
			}
			break;
	}
}


function delSelItem() {
	var sel = document.we_form.backup_select;
	if (sel.selectedIndex > -1) {
		sel.remove(sel.selectedIndex);
	}
}

function showAll() {
	var a = document.we_form.backup_select.options;
	var b = document.we_form.show_all;

	if (b.checked) {
		b.value = 1;
		for (i = 0; i < extra_files.length; i++) {
			a[a.length] = new Option(extra_files_desc[i], extra_files[i]);
		}
	} else {
		b.value = 0;
		for (i = a.length - 1; i > -1; i--) {
			for (j = extra_files.length - 1; j > -1; j--) {
				if (a[i].value == extra_files[j]) {
					a[i] = null;
					break;
				}
			}
		}
	}
}

/**
 * webEdition CMS
 *
 * This source is part of webEdition CMS. webEdition CMS is free software; you
 * can redistribute it and/or modify it under the terms of the GNU General
 * Public License as published by the Free Software Foundation; either version 3
 * of the License, or any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html. A copy is found in the textfile
 * license.txt
 *
 * @license http://www.gnu.org/copyleft/gpl.html GPL
 */
package org.webedition.eplugin.util;

import java.security.AccessController;
import org.webedition.eplugin.privileged.PrivilegedRun;

/**
 *
 * @author slavko
 */
public class CmdProxy {

	private CmdProxy() {
	}

	public static String executeCmd(String cmd) {

		String out = new String();

		PrivilegedRun pr = new PrivilegedRun(cmd);
		out = (AccessController.doPrivileged(pr)).toString();

		return out;

	}
}

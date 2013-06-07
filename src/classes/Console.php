<?php
/**
 * cosmonautexperience.com
 *
 * Website for The Cosmonaut movie.
 *
 * NOTE OF LICENSE
 * Licensed under GNU General Public License version 3.0
 *
 * Copyright (c) 2013 Tecnilógica Soluciones Avanzadas.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the GNU Public License v3.0
 * which accompanies this distribution, and is available at
 * http://www.gnu.org/licenses/gpl.html
 *
 * Contributors:
 * Tecnilógica Soluciones Avanzadas - initial API and implementation
 *
 * @package   cosmonautexperience
 * @author    Tecnilógica soluciones avanzadas
 * @copyright Copyright (c) 2003 - 2013, Tecnilógica soluciones avanzadas, S.A. (http://tecnilogica.com/)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link      http://cosmonautexperience.com
 *
 *
 * Console
 *
 * Class with a static method to display debugg messages.
 * 
 * Use sample:
 * Console::log($var);
 *
 */
class Console {

	/**
	 * Print a block opf text surrounded by <pre> tags to improve reading. This
	 * method only prints messages in development mode defined in /config/config.cfg file.
	 * 
	 * @param	String $s Item to print
	 */
	static public function log($s) {
		// print only on development mode
		if (!is_null($s) && F3::instance()->get('ENVIRONMENT')=='DEVELOPMENT') {
			// use print_r function to display arrays
			if (is_array($s)) {
				echo "<pre>";
				$s = print_r($s);
				echo "</pre>";
			} else if(is_object($s)) {
				echo "<pre>";
				// use var_dump
				$s = var_dump($s);
				echo "</pre>";
			} else {
				echo "<pre>".$s."</pre>";
			}
		}
	}
}
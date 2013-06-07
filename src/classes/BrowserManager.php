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
 * BrowserManager
 *
 * Class to get browser language preferences. It will be used on each request
 * to handle dictionaries.
 *
 * BrowserManager::get_language();
 *
 */
class BrowserManager {

  /**
   * Get the language ID from the browser. Priority as follows:
   * Return value of laguage cookie is found.
   * If not look for spanish id ('es') and return it if it has been found in
   * $_SERVER['HTTP_ACCEPT_LANGUAGE'] collection.
   * Otherwise, return english ('en').
   *
   * @return  String Language ID ('es', 'en', 'de' ...)
   */
  static public function get_browser_language() {
    $f3 = F3::instance();

    if ($f3->get('COOKIE.lang') != "") {
      return $f3->get('COOKIE.lang');
    } else if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
      // de,es-es;q=0.8,es;q=0.6,en-us;q=0.4,en;q=0.2
		  if (!strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], "es")) {
		    return "en";
		  } else {
		    return "es";
		  }
    }
    return strtolower($f3->get('DEFAULT_LANG'));
  }
}
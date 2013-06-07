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
 * Common operations executed on each request
 */

/*
 * set_language
 *
 * @param String $lang
 *
 * Define language variable and a LANGUAGE cookie.
 */
function set_language($lang) {
	$f3 = F3::instance();
	$f3->set("LANGUAGE", $lang);
	$f3->set("BASE_URL", $f3->get("BASE_URL_".strtoupper($lang)));
	$f3->set("COOKIE.lang", $lang);
}

// ERROR HANDLING
$f3->set("DEBUG", $f3->get("DEBUGLevel"));
if ($f3->get("ENVIRONMENT")!="DEVELOPMENT") {
	$f3->set("ONERROR", "SiteController->error_handler");
}

$f3->set("LOCALES", $f3->get("PATH_DICT"));
// select dictionary language
if ("http://".$_SERVER["SERVER_NAME"] == $f3->get("BASE_URL_EN")) {
	set_language("en");
} else if ("http://".$_SERVER["SERVER_NAME"] == $f3->get("BASE_URL_ES")) {
	set_language("es");
} else {
	$browser_language = BrowserManager::get_browser_language();
  $lang = $browser_language == "es" ? "es" : "en";
  $new_url = "http://".$lang.".".$f3->get("BASE_URL_NEUTRAL").$_SERVER["REQUEST_URI"];
  $f3->reroute($new_url);
}

$f3->SET("DB_SHOP", new DB\SQL( $f3->get('DB_SHOP_STRING'), $f3->get('DB_SHOP_USER'), $f3->get('DB_SHOP_PASS') ) );
$f3->SET("DB", new DB\SQL( $f3->get('DB_STRING'), $f3->get('DB_USER'), $f3->get('DB_PASS') ) );
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
 *
 * QuoteModel
 *
 * Quote table structure:
 * quo_id  int(11) primary key
 * quo_en  varchar(128)
 * quo_es  varchar(128)
 * quo_author_es varchar(64)
 * quo_author_en varchar(64)
 */
class QuoteModel {

	/**
	 * list_quotes
	 *
	 * Get a collection with all media press quotes
	 *
	 * @return Array
	 */
	function list_quotes() {
		$f3 = F3::instance();
		$db = $f3->get("DB");
		if (strtolower($f3->get("LANGUAGE"))=="es") {
			$result = $db->exec("SELECT quo_es AS quote, quo_author_es AS author FROM quote ORDER BY quo_author_es ASC");
		} else {
			$result = $db->exec("SELECT quo_en AS quote, quo_author_en AS author FROM quote ORDER BY quo_author_en ASC");
		}
		return $result;
		
	}
}
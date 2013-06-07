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
 * VimeoController
 *
 * Controller class to handle requests that interact with Vimeo API.
 *
 */
class VimeoController {


	/**
	 * get_thumbail
	 *
	 * Get thumbnail of a Vimeo video identified by ID.
	 *
	 * @param PARAMS.id Integer Unique identifier of a Vimeo video.
	 * @return Array json_encoded
	 */
	function get_thumbnail() {
		$f3 = F3::instance();

		$id = $f3->get("PARAMS.id");
		$api_url = "http://vimeo.com/api/v2/video/$id.php";

		try {
			$hash = unserialize(file_get_contents($api_url));
			echo json_encode(array("result"=>"ok", "msg"=>$hash[0]['thumbnail_large']));
		} catch (Exception $e) {
			echo json_encode(array("result"=>"error"));
		}

	}
}
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
 * CategoryModel
 *
 * Model class for video categories. No need to create a database table.
 * 
 */
class CategoryModel {

  /**
   * to_select
   *
   * @return Associative array (cat_id, cat_value)
   *
   * Build an array of admin users prepared to popullate select inputs.
   */
  function to_select() {
    $categories = array (
      array("cat_id"=>"_TRANSMEDIA_", "cat_value"=>"Transmedia"),
      array("cat_id"=>"_EASTERN_EGG_", "cat_value"=>"Eastern egg"),
      array("cat_id"=>"_MOVIE_", "cat_value"=>"La película"),
      array("cat_id"=>"_DIARY_", "cat_value"=>"Video diarios"),
      array("cat_id"=>"_HUMMINGBIRD_", "cat_value"=>"The hummingbird"),
      array("cat_id"=>"_BRONKING_", "cat_value"=>"Bronking of")
    );
    return $categories;
  }

}
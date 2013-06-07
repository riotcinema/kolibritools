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
 * CharacterModel
 * Nayik characters are based on characters table with the following structure:
 * cha_id int(11) PRIMARY KEY AUTO INCREMENT
 * cha_name varchar(32)
 * cha_fullname varchar(128)
 * cha_avatar varchar(128)
 */
class CharacterModel {



  /**
   * get_by_id
   *
   * Get a character identified by Id
   *
   * @param $id Integer Unique character key
   * @return Array Nayik character in array format.
   */
  function get_by_id($id) {
    $f3 = F3::instance();

    $db = $f3->get("DB");
    $query = " SELECT * FROM characters WHERE cha_id=:cha_id";
    $params = array(':cha_id'=>$id);
    $character = $db->exec($query,$params);
    if (is_array($character) && count($character)==1) {
      return $character[0];
    } else {
      return array();
    }
  }


  /**
   * getCharacters
   *
   * Get a collection of Nayik characters.
   *
   * @return Associtive array with cha_id key
   */
  function getCharacters() {
    $f3 = F3::instance();
    $result = array();

    $db = $f3->get("DB");
    $query = " SELECT * FROM characters";
    $characters = $db->exec($query);
    foreach ($characters as $k=>$c) {
      $result[$c['cha_id']] = $c;
      unset($characters[$k]);
    }
    return $result;
  }


  /**
   * to_select
   *
   * Build an array of Nayik characters prepared to popullate select inputs.
   *
   * @return Associative array (cha_id, cha_fullname)
   */
  function to_select() {
    $f3 = F3::instance();
    $db = $f3->get("DB");
    $result = $db->exec(" SELECT cha_id, cha_fullname FROM characters ORDER BY cha_fullname ASC");
    foreach ($result as $k=>$character) {
      $characters[$character["cha_id"]] = $result[$k];
    }
    return $characters;
  }

}
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
 * CreditModel
 *
 * Credit model is based on credit table with the following structure:
 * cre_id int(11) primary key
 * cre_name varchar(128)
 * cre_role varchar(16) only _INVESTOR_ and _BACKER_ values allowed.
 */
class CreditModel {

  /**
   * get_backers
   *
   * @return Array of backers
   */
  function get_backers() {
    return $this->get_credits(array('cre_role=?','_BACKER_'));
  }


  /**
   * get_credits
   *
   * @param $filters
   *
   * Get array of elements in credit table.
   */
  function get_credits($filters=array()) {
    $f3 = F3::instance();

    $db = $f3->get("DB");

    $credit_mapper = new DB\SQL\Mapper($f3->get("DB"), "credit");
    $credits = $credit_mapper->afind($filters);

    return $credits;
  }


  /**
   * get_investors
   *
   * @return Array of investors
   */
  function get_investors() {
    return $this->get_credits(array('cre_role=?','_INVESTOR_'));
  }


  /**
   * insert
   *
   * @param $name String
   * @param $role String
   *
   * Create a new entry in credit table.
   */
  function insert($name, $role) {
    $f3 = F3::instance();

    $credit_mapper = new DB\SQL\Mapper($f3->get("DB"), "credit");
    $credit_mapper->cre_name = $name;
    $credit_mapper->cre_role = $role;
    $credit_mapper->save();
  }

}
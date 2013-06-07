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
 * GroupModel
 *
 * Customers groups.
 */
class GroupModel {

  /**
   * to_select
   *
   * Get all customers groups and build an array ready to popullate select
   * input fields.
   * select.
   */
  function to_select() {
    $f3 = F3::instance();

    $db = $f3->get("DB_SHOP");
    $query = "SELECT 
      g.id_group,
      gl.name
      FROM ps_group g
      INNER JOIN ps_group_lang gl ON g.id_group=gl.id_group
      INNER JOIN ps_lang l ON gl.id_lang=l.id_lang
      WHERE l.id_lang=1
      ORDER BY gl.name ASC";
    $result = $db->exec($query);
    foreach ($result as $k=>$group) {
      $groups[$group["id_group"]] = $result[$k];
    }
    return $groups;
  }

}
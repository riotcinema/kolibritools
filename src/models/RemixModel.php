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
 * RemixModel
 *
 * Remix table structure:
 * rem_id int(11) primary key
 * rem_title varchar(128)
 * rem_url varchar(128)
 * rem_author  varchar(128)
 * rem_channel_url varchar(128)
 * rem_highlight tinyint(1)
 */
class RemixModel {

  /**
   * get_highlight
   *
   * Look for one video remix flagged as highlighted
   *
   * @return Array video remix in array format.
   */
  function get_highlight() {
    $f3 = F3::instance();
    $remix_mapper = new DB\SQL\Mapper($f3->get("DB"), "remix");
    $aRemix = $remix_mapper->afindone(array('rem_highlight=1'));
    return $aRemix;
  }


  /**
   * highlight
   *
   * Mark a video remix as highlighted.
   *
   * @param   $id Integer
   */
  function highlight($id) {
    $f3 = F3::instance();
    $db = $f3->get("DB");
    return $db->exec("UPDATE remix SET rem_highlight=0 WHERE rem_id!=".$id);
  }

}
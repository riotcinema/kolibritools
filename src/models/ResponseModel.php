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
 * ResponseModel
 *
 * response table stucture:
 * res_id int(11) primary key
 * res_fk_com_id int(11) Foreign key to comments table
 * res_fk_adm_id int(11) Foreign key to admin_user table
 * res_comment text
 * res_date
 */
class ResponseModel {

  /**
   * a_responses
   *
   * @param  $vid_id Integer Unique identifier of a video.
   * @return $ordered Associative array with responses of a video.
   */
  function a_responses($vid_id=NULL) {

    $f3 = F3::instance();

    $responses = array();
    $admin_ids = array();
    $admins = array();
    $ordered = array();
    $params = array();

    // get comments
    $db = $f3->get("DB");
    $query = "SELECT
        res_id,
        res_comment,
        res_date,
        res_fk_adm_id,
        res_fk_com_id
      FROM response
      INNER JOIN comment ON res_fk_com_id=com_id ";
    if ($vid_id!=NULL) {
      $query .= " INNER JOIN video ON com_fk_vid_id=vid_id ";
      $query .= " WHERE com_fk_vid_id=:vid_id ";
      $params = array (":vid_id"=>$vid_id);

    }
    $query .= " ORDER BY res_date ASC";
    $responses = $db->exec($query, $params);

    // admin user
    foreach($responses as $response) {
      $admin_ids[$response["res_fk_adm_id"]] = $response["res_fk_adm_id"];
    }
    $admin_model = new AdminModel();
    $admins = $admin_model->list_by_ids($admin_ids);

    // questions
    foreach ($responses as $key=>$response) {
      $ordered[$response['res_fk_com_id']] = $response;
      if (array_key_exists($response["res_fk_adm_id"], $admins)) {
        $ordered[$response['res_fk_com_id']]["usu_id"] = $admins[$response["res_fk_adm_id"]]["usu_id"];
        $ordered[$response['res_fk_com_id']]["usu_nick"] = $admins[$response["res_fk_adm_id"]]["usu_nick"];
        $ordered[$response['res_fk_com_id']]["usu_email"] = $admins[$response["res_fk_adm_id"]]["usu_email"];
      }
      unset($responses[$key]);

    }

    return $ordered;

  }

}
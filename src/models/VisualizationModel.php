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
 * VisualizationModel
 *
 * Stucture of visualizations table:
 * vis_id int(11) primary key
 * vis_fk_vid_id int(11) video foreign key
 * vis_fk_use_id int(11) customer foreign key
 * vis_date  datetime
 */
class VisualizationModel {

  /**
   * create
   *
   * Insert a new visualization.
   *
   * @param $vid_id Integer Video ID
   * @param $use_id Integer Customer ID.
   */
  function create($vid_id, $use_id) {
    $f3 = F3::instance();

    $error = array();

    if (empty($vid_id)) {
      $error[] =  "No has especificado el video.";
    }

    if (empty($use_id)) {
      $error[] =  "No has especificado el usuario.";
    }

    if (count($error)==0) {
      $vis_mapper = new DB\SQL\Mapper($f3->get("DB"), "visualization");
      $vis_mapper->vis_fk_vid_id = $vid_id;
      $vis_mapper->vis_fk_use_id = $use_id;
      $vis_mapper->vis_date = date("Y-m-d H:i:s");

      try {
        $vis_mapper->save();
        return $vis_mapper->vis_id;
      } catch (Exception $e) {
        $error[] = $e->getMessage();
      }
      
    }
    return $error;

  }


  /**
   * _a_get_by_user
   *
   * Get a collection of visualizations from a user.
   *
   * @param   $id Integer
   * @return  $visualizations Associative array.
   */
  function _a_get_by_user($id) {
    $f3 = F3::instance();
    $db = $f3->get("DB");
    $query = "SELECT 
      vis_id,
      vis_date,
      vis_fk_vid_id,
      vid_title_en,
      vid_title_es
      FROM visualization
      INNER JOIN video ON vis_fk_vid_id=vid_id
      WHERE vis_fk_use_id=:id
      ORDER BY vis_date DESC ";
    $params = array(":id"=>$id);
    $visualizations = $db->exec($query,$params);
    return $visualizations;
  }



  /**
   * get_eastern_eggs
   *
   * Get a collection of eastern egg videos visualized by a user.
   *
   * @param $user_id ID of a user
   * @return Array of visuzaliations
   *
   */
  function get_eastern_eggs($user_id) {
    $f3 = F3::instance();
    $db = $f3->get("DB");

    $query = " SELECT
      vis_id,
      vis_fk_use_id,
      vis_fk_vid_id,
      vid_title_en,
      vid_title_es,
      vis_date
      FROM visualization
      INNER JOIN video ON vis_fk_vid_id=vid_id
      WHERE (vid_category='_EASTERN_EGG_' OR vid_category='_DIARY_') AND vis_fk_use_id=".$user_id."
      ORDER BY vis_date DESC ";
    $visualizations = $db->exec($query);
    return $visualizations;
  }


  /**
   * get_transmedia
   *
   * Get a collection of transmedia videos visualized by a user.
   *
   * @param $user_id ID of a user
   * @return Array of visuzaliations
   */
  function get_transmedia($user_id) {
    $f3 = F3::instance();
    $db = $f3->get("DB");

    $query = " SELECT
    vis_id,
    vis_fk_use_id,
    vis_fk_vid_id,
    vid_title_en,
    vid_title_es,
    vis_date
    FROM visualization
    INNER JOIN video ON vis_fk_vid_id=vid_id
    WHERE vid_category='_TRANSMEDIA_' AND vis_fk_use_id=".$user_id."
    ORDER BY vis_date DESC ";

    $visualizations = $db->exec($query);
    return $visualizations;
  }


  /**
   * get_visualizations
   *
   * Get a collection of visualizations according to some filtering options.
   *
   * @param Array Filtering options.
   * @return Array of visualizations.
   */
  function get_visualizations($filters=array()) {
    $f3 = F3::instance();
    $db = $f3->get("DB");

    $query = " SELECT
      vis_id,
      vis_fk_use_id,
      vis_fk_vid_id,
      vid_title_en,
      vid_title_es,
      vis_date
      FROM visualization
      INNER JOIN user ON vis_fk_use_id=use_id
      INNER JOIN video ON vis_fk_vid_id=vid_id
      WHERE 1=1 ";

    // any filtering options?
    foreach ($filters as $k=>$v) {
      $query .= " AND ".$k." = '".$v."' ";
    }
    $query .= " ORDER BY vis_date DESC ";
    $visualizations = $db->exec($query);

    return $visualizations;

  }

}
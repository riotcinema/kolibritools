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
 * NayikModel
 *
 * nayik table structure:
 * nay_id int(11)
 * nay_fk_cha_id int(11) Foreign key to characters table.
 * nay_date  datetime
 * nay_comment text
 * nay_parent_id int(11)
 */
class NayikModel {

  /**
   * countPosts
   *
   * Get the ammount of Nayik parent posts. Parent posts have no may_parent_id
   * value.
   *
   * @return Integer Number of posts
   */
  function countPosts() {
    $f3 = F3::instance();

    $db = $f3->get("DB");
    $query = " SELECT COUNT(*) FROM nayik WHERE nay_parent_id IS NULL ";
    $result = $db->exec($query);
    if (is_array($result) && array_key_exists('COUNT(*)', $result[0])) {
      return $result[0]['COUNT(*)'];
    } else {
      return 0;
    }
  }


  /**
   * delete
   *
   * Delete a Nayik post and all it's comments.
   *
   * @param $id Integer ID of the post to delete.
   */
  function delete($id) {
    $f3 = F3::instance();
    $db = $f3->get("DB");
    $query = " DELETE FROM nayik WHERE nay_id=:id OR nay_parent_id=:parent_id ";
    $params = array(':id'=>$id, ':parent_id'=>$id);
    $result = $db->exec($query, $params);
  }



  /**
   * get_by_id
   *
   * @param $id ID of the post to retrieve.
   * @return $aPost Post mapper
   */
  function get_by_id($id) {
    $f3 = F3::instance();

    $nayik_mapper = new DB\SQL\Mapper($f3->get("DB"), "nayik");
    $aPost = $nayik_mapper->findone(array('nay_id=:id',array(':id'=>$id)));
    return $aPost;
  }


  /**
   * getPosts
   *
   * Retrieve all Nayik parent posts. Parent posts have no nay_parent_id value.
   *
   * @param offset Integer
   * @param $limit Integer
   */
  function getPosts($offset=NULL, $limit=NULL) {
    $f3 = F3::instance();

    $db = $f3->get("DB");
    $result = array();
    $post_ids = array();
    $posts = array();
    $today = new DateTime(date("Y-m-d H:i:s"));

    $characters = array();
    $character_model = new CharacterModel();
    $characters = $character_model->getCharacters();

    $query = " SELECT * FROM nayik WHERE nay_parent_id IS NULL ";
    if ($offset!=NULL && $limit!=NULL) {
      $query .= " LIMIT($offset, $limit) ";
    }
    $query .= " ORDER BY nay_date ASC";

    try {
      $result = $db->exec($query);

      foreach($result as $k=>$p) {
        $release = new DateTime($p['nay_date']);
        $countdown = $today->diff($release);
        $post_ids[] = $p['nay_id'];
        $posts[$p['nay_id']] = $p;
        if (array_key_exists($p['nay_fk_cha_id'],$characters)) {
          $posts[$p['nay_id']]['author'] = $characters[$p['nay_fk_cha_id']];
        }
        $posts[$p['nay_id']]["nay_diff"] = $countdown->days;
        unset($result[$k]);
      }
      $responses = $this->getResponses($post_ids);
      foreach($responses as $k=>$r) {
        $release = new DateTime($r['nay_date']);
        $countdown = $today->diff($release);
        if (array_key_exists($r['nay_parent_id'], $posts)) {
          $posts[$r['nay_parent_id']]['responses'][$k] = $r;
          if (array_key_exists($r['nay_fk_cha_id'],$characters)) {
            $posts[$r['nay_parent_id']]['responses'][$k]['author'] = $characters[$r['nay_fk_cha_id']];
          }
        }
        $posts[$r['nay_parent_id']]['responses'][$k]['nay_diff'] = $countdown->days;
      }
    } catch (Exception $e) {
      $error[] = $e->getMessage();
      Console::log($error);
    }
    return $posts;
  }



  /**
   * getResponses
   *
   * Get all post responses for each post received as parameter
   *
   * @param post_ids Array with a collection of post ids.
   */
  function getResponses($post_ids=NULL) {
    $f3 = F3::instance();

    $db = $f3->get("DB");
    $result = array();
    $post_ids = array();

    $query = " SELECT * FROM nayik WHERE nay_parent_id IS NOT NULL ";
    if ($post_ids!=NULL) {
      $query .= " WHERE nay_parent_id IN ".implode(',', $post_ids)." ";
    }
    $query .= " ORDER BY nay_date ASC";

    try {
      $result = $db->exec($query);
      return $result;

    } catch (Exception $e) {
      $error[] = $e->getMessage();
      Console::log($error);
    }
  }

}
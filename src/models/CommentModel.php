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
 * CommentModel
 *
 * Video comments are based on comment table with the following structure:
 * com_id integer(11) PRIMARY KEY
 * com_fk_vid_id integer(11) FOREIGN KEY to video table
 * com_fk_use_id integer(11) FOREIGN KEY to customer table (Prestashop)
 * com_date datetime
 */
class CommentModel {


  /**
   * create
   *
   * @param vid_id Integer
   * @param use_id Integer
   * @param comment_id String
   *
   */
  function create($vid_id, $use_id, $comment) {
    $f3 = F3::instance();

    $error = array();

    $video_mapper = new DB\SQL\Mapper($f3->get("DB"), "video");
    $video_mapper->afind(array('vid_id=:id',array(':id'=>$vid_id)));
    if (count($video_mapper)!=1) {
      $error[] = "El video no es válido.";
    }

    $customer_mapper = new DB\SQL\Mapper($f3->get("DB_SHOP"), "ps_customer");
    $customer_mapper->afind(array('id_customer=:id',array(':id'=>$use_id)));
    if (count($customer_mapper)!=1) {
      $error[] = "El usuario no es válido.";
    }

    if (empty($comment)) {
      $error[] =  "No has indicado el contenido del comentario.";
    }

    if (count($error) == 0) {
      $comment_mapper = new DB\SQL\Mapper($f3->get("DB"), "comment");
      $comment_mapper->com_comment = $comment;
      $comment_mapper->com_fk_vid_id = $vid_id;
      $comment_mapper->com_fk_use_id = $use_id;
      $comment_mapper->com_date = date("Y-m-d H:i:s");

      try {
        $comment_mapper->save();
        return $comment_mapper->com_id;
      } catch(Exception $e) {
        $error[] = $e->getMessage();
      }
    }
    return $error;
  }


  /**
   * a_comments
   *
   * Get a collection of comments in array format
   *
   * @param   $vid_id Integer Id of video
   * @return  $ordered_comments Associative array with com_id as key
   *
   */
  function a_comments($vid_id=NULL) {

    $f3 = F3::instance();

    $comments = array();
    $user_ids = array();
    $users = array();
    $ordered = array();
    $params = array();

    // get the comments
    $db = $f3->get("DB");
    $query = "SELECT
        com_id,
        com_comment,
        com_date,
        com_fk_use_id,
        com_fk_vid_id,
        vid_id,
        vid_title_en,
        vid_title_es
      FROM comment
      INNER JOIN video ON com_fk_vid_id=vid_id ";
    if ($vid_id!=NULL) {
      $query .= " WHERE com_fk_vid_id=:vid_id";
      $params = array(":vid_id"=>$vid_id);
    }
    $query .= " ORDER BY com_date ASC";
    $comments = $db->exec($query, $params);

    // get users that commented
    foreach($comments as $comment) {
      $user_ids[$comment["com_fk_use_id"]] = $comment["com_fk_use_id"];
    }
    $customer_model = new CustomerModel();
    $users = $customer_model->list_by_ids($user_ids);

    // comments ordered
    foreach ($comments as $key=>$comment) {
      $ordered[$comment['com_id']] = $comment;
      if (array_key_exists($comment["com_fk_use_id"], $users)) {
        $ordered[$comment['com_id']]["id_customer"] = $users[$comment["com_fk_use_id"]]["id_customer"];
        $ordered[$comment['com_id']]["email"] = $users[$comment["com_fk_use_id"]]["email"];
        $ordered[$comment['com_id']]["firstname"] = $users[$comment["com_fk_use_id"]]["firstname"];
        $ordered[$comment['com_id']]["lastname"] = $users[$comment["com_fk_use_id"]]["lastname"];
      }
      unset($comments[$key]);

    }

    return $ordered;

  }


  /**
   * a_get
   *
   * @param   $com_id Integer
   * @return  $result Associative array of a comment
   *
   * Look for a comment by id and return it in array format.
   */
  function a_get($com_id=NULL) {
    $f3 = F3::instance();

    $db = $f3->get("DB");
    $query = "SELECT
        com_id,
        com_comment,
        com_date,
        com_fk_use_id,
        com_fk_vid_id,
        vid_id,
        vid_title_en,
        vid_title_es
      FROM comment
      INNER JOIN video ON com_fk_vid_id=vid_id
      WHERE com_id=$com_id
      LIMIT 1";
    $result = $db->exec($query);

    return $result[0];
  }



  /**
   * _a_get_by_user
   *
   *
   * Look comments of a user and return them in array format.
   * @param   $id Integer
   * @return  $comments Associative array
   */
  function _a_get_by_user($id) {
    $f3 = F3::instance();

    $db = $f3->get("DB");
    $query = "SELECT 
      com_id,
      com_comment,
      com_date,
      com_fk_vid_id,
      vid_title_en,
      vid_title_es
      FROM comment
      INNER JOIN video ON com_fk_vid_id=vid_id
      WHERE com_fk_use_id=:id
      ORDER BY com_date DESC ";
    $params = array(":id"=>$f3->get('PARAMS.id'));
    $comments = $db->exec($query,$params);

    return $comments;
  }



  /**
   * join_questions_answers
   *
   * @param   $questions  Associative array of questions
   * @param   $answers    Associative array of answers
   * @return  $question   Associative array of answers grouped with questions.
   *
   * NOTE: Both input arrays have parent id as a key.
   */
  function join_questions_answers($questions, $answers) {
    $f3 = F3::instance();
    foreach ($answers as $k=>$answer) {
      if (array_key_exists($k, $questions)) {
        $questions[$k]["com_response"] = $answers[$k];
      }
    }
    return $questions;
  }

}
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
 * ResponseController
 *
 * Class to handle video comment responses.
 * response table stucture:
 * res_id int(11) primary key
 * res_fk_com_id int(11) Foreign key to comments table
 * res_fk_adm_id int(11) Foreign key to admin_user table
 * res_comment text
 * res_date
 */
class ResponseController {

  /**
   * _add
   *
   * @param PARAMS.com_id
   *
   * Prepare template to add a new response to a video comment. Data of the
   * original comment, user and video are required.
   */
  function _add() {

    $f3 = F3::instance();
    $f3->set('comments_selected', 'active');

    $security = new SessionManager();
    $security->force_logout();

    $f3->scrub($_GET);

    // original comment
    $comment_model = new CommentModel();
    $question = $comment_model->a_get($f3->get('PARAMS.com_id'));
    $f3->set("question", $question);

    // original video
    $video_model = new VideoModel();
    $video = $video_model->get_by_id($question["com_fk_vid_id"]);
    $f3->set("video", $video);

    // original author
    $customer_model = new CustomerModel();
    $customer = $customer_model->a_get($question["com_fk_use_id"]);
    $f3->set("customer", $customer);

    // get admin users
    $admin_model = new AdminModel();
    $admins = $admin_model->to_select();
    $f3->set("admins", $admins);

    $f3->set('main_content', 'response_add.html');
    echo Template::instance()->render('main_template.html');
  }



  /**
   * _delete
   *
   * @param   PARAMS.id Integer
   *
   * Delete a response.
   */
  function _delete() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $response_mapper = new DB\SQL\Mapper($f3->get("DB"), "response");
    $response_mapper->load(array('res_id=:id',array(':id'=>$f3->get('PARAMS.id'))));
    try {
      $response_mapper->erase();
      $f3->reroute("/comments/list");
    } catch (Exception $e) {
      $f3->set('moderror', true);
      $f3->set('moderrortext', array("Ocurrió un problema al borrar la respuesta al comentario."));
      
      $f3->set('comments_selected','active');
      $f3->set('main_content', 'comments_list.html');
      echo Template::instance()->render('main_template.html');
    }
  }



  /**
   * _edit
   *
   * @param   PARAMS.id Integer
   *
   * Get a response and fill a webform to update.
   */
  function _edit() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $response_mapper = new DB\SQL\Mapper($f3->get("DB"), "response");
    $response_mapper->load(array('res_id=:id',array(':id'=>$f3->get('PARAMS.id'))));
    $response_mapper->copyTo('POST');

    // original comment
    $comment_model = new CommentModel();
    $question = $comment_model->a_get($f3->get('PARAMS.com_id'));
    $f3->set("question", $question);

    // original video
    $video_model = new VideoModel();
    $video = $video_model->get_by_id($question["com_fk_vid_id"]);
    $f3->set("video", $video);

    // original author
    $customer_model = new CustomerModel();
    $customer = $customer_model->a_get($question["com_fk_use_id"]);
    $f3->set("customer", $customer);

    // admin users to response
    $admin_model = new AdminModel();
    $admins = $admin_model->to_select();
    $f3->set("admins", $admins);

    $f3->set('comments_selected','active');
    $f3->set('main_content', 'response_add.html');
    echo Template::instance()->render('main_template.html');

  }



  /**
   * _update
   *
   * @param   POST.com_comment String
   * @param   POST.com_comment String
   * @param   POST.com_fk_vid_id Integer
   * @param   POST.com_fk_use_id Integer
   *
   * Add a new response or update an existing one.
   */
  function _update() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $error = array();

    // validación de parámetros y eliminación de tags no permitidos
    $f3->scrub($_POST);

    // recuperar por ID si existe, asignar fecha de ahora si no existe
    $id = $f3->get('POST.res_id');
    $response_mapper = new DB\SQL\Mapper($f3->get("DB"), "response");
    if ($id) {    
      $response_mapper->load(array('res_id=:id',array(':id'=>$id)));
    } else  {
      $response_mapper->res_date = date("Y-m-d H:i:s");
    }

    $comment = $f3->get('POST.res_comment');
    if (empty($comment)) {
      $error[] =  "No has indicado el contenido de la respuesta.";
    }

    $user = $f3->get('POST.res_fk_adm_id');
    if (empty($user) || is_null($user) || !is_numeric($user)) {
      $error[] =  "No has seleccionado el usuario autor de la respuesta.";
    }

    if (count($error) == 0) {

      $response_mapper->res_comment = $comment;
      $response_mapper->res_fk_com_id = $f3->get("POST.res_fk_com_id");
      $response_mapper->res_fk_adm_id = $f3->get("POST.res_fk_adm_id");

      try {
        $response_mapper->save();
        $f3->reroute("/comments/list");
      } catch (Exception $e) {
        $error[] = $e::getMessage();
      }
    
    } else {

      $f3->set('moderror', true);
      $f3->set('moderrortext', $error);

      // original comment
      $comment_model = new CommentModel();
      $question = $comment_model->get_by_id($f3->get('PARAMS.com_id'));
      $f3->set("question", $question);

      // original video
      $video_model = new VideoModel();
      $video = $video_model->get_by_id($question["com_fk_vid_id"]);
      $f3->set("video", $video);

      // original author
      $customer_model = new CustomerModel();
      $customer = $customer_model->a_get($question["com_fk_use_id"]);
      $f3->set("customer", $customer);

      // admin users
      $admin_model = new AdminModel();
      $admins = $admin_model->to_select();
      $f3->set("admins", $admins);

      $f3->set('comments_selected','active');
      $f3->set('main_content', 'response_add.html');
      echo Template::instance()->render('main_template.html');

    }

  }

}
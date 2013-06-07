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
 * CommentController
 *
 * CRUP operations for video comments.
 * Video comments are based on comment table with the following structure:
 * com_id integer(11) PRIMARY KEY
 * com_fk_vid_id integer(11) FOREIGN KEY to video table
 * com_fk_use_id integer(11) FOREIGN KEY to customer table (Prestashop)
 * com_date datetime
 */
class CommentController {

  /**
   * _add
   *
   * Prepare template for creating a new comment. Some select input will 
   * require data from videos, users and admin users.
   */
  function _add() {
    $f3 = F3::instance();
    $f3->set('comments_selected', 'active');
    
    $video_model = new VideoModel();
    $f3->set ("videos", $video_model->to_select() );

    $customer_model = new CustomerModel();
    $f3->set ("users", $customer_model->to_select() );

    $admin_model = new AdminModel();
    $f3->set ("admins", $admin_model->to_select() );

    $f3->set('main_content', 'comments_add.html');
    echo Template::instance()->render('main_template.html');
  }


  /**
   * _delete
   *
   * @param   PARAMS.id Integer
   *
   * Delete a video comment identified by id.
   */
  function _delete() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $comment_mapper = new DB\SQL\Mapper($f3->get("DB"), "comment");
    $comment_mapper->load(array('com_id=:id',array(':id'=>$f3->get('PARAMS.id'))));
    try {
      $comment_mapper->erase();
      $f3->reroute("/comments/list");
    } catch (Exception $e) {
      $f3->set('moderror', true);
      $f3->set('moderrortext', array("Ocurrió un problema al borrar el comentario."));
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
   * Get a comment by Id, copy data via POST and fill webform to edit the 
   * comment. Comment template will require some data from videos, admin users
   * and customers to popullate select input fields.
   */
  function _edit() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $comment_mapper = new DB\SQL\Mapper($f3->get("DB"), "comment");
    $comment_mapper->load(array('com_id=:id',array(':id'=>$f3->get('PARAMS.id'))));
    $comment_mapper->copyTo('POST');

    $video_model = new VideoModel();
    $f3->set ("videos", $video_model->to_select() );

    $customer_model = new CustomerModel();
    $f3->set ("users", $customer_model->to_select() );

    $admin_model = new AdminModel();
    $f3->set ("admins", $admin_model->to_select() );

    $f3->set('comments_selected','active');
    $f3->set('main_content', 'comments_add.html');
    echo Template::instance()->render('main_template.html');

  }


  /**
   * _list
   *
   * @access   public
   *
   * Get all comments and it's responses, join them so we can list them
   * properly and prepare paginated list.
   */  
  function _list() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $comment_model = new CommentModel();
    $questions = $comment_model->a_comments();
    $response_model = new ResponseModel();
    $answers = $response_model->a_responses();
    $comments = $comment_model->join_questions_answers($questions, $answers);

// Console::log($comments);
// die();

    // preparepagination
    $page = $f3->get('GET.page') == null ? 1 : $f3->get('GET.page');
    $offset = ($page-1)*$f3->get("PAGE_SIZE");
    $limit = $f3->get("PAGE_SIZE");
    $f3->set('comments',array_slice($comments, $offset, $limit));

    $pagination = new Pagination($limit, count($comments));
    $paginationArray = array();
    if ($pagination->getNumPages()>1) {
      $paginationArray = $pagination->getPagination($limit);
    }
    list($offset, $length) = $pagination->getLimit();
    $f3->set('paginationArray', $paginationArray);
    $f3->set('paginationURL', $f3->get('BASE_URL') . "/admin/comments/list?page=");

    $f3->set('comments_selected', 'active');

    $f3->set('main_content', 'comments_list.html');
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
   * Update a comment or create a new one (if no id parameter was sent).
   */
  function _update() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $error = array();

    $f3->scrub($_POST);

    // get comment by id or set timestamp if a new one is going to be created
    $id = $f3->get('POST.com_id');
    $comment_mapper = new DB\SQL\Mapper($f3->get("DB"), "comment");
    if ($id) {    
      $comment_mapper->load(array('com_id=:id',array(':id'=>$id)));
    } else  {
      $comment_mapper->com_date = date("Y-m-d H:i:s");
    }

    $comment = $f3->get('POST.com_comment');
    if (empty($comment)) {
      $error[] =  "No has indicado el contenido del comentario.";
    }

    $user = $f3->get('POST.com_fk_use_id');
    if (empty($user) || is_null($user) || !is_numeric($user)) {
      $error[] =  "No has seleccionado el usuario autor del comentario.";
    }

    $video = $f3->get('POST.com_fk_vid_id');
    if (empty($video) || is_null($video) || !is_numeric($video)) {
      $error[] =  "No has indicado el video que está comentando.";
    }

    if (count($error) == 0) {

      $comment_mapper->com_comment = $comment;
      $comment_mapper->com_fk_vid_id = $video;
      $comment_mapper->com_fk_use_id = $user;

      try {
        $comment_mapper->save();
        $f3->reroute("/comments/list");
      } catch (Exception $e) {
        $f3->set('moderror', true);
        $error[] = $e::getMessage();
        $f3->set('moderrortext', $error);

        $video_model = new VideoModel();
        $f3->set ("videos", $video_model->to_select() );

        $user_model = new UserModel();
        $f3->set ("users", $user_model->to_select() );

        $f3->set('comments_selected','active');
        $f3->set('main_content', 'comments_add.html');
        echo Template::instance()->render('main_template.html');
      }
    
    } else {

      $f3->set('moderror', true);
      $f3->set('moderrortext', $error);

      $video_model = new VideoModel();
      $f3->set ("videos", $video_model->to_select() );

      $user_model = new UserModel();
      $f3->set ("users", $user_model->to_select() );

      $f3->set('comments_selected','active');
      $f3->set('main_content', 'comments_add.html');
      echo Template::instance()->render('main_template.html');

    }

  }

}
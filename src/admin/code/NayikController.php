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
 * NayikController
 * 
 * Class to handle administrative tools for Nayik conversations.
 * nayik table structure:
 * nay_id int(11)
 * nay_fk_cha_id int(11) Foreign key to characters table.
 * nay_date  datetime
 * nay_comment text
 * nay_parent_id int(11)
 */
class NayikController {

  /**
   * _add
   *
   * Prepare template to create a new Nayik post.
   */
  function _add() {
    $f3 = F3::instance();
    $f3->set('nayik_selected', 'active');

    $character_model = new CharacterModel();
    $f3->set ("characters", $character_model->to_select() );

    $f3->set('main_content', 'nayik_add.html');
    echo Template::instance()->render('main_template.html');
  }


  /**
   * _addResponse
   *
   * Prepare template to create a new response to a Nayik post.
   */
  function _addResponse() {
    $f3 = F3::instance();
    $f3->set('nayik_selected', 'active');

    $f3->scrub($_GET);

    // get the original Nayik post
    $nayik_model = new NayikModel();
    $post = $nayik_model->get_by_id($f3->get('PARAMS.nay_id'));
    $f3->set("post", $post);

    // ... and the original author
    $character_model = new CharacterModel();
    $character = $character_model->get_by_id($post->nay_fk_cha_id);
    $f3->set("character", $character);

    // get all available characters to response
    $f3->set ("characters", $character_model->to_select() );

    $f3->set('main_content', 'nayik_response_add.html');
    echo Template::instance()->render('main_template.html');
  }



  /**
   * _delete
   *
   * @param   PARAMS.id Integer
   *
   * Delete a post.
   */
  function _delete() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $nayik_model = new NayikModel();
    try {
      $nayik_model->delete($f3->get('PARAMS.id'));
      $f3->reroute("/nayik/list");
    } catch (Exception $e) {
      $f3->set('moderror', true);
      $f3->set('moderrortext', array("Ocurrió un problema al borrar el post."));
      
      $f3->set('nayik_selected','active');
      $f3->set('main_content', 'nayik_list.html');
      echo Template::instance()->render('main_template.html');
    }
  }



  /**
   * _deleteResponse
   *
   * Delete a response to a post.
   */
  function _deleteResponse() {
    $this->_delete();
  }


  /**
   * _edit
   *
   * @param   PARAMS.id Integer
   *
   * Get a Nayik post by Id, copy data via POST and fill edit form.
   */
  function _edit() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $nayik_model = new NayikModel();
    $post = $nayik_model->get_by_id($f3->get('PARAMS.id'));
    $post->copyTo("POST");

    $character_model = new CharacterModel();
    $f3->set ("characters", $character_model->to_select() );

    $f3->set('op','_UPDATE_');
    $f3->set('nayik_selected','active');
    $f3->set('main_content', 'nayik_add.html');
    echo Template::instance()->render('main_template.html');

  }


  /**
   * _editResponse
   *
   * @param   PARAMS.id Integer
   *
   * Get a Nayik post by Id, copy data via POST and fill edit form.
   */
  function _editResponse() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $nayik_model = new NayikModel();
    $post = $nayik_model->get_by_id($f3->get('PARAMS.nay_id'));
    // $post->copyTo("POST");
    $f3->set("post", $post);

    $character_model = new CharacterModel();
    $f3->set("character", $character_model->get_by_id($post->nay_fk_cha_id));
    $f3->set("characters", $character_model->to_select() );

    $response = $nayik_model->get_by_id($f3->get('PARAMS.id'));
    $response->copyTo("POST");

    $f3->set('op','_UPDATE_');
    $f3->set('nayik_selected','active');
    $f3->set('main_content', 'nayik_response_add.html');
    echo Template::instance()->render('main_template.html');

  }



  /**
   * _list
   *
   * Get all Nayik conversations and list them.
   * Pagination basics:
   * - Page number get by URL parameter.
   * - Pagination class declared in /classes/Pagination.php
   * - Size of page get by URL parameter
   */  
  function _list() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    // preparar la paginación
    if ( $f3->get('GET.page') == null) {
      $page = 1;
    } else {
      $page = $f3->get('GET.page');
    }
    $offset = ($page-1)*$f3->get("PAGE_SIZE");
    $limit = $f3->get("PAGE_SIZE");

    $nayik_model = new NayikModel();
    $posts = $nayik_model->getPosts();
    $f3->set('posts',$posts);
    $total = $nayik_model->countPosts();
    $f3->set("total",$total);

    $pagination = new Pagination($limit, count($posts));
    $paginationArray = array();
    if ($pagination->getNumPages()>1) {
      $paginationArray = $pagination->getPagination($limit);
    }
    list($offset, $length) = $pagination->getLimit();
    $f3->set('paginationArray', $paginationArray);
    $f3->set('paginationURL', $f3->get('BASE_URL') . "/admin/nayik/list?page=");

    $f3->set('nayik_selected', 'active');

    $f3->set('main_content', 'nayik_list.html');
    echo Template::instance()->render('main_template.html');
  }


  /**
   * _update
   *
   * @param   POST.nay_id Integer
   * @param   POST.nay_date String
   * @param   POST.nay_comment String
   * @param   POST.nay_fk_cha_id Integer
   *
   * Updates an existing post or creates a new one.
   */
  function _update() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $nayik_mapper = new DB\SQL\Mapper($f3->get("DB"), "nayik");
    $error = array();

    $character_model = new CharacterModel();
    $f3->set ("characters", $character_model->to_select() );

    $f3->scrub($_POST);

    // rget post by id if it exists
    $id = $f3->get('POST.nay_id');
    if ($id) {    
      $nayik_mapper->load(array('nay_id=:id',array(':id'=>$id)));
    }

    $comment = $f3->get('POST.nay_comment');
    if (empty($comment)) {
      $error[] =  "No has indicado el comentario del post.";
    }

    $date = $f3->get('POST.nay_date');
    if (empty($date)) {
      $error[] =  "No has indicado la fecha de publicación del post.";
    }

    $character = $f3->get('POST.nay_fk_cha_id');

    if (count($error)==0) {

      $nayik_mapper->nay_comment = $comment;
      $nayik_mapper->nay_date = $date;
      $nayik_mapper->nay_fk_cha_id = $character;

      try {
        $nayik_mapper->save();
        $f3->reroute("/nayik/list");
      } catch (Exception $e) {
        $error[] = $e->getMessage();
        $f3->set('moderror', true);
        $f3->set('moderrortext', $error);
        $f3->set('nayik_selected','active');
        $f3->set('main_content', 'nayik_add.html');
        echo Template::instance()->render('main_template.html');
      }
    
    } else {
      
      $f3->set('moderror', true);
      $f3->set('moderrortext', $error);
      
      $f3->set('nayik_selected','active');
      $f3->set('main_content', 'nayik_add.html');
      echo Template::instance()->render('main_template.html');

    }

  }



  /**
   * _updateResponse
   *
   * @param   POST.nay_id Integer
   * @param   POST.nay_date String
   * @param   POST.nay_comment String
   * @param   POST.nay_fk_cha_id Integer
   * @param   POST.nay_parent_id Integer
   *
   * Updates an existing response to a nayik post.
   */
  function _updateResponse() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $nayik_mapper = new DB\SQL\Mapper($f3->get("DB"), "nayik");
    $error = array();

    $character_model = new CharacterModel();
    $f3->set ("characters", $character_model->to_select() );

    $f3->scrub($_POST);

    // get post by id if it exists
    $id = $f3->get('POST.nay_id');
    if ($id) {    
      $nayik_mapper->load(array('nay_id=:id',array(':id'=>$id)));
    }

    $comment = $f3->get('POST.nay_comment');
    if (empty($comment)) {
      $error[] =  "No has indicado el comentario del post.";
    }

    $date = $f3->get('POST.nay_date');
    if (empty($date)) {
      $error[] =  "No has indicado la fecha de publicación del post.";
    }

    $character = $f3->get('POST.nay_fk_cha_id');

    $parent = $f3->get('POST.nay_parent_id');

    if (count($error)==0) {

      $nayik_mapper->nay_comment = $comment;
      $nayik_mapper->nay_date = $date;
      $nayik_mapper->nay_fk_cha_id = $character;
      $nayik_mapper->nay_parent_id = $parent;

      try {
        $nayik_mapper->save();
        $f3->reroute("/nayik/list");
      } catch (Exception $e) {
        $error[] = $e->getMessage();
        $f3->set('moderror', true);
        $f3->set('moderrortext', $error);
        $f3->set('nayik_selected','active');
        $f3->set('main_content', 'nayik_response_add.html');
        echo Template::instance()->render('main_template.html');
      }
    
    } else {
      
      $f3->set('moderror', true);
      $f3->set('moderrortext', $error);
      
      $f3->set('nayik_selected','active');
      $f3->set('main_content', 'nayik_response_add.html');
      echo Template::instance()->render('main_template.html');

    }

  }

}
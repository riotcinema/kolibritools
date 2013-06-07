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
 * RemixController
 *
 * Class to handle administrative tools for remix videos.
 * Remix table structure:
 * rem_id int(11) primary key
 * rem_title varchar(128)
 * rem_url varchar(128)
 * rem_author  varchar(128)
 * rem_channel_url varchar(128)
 * rem_highlight tinyint(1)
 */
class RemixController {

  /**
   * _add
   *
   * Prepare template to create a new video remix.
   */
  function _add() {
    $f3 = F3::instance();
    $f3->set('remix_selected', 'active');

    $f3->set('main_content', 'remixes_add.html');
    echo Template::instance()->render('main_template.html');
  }


  /**
   * _delete
   *
   * @param   PARAMS.id Integer
   *
   * Delete a video remix.
   */
  function _delete() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $remix_mapper = new DB\SQL\Mapper($f3->get("DB"), "remix");
    $remix_mapper->load(array('rem_id=:id',array(':id'=>$f3->get('PARAMS.id'))));
    try {
      $remix_mapper->erase();
      $f3->reroute("/remix/list");
    } catch (Exception $e) {
      $f3->set('moderror', true);
      $f3->set('moderrortext', array("Ocurrió un problema al borrar la remezcla."));
      
      $f3->set('remix_selected','active');
      $f3->set('main_content', 'remixes_list.html');
      echo Template::instance()->render('main_template.html');
    }
  }


  /**
   * _edit
   *
   * @param   PARAMS.id Integer
   *
   * Get a video remix, copy via POST and prepare a template tu update.
   */
  function _edit() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $remix_mapper = new DB\SQL\Mapper($f3->get("DB"), "remix");
    $remix_mapper->load(array('rem_id=:id',array(':id'=>$f3->get('PARAMS.id'))));
    $remix_mapper->copyTo('POST');

    $f3->set('remix_selected','active');
    $f3->set('main_content', 'remixes_add.html');
    echo Template::instance()->render('main_template.html');
  }


  /**
   * _list
   *
   * Get all video remix and list them.
   * Pagination basics:
   * - Page number get by URL parameter.
   * - Pagination class declared in /classes/Pagination.php
   * - Size of page get by URL parameter
   */  
  function _list() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $remix_mapper = new DB\SQL\Mapper($f3->get("DB"), "remix");
    $remixes = $remix_mapper->find();

    $page = $f3->get('GET.page') == null ? 1 :$f3->get('GET.page');
    $offset = ($page-1)*$f3->get("PAGE_SIZE");
    $limit = $f3->get("PAGE_SIZE");
    $f3->set('remixes',array_slice($remixes, $offset, $limit));

    $pagination = new Pagination($limit, count($remixes));
    $paginationArray = array();
    if ($pagination->getNumPages()>1) {
      $paginationArray = $pagination->getPagination($limit);
    }
    list($offset, $length) = $pagination->getLimit();
    $f3->set('paginationArray', $paginationArray);
    $f3->set('paginationURL', $f3->get('BASE_URL') . "/admin/remix/list?page=");

    $f3->set('remixes_selected', 'active');

    $f3->set('main_content', 'remixes_list.html');

    echo Template::instance()->render('main_template.html');
  }


  /**
   * _update
   *
   * @param   POST.rem_id Integer
   * @param   POST.rem_title String
   * @param   POST.rem_url_en String
   * @param   POST.rem_author_es String
   * @param   POST.rem_channel_url String
   * @param   POST.rem_highlight Boolean
   *
   * Update an existing remix or create a new one.
   */
  function _update() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $remix_mapper = new DB\SQL\Mapper($f3->get("DB"), "remix");
    $remix_mapper_check = new DB\SQL\Mapper($f3->get("DB"), "remix");
    $error = array();

    $f3->scrub($_POST);

    // get by id if remix esists
    $id = $f3->get('POST.rem_id');
    if ($id) {    
      $remix_mapper->load(array('rem_id=:id',array(':id'=>$id)));
    }

    $title = $f3->get('POST.rem_title');
    if (empty($title)) {
      $error[] =  "No has indicado el título del video.";
    }

    $url = $f3->get('POST.rem_url');
    if (empty($url)) {
      $error[] =  "No has indicado la URL del vídeo.";
    }
    $existing = $remix_mapper_check->load(array("rem_id!=:id AND rem_url=:url", array(":id"=>$id, ":url"=>$url) ));
    if (!$remix_mapper_check->dry()) {
      $error[] = "Ya existe un remix con esa URL."; 
    }

    $highlight = $f3->get('POST.rem_highlight');
    $highlight = $highlight == 'on' ? true : false;

    $author = $f3->get('POST.rem_author');
    if (empty($author)) {
      $error[] =  "No has indicado el autor del remix.";
    }

    $channel_url = $f3->get('POST.rem_channel_url');
    if (!empty($channel_url) && (!filter_var($channel_url, FILTER_VALIDATE_URL))) {
      $error[] =  "La URL del canal del autor no es válida.";
    }

    if (count($error)==0) {

      $remix_mapper->rem_title = $title;
      $remix_mapper->rem_url = $url;
      $remix_mapper->rem_highlight = $highlight;
      $remix_mapper->rem_author = $author;
      $remix_mapper->rem_channel_url = $channel_url;

      try {
        $remix_mapper->save();
        if ($remix_mapper->rem_highlight) {
          $remix_model = new RemixModel();
          $remix_model->highlight($remix_mapper->rem_id);
        }
        $f3->reroute("/remixes/list");
      } catch (Exception $e) {
        $error[] = $e->getMessage();
        $f3->set('moderror', true);
        $f3->set('moderrortext', $error);
        $f3->set('remix_selected','active');
        $f3->set('main_content', 'remixes_add.html');
        echo Template::instance()->render('main_template.html');
      }
    
    } else {
      
      $f3->set('moderror', true);
      $f3->set('moderrortext', $error);
      
      $f3->set('remix_selected','active');
      $f3->set('main_content', 'remixes_add.html');
      echo Template::instance()->render('main_template.html');

    }

  }

}
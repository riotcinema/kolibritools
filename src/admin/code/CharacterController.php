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
 * CharacterController
 * 
 * CRUD operations for Nayik Characters.
 * Nayik characters are based on characters table with the following structure:
 * cha_id int(11) PRIMARY KEY AUTO INCREMENT
 * cha_name varchar(32)
 * cha_fullname varchar(128)
 * cha_avatar varchar(128)
 */
class CharacterController {


  /**
   * _add
   *
   * Prepare template to create a new Nayik character.
   */
  function _add() {
    $f3 = F3::instance();
    $f3->set('characters_selected', 'active');

    $f3->set('main_content', 'characters_add.html');
    echo Template::instance()->render('main_template.html');
  }



  /**
   * _edit
   *
   * @param   PARAMS.id Integer
   *
   * Get a character from database by id, copy data via POST anf fill form to
   * update it's info.
   */
  function _edit() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $character_mapper = new DB\SQL\Mapper($f3->get("DB"), "characters");
    $character_mapper->load(array('cha_id=:id',array(':id'=>$f3->get('PARAMS.id'))));
    $character_mapper->copyTo('POST');

    $f3->set('characters_selected','active');
    $f3->set('main_content', 'characters_add.html');
    echo Template::instance()->render('main_template.html');
  }




  /**
   * _list
   *
   * @access   public
   *
   * Get a list of all chacaters of nayik and list them.
   * Pagination is based on:
   * - Page number get by URL params.
   * - Pagination class defined in /classes/Pagination.php
   * - Page size defined via PAGE_SIZE constant in config.php
   */  
  function _list() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    // preparar la paginación
    $page = $f3->get('GET.page') == null ? 1 : $f3->get('GET.page');
    $offset = ($page-1)*$f3->get("PAGE_SIZE");
    $limit = $f3->get("PAGE_SIZE");

    // retrieve characters
    $character_mapper = new DB\SQL\Mapper($f3->get("DB"), "characters");
    $characters = $character_mapper->find(NULL, array('offset'=>$offset, 'limit'=>$limit));
    $total = $character_mapper->count();
    $f3->set('characters',$characters);

    $pagination = new Pagination($limit, count($characters));
    $paginationArray = array();
    if ($pagination->getNumPages()>1) {
      $paginationArray = $pagination->getPagination($limit);
    }
    list($offset, $length) = $pagination->getLimit();
    $f3->set('paginationArray', $paginationArray);
    $f3->set('paginationURL', $f3->get('BASE_URL') . "/admin/characters/list?page=");

    $f3->set('characters_selected', 'active');

    $f3->set('main_content', 'characters_list.html');
    echo Template::instance()->render('main_template.html');
  }



  /**
   * _update
   *
   * @param   POST.cha_id       Integer
   * @param   POST.cha_name     String
   * @param   POST.cha_fullname String
   * @param   POST.cha_avatar   String
   *
   * Update an existing Nyik character or create a new one.
   */
  function _update() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $error = array();

    $character_mapper = new DB\SQL\Mapper($f3->get("DB"), "characters");

    $f3->scrub($_POST);

    // get character ID if it exists
    $id = $f3->get('POST.cha_id');
    if ($id) {    
      $character_mapper->load(array('cha_id=:id',array(':id'=>$id)));
    }

    $name = $f3->get('POST.cha_name');
    if (empty($name)) {
      $error[] =  "No has indicado el nombre del personaje.";
    }

    $fullname = $f3->get('POST.cha_fullname');
    if (empty($fullname)) {
      $error[] =  "No has indicado el nombre completo del personaje.";
    }

    $avatar = $f3->get('POST.cha_avatar');
    if (empty($avatar)) {
      $error[] =  "No has indicado el avatar del personaje.";
    }

    if (count($error)==0) {

      $character_mapper->cha_name = $name;
      $character_mapper->cha_fullname = $fullname;
      $character_mapper->cha_avatar = $avatar;

      try {
        $character_mapper->save();
        $f3->reroute("/characters/list");
      } catch (Exception $e) {
        $error[] = $e->getMessage();
        $f3->set('moderror', true);
        $f3->set('moderrortext', $error);
        $f3->set('characters_selected','active');
        $f3->set('main_content', 'characters_add.html');
        echo Template::instance()->render('main_template.html');
      }
    
    } else {
      
      $f3->set('moderror', true);
      $f3->set('moderrortext', $error);
      
      $f3->set('characters_selected','active');
      $f3->set('main_content', 'characters_add.html');
      echo Template::instance()->render('main_template.html');

    }

  }

}
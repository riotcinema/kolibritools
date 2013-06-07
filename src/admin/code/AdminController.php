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
 * AdminController
 *
 * CRUD operations for admin users.
 * Admin user is based on admin_user table with the following structure:
 * usu_id int(11) PRIMARY KEY AUTO INCREMENT
 * usu_nick varchar(250)
 * usu_password varchar(250)
 * usu_email varchar(250)
 * usu_level int(11)
 */
class AdminController {

  /**
   * _add
   *
   * Prepare template for creating a new administrator user.
   */
  function _add() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();
    $security->force_tecni();

    $f3->set('admins_selected', 'active');

    $f3->set('main_content', 'admins_add.html');
    echo Template::instance()->render('main_template.html');
  }



  /**
   * _delete
   *
   * @param   PARAMS.id Integer
   *
   * Delete an admin user identified by id in database.
   */
  function _delete() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();
    $security->force_tecni();

    $admin_mapper = new DB\SQL\Mapper($f3->get("DB"), "admin_user");
    $admin_mapper->load(array('usu_id=:id',array(':id'=>$f3->get('PARAMS.id'))));
    try {
      $admin_mapper->erase();
      $f3->reroute("/admins/list");
    } catch (Exception $e) {
      $f3->set('moderror', true);
      $f3->set('moderrortext', array("Ocurrió un problema al borrar el administrador."));
      
      $f3->set('admins_selected','active');
      $f3->set('main_content', 'admins_list.html');
      echo Template::instance()->render('main_template.html');
    }
  }



  /**
   * _edit
   *
   * @param   PARAMS.id Integer
   *
   * Get the admin user identified by id and fill a web form with data.
   */
  function _edit() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();
    $security->force_tecni();

    $admin_model = new AdminModel();
    $admin = $admin_model->get($f3->get('PARAMS.id'));
    $admin->copyTo("POST");

    $f3->set('admins_selected','active');
    $f3->set('main_content', 'admins_add.html');
    echo Template::instance()->render('main_template.html');

  }



  /**
   * _list
   *
   * @access   public
   *
   * Get all admin users and render a template to list them.
   * Pagination is based on:
   * - Page number get by URL params.
   * - Pagination class defined in /classes/Pagination.php
   * - Page size defined via PAGE_SIZE constant in config.php
   */  
  function _list() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();
    $security->force_tecni();

    // prepare pagination
    $page = $f3->get('GET.page') == null ? 1 : $f3->get('GET.page');
    $offset = ($page-1)*$f3->get("PAGE_SIZE");
    $limit = $f3->get("PAGE_SIZE");

    // retrieve admin users
    $admin_mapper = new DB\SQL\Mapper($f3->get("DB"), "admin_user");
    $admins = $admin_mapper->find(NULL, array('offset'=>$offset, 'limit'=>$limit));
    $total = $admin_mapper->count();
    $f3->set('admins',$admins);

    $pagination = new Pagination($limit, count($admins));
    $paginationArray = array();
    if ($pagination->getNumPages()>1) {
      $paginationArray = $pagination->getPagination($limit);
    }
    list($offset, $length) = $pagination->getLimit();
    $f3->set('paginationArray', $paginationArray);
    $f3->set('paginationURL', $f3->get('BASE_URL') . "/admin/admins/list?page=");

    $f3->set('admins_selected', 'active');

    $f3->set('main_content', 'admins_list.html');
    echo Template::instance()->render('main_template.html');
  }



  /**
   * _password
   *
   * @param   PARAMS.id Integer
   *
   * Get a user identified by Id and render web form to reset password.
   */
  function _password() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();
    $security->force_tecni();

    $admin_model = new AdminModel();
    $admin = $admin_model->get($f3->get('PARAMS.id'));
    $admin->copyTo("POST");

    $f3->set('admins_selected','active');
    $f3->set('main_content', 'admins_password.html');
    echo Template::instance()->render('main_template.html');

  }


  /**
   * _reset_password
   *
   * @param   POST.usu_id         Integer
   * @param   POST.usu_password   String
   * @param   POST.usu_password2  String
   *
   * Update password of an admin user identified by Id.
   */
  function _reset_password() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();
    $security->force_tecni();

    $error = array();

    $f3->scrub($_POST);

    $id = $f3->get('POST.usu_id');
    $password = $f3->get('POST.usu_password');
    $password2 = $f3->get('POST.usu_password_2');

    $admin_model = new AdminModel();
    $admin = $admin_model->get($id);
    $admin->copyTo("POST");

    // update password process
    $admin_reset = $admin_model->reset_password($id, $password, $password2);
    if (is_array($admin_reset)) {
      $error = array_merge($admin_reset, $error);
    } else {
      // everything ok?
      $f3->reroute("/admins/list");
    }

    // Something went wrong. Need to build errors array
    $f3->set('moderror', true);
    $f3->set('moderrortext', $error);
    $f3->set('admins_selected','active');
    $f3->set('main_content', 'admins_password.html');
    echo Template::instance()->render('main_template.html');

  }




  /**
   * _update
   *
   * @param   POST.usu_id         Integer
   * @param   POST.usu_nick       String
   * @param   POST.usu_email      String
   * @param   POST.usu_level      Integer
   * @param   POST.usu_password   String
   * @param   POST.usu_password2  String
   *
   * Update an existing admin user or create a new one.
   */
  function _update() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();
    $security->force_tecni();

    $error = array();

    $f3->scrub($_POST);

    $admin_model = new AdminModel();

    $id = $f3->get('POST.usu_id');
    $nick = $f3->get('POST.usu_nick');
    $email = $f3->get('POST.usu_email');
    $level = $f3->get('POST.usu_level');
    $level = $level == 'on' ? 100 : NULL;
    $password = $f3->get('POST.usu_password');
    $password2 = $f3->get('POST.usu_password_2');

    if (empty($id)) {

      // create a new admin user
      $admin_create = $admin_model->create($nick, $email, $level, $password, $password2);
      if (is_array($admin_create)) {
        $error = $admin_create;
        // Something went wrong. Need to build errors array
        $f3->set('moderror', true);
        $f3->set('moderrortext', $error);
        $f3->set('admins_selected','active');
        $f3->set('main_content', 'admins_add.html');
        echo Template::instance()->render('main_template.html');

      } else {
        // everything ok?
        $f3->reroute("/admins/list");
      }

    } else {

      // update an admin user
      $admin_update = $admin_model->update($id, $nick, $email, $level);
      if (is_array($admin_update)) {
        $error = $admin_update;
        // Something went wrong. Need to build errors array
        $f3->set('moderror', true);
        $f3->set('moderrortext', $error);
        $f3->set('admins_selected','active');
        $f3->set('main_content', 'admins_add.html');
        echo Template::instance()->render('main_template.html');

      } else {
        // everything ok?
        $f3->reroute("/admins/list");
      }

    }

  }

}
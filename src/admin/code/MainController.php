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
 * MainController
 * 
 * Login, logout and home page operations.
 */
class MainController {

  /**
   * login
   *
   * @param   String PARAM.user
   * @param   String PARAM.password
   *
   * Login operation to validate an admin user access.
   */
  function login() {
    $f3 = F3::instance();

    $result = array ("success"=>TRUE, "msg"=>array());

    $f3->scrub($_POST);

    $aAdminUser=new DB\SQL\Mapper($f3->get("DB"),'admin_user');
    $aAdminUser->load(array('usu_nick=?',$f3->get("POST.user")));
    if ($aAdminUser->dry()) {
      $f3->reroute('/loginerror');
    }

    $bcrypt = new Bcrypt(12);
    if ($bcrypt->verify($f3->get("POST.password"), $aAdminUser->usu_password)) {   
      $f3->set('SESSION.admin_login',$aAdminUser->usu_nick);
      $f3->set('SESSION.admin_id',$aAdminUser->usu_id);
      $f3->set('SESSION.admin_level',$aAdminUser->usu_level);
      $aAdminUser->usu_logindate = date('Y-m-d H:i:s');
      $aAdminUser->save();
      $f3->reroute('/');
    } else {
      $f3->reroute('/loginerror');
    }
  }


  /**
   * login_error
   *
   * Prepare a template to display an indication of incorrect login.
   * a true.
   */
  function login_error() {
    $f3 = F3::instance();

    $f3->set('loginerror',true);
    $f3->set('tem_content', 'login.html');
    echo Template::instance()->render('login_template.html');
  }


  /**
   * logout
   *
   * Destroy session data and reroute user to login section.
   */
  function logout() {
    $f3 = F3::instance();

    $f3->clear('SESSION');
    $f3->reroute('/');
  }


  /**
   * main
   *
   * Display login template for the admin module.
   */
  function main() {
    $f3 = F3::instance();

    $security = new SessionManager();
    if ($security->is_admin()) {
      echo Template::instance()->render('main_template.html');
    } else {
      $f3->set('tem_content', 'login.html');
      echo Template::instance()->render('login_template.html');
    }
    
  }

}
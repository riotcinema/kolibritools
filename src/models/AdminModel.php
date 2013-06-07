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
 * AdminModel
 *
 * Admin user is based on admin_user table with the following structure:
 * usu_id int(11) PRIMARY KEY AUTO INCREMENT
 * usu_nick varchar(250)
 * usu_password varchar(250)
 * usu_email varchar(250)
 * usu_level int(11)
 */
class AdminModel {

  /**
   * create
   *
   * @param $nick       String
   * @param $email      String
   * @param $level      String
   * @param $password   String
   * @param $password2  String
   *
   * Create a new admin user.
   */
  function create ($nick, $email, $level, $password, $password2) {
    $f3 = F3::instance();

    $error = array();

    $admin_mapper_check = new DB\SQL\Mapper($f3->get("DB"), "admin_user");
    $admin_mapper = new DB\SQL\Mapper($f3->get("DB"), "admin_user");

    if (empty($nick)) {
      $error[] =  "No has indicado el nombre de usuario.";
    }
    $existing = $admin_mapper_check->load(array("usu_nick=:nick",array(":nick"=>$nick)));
    if (!$admin_mapper_check->dry()) {
      $error[] = "El nick de usuario ya existe."; 
    }

    if (empty($email) || (!filter_var($email, FILTER_VALIDATE_EMAIL))) {
      $error[] =  "No has indicado el email del usuario.";
    }
    $existing = $admin_mapper_check->load(array("usu_email=:email",array(":email"=>$email)));
    if (!$admin_mapper_check->dry()) {
      $error[] = "El email $email ya existe."; 
    }

    if (empty($password) || empty($password2)) {
      $error[] =  "No has indicado la contraseña del usuario.";
    }
    if ($password!=$password2) {
      $error[] =  "La contraseña y su verificación no existen.";
    }

    if (count($error)==0) {

      $bcrypt = new Bcrypt(12);
      $admin_mapper->usu_nick = $nick;
      $admin_mapper->usu_email = $email;
      $admin_mapper->usu_level = $level;
      $admin_mapper->usu_password = $bcrypt->hash($password);

      try {
        $admin_mapper->save();
        return $admin_mapper->usu_id;
      } catch (Exception $e) {
        $error[] = $e->getMessage();
      }
    }
    return $error;
  }


  /**
   * get
   *
   * @param $id Integer
   *
   * Get admin user by id.
   */
  function get($id) {
    $f3 = F3::instance();

    $admin_mapper = new DB\SQL\Mapper($f3->get("DB"), "admin_user");
    $admin_mapper->load(array('usu_id=:id',array(':id'=>$id)));

    return $admin_mapper;
  }


  /**
   * list_by_ids
   *
   * @param $ids  Array
   *
   * Get a group of admin users identified by a collection of ids.
   */
  function list_by_ids ($ids) {
    $f3 = F3::instance();

    $admins  =array();
    if (!is_array($ids)) {
      return $admins;
    }

    $db = $f3->get("DB");
    $query = " SELECT usu_id, usu_nick, usu_email FROM admin_user WHERE usu_id IN ( ".implode(", ", $ids )." ) ";
    try {
      $result = $db->exec($query);
      // build an associative array with usu_id as key
      foreach ($result as $r=>$admin) {
        $admins[$admin["usu_id"]] = $admin;
        unset($result[$r]);
      }

    } catch (Exception $e) {
      $error[] = $e->getMessage();
    }

    return $admins;

  }



  /**
   * reset_password
   *
   * @param $id        Integer
   * @param $password  String
   * @param $password2 String
   */
  function reset_password($id, $password, $password2) {

    $f3 = F3::instance();

    $error = array();

    $user_mapper = new DB\SQL\Mapper($f3->get("DB"), "admin_user");

    if (empty($password) || empty($password2)) {
      $error[] =  "No has indicado la contraseña del usuario.";
    }
    if ($password!=$password2) {
      $error[] =  "La contraseña y su verificación no existen.";
    }

    if (count($error)==0) {

      $bcrypt = new Bcrypt(12);
      $db = $f3->get("DB");
      $query = " UPDATE admin_user SET usu_password = :password WHERE usu_id = :id ";
      $params = array(':password'=>$bcrypt->hash($password), ':id'=>$id);

      try {
        $result = $db->exec($query, $params);
        return TRUE;
      } catch (Excpetion $e) {
        $error[] = $e->getMessage();
      }

    }
    return $error;

  }



  /**
   * to_select
   *
   * Build an array of admin users prepared to popullate select inputs.
   *
   * @return Associative array (usu_id, usu_nick, usu_email)
   */
  function to_select() {
    $f3 = F3::instance();
    $db = $f3->get("DB");
    return $db->exec("SELECT usu_id, usu_nick, usu_email FROM admin_user ORDER BY usu_nick ASC");
  }



  /**
   * update
   *
   * @param $id     Integer
   * @param $level  String
   * @param $nick   String
   * @param $email  String
   *
   * Update admin users data.
   */
  function update($id, $nick, $email, $level) {

    $f3 = F3::instance();

    $error = array();

    $admin_mapper_check = new DB\SQL\Mapper($f3->get("DB"), "admin_user");
    $user_mapper = new DB\SQL\Mapper($f3->get("DB"), "admin_user");

    if (empty($nick)) {
      $error[] =  "No has indicado el nick del usuario.";
    }
    $existing = $admin_mapper_check->afind(array("usu_id!=:id AND usu_nick=:nick",array(":id"=>$id, ":nick"=>$nick)));
    if (count($existing)>0) {
      $error[] = "El nick de usuario ya existe."; 
    }

    if (empty($email) || (!filter_var($email, FILTER_VALIDATE_EMAIL))) {
      $error[] =  "No has indicado el email del usuario.";
    }
    $existing = $admin_mapper_check->afind(array("usu_id!=:id AND usu_email=:email",array(":id"=>$id, ":email"=>$email)));
    if (count($existing)>0) {
      $error[] = "El e-mail de usuario ya existe."; 
    }

    if (count($error)==0) {

      $db = $f3->get("DB");
      $query = " UPDATE admin_user SET usu_nick = :nick, usu_email = :email, usu_level = :level WHERE usu_id = :id ";
      $params = array(':nick'=>$nick, ':email'=>$email, ':level'=>$level, ':id'=>$id );
      try {
        $result = $db->exec($query, $params);
        return TRUE;
      } catch (Excpetion $e) {
        $error[] = $e->getMessage();
      }

    }
    return $error;

  }

}
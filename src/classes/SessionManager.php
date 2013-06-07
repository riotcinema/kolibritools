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
 * SessionManager
 *
 * Class to handle sessions and privileges.
 * 
 */
class SessionManager {

  /**
   * force_logout
   *
   * Force logout of a user if it does not have admin user privileges. Destroy
   * session and redirect to login page.
   */
  public function force_logout() {
    $f3 = F3::instance();
    if ($this->is_admin()==FALSE) {
      $f3->clear('SESSION');
      $f3->reroute('/');
    }
  }


  /**
   * force_tecni
   *
   * Force logout of a user if it does not have SUPER USER privileges. Destroy
   * session and redirect to login page.
   */
  public function force_tecni() {
    $f3 = F3::instance();  
    if ($f3->get('SESSION.admin_level')!=100) {
      $f3->reroute('/');
    }
  }


  /**
   * is_admin
   *
   * @return Boolean
   *
   * Check if a user has admin privileges.
   */
  public function is_admin() {
    $f3 = F3::instance();
    return $f3->get('SESSION.admin_login') && $f3->get('SESSION.admin_id');
  }


  /**
   * is_guest
   * 
   * @return Boolean
   *
   * Check if user is a guest.
   */
	static function is_guest() {
		$f3 = F3::instance();
		$login = $f3->get('COOKIE.login');
		$auth = isset($login);
		return $auth ? 1 : 0;
	}


  /**
   * is_logged
   *
   * @access      static
   * @return      Boolean
   *
   *
   * Check if user is logged via SESSION data.
   */
  static function is_logged() {
    $f3 = F3::instance();
    $login = $f3->get('SESSION.login');
    $id = $f3->get('SESSION.id');
    $auth = isset($login) && isset($id);
    return $auth ? 1 : 0;
  }


  /**
   * set_guest
   * 
   * 
   */
  static function set_guest($login) {
  	$f3 = F3::instance();
  	$login = $login;
  	$id = md5(uniqid(rand(), true));
  	$f3->set('COOKIE.login', $login);
  }



  /**
   * validate
   *
   * @param   $login String
   * @param   $password String
   * @return  $response Array("result"=>"ok/error", "msg"=>"")
   *
   * Validate access data of a user. Create a new session if valid. Return an
   * array with result of validation process.
   */
  public function validate($login, $password) {

    $f3 = F3::instance();
    $response = array("result"=>"error", "msg"=>$f3->get("dict_log_err"));

    if (!empty($login) && !empty($password)) {
    	// primero comprobamos que sea un productor
    	$customer_model = new CustomerModel();
			$user = $customer_model->is_a_backer($login);
			if ($user==NULL) {
				// si no lo es, a ver si ha comprado un K-Pass
				$user = $customer_model->is_a_cosmonaut($login);
				if ($user==NULL) {
					// si no tiene K-Pass a ver si por lo menos ha comprado algo
					$user = $customer_model->is_a_buyer($login);
				}
			}

			if ($user!=NULL) {
				if ($user['passwd'] == md5(addslashes($f3->get("DB_SHOP_COOKIE").$password))) {
					$f3->set("SESSION.login", $user["email"]);
					$f3->set("SESSION.firstname", $user["firstname"]);
					$f3->set("SESSION.lastname", $user["lastname"]);
					$f3->set("SESSION.id", $user["id_customer"]);
					$f3->set("COOKIE.login", $user["email"]);
					$f3->set("COOKIE.firstname", $user["firstname"]);
					$f3->set("COOKIE.lastname", $user["lastname"]);
					$f3->set("COOKIE.id", $user["id_customer"]);
					$response = array("result"=>"ok", "msg"=>"");
					return $response;
				}
			}
    }
    $response = array("result"=>"error", "msg"=>$f3->get("dict_log_err_missing"));
    return $response;
  }

}
?>
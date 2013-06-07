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
 * UserController
 *
 * Class to handle customer CRUD operations.
 * IMPORTANT: Customers belong to the shop app, not the web app.
 */
class UserController {

  /**
   * _add
   *
   * Prepare template to create a new customer.
   */
  function _add() {
    $f3 = F3::instance();
    $f3->set('users_selected', 'active');

    $group_model = new GroupModel();
    $f3->set ("groups", $group_model->to_select() );

    $f3->set('main_content', 'users_add.html');
    echo Template::instance()->render('main_template.html');
  }


  /**
   * _delete
   *
   * @param   PARAMS.id Integer
   *
   * Delete a customer. Actually, a customer is never deleted. It's just 
   * flagged as not active.
   */
  function _delete() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $error = array();
    $id = $f3->get('PARAMS.id');

    $customer_model = new CustomerModel();

    $result = $customer_model->delete($id);
    if (!is_array($result)) {
      $f3->reroute("/users/list");
    } else {
      $error = array_merge($error, $result);
    }

    // something went wrong
    $f3->set('moderror', true);
    $f3->set('moderrortext', $error);
    
    $f3->set('users_selected','active');
    $f3->set('main_content', 'users_list.html');
    echo Template::instance()->render('main_template.html');
  }


  /**
   * _draw
   *
   * Get all customer ids and prepare a template to draw a random customer.
   */
  function _draw() {
  	$f3 = F3::instance();

  	$security = new SessionManager();
  	$security->force_logout();

    $db = $f3->get("DB_SHOP");
    $query = "SELECT DISTINCT(id_customer) FROM ps_customer WHERE deleted=0 AND active=1";
    try {
      $result = $db->exec($query);
    } catch (Excpetion $e) {
      $error[] = $e->getMessage();
    }
    $f3->set("draw_numbers", count($result));
  	$f3->set('draw_selected', 'active');
  
  	$f3->set('main_content', 'users_draw.html');
  	echo Template::instance()->render('main_template.html');
  }


  /**
   * _draw_generate
   *
   * Get a random customer.
   */
  function _draw_generate() {
  	$f3 = F3::instance();
  
  	$security = new SessionManager();
  	$security->force_logout();
  
  	$db = $f3->get("DB_SHOP");
  	$query = "SELECT DISTINCT(id_customer) FROM ps_customer WHERE deleted=0 AND active=1";
  	try {
  		$result = $db->exec($query);
  	} catch (Excpetion $e) {
  		$error[] = $e->getMessage();
  	}

  	foreach ($result as $k=>$v) {
  		$draw_numbers[] = $v["id_customer"];
  		unset($result[$k]);
  	}
  	$winner_id = array_rand($draw_numbers);
  	$customer_model = new CustomerModel();
  	$winner = $customer_model->a_get($draw_numbers[$winner_id]);
  	if (is_array($winner)) {
  		echo json_encode(array("result"=>"ok", "msg"=>$winner));  		
  	} else {
  		echo json_encode(array("result"=>"error", "msg"=>"No se ha podido recuperar el ganador."));
  	}
  }
  


  /**
   * _edit
   *
   * @param   PARAMS.id Integer
   *
   * Get a user by id, copy via POST and fill a webform to update.
   */
  function _edit() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $group_model = new GroupModel();
    $f3->set ("groups", $group_model->to_select() );

    $customer_model = new CustomerModel();
    $user = $customer_model->_get($f3->get('PARAMS.id'));
    $user->copyTo("POST");

    $comment_model = new CommentModel();
    $comments = $comment_model->_a_get_by_user($f3->get('PARAMS.id'));
    $f3->set("comments", $comments);

    $visualization_model = new VisualizationModel();
    $visualizations = $visualization_model->_a_get_by_user($f3->get('PARAMS.id'));
    $f3->set("visualizations", $visualizations);

    $f3->set('op','_UPDATE_');
    $f3->set('users_selected','active');
    $f3->set('main_content', 'users_add.html');
    echo Template::instance()->render('main_template.html');

  }


  /**
   * _list
   *
   * Get all customers and list them in a tabulated format.
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
    $page = $f3->get('GET.page') == null ? 1 : $f3->get('GET.page') ;
    $offset = ($page-1)*$f3->get("PAGE_SIZE");
    $limit = $f3->get("PAGE_SIZE");

    $customer_mapper = new DB\SQL\Mapper($f3->get("DB_SHOP"), "ps_customer");
    $customers = $customer_mapper->afind(NULL, array('offset'=>$offset, 'limit'=>$limit));
    $total = $customer_mapper->count();
    $f3->set('users',$customers);
    $f3->set('total',$total);

    $pagination = new Pagination($limit, $customer_mapper->count());
    $paginationArray = array();
    if ($pagination->getNumPages()>1) {
      $paginationArray = $pagination->getPagination($limit);
    }
    list($offset, $length) = $pagination->getLimit();
    $f3->set('paginationArray', $paginationArray);
    $f3->set('paginationURL', $f3->get('BASE_URL') . "/admin/users/list?page=");

    $f3->set('users_selected', 'active');

    $f3->set('main_content', 'users_list.html');
    echo Template::instance()->render('main_template.html');
  }


  /**
   * _recover
   *
   * @param   PARAMS.id Integer
   *
   * Get a user by ID and remove inactive/deleted flag.
   */
  function _recover() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $error = array();
    $id = $f3->get('PARAMS.id');

    $customer_model = new CustomerModel();

    $result = $customer_model->recover($id);
    if (!is_array($result)) {
      $f3->reroute("/users/list");
    } else {
      $error = array_merge($error, $result);
    }

    // something went wrong
    $f3->set('moderror', true);
    $f3->set('moderrortext', $error);
    
    $f3->set('users_selected','active');
    $f3->set('main_content', 'users_list.html');
    echo Template::instance()->render('main_template.html');
  }


  /**
   * _update
   *
   * @param   POST.id_customer Integer
   * @param   POST.id_default_group Integer
   * @param   POST.firstname String
   * @param   POST.lastname String
   * @param   POST.email String
   * @param   POST.note String
   * @param   POST.password String
   *
   * Update an existing user or create a new one.
   */
  function _update() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $error = array();

    $f3->scrub($_POST);

    $customer_model = new CustomerModel();

    $id = $f3->get('POST.id_customer');
    $firstname = $f3->get('POST.firstname');
    $lastname = $f3->get('POST.lastname');
    $email = $f3->get('POST.email');
    $note = $f3->get('POST.note');
    $password = $f3->get('POST.password');
    $group = $f3->get('POST.id_default_group');

    if (empty($id)) {
      $result = $customer_model->create($firstname, $lastname, $email, $note, $password, $group);
    } else {
      $result = $customer_model->update($id, $firstname, $lastname, $email, $note, $group);
    }

    if (!is_array($result)) {
      $f3->reroute("/users/list");
    } else {
      // algo salió mal y la respuesta es un array de errores
      $group_model = new GroupModel();
      $f3->set ("groups", $group_model->to_select() );
      $f3->set('moderror', true);
      $error = array_merge($error, $result);
      $f3->set('moderrortext', $error);
      $f3->set('users_selected','active');
      $f3->set('main_content', 'users_add.html');
      echo Template::instance()->render('main_template.html');
    }

  }

}
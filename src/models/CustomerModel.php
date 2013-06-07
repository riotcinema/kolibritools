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
 * UserModel
 *
 * IMPORTANT: Customers belong to the shop app, not the web app.
 * 
 */
class CustomerModel {


  /**
   * a_get
   *
   * @param   $id Integer
   * @return  $customer Array
   *
   * Get a customer in array format.
   */
  function a_get($id) {
    $f3 = F3::instance();

    $user = array();

    $db = $f3->get("DB_SHOP");
    $query = " SELECT * FROM ps_customer WHERE id_customer =:id LIMIT 1 ";
    $params = array(":id"=>$id);
    try {
      $result = $db->exec($query, $params);
      if (is_array($result) && count($result)==1) {
        $user = $result[0];
      }
    } catch (Exception $e) {
      $error[] = $e->getMessage();
    }

    return $user;

  }



  /**
   * _get
   *
   * @param   $id Integer
   * @return  $user_mapper Mapper
   *
   * Get a customer in mapper format.
   */
  function _get($id) {
    $f3 = F3::instance();

    $user_mapper = new DB\SQL\Mapper($f3->get("DB_SHOP"), "ps_customer");
    $user_mapper = $user_mapper->findone(array('id_customer=:id',array(':id'=>$id)));

    return $user_mapper;
  }


  /**
   * _list
   *
   * @return Array
   *
   * Get all customers in array format.
   */
  function _list() {
    $f3 = F3::instance();
    $customer_mapper = new DB\SQL\Mapper($f3->get("DB_SHOP"), "ps_customer");
    $customers = $customer_mapper->afind();
    return $customers;
  }


  /**
   * change_profile
   *
   *
   * Change user information in database.
   *
   * @param $id         Integer
   * @param $firstname  String
   * @param $lastname   String
   * @param $email      String
   */
  function change_profile($id, $firstname, $lastname, $email) {
  
  	$f3 = F3::instance();
  
  	$error = array();
  
  	if (empty($firstname)) {
  		$error[] =  "No has indicado el nombre del usuario.";
  	}
  
  	if (empty($lastname)) {
  		$error[] =  "No has indicado el apellido del usuario.";
  	}
  
  	if (empty($email) || (!filter_var($email, FILTER_VALIDATE_EMAIL))) {
  		$error[] =  "No has indicado el email del usuario.";
  	}
  
  	$user_mapper_check = new DB\SQL\Mapper($f3->get("DB_SHOP"), "ps_customer");
  	$existing = $user_mapper_check->afind(array("id_customer!=:id AND email=:email",array(":id"=>$id, ":email"=>$email)));
  	if (count($existing)>0) {
  		$error[] = "El email $email ya existe.";
  	}
  
  	if (count($error)==0) {
  
	  	$db = $f3->get("DB_SHOP");
	  	$query = " UPDATE ps_customer
	  	SET
		  	firstname = :firstname,
		  	lastname = :lastname,
		  	email = :email,
		  	last_passwd_gen = :last_passwd_gen,
		  	date_add = :date_add,
		  	date_upd = :date_upd
	  	WHERE
		  	id_customer = :id_customer ";
	  	$params = array(
		  	':firstname' => $firstname,
		  	':lastname' => $lastname,
		  	':email' => $email,
		  	':last_passwd_gen' => date("Y-m-d H:i:s"),
		  	':date_add' => date("Y-m-d H:i:s"),
		  	':date_upd' => date("Y-m-d H:i:s"),
		  	':id_customer' => $id
	  	);
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
   * create
   *
   *
   * Create a new customer in shop.
   *
   * @param $firstname  String
   * @param $lastname   String
   * @param $email      String
   * @param $note       String
   * @param $password   String
   */
  function create ($firstname, $lastname, $email, $note, $password, $group) {
    $f3 = F3::instance();

    $error = array();

    if (empty($firstname)) {
      $error[] =  "No has indicado el nombre del usuario.";
    }

    if (empty($lastname)) {
      $error[] =  "No has indicado el apellido del usuario.";
    }

    if (empty($email) || (!filter_var($email, FILTER_VALIDATE_EMAIL))) {
      $error[] =  "No has indicado el email del usuario.";
    }

    if (empty($note)) {
      $note = $this->generate_code();
    }

    $user_mapper_check = new DB\SQL\Mapper($f3->get("DB_SHOP"), "ps_customer");
    $existing = $user_mapper_check->load(array("email=:email",array(":email"=>$email)));
    if (!$user_mapper_check->dry()) {
      $error[] = "El email $email ya existe."; 
    }
    $existing = $user_mapper_check->load(array("note=:note",array(":note"=>$note)));
    if (!$user_mapper_check->dry()) {
      $error[] = "El código para el sorteo $note ya existe."; 
    }

    if (empty($password)) {
      $error[] =  "No has indicado la contraseña del usuario.";
    }

    if (count($error)==0) {

      $db = $f3->get("DB_SHOP");
      $customer = new DB\SQL\Mapper($f3->get("DB_SHOP"), "ps_customer");

      $customer->passwd = md5(addslashes($f3->get("DB_SHOP_COOKIE").$password));
      $customer->id_shop_group = 1;
      $customer->id_shop = 1;
      $customer->id_gender = 1;
      $customer->id_default_group = $group;
      $customer->id_risk = 0;
      $customer->firstname = $firstname;
      $customer->lastname = $lastname;
      $customer->email = $email;
      $customer->last_passwd_gen = date("Y-m-d H:i:s");
      $customer->birthday = NULL;
      $customer->newsletter = 0;
      $customer->ip_registration_newsletter = NULL;
      $customer->newsletter_date_add = NULL;
      $customer->optin = 0;
      $customer->website = NULL;
      $customer->outstanding_allow_amount = "0.000000";
      $customer->show_public_prices = 0;
      $customer->max_payment_days = 0;
      $customer->secure_key = "6a7232710d29c9e5182c5faa823a3a2a";
      $customer->note = $note;
      $customer->active = 1;
      $customer->is_guest = 0;
      $customer->deleted = 0;
      $customer->date_add = date("Y-m-d H:i:s");
      $customer->date_upd = date("Y-m-d H:i:s");

      try {
        $customer->save();
        $id = $customer->id_customer;
        // update customer group info
        $group_mapper = new DB\SQL\Mapper($f3->get("DB_SHOP"), "ps_customer_group");
        $group_mapper->id_customer = $id;
        $group_mapper->id_group = $group;
        $group_mapper->save();
        return $id;

      } catch (Exception $e) {
        $error[] = $e->getMessage();
      }
    }
    return $error;
  }


  /**
   * delete
   *
   * Delete a customer.
   * IMPORTANT: Customers are not actually deleted. They're flagged as inactive
   * and deleted but data is present in database.
   *
   * @param $id  Integer
   * @return TRUE if success, error array if not
   */
  function delete ($id) {
    $f3 = F3::instance();

    $error = array();

    $db = $f3->get("DB_SHOP");
    $query = " UPDATE ps_customer SET deleted=1, active=0 WHERE id_customer = :id_customer ";
    $params = array ( ':id_customer' => $id );
    try {
      $result = $db->exec($query, $params);
      return TRUE;
    } catch (Excpetion $e) {
      $error[] = $e->getMessage();
    }
    return $error;

  }


  /**
   * generate_code
   *
   * Create a random string code for a customer. Code will be used in customers
   * draw.
   *
   * @return String Code or error array.
   */
  private function generate_code() {
    $f3 = F3::instance();

    $db = $f3->get("DB_SHOP");
    $query = " SELECT MAX(id_customer) as code FROM ps_customer ";
    try {
      $result = $db->exec($query);
      $code = (int)$result[0]["code"]+1;
      return $code;
    } catch (Exception $e) {
      $error[] = $e->getMessage();
      return $error;
    }

  }


  /**
   * generate_password
   *
   * Create a random string used as password for a user.
   *
   * @param 	Integer $length
   * @return	String
   */
  function generate_password($length=8) {
  	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  	$count = mb_strlen($chars);
  	for ($i = 0, $result = ''; $i < $length; $i++) {
  		$index = rand(0, $count - 1);
  		$result .= mb_substr($chars, $index, 1);
  	}
  	return $result;
  }


  /**
   * is_a_backer
   * 
   * @param	$email String
   * 
   * Look for a user identified by email and check if the customer belongs to 
   * backers group.
   */
  function is_a_backer($email) {
  	$f3 = F3::instance();
  	
  	if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
  		$db_shop = $f3->get("DB_SHOP");
  		$query = " SELECT c.id_customer, c.firstname, c.lastname, c.email, c.passwd
  		FROM ps_customer c
  		WHERE c.email=:email
  		AND c.id_default_group=:group
  		LIMIT 1 ";
  		$params = array(':email'=>$email, ':group'=>$f3->get("DB_SHOP_BACKERS_ID"));
  		$result = $db_shop->exec($query, $params);
  		if (!empty($result)) {
  			return $result[0];
  		}
  	}
  	return NULL;
  }


  /**
   * is_a_buyer
   *
   * @param	$email String
   *
   * Look for a user identified by email and check if the customer has
   * purchased any product in the shop.
   */
  function is_a_buyer($email) {
  	$f3 = F3::instance();
  	 
  	if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
  
  		$db_shop = $f3->get("DB_SHOP");
  		$query = " SELECT c.id_customer, c.firstname, c.lastname, c.email, c.passwd
  		FROM ps_customer c
      INNER JOIN ps_orders o ON c.id_customer=o.id_customer
      INNER JOIN ps_order_detail od ON o.id_order=od.id_order
      INNER JOIN ps_order_state os ON o.current_state=os.id_order_state
  		WHERE c.email=:email
  		AND os.paid=1
  		LIMIT 1 ";
  		$params = array(':email'=>$email);
  		$result = $db_shop->exec($query, $params);
  		if (!empty($result)) {
  			return $result[0];
  		}
  	}
  	return NULL;
  }
  

  /**
   * is_a_cosmonaut
   * 
   * @param	$email String
   * 
   * Look for a user identified by email and check if the customer have
   * purchased a K-Pass in the shop.
   */
  function is_a_cosmonaut($email) {
  	$f3 = F3::instance();
  	if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
  	
  		$db_shop = $f3->get("DB_SHOP");
  		$query = " SELECT c.id_customer, c.firstname, c.lastname, c.email, c.passwd
  		FROM ps_customer c
      INNER JOIN ps_orders o ON c.id_customer=o.id_customer
      INNER JOIN ps_order_detail od ON o.id_order=od.id_order
      INNER JOIN ps_order_state os ON o.current_state=os.id_order_state
  		WHERE c.email=:email
  		AND os.paid=1
  		AND od.product_id=:k_pass
  		LIMIT 1 ";
  		$params = array(':email'=>$email, ':k_pass'=>$f3->get("DB_SHOP_KPASS_ID"));
  		$result = $db_shop->exec($query, $params);
  		if (!empty($result)) {
  			return $result[0];
  		}
  	}
  	return NULL;  	
  }


  /**
   * list_by_ids
   *
   * Get a collection of customers identified by the array of ids.
   *
   * @param $ids  Array
   */
  function list_by_ids ($ids) {
    $f3 = F3::instance();

    $users  =array();
    if (!is_array($ids)) {
      return $users;
    }

    $db = $f3->get("DB_SHOP");
    $query = " SELECT
      id_customer,
      firstname,
      lastname,
      note,
      email
      FROM ps_customer
      WHERE id_customer IN ( ".implode(", ", $ids )." ) ";
    try {
      $result = $db->exec($query);
      // build an associative array with id_customer as key
      foreach ($result as $r=>$user) {
        $users[$user["id_customer"]] = $user;
        unset($result[$r]);
      }

    } catch (Exception $e) {
      $error[] = $e->getMessage();
    }

    return $users;

  }

	/**
	 * new_cosmonaut
   *
   * Create a new customer of backers group with minimal information. This
   * process was only used during initial import process.
   *
   * @return $email String
   * @return $name String
	 */
	function new_cosmonaut($email, $name) {

    $f3 = F3::instance();

		$firstname = $name;
		$lastname = $name;
		$note = "";
		$password = $this->generate_password();
		$group = 4; // Backer
		$creation = $this->create($firstname, $lastname, $email, $note, $password, $group);

    // send e-mail
    $boundary = uniqid("HTMLDEMO");

    // $header  = "From: ".$f3->get("dict_com_the_cosmonaut")." <".$f3->get("MAIL_NO_REPLY").">\r\n";
    $header  = "From: fernando.porres@tecnilogica.com <".$f3->get("MAIL_NO_REPLY").">\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-type: text/html; charset=utf-8'\r\n";

    $link = strtolower($f3->get("LANGUAGE"))=="es" ? $f3->get("URL_SHOP_ES")."&controller=password" : $f3->get("URL_SHOP_EN")."&controller=password";

    $send = mail(
      $email,
      $f3->get("dict_mai_header_thankyou"),
      $f3->get("dict_mai_header_text_1")."<br/>".$f3->get("dict_mai_header_text_2")." ".$email."<br/>".$link,
      $header);

	}


  /**
   * recover
   *
   * Recover a customer previously flagged as deleted or inactive.
   *
   * @param $id  Integer
   */
  function recover ($id) {
    $f3 = F3::instance();

    $error = array();

    $db = $f3->get("DB_SHOP");
    $query = " UPDATE ps_customer SET deleted=0, active=1 WHERE id_customer = :id_customer ";
    $params = array ( ':id_customer' => $id );
    try {
      $result = $db->exec($query, $params);
      return TRUE;
    } catch (Excpetion $e) {
      $error[] = $e->getMessage();
    }
    return $error;

  }


  /**
   * to_select
   *
   * Create an array with customers data to popullate select inputs.
   *
   * @return Array
   */
  function to_select() {
    $f3 = F3::instance();
    $db = $f3->get("DB_SHOP");
    return $db->exec("SELECT id_customer, email, firstname, lastname FROM ps_customer ORDER BY email ASC");
  }



  /**
   * update
   *
   * Update a customer info.
   *
   * @param $id         Integer
   * @param $firstname  String
   * @param $lastname   String
   * @param $email      String
   */
  function update($id, $firstname, $lastname, $email, $note, $group) {

    $f3 = F3::instance();

    $error = array();

    if (empty($firstname)) {
      $error[] =  "No has indicado el nombre del usuario.";
    }

    if (empty($lastname)) {
      $error[] =  "No has indicado el apellido del usuario.";
    }

    if (empty($email) || (!filter_var($email, FILTER_VALIDATE_EMAIL))) {
      $error[] =  "No has indicado el email del usuario.";
    }

    if (empty($note)) {
      $error[] =  "No has indicado el código para el sorteo.";
    }

    $user_mapper_check = new DB\SQL\Mapper($f3->get("DB_SHOP"), "ps_customer");
    $existing = $user_mapper_check->afind(array("id_customer!=:id AND email=:email",array(":id"=>$id, ":email"=>$email)));
    if (count($existing)>0) {
      $error[] = "El email $email ya existe."; 
    }

    $existing = $user_mapper_check->afind(array("id_customer!=:id AND note=:note",array(":id"=>$id, ":note"=>$note)));
    if (count($existing)>0) {
      $error[] = "El código para el sorteo $note ya existe."; 
    }


    if (count($error)==0) {

      $db = $f3->get("DB_SHOP");
      $query = " UPDATE ps_customer
        SET
        firstname = :firstname,
        lastname = :lastname,
        email = :email,
        note = :note,
        id_default_group = :id_default_group,
        last_passwd_gen = :last_passwd_gen,
        date_add = :date_add,
        date_upd = :date_upd
        WHERE
        id_customer = :id_customer ";
      $params = array(
        ':firstname' => $firstname,
        ':lastname' => $lastname,
        ':email' => $email,
        ':id_default_group' => $group,
        ':note' => $note,
        ':last_passwd_gen' => date("Y-m-d H:i:s"),
        ':date_add' => date("Y-m-d H:i:s"),
        ':date_upd' => date("Y-m-d H:i:s"),
        ':id_customer' => $id
      );
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
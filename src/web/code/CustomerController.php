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
 * CustomerController
 *
 * Controller class for user account operations. Each user is basically a 
 * customer in the shop.
 */
class CustomerController {


	/**
	 * user_delete
	 *
	 * @param  SESSION.id Integer identifier of a customer
	 * @return resul Associative array json encoded with result code and message.
	 *
	 * Delete a Customer account, clear SESSION data and returl result via json.
	 */
	function user_delete() {
		$f3 = F3::instance();
	
		$customer_model = new CustomerModel();
		$id = $f3->get("SESSION.id");
		$result = $customer_model->delete($id);

		if ($result==TRUE) {
			$response = array("result"=>"ok", "msg"=>$f3->get("dict_com_ope_success"));
		} else {
			$response = array("result"=>"error", "msg"=>$f3->get("dict_com_ope_error"));
		}
		$f3->clear('SESSION');
		echo json_encode($response);
	}
	
	
	/**
	 * user_update
	 *
	 * @param 	POST.use_id
	 * @param 	POST.use_firstname
	 * @param 	POST.use_lastname
	 * @param 	POST.use_email
	 *
	 * Update customer data in the shop database. Refresh those data in SESSION 
	 * and return result operation json encoded.
	 */
	function user_update() {
		$f3 = F3::instance();
	
		$f3->scrub($_POST);
	
		$customer_model = new CustomerModel();
		$id = $f3->get("POST.use_id");
		$firstname = $f3->get("POST.use_firstname");
		$lastname = $f3->get("POST.use_lastname");
		$email = $f3->get("POST.use_email");
	
		$result = $customer_model->change_profile($id, $firstname, $lastname, $email);
	
		if ($result==TRUE) {
			$response = array("result"=>"ok", "msg"=>$f3->get("dict_com_ope_success"));
			$f3->clear('SESSION');
			$f3->set("SESSION.id", $id);
			$f3->set("SESSION.login", $email);
			$f3->set("SESSION.firstname", $firstname);
			$f3->set("SESSION.lastname", $lastname);
		} else {
			$response = array("result"=>"error", "msg"=>$f3->get("dict_com_ope_error"));
		}
		echo json_encode($response);
	}

}
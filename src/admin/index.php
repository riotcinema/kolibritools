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
 * index.php
 *
 * Base file in admin module
 */

//LOAD BASE LIBRARY
$f3 = require("../lib/base.php");

// GLOBAL VARIABLES DEFINITION
$f3->set("ENCODING", "UTF-8");
$f3->set("UI", "templates/");
$f3->set("TEMP", "../temp/");
$f3->set("AUTOLOAD", "./code/; ../classes/; ../models/;");
$f3->set("UPLOADS", "../files/");

// LOAD CONFIG FILES
$f3->config("../config/config.cfg");
$f3->config("../config/constants.cfg");

include_once("../config/common.php");

$f3->route("GET  /",                                  		"MainController->main");
$f3->route("POST /login",                             		"MainController->login");
$f3->route("GET  /loginerror",                        		"MainController->login_error");
$f3->route("GET  /logout",                            		"MainController->logout");

$f3->route("GET  /admins/list",                       		"AdminController->_list");
$f3->route("GET  /admins/add",                        		"AdminController->_add");
$f3->route("GET  /admins/edit/@id",                   		"AdminController->_edit");
$f3->route("POST /admins/update",                     		"AdminController->_update");
$f3->route("GET  /admins/delete/@id",                 		"AdminController->_delete");
$f3->route("GET  /admins/password/@id",               		"AdminController->_password");
$f3->route("POST /admins/password/reset",             		"AdminController->_reset_password");

$f3->route("GET  /comments/list",                     		"CommentController->_list");
$f3->route("GET  /comments/add",                      		"CommentController->_add");
$f3->route("GET  /comments/edit/@id",                 		"CommentController->_edit");
$f3->route("POST /comments/update",                   		"CommentController->_update");
$f3->route("GET  /comments/delete/@id",               		"CommentController->_delete");

$f3->route("GET  /comments/@com_id/response/add",        	"ResponseController->_add");
$f3->route("GET  /comments/@com_id/response/edit/@id",   	"ResponseController->_edit");
$f3->route("POST /comments/@com_id/response/update",     	"ResponseController->_update");
$f3->route("GET  /comments/@com_id/response/delete/@id", 	"ResponseController->_delete");

$f3->route("GET  /quotes/list",                       		"QuoteController->_list");
$f3->route("GET  /quotes/add",                        		"QuoteController->_add");
$f3->route("GET  /quotes/edit/@id",                   		"QuoteController->_edit");
$f3->route("POST /quotes/update",                     		"QuoteController->_update");
$f3->route("GET  /quotes/delete/@id",                 		"QuoteController->_delete");

$f3->route("GET  /remixes/list",                      		"RemixController->_list");
$f3->route("GET  /remixes/add",                       		"RemixController->_add");
$f3->route("GET  /remixes/edit/@id",                  		"RemixController->_edit");
$f3->route("POST /remixes/update",                    		"RemixController->_update");
$f3->route("GET  /remixes/delete/@id",                		"RemixController->_delete");

$f3->route("GET  /users/list",                        		"UserController->_list");
$f3->route("GET  /users/add",                         		"UserController->_add");
$f3->route("GET  /users/draw",                    				"UserController->_draw");
$f3->route("POST /users/draw/generate [ajax]",        		"UserController->_draw_generate");
$f3->route("GET  /users/edit/@id",                    		"UserController->_edit");
$f3->route("POST /users/update",                      		"UserController->_update");
$f3->route("GET  /users/delete/@id",                  		"UserController->_delete");
$f3->route("GET  /users/recover/@id",                 		"UserController->_recover");

$f3->route("GET  /videos/list",                       		"VideoController->_list");
$f3->route("GET  /videos/add",                        		"VideoController->_add");
$f3->route("GET  /videos/edit/@id",                   		"VideoController->_edit");
$f3->route("POST /videos/update",                     		"VideoController->_update");
$f3->route("GET  /videos/delete/@id",                 		"VideoController->_delete");

$f3->route("GET  /visualizations/list",               		"VisualizationController->_list");
$f3->route("GET  /visualizations/add",                		"VisualizationController->_add");
$f3->route("GET  /visualizations/edit/@id",           		"VisualizationController->_edit");
$f3->route("POST /visualizations/update",             		"VisualizationController->_update");
$f3->route("GET  /visualizations/delete/@id",         		"VisualizationController->_delete");

$f3->route("GET  /import/customers/incomplete",       		"ImportController->import_incomplete_customers");
$f3->route("GET  /import/customers",         							"ImportController->import_customers");
$f3->route("GET  /import/producers",         							"ImportController->import_producers");

$f3->route("GET  /characters/list",                       "CharacterController->_list");
$f3->route("GET  /characters/add",                        "CharacterController->_add");
$f3->route("GET  /characters/edit/@id",                   "CharacterController->_edit");
$f3->route("POST /characters/update",                     "CharacterController->_update");

$f3->route("GET  /nayik/add",                             "NayikController->_add");
$f3->route("GET  /nayik/list",                            "NayikController->_list");
$f3->route("GET  /nayik/edit/@id",                        "NayikController->_edit");
$f3->route("POST /nayik/update",                          "NayikController->_update");
$f3->route("GET  /nayik/delete/@id",                      "NayikController->_delete");
$f3->route("GET  /nayik/@nay_id/response/add",            "NayikController->_addResponse");
$f3->route("GET  /nayik/@nay_id/response/edit/@id",       "NayikController->_editResponse");
$f3->route("POST /nayik/@nay_id/response/update",         "NayikController->_updateResponse");
$f3->route("GET  /nayik/@nay_id/response/delete/@id",     "NayikController->_deleteResponse");

$f3->run();
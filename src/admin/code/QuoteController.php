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
 * QuoteController
 *
 * Class to handle administrative tools for media press quotes. Media press 
 * quotes will be displayed in public home page.
 * Quote table structure:
 * quo_id  int(11) primary key
 * quo_en  varchar(128)
 * quo_es  varchar(128)
 * quo_author_es varchar(64)
 * quo_author_en varchar(64)
 */
class QuoteController {

  /**
   * _add
   *
   * @access      public
   *
   * Prepare a template to add / edit a media press quote.
   */
  function _add() {
    $f3 = F3::instance();
    $f3->set('quotes_selected', 'active');

    $f3->set('main_content', 'quotes_add.html');
    echo Template::instance()->render('main_template.html');
  }


  /**
   * _delete
   *
   * @param   PARAMS.id Integer
   *
   * Delete a media press quote identified by ID.
   */
  function _delete() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $quote_mapper = new DB\SQL\Mapper($f3->get("DB"), "quote");
    $quote_mapper->load(array('quo_id=:id',array(':id'=>$f3->get('PARAMS.id'))));
    try {
      $quote_mapper->erase();
      $f3->reroute("/quotes/list");
    } catch (Exception $e) {
      $f3->set('moderror', true);
      $f3->set('moderrortext', array("Ocurrió un problema al borrar la cita."));
      
      $f3->set('quotes_selected','active');
      $f3->set('main_content', 'quotes_list.html');
      echo Template::instance()->render('main_template.html');
    }
  }


  /**
   * _edit
   *
   * @param   PARAMS.id Integer
   *
   * Get a media press quote from database, copy it's data via POST and fill a
   * web form so admin user can update it.
   */
  function _edit() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $quote_mapper = new DB\SQL\Mapper($f3->get("DB"), "quote");
    $quote_mapper->load(array('quo_id=:id',array(':id'=>$f3->get('PARAMS.id'))));
    $quote_mapper->copyTo('POST');

    $f3->set('quotes_selected','active');
    $f3->set('main_content', 'quotes_add.html');
    echo Template::instance()->render('main_template.html');
  }


  /**
   * _list
   *
   * Get all media press quotes and list them.
   * Pagination basics:
   * - Page number get by URL parameter.
   * - Pagination class declared in /classes/Pagination.php
   * - Size of page get by URL parameter
   */  
  function _list() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $quote_mapper = new DB\SQL\Mapper($f3->get("DB"), "quote");
    $quotes = $quote_mapper->find();

    // preparar la paginación
    if ( $f3->get('GET.page') == null) {
      $page = 1;
    } else {
      $page = $f3->get('GET.page');
    }
    $offset = ($page-1)*$f3->get("PAGE_SIZE");
    $limit = $f3->get("PAGE_SIZE");
    $f3->set('quotes',array_slice($quotes, $offset, $limit));

    $pagination = new Pagination($limit, count($quotes));
    $paginationArray = array();
    if ($pagination->getNumPages()>1) {
      $paginationArray = $pagination->getPagination($limit);
    }
    list($offset, $length) = $pagination->getLimit();
    $f3->set('paginationArray', $paginationArray);
    $f3->set('paginationURL', $f3->get('BASE_URL') . "/admin/quotes/list?page=");

    $f3->set('quotes_selected', 'active');

    $f3->set('main_content', 'quotes_list.html');
    echo Template::instance()->render('main_template.html');
  }


  /**
   * _update
   *
   * @param   POST.quo_id Integer
   * @param   POST.quo_en String
   * @param   POST.quo_es String
   * @param   POST.quo_author String
   *
   * Update or create a media press quote.
   */
  function _update() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $quote_mapper = new DB\SQL\Mapper($f3->get("DB"), "quote");
    $quote_mapper_check = new DB\SQL\Mapper($f3->get("DB"), "quote");
    $error = array();

    $f3->scrub($_POST);

    // get the ID if quote exists
    $id = $f3->get('POST.quo_id');
    if ($id) {    
      $quote_mapper->load(array('quo_id=:id',array(':id'=>$id)));
    }

    $quo_en = $f3->get('POST.quo_en');
    if (empty($quo_en)) {
      $error[] =  "No has indicado el texto en Inglés.";
    }

    $quo_author_en = $f3->get('POST.quo_author_en');
    if (empty($quo_author_en)) {
    	$error[] =  "No has indicado el autor de la cita (Inglés).";
    }

    $quo_es = $f3->get('POST.quo_es');
    if (empty($quo_es)) {
      $error[] =  "No has indicado el texto en Español.";
    }

    $quo_author_es = $f3->get('POST.quo_author_es');
    if (empty($quo_author_es)) {
    	$error[] =  "No has indicado el autor de la cita (Español).";
    }

    $category = $f3->get('POST.vid_category');

    if (count($error)==0) {

      $quote_mapper->quo_es = $quo_es;
      $quote_mapper->quo_en = $quo_en;
      $quote_mapper->quo_author_en = $quo_author_en;
      $quote_mapper->quo_author_es = $quo_author_es;

      try {
        $quote_mapper->save();
        $f3->reroute("/quotes/list");
      } catch (Exception $e) {
        $error[] = $e->getMessage();
        $f3->set('moderror', true);
        $f3->set('moderrortext', $error);
        $f3->set('quotes_selected','active');
        $f3->set('main_content', 'quotes_add.html');
        echo Template::instance()->render('main_template.html');
      }
    
    } else {
      
      $f3->set('moderror', true);
      $f3->set('moderrortext', $error);
      
      $f3->set('quotes_selected','active');
      $f3->set('main_content', 'quotes_add.html');
      echo Template::instance()->render('main_template.html');

    }

  }

}
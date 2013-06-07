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
 * VideoController
 *
 * Class to handle video CRUD operations.
 * Video is based on video table with the following structure:
 * vid_id integer(11) primary key
 * vid_title_es varchar(128)
 * vid_title_en varchar(128)
 * vid_url_en varchar(150)
 * vid_url_es varchar(128)
 * vid_protected tinyint(1)
 * vid_release datetime
 * vid_synopsis_short_en varchar(512)
 * vid_synopsis_short_es varchar(512)
 * vid_synopsis_long_en text
 * vid_synopsis_long_es text
 * vid_duration int(11)
 * vid_order int(2)
 * vid_category varchar(16)
 * vid_thumbnail varchar(128)
 * vid_cover varchar(128)
 * vid_slug varchar(64)
 */
class VideoController {

  /**
   * _add
   *
   *
   * Prepare a template to create a new video.
   */
  function _add() {
    $f3 = F3::instance();
    $f3->set('videos_selected', 'active');

    $category_model = new CategoryModel();
    $f3->set ("categories", $category_model->to_select() );

    $f3->set('main_content', 'videos_add.html');
    echo Template::instance()->render('main_template.html');
  }


  /**
   * _delete
   *
   * @param   PARAMS.id Integer
   *
   * Delete an existing video.
   */
  function _delete() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $video_mapper = new DB\SQL\Mapper($f3->get("DB"), "video");
    $video_mapper->load(array('vid_id=:id',array(':id'=>$f3->get('PARAMS.id'))));
    try {
      $video_mapper->erase();
      $f3->reroute("/videos/list");
    } catch (Exception $e) {
      $f3->set('moderror', true);
      $f3->set('moderrortext', array("Ocurrió un problema al borrar el video."));
      
      $f3->set('videos_selected','active');
      $f3->set('main_content', 'videos_list.html');
      echo Template::instance()->render('main_template.html');
    }
  }


  /**
   * _edit
   *
   * @param   PARAMS.id Integer
   *
   * Get a video by id, copy via POST and prepare a webform to update.
   */
  function _edit() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $video_mapper = new DB\SQL\Mapper($f3->get("DB"), "video");
    $video_mapper->load(array('vid_id=:id',array(':id'=>$f3->get('PARAMS.id'))));
    $video_mapper->copyTo('POST');

    $category_model = new CategoryModel();
    $f3->set ("categories", $category_model->to_select() );

    $f3->set('videos_selected','active');
    $f3->set('main_content', 'videos_add.html');
    echo Template::instance()->render('main_template.html');
  }


  /**
   * _list
   *
   * Get all videos and list them in a tabulated format.
   * Pagination basics:
   * - Page number get by URL parameter.
   * - Pagination class declared in /classes/Pagination.php
   * - Size of page get by URL parameter
   */  
  function _list() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    // prepare pagination
    $page = $f3->get('GET.page') == null ? 1 : $f3->get('GET.page') ;
    $offset = ($page-1)*$f3->get("PAGE_SIZE");
    $limit = $f3->get("PAGE_SIZE");

    $video_mapper = new DB\SQL\Mapper($f3->get("DB"), "video");
    $videos = $video_mapper->find(NULL, array('offset'=>$offset, 'limit'=>$limit));
    $total = $video_mapper->count();
    $f3->set('videos',$videos);
    $f3->set('total',$total);

    $pagination = new Pagination($limit, $total);
    $paginationArray = array();
    if ($pagination->getNumPages()>1) {
      $paginationArray = $pagination->getPagination($limit);
    }
    list($offset, $length) = $pagination->getLimit();
    $f3->set('paginationArray', $paginationArray);
    $f3->set('paginationURL', $f3->get('BASE_URL') . "/admin/videos/list?page=");

    $f3->set('videos_selected', 'active');

    $f3->set('main_content', 'videos_list.html');
    echo Template::instance()->render('main_template.html');
  }


  /**
   * _update
   *
   * @param   POST.vid_id Integer
   * @param   POST.vid_title_en String
   * @param		POST.vid_title_es String
   * @param   POST.vid_url_en String
   * @param   POST.vid_url_es String
   * @param   POST.vid_protected Boolean
   * @param   POST.vid_published Boolean
   * @param   POST.vid_release Boolean
   * @param   POST.vid_synopsis_short_en String
   * @param   POST.vid_synopsis_short_es String
   * @param   POST.vid_synopsis_long_en String
   * @param   POST.vid_synopsis_long_es String
   * @param   POST.vid_duration Integer
   * @param   POST.vid_order Integer
   * @param   POST.vid_thumbnail String
   * @param   POST.vid_cover String
   * @param   POST.vid_slug String
   *
   * Create a new video or update an existing one.
   */
  function _update() {
    $f3 = F3::instance();

    $security = new SessionManager();
    $security->force_logout();

    $video_mapper = new DB\SQL\Mapper($f3->get("DB"), "video");
    $video_mapper_check = new DB\SQL\Mapper($f3->get("DB"), "video");
    $error = array();

    $category_model = new CategoryModel();
    $f3->set ("categories", $category_model->to_select() );

    $f3->scrub($_POST);

    // get a video by id
    $id = $f3->get('POST.vid_id');
    if ($id) {    
      $video_mapper->load(array('vid_id=:id',array(':id'=>$id)));
    }

    $title_en = $f3->get('POST.vid_title_en');
    if (empty($title_en)) {
      $error[] =  "No has indicado el título del video (Inglés).";
    }

    $title_es = $f3->get('POST.vid_title_es');
    if (empty($title_es)) {
    	$error[] =  "No has indicado el título del video (Español).";
    }

    $url_en = $f3->get('POST.vid_url_en');
    if (empty($url_en)) {
      $error[] =  "No has indicado la URL del vídeo en inglés.";
    }

    $release = $f3->get('POST.vid_release');
    if (empty($release)) {
    	$error[] =  "No has indicado la fecha de publicación del vídeo.";
    }

    $url_es = $f3->get('POST.vid_url_es');
    if (empty($url_es)) {
      $error[] =  "No has indicado la URL del vídeo en español.";
    }

    $category = $f3->get('POST.vid_category');

    $protected = $f3->get('POST.vid_protected');
    $protected = $protected == 'on' ? true : false;

    $synopsis_short_en = $f3->get('POST.vid_synopsis_short_en');
    if (empty($synopsis_short_en)) {
      $error[] =  "No has indicado la sinopsis corta del vídeo en inglés.";
    }

    $synopsis_short_es = $f3->get('POST.vid_synopsis_short_es');
    if (empty($synopsis_short_es)) {
      $error[] =  "No has indicado la sinopsis corta del vídeo en español.";
    }

    $synopsis_long_en = $f3->get('POST.vid_synopsis_long_en');
    if (empty($synopsis_long_en)) {
      $error[] =  "No has indicado la sinopsis larga del vídeo en inglés.";
    }

    $synopsis_long_es = $f3->get('POST.vid_synopsis_long_es');
    if (empty($synopsis_long_es)) {
      $error [] =  "No has indicado la sinopsis larga del vídeo en español.";
    }

    $duration = $f3->get('POST.vid_duration');
    if (!empty($duration) && !is_numeric($duration)) {
      $error [] =  "La duración del video no ha sido especificada con un número vaĺido.";
    }

    $order = $f3->get('POST.vid_order');
    
    $existing = $video_mapper_check->load(array("vid_id!=:id AND vid_order=:order AND vid_category=:category", array(":id"=>$id, ":order"=>$order, ":category"=>$category) ));
    if (!$video_mapper_check->dry()) {
      $error[] = "Ya existe un video con ese número de orden en la category"; 
    }

    $slug = $f3->get('POST.vid_slug');
    $existing = $video_mapper_check->load(array("vid_id!=:id AND vid_slug=:slug", array(":id"=>$id, ":slug"=>$slug) ));
    if (!$video_mapper_check->dry()) {
      $error[] = "Ya existe un video con ese slug."; 
    }

    $thumbnail = $f3->get('POST.vid_thumbnail');

    $cover = $f3->get('POST.vid_cover');

    if (count($error)==0) {

      $video_mapper->vid_title_en = $title_en;
      $video_mapper->vid_title_es = $title_es;
      $video_mapper->vid_category = $category;
      $video_mapper->vid_url_en = $url_en;
      $video_mapper->vid_url_es = $url_es;
      $video_mapper->vid_protected = $protected;
      $video_mapper->vid_release = $release;
      $video_mapper->vid_synopsis_short_en = $synopsis_short_en;
      $video_mapper->vid_synopsis_short_es = $synopsis_short_es;
      $video_mapper->vid_synopsis_long_en = $synopsis_long_en;
      $video_mapper->vid_synopsis_long_es = $synopsis_long_es;
      $video_mapper->vid_duration = $duration;
      $video_mapper->vid_order = $order;
      $video_mapper->vid_thumbnail = $thumbnail;
      $video_mapper->vid_cover = $cover;
      $video_mapper->vid_slug = $slug;

      try {
        $video_mapper->save();
        $f3->reroute("/videos/list");
      } catch (Exception $e) {
        $error[] = $e->getMessage();
        $f3->set('moderror', true);
        $f3->set('moderrortext', $error);
        $f3->set('videos_selected','active');
        $f3->set('main_content', 'videos_add.html');
        echo Template::instance()->render('main_template.html');
      }
    
    } else {
      
      $f3->set('moderror', true);
      $f3->set('moderrortext', $error);
      
      $f3->set('videos_selected','active');
      $f3->set('main_content', 'videos_add.html');
      echo Template::instance()->render('main_template.html');

    }

  }

}
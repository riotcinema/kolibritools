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
 * Clase controladora para recibir las peticiones que atienden videos. La 
 * mayoría de las funciones responden a peticiones Ajax.
 * 
 * AJAX requests to handle video interactions such as comments, create
 * visualizations and move across carrousels.
 * 
 */
class VideoController {

  /**
   * comment_video
   *
   * Create a new comment for a video via AJAX request and send a notification
   * mail to contact mail.
   *
   * @param POST.vid_id Integer Unique video ID
   * @param POST.com_comment String Video comment
   * @param SESSION.id Integer Unique user ID
   */
  function comment_video() {
    $f3 = F3::instance();

    if (SessionManager::is_logged()) {
      $f3->scrub($_POST);

      $video_id = $f3->get("POST.vid_id");
      $comment = $f3->get("POST.com_comment");
      $user_id = $f3->get("SESSION.id");

      $comment_model = new CommentModel();
      $video_model = new VideoModel();
      $user_model = new UserModel();
      $video = $video_model->get_by_id($video_id);
      $response = $comment_model->create($video_id, $user_id, $comment);
      if (is_array($response)) {
        $result = array("status"=>"error", "msg"=>$f3->get("dict_vid_comment_error"));
      } else {

        $f3->set("comment", $comment);
        $f3->set("title", $video['vid_title_en']);

        // send e-mail
        $boundary = uniqid("HTMLDEMO");
        $header  = "From: ".$f3->get("dict_com_the_cosmonaut")." <".$f3->get("MAIL_NO_REPLY").">\r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-type: text/html; charset=utf-8'\r\n";
        $send = mail(
            $f3->get("MAIL_CONTACT"),
            $f3->get("MAIL_COMMENT_SUBJECT"),
            Template::instance()->render('mail_comment_video.html'),
            $header);
        if ($send==FALSE) {
          $result = array("status"=>"error", "msg"=>$f3->get("dict_tra_dem_error_sendmail"));
        } else {
          $result = array("status"=>"ok", "msg"=>$f3->get("dict_vid_comment_success"));
        }
      }
    } else {
      $result = array("status"=>"error", "msg"=>$f3->get("dict_vid_comment_error"));
    }
    echo json_encode($result);
  }




  /**
   * get_next_video
   *
   * Get data of the next video identified by the parameter slug.
   *
   * @param  vid_category String
   * @param  vid_slug String
   * @return Array json encoded
   */
  function get_next_video() {
    $f3 = F3::instance();
    $video_model = new VideoModel();
    $current = $video_model->get_by_slug($f3->get("POST.vid_slug"));
    $next = $video_model->get_next($current['vid_order'], $f3->get("POST.vid_category"));
    if (is_array($next) ) {
      echo json_encode(array("status"=>"ok", "result"=>$next));
    } else {
      echo json_encode(array("status"=>"error", "msg"=>"dict_db_video_not_found"));
    }
  }


  /**
   * get_prev_video
   *
   * Get data of the previous video identified by the argument slug.
   *
   * @param  vid_category String
   * @param  vid_slug String
   * @return Array json encoded
   */
  function get_prev_video() {
    $f3 = F3::instance();
    $video_model = new VideoModel();
    $current = $video_model->get_by_slug($f3->get("POST.vid_slug"));
    $prev = $video_model->get_prev($current['vid_order'], $f3->get("POST.vid_category"));
    if (is_array($prev) ) {
      echo json_encode(array("status"=>"ok", "result"=>$prev));
    } else {
      echo json_encode(array("status"=>"error", "msg"=>"dict_db_video_not_found"));
    }
  }



  /**
   * get_video_detail
   *
   * Get data of the video identified by the argument id.
   *
   * @param  POST.vid_slug
   * @return Array json encoded
   */
  function get_video_detail() {
    $f3 = F3::instance();
    $video_model = new VideoModel();
    $video = $video_model->get_by_slug($f3->get("POST.vid_slug"));
    if (!SessionManager::is_logged() && $video['vid_protected']==1) {
      $video['vid_url_en'] = "";
      $video['vid_url_es'] = "";
    }
    if (is_array($video) ) {
      echo json_encode(array("status"=>"ok", "result"=>$video));
    } else {
      echo json_encode(array("status"=>"error", "msg"=>"dict_db_video_not_found"));
    }
  }


  /**
   * video_watched
   *
   * Get a Video ID and create a visualization of that video for the current
   * user.
   *
   * @param  POST.vid_id Integer ID único del video
   * @return result of insert query.
   */
  function video_watched() {
    $f3 = F3::instance();
    $vid_id = $f3->get("POST.vid_id");
    $sess = $f3->get("SESSION");
    if (count($sess)>0 && array_key_exists("id", $sess)) {
      $video_model = new VideoModel();
      $result = $video_model->flag_as_seen($vid_id, $sess["id"]);
    }
  }

}
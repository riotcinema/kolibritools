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
 * VideoModel
 *
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
class VideoModel {

  /**
   * flag_as_seen
   *
   * Do nothing if user already saw the video. Otherwise flag as seen.
   *
   * @param $vid_id Integer Video ID
   * @param $use_id Integer Customer ID
   */
  function flag_as_seen($vid_id, $use_id) {
    $f3 = F3::instance();

    $result = array("status"=>"error", "msg"=>"El video no se ha podido marcar como visto.");

    $video_model = new VideoModel();
    if (!$video_model->is_watched($vid_id, $use_id)) {
      $vis_model = new VisualizationModel();
      $result = $vis_model->create($vid_id, $use_id);
      if (!is_array($result)) {
        return array("status"=>"ok", "msg"=>"Video marcado como visto");
      }

    }

    return $result;

  }


  /**
   * get_by_id
   *
   * Get a Video in array format identified by ID.
   *
   * @param $id Integer
   */
  function get_by_id($id) {
    $f3 = F3::instance();
    $video_mapper = new DB\SQL\Mapper($f3->get("DB"), "video");
    $aVideo = $video_mapper->afindone(array('vid_id=:id',array(':id'=>$id)));
    return $aVideo;
  }


  /**
   * get_by_slug
   *
   * Get a Video in array format identified by slug.
   *
   * @param $slug String
   */
  function get_by_slug($slug) {
    $f3 = F3::instance();

    $video_mapper = new DB\SQL\Mapper($f3->get("DB"), "video");
    $aVideo = $video_mapper->afindone(array('vid_slug=:slug',array(':slug'=>$slug)));

    $comment_model = new CommentModel();
    $questions = $comment_model->a_comments($aVideo["vid_id"]);
    $response_model = new ResponseModel();
    $answers = $response_model->a_responses($aVideo["vid_id"]);
    $comments = $comment_model->join_questions_answers($questions, $answers);
    $aVideo["vid_comments"] = $comments;
    return $aVideo;
  }


  /**
   * get_diaries
   *
   * Get a collection of video of _DIARY_ category.
   *
   * @return Array
   */
  function get_diaries() {
    $f3 = F3::instance();
    $db = $f3->get("DB");

    // inicio de la consulta
    $query = " SELECT * FROM video
      WHERE vid_category='_DIARY_'
    	ORDER BY vid_order ASC ";
    $videos = $db->exec($query);
    return $videos;
  }


  /**
   * get_easter_eggs
   *
   * Get a collection of video of _EASTERN_EGG_ category.
   *
   * @return Array
   */
  function get_eastern_eggs() {
    $f3 = F3::instance();
    $db = $f3->get("DB");

    // inicio de la consulta
    $query = " SELECT * FROM video
      WHERE vid_category='_EASTERN_EGG_' ";
    $videos = $db->exec($query);
    return $videos;
  }


  /**
   * get_first
   *
   * Get the first video of a category. Videos are ordered by a flag field
   * called order in video table.
   *
   * @param   $category String
   * @return  $array Video in array format.
   */
  function get_first($category) {
    $f3 = F3::instance();
    $db = $f3->get("DB");
    $query = "SELECT vid_slug FROM video WHERE vid_order=(SELECT MIN(vid_order) FROM video WHERE vid_order!=0 AND vid_release<=NOW() AND vid_category='".$category."' ) AND  vid_category='".$category."' ";
    $result = $db->exec($query);
    if (count($result)==1) {
      return $this->get_by_slug($result[0]["vid_slug"]);
    }
  }


  /**
   * get_last
   *
   * Get the last video of a category. Videos are ordered by a flag field
   * called order in video table.
   *
   * @param   $category String
   * @return  $array Video in array format.
   */
  function get_last($category) {
    $f3 = F3::instance();
    $db = $f3->get("DB");
    $query = " SELECT vid_slug FROM video WHERE vid_order=(SELECT MAX(vid_order) FROM video WHERE vid_order!=0 AND vid_release<=NOW() AND vid_category='".$category."' ) AND vid_category='".$category."' ";
    $result = $db->exec($query);
    if (count($result)==1) {
      return $this->get_by_slug($result[0]["vid_slug"]);
    }
  }


  /**
   * get_next
   *
   * Get the next video of a category. Videos are ordered by a flag field
   * called order in video table.
   *
   * @param   $category String
   * @param   $order Integer
   * @return  $array Video in array format.
   */
  function get_next($order, $category) {
    $f3 = F3::instance();
    $db = $f3->get("DB");
    $query = " SELECT vid_slug FROM video WHERE vid_order=(SELECT MIN(vid_order) FROM video WHERE vid_order!=0 AND vid_order>".$order." AND vid_release<=NOW() AND vid_category='".$category."' ) AND vid_category='".$category."' ";
    $result = $db->exec($query);
    if (count($result)==1) {
    return $this->get_by_slug($result[0]["vid_slug"]);
    } else {
      return $this->get_first($category);
    }
  }


  /**
   * get_parallax
   *
   * Get a collection of video of _TRANSMEDIA_ category.
   *
   * @return Array
   */
  function get_parallax() {
    return $this->get_videos(array('vid_category=?','_TRANSMEDIA_'));
  }


  /**
   * get_prev
   *
   * Get the previous video of a category. Videos are ordered by a flag field
   * called order in video table.
   *
   * @param   $category String
   * @param   $order Integer
   * @return  $array Video in array format.
   */
  function get_prev($order, $category) {
    $f3 = F3::instance();
    $db = $f3->get("DB");
    $query = " SELECT vid_slug FROM video WHERE vid_order=(SELECT MAX(vid_order) FROM video WHERE vid_order!=0 AND vid_order<".$order." AND vid_release<=NOW() AND vid_category='".$category."' ) AND vid_category='".$category."' ";
    $result = $db->exec($query);

    if (count($result)==1) {
    return $this->get_by_slug($result[0]["vid_slug"]);
    } else {
      return $this->get_last($category);
    }
  }


  /**
   * get_the_movie
   *
   * Get the movie. The Cosmonaut movie is the only video with _MOVIE_ category.
   *
   * @return Array
   */
  function get_the_movie() {
  	$f3 = F3::instance();
  	$db = $f3->get("DB");
    $query = "SELECT * FROM video WHERE vid_category=:category LIMIT 1 ";
    $params = array("category"=>'_MOVIE_');
    $result = $db->exec($query, $params);

    if (count($result)==1) {
    	return $result[0];
    } else {
    	return NULL;
    }
  }


  /**
   * get_transmedia
   *
   * Get a collection of video of _TRANSMEDIA_ category.
   *
   * @return Array
   */
  function get_transmedia($order=array()) {
    return $this->get_videos(array('vid_category=?','_TRANSMEDIA_'));
  }


  /**
   * get_videos
   *
   * Get a collection of videos with filter options.
   *
   * @return Array
   */
  function get_videos($filters=array()) {
    $f3 = F3::instance();
    $db = $f3->get("DB");
    $video_mapper = new DB\SQL\Mapper($f3->get("DB"), "video");
    $videos = $video_mapper->afind($filters);
    return $videos;
  }



  /**
   * is_watched
   *
   * Check if a customer has seen a video. Both video and user are identified
   * by id in parameter list.
   *
   * @return Boolean
   */
  function is_watched($vid_id, $use_id) {
    $f3 = F3::instance();

    $db = $f3->get("DB");

    $query = "SELECT vis_id FROM visualization WHERE vis_fk_vid_id=:vid_id AND vis_fk_use_id=:use_id ";
    $params = array("vid_id"=>$vid_id, "use_id"=>$use_id);
    $result = $db->exec($query, $params);
    return count($result)==0 ? FALSE : TRUE;
  }



  /**
   * to_select
   *
   * Get videos collection an return them in associative array format ready to
   * popullate input select items.
   *
   * @return Associative array (vid_id, vid_title)
   */
  function to_select() {
    $f3 = F3::instance();
    $db = $f3->get("DB");
    if (strtolower($f3->get("LANGUAGE"))=="es") {
    	$result = $db->exec(" SELECT vid_id, vid_title_es AS vid_title FROM video ORDER BY vid_title_es ASC");
    } else {
    	$result = $db->exec(" SELECT vid_id, vid_title_en AS vid_title FROM video ORDER BY vid_title_en ASC");
    }
    foreach ($result as $k=>$video) {
      $videos[$video["vid_id"]] = $result[$k];
    }
    return $videos;
  }

}
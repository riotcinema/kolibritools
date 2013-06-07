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
 * SiteController
 *
 * Handle almost every request of the website. Most of these methods collect
 * partial files (JS, Css), get data from database and assembly these partial
 * templates.
 */
class SiteController {


	/**
		* about
		*
		* Prepare /about template. This is a basic page with static information. No
		* database access is required.
		* About template has 5 sections: header, principles, behind, meet and press
		*/
	function about() {
		$f3 = F3::instance();

		$f3->set('external_js', array (
			"http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js",
			"http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js",
			"http://a.vimeocdn.com/js/froogaloop2.min.js"
		));

		$f3->set("extra_js", array (
			"lib/modernizr-2.5.3-min.js",
			"lib/plugins.js",
			"src/cosmonaut.data.js",
			"src/cosmonaut.common.js",
			"src/cosmonaut.press.js",
			"src/cosmonaut.template.js",
			"src/cosmonaut.login.js",
			"src/cosmonaut.video.js",
      "src/cosmonaut.vimeo.js",
			"src/cosmonaut.about.js"
		));

		// variables to be used in final render
		$this->prepare_language_data();
		$f3->set("body_id","about");
 		$f3->set("short_url", "");
		$f3->set("about_selected", true);

		// get partial templates and render
		$f3->set('tem_header', 'site_header.html');
		$f3->set('tem_content', 'about_template.html');
		$f3->set('press_template', 'press_template.html');
		$f3->set('tem_blog', 'site_blog.html');
		$f3->set('tem_footer', 'site_footer.html');
		echo Template::instance()->render('site_template.html');

	}


	/**
	 * authorized
	 *
	 * Check if current user is logged.
	 *
	 * @return Array json encoded
	 */
	function authorized() {
		$f3 = F3::instance();
		$response = array("result"=>"error");
		if (SessionManager::is_logged()) {
			$response = array("result"=>"ok"); 
		} else if (SessionManager::is_guest()) {
				$response = array("result"=>"ok");
		}
		echo json_encode($response);
	}


	/**
	 * behind_scenes
	 *
	 * Prepare /behind_the_scenes template.
	 */
	function behind_scenes() {
		$f3 = F3::instance();

		$f3->set('tem_header', 'site_header.html');
		$f3->set('tem_content', 'behind_scenes_template.html');
		$f3->set('tem_kpass', 'home_shop.html');
		$f3->set('vid_detail',  'video_template.html');
		$f3->set('tem_login', 'site_login.html');
		$f3->set('tem_footer', 'site_footer.html');

		$f3->set("extra_css", array("style.css"));

		$f3->set('external_js', array (
			"http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js",
			"http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js",
			"http://platform.twitter.com/widgets.js",
			"http://a.vimeocdn.com/js/froogaloop2.min.js"
		));

		$f3->set("extra_js", array (
			"lib/jquery.validity.min.js",
 			"lib/jquery.carouFredSel-6.2.0-packed.js",
			"lib/modernizr-2.5.3-min.js",
			"lib/plugins.js",
			"src/cosmonaut.data.js",
			"src/cosmonaut.common.js",
			"src/cosmonaut.template.js",
			"src/cosmonaut.behind.js",
			"src/cosmonaut.login.js",
			"src/cosmonaut.video.js",
			"src/cosmonaut.facebook.js",
			"src/cosmonaut.twitter.js"
		));

		// get goo.gl short URLs in case user tweets
    if (strtolower($f3->get("LANGUAGE"))=="es") {
      $f3->set("short_url", $f3->get("URL_SHORT_BS_ES"));
    } else {
      $f3->set("short_url", $f3->get("URL_SHORT_BS_EN"));
    }

    // collect all video diaries data and check if each one has been already
    // published (compare vid_release date with today date).
		$today = new DateTime(date("Y-m-d H:i:s"));
		$video_model = new VideoModel();
		$diaries = $video_model->get_diaries();
		foreach ($diaries as $k=>$v) {
			$release = new DateTime($v["vid_release"]);
			$countdown = $today->diff($release);
			$diaries[$k]["vid_title"] = strtolower($f3->get("LANGUAGE")) == "es" ? $v["vid_title_es"] : $v["vid_title_en"];
			$diaries[$k]["vid_published"] = $countdown->invert==0 ? 0 : 1 ;
		}
		$f3->set("diaries", $diaries);

    // collect all eastern egg videos data and check if each one has been
    //  already published (compare vid_release date with today date).
		$easterns = $video_model->get_eastern_eggs();
		foreach ($easterns as $k=>$v) {
			$release = new DateTime($v["vid_release"]);
			$countdown = $today->diff($release);
			$easterns[$k]["vid_title"] = strtolower($f3->get("LANGUAGE")) == "es" ? $v["vid_title_es"] : $v["vid_title_en"];
			$easterns[$k]["vid_published"] = $countdown->invert==0 ? 0 : 1 ;
		}
		$f3->set("easterns", $easterns);

		$this->prepare_language_data();

		$f3->set("body_id", "main");
		$f3->set("body_class", "behind-section");
		$f3->set("facebook_required", TRUE);
		$f3->set("about_selected", true);

		echo Template::instance()->render('site_template.html');
	}



	/**
		* credits
		*
		* Prepare /credits template.
		* This page is huge, so backers and investors have been stored in a
		* database table 'credits'.
		*/
	function credits() {
		$f3 = F3::instance();

		$f3->set('tem_header', 'site_header.html');
		$f3->set('tem_content', 'credits_template.html');

		$f3->set("extra_css", array("style.css"));

		$f3->set('external_js', array (
			"http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js",
			"http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js",
		));

		$f3->set("extra_js", array (
			"lib/modernizr-2.5.3-min.js",
			"lib/plugins.js",
			"src/cosmonaut.data.js",
			"src/cosmonaut.common.js",
			"src/cosmonaut.template.js",
			"src/cosmonaut.credits.js"
		));

		$this->prepare_language_data();

		// get data from database
		$credit_model = new CreditModel();
		$f3->set("backers", $credit_model->get_backers());
		$f3->set("investors", $credit_model->get_investors());

		$f3->set("body_id", "remix");
		$f3->set("about_selected", true);

		echo Template::instance()->render('site_template.html');
	}


	/**
	 * dashboard
	 *
	 * Prepare customr's dashboard template (/dashboard). This page is only
	 * visible to registered customers. In this page, a user can see how many
	 * transmedia and eastern eggs has already seen (visulization table).
	 */
	function dashboard() {
		$f3 = F3::instance();

		// page only available to logged users
		$security = new SessionManager();
		if (!$security->is_logged()) {

			$f3->reroute("/k_pass");

		} else {
			$this->prepare_language_data();

			// check how many videos have already been seen
			$visualization_model = new VisualizationModel();
			$f3->set("visualized_transmedia", count($visualization_model->get_transmedia($f3->get("SESSION.id"))));
			$f3->set("visualized_easterns", count($visualization_model->get_eastern_eggs($f3->get("SESSION.id"))));

			// check hown many videos have been already published.
			$video_model = new VideoModel();
			$f3->set("num_transmedia", count($video_model->get_transmedia()));
			$e = count($video_model->get_eastern_eggs());
			$d = count($video_model->get_diaries());
 			$f3->set("num_easterns",  $e+$d );

 			// check if a user has seen all videos of any category, a badge will be
 			// displayed in the template
 			$total_visualizations = $f3->get("visualized_transmedia") + $f3->get("visualized_easterns") + $f3->get("visualized_newsletter") + $f3->get("visualized_the_book");
 			$total_media = $f3->get("num_transmedia") + $f3->get("num_easterns") + 1 + 1;
 			if ($total_visualizations < $total_media/2) {
 				$f3->set("visualization_message", $f3->get("dict_das_looks_like_you"));
 			} else if ($total_visualizations == $total_media) {
				$f3->set("visualization_message", $f3->get("dict_das_looks_like_experienced"));
 			} else {
				$f3->set("visualization_message", $f3->get("dict_das_looks_like_you_still"));
 			}

 			// get data of current customer. Part of the template displays user info.
	    $user_mapper = new DB\SQL\Mapper($f3->get("DB_SHOP"), "ps_customer");
	    $user_mapper->load(array('id_customer=:id',array(':id'=>$f3->get('SESSION.id'))));
	    $user_mapper->copyTo('POST');

			$f3->set('tem_header', 'site_header.html');

			$f3->set('tem_content', 'dashboard_template.html');

			$f3->set('tem_blog', 'site_blog.html');
			$f3->set('tem_footer', 'site_footer.html');

			$f3->set("extra_css", array(
				"style.css",
				"jquery.validity.css"
			));

			$f3->set('external_js', array (
				"http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js",
				"http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js",
			));

			$f3->set("extra_js", array (
				"lib/jquery.validity.min.js",
				"src/cosmonaut.template.js",
				"src/cosmonaut.data.js",
				"src/cosmonaut.common.js",
				"src/cosmonaut.dashboard.js"
			));

			$f3->set("k_pass_selected", true);

			echo Template::instance()->render('site_template.html');

		}

	}


	/**
	 * demand
	 *
	 * Set auto_open with the name of a jQuery dialog element that shoul be auto
	 * opened on /live_experience request.
	 */
	function demand() {
		$f3 = F3::instance();
		$f3->set("auto_open", "lightbox-demand");
		$this->live_experience();
	}



	/**
	 * demand_show
	 *
	 * Get a demand event form and send it (via e-mail) to the contact mail address.
	 *
	 * @param		POST.alert_me Boolean
	 * @param		POST.city String
	 * @param		POST.email String
	 * @param		POST.help Boolean
	 * @param		POST.type_experience Boolean
	 * @param		POST.type_normal Boolean
	 * @param		POST.type_screen Boolean
	 * @param		POST.zip_code String
	 */
	function demand_show() {
		$f3 = F3::instance();
		$result = array ("success"=>TRUE, "msg"=>array());
	
		$f3->scrub($_POST);
	
		// at least one type of experience should be selected
		$type_experience = $f3->get("POST.type_experience");
		$type_experience = isset($type_experience) && $type_experience=="true" ? TRUE : NULL;
		$type_normal = $f3->get("POST.type_normal");
		$type_normal = isset($type_normal) && $type_normal=="true" ? TRUE : NULL;
		$type_screen = $f3->get("POST.type_screen");
		$type_screen = isset($type_screen) && $type_screen=="true" ? TRUE : NULL;
		if ($type_experience==NULL && $type_normal==NULL && $type_screen==NULL) {
			$result["success"] = FALSE;
			$result["msg"][] = $f3->get("dict_tra_dem_error_type");
		}
	
		// city is a required field
		$city = $f3->get("POST.city");
		if (!isset($city) || empty($city)) {
			$result["success"] = FALSE;
			$result["msg"][] = $f3->get("dict_tra_dem_error_generic_demand");
		}
	
		// email is a required field
		$email = $f3->get("POST.email");
		$email = isset($email) && $email!="" ? $email : NULL;
		if (!isset($email) || empty($email)) {
			$result["success"] = FALSE;
			$result["msg"][] = $f3->get("dict_tra_dem_error_email");
		}
	
		// zip code is a required field
		$zip_code = $f3->get("POST.zip_code");
		if (!isset($zip_code) || empty($zip_code)) {
			$result["success"] = FALSE;
			$result["msg"][] = $f3->get("dict_tra_dem_error_generic_demand");
		}
	
		// all fields where properly filled
		if ($result["success"]==TRUE) {
			$alert_me = $f3->get("POST.alert_me");
			$alert_me = isset($alert_me) && $alert_me=="on" ? TRUE : FALSE;
	
			$help = $f3->get("POST.help");
			$help = isset($help) && $help=="on" ? TRUE : FALSE;

			$f3->set('alert_me', $alert_me);
			$f3->set('city', $city);
			$f3->set('email', $email);
			$f3->set('help', $help);
			$f3->set('type_experience', $type_experience);
			$f3->set('type_normal', $type_normal);
			$f3->set('type_screen', $type_screen);
			$f3->set('zip_code', $zip_code);
	
			// send e-mail
			$boundary = uniqid("HTMLDEMO");
			$header  = "From: ".$f3->get("dict_com_the_cosmonaut")." <".$f3->get("MAIL_NO_REPLY").">\r\n";
			$header .= "MIME-Version: 1.0\r\n";
			$header .= "Content-type: text/html; charset=utf-8'\r\n";
			$send = mail(
					$f3->get("MAIL_CONTACT"),
					$f3->get("MAIL_DEMAND_SUBJECT"),
					Template::instance()->render('mail_demand_event.html'), $header);
	
			if ($send==FALSE) {
				$result["success"] = FALSE;
				$result["msg"][] = $f3->get("dict_tra_dem_error_sendmail");
			}
		}
		echo json_encode($result);
	}


	/**
	 * downloads
	 *
	 * Set jump_to with the name of a DOM section in /about page. Once this page
	 * has been loaded, a scroll effect will be fired.
	 *
	 */
	function downloads() {
		$f3 = F3::instance();
		$f3->set("jump_to", "press-area");
		$this->remix();
	}


	/**
	 * error_404
	 *
	 * Render /404 error page.
	 */
	function error_404() {
		$f3 = F3::instance();

		$f3->set('tem_header', 'site_header.html');
		$f3->set('tem_content', '404_template.html');
		$f3->set('tem_footer', 'site_footer.html');

		$f3->set("extra_css", array("style.css"));

		$f3->set('external_js', array ("http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"));

		$f3->set("extra_js", array (
			"src/cosmonaut.data.js",
			"src/cosmonaut.common.js",
			"src/cosmonaut.template.js",
			"src/cosmonaut.404.js"
		));
		$f3->set("body_id","about");

		$this->prepare_language_data();

		echo Template::instance()->render('site_template.html');
	}


	/**
	 * error_handler
	 *
	 * Common error handling method. Redirect to 404 page if web app is not
	 * executing in development mode.
	 */
	function error_handler() {
		$f3 = F3::instance();
		$error = $f3->get("ERROR");
		if (is_array($error) && $error['code']==404) {
		 	$f3->reroute("/404");
		} else {
			if ($f3->get("ENVIRONMENT")=="DEVELOPMENT") {

			} else {
				$f3->reroute("/404");
			}
		}
  }


	/**
	 * film_universe
	 *
	 * Prepare /film_universe template. It
	 */
	function film_universe() {
		$f3 = F3::instance();

		// check if Movie is released. In that case, template will display a panel
		// in which users can see the movie.
		$video_model = new VideoModel();
		$movie = $video_model->get_the_movie();
		$f3->set("movie", $movie);
		$release = new DateTime($movie['vid_release']);
		$today = new DateTime(date("Y-m-d H:i:s"));
		$countdown = $today->diff($release);
		if ($countdown->invert == 1) {
			$f3->set('released', TRUE);
			$f3->set("lang_url_the_movie", strtoupper($f3->get("LANGUAGE"))=="ES" ? $movie['vid_url_es'] : $movie['vid_url_en']);
		} else {
			$f3->set("lang_url_the_movie", "");
		}

		// partial templates
		$f3->set('tem_header', 'site_header.html');
		$f3->set('tem_login', 'site_login.html');
		$f3->set('tem_content', 'universe_template.html');
		$f3->set('vid_detail',  'video_template.html');
		$f3->set('the_movie', 'movie_template.html');
		$f3->set('tem_blog', 'site_blog.html');
		$f3->set('tem_footer', 'site_footer.html');

		$f3->set("extra_css", array(
			"style.css",
			"jquery.validity.css"
		));

		$f3->set('external_js', array (
			"http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js",
			"http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js",
			"http://platform.twitter.com/widgets.js",
			"http://a.vimeocdn.com/js/froogaloop2.min.js"
		));

		$f3->set("extra_js", array (
			"lib/jquery.validity.min.js",
			"lib/modernizr-2.5.3-min.js",
			"lib/plugins.js",
			"lib/jquery.localscroll-1.2.7-min.js",
			"lib/jquery.parallax-1.1.3.js",
			"lib/jquery.scrollTo-1.4.2-min.js",
			"src/cosmonaut.data.js",
			"src/cosmonaut.common.js",
			"src/cosmonaut.login.js",
			"src/cosmonaut.movie.js",
			"src/cosmonaut.template.js",
			"src/cosmonaut.universe.js",
			"src/cosmonaut.facebook.js",
			"src/cosmonaut.twitter.js",
			"src/cosmonaut.video.js"
		));

		// short goo.gl URLs in case user wnats to tweet
    if (strtolower($f3->get("LANGUAGE"))=="es") {
      $f3->set("short_url", $f3->get("URL_SHORT_FU_ES"));
    } else {
      $f3->set("short_url", $f3->get("URL_SHORT_FU_EN"));
    }

		$this->prepare_language_data();

		// get all transmedia videos
		$videos = $video_model->get_parallax();
		$parallax_videos = array();
		// Each parallax video has a position in the parallax sections. Video slug
		// is used for positioning in CSS
		$f3->set("i_bg1", array('the-okbs', 'the-good-old-days-i', 'the-good-old-days-ii', 'the-good-old-days-iii'));
		$f3->set("i_bg2", array('the-good-old-days-iv', 'the-moon-landing', 'bondarenko', 'secret-conversations-i', 'secret-conversations-ii', 'tanaziov'));
		$f3->set("i_bg3", array('komarov', 'star-city1', 'star-city2', 'star-city3', 'star-city4', 'star-city5'));
		$f3->set("i_bg4", array('star-city6', 'cosmo1', 'cosmo2', 'cosmo3', 'cosmo4', 'cosmo5', 'cosmo6'));
		$f3->set("i_bg5", array('cosmo7', 'cosmo8', 'cosmo9', 'cosmo10'));
		$f3->set("i_bg6", array('cosmo11', 'cosmo12', 'cosmo13', 'cosmo14', 'cosmo15', 'cosmo16', 'cosmo17'));
		$f3->set("i_bg7", array('cosmo18', 'cosmo19', 'cosmo21'));

		foreach ($videos as $k=>$v) {
			$parallax_videos[$v["vid_slug"]] = $v;
			$release = new DateTime($v["vid_release"]);
			$countdown = $today->diff($release);
			$parallax_videos[$v["vid_slug"]]["vid_published"] = $countdown->invert==0 ? 0 : 1 ;
			$parallax_videos[$v["vid_slug"]]["vid_title"] = strtolower($f3->get("LANGUAGE"))=="es" ? $v["vid_title_es"] : $v["vid_title_en"];			
			$parallax_videos[$v["vid_slug"]]["alt_title"] = $countdown->invert==0 ? $this->date2title($v["vid_release"]) : $parallax_videos[$v["vid_slug"]]["vid_title"] ;
			unset ($videos[$k]);
		}

		$f3->set("facebook_required", TRUE);
		$f3->set("parallax_videos", $parallax_videos);

		$f3->set("universe_selected", true);
		$f3->set("body_id", "film-universe");
		echo Template::instance()->render('site_template.html');
	}



	/**
	 * find_premiere
	 * 
	 * Set jump_to with the name of a DOM section in /live_experience page. Once
	 * this page has been loaded, a scroll effect will be fired.
	 */
	function find_premiere() {
		$f3 = F3::instance();
		$f3->set("jump_to", "sectionMap");
		$this->live_experience();
	}



	/**
	 * guest
	 *
	 * Set current user as a guest in the page. A guest session will be created
	 * granting access to the movie.
	 */
	function guest() {
		$f3 = F3::instance();
		if (SessionManager::is_logged()==FALSE && SessionManager::is_guest()==FALSE) {
	  	$login = "anonymous@elcosmonauta.es";
	  	SessionManager::set_guest($login);
		}
	}


	/**
		* home
		*
		* Home page of the site.
		*
		*/
	function home() {
		$f3 = F3::instance();

		// Get release date of The Cosmonaut movie. If the movie has been released
		// a "watch it now" button is available. otherwise a countdown will be
		// displayed.
		$video_model = new VideoModel();
		$movie = $video_model->get_the_movie();
		$release = new DateTime($movie['vid_release']);
		$today = new DateTime(date("Y-m-d H:i:s"));
		$countdown = $today->diff($release);
		if ($countdown->invert == 1) {
			$released = TRUE;
			$f3->set('released', $released);
		}

		// partial templates
		$f3->set('tem_header', 'site_header.html');
		$f3->set('hom_video', 'home_video.html');
		$f3->set('hom_quotes', 'quotes_template.html');
		$f3->set('hom_film', 'home_film.html');
		$f3->set('tem_infowindow', 'map_infowindow.html');
		$f3->set('hom_map', 'map_template.html');
		$f3->set('map_overlay', 'map_overlay_home.html');
		$f3->set('hom_shop', 'home_shop.html');
		$f3->set('tem_content', 'home_template.html');
		$f3->set('tem_blog', 'site_blog.html');
		$f3->set('tem_footer', 'site_footer.html');

		$f3->set("extra_css", array("style.css"));
		$f3->set("external_css", array ( "http://libs.cartocdn.com/cartodb.js/v2/themes/css/cartodb.css"));

		$f3->set('external_js', array (
			"http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js",
			"https://maps.googleapis.com/maps/api/js?key=AIzaSyBkkUAGV30LWjX_FzgJF2sj1W07iXYBLnM&sensor=true",
			"http://libs.cartocdn.com/cartodb.js/v2/cartodb.js"
		));

		$f3->set("extra_js", array (
			"lib/modernizr-2.5.3-min.js",
			"lib/plugins.js",
			"lib/jquery.localscroll-1.2.7-min.js",
			"lib/jquery.parallax-1.1.3.js",
			"lib/jquery.scrollTo-1.4.2-min.js",
			"src/cosmonaut.data.js",
			"src/cosmonaut.common.js",
			"src/cosmonaut.template.js",
			"src/cosmonaut.map.js",
			"src/cosmonaut.home.js"
		));

		$this->prepare_language_data();

		// list all media quotes
		$quote_model = new QuoteModel();
		$f3->set("quotes", $quote_model->list_quotes());

		// composition of countdown message
		if (isset($released) && $released==TRUE) {
			$f3->set("countdowm_link", "watch_it_now");
			$f3->set("countdown_message", $f3->get("dict_hom_watch_it_for_free") );
		} else {
		  $f3->set("countdowm_link", "film_universe");
			$f3->set("countdown_message", $f3->get("dict_hom_countdown_1").$countdown->days.$f3->get("dict_hom_countdown_2") );
		}

		echo Template::instance()->render('site_template.html');
	}


	/**
	 * k_pass
	 *
	 * Prepare /k-pass template. This page is only available to not logged
	 * users. If a user is logged, /dashboard template will be displayed
	 * instead.
	 */
	function k_pass() {
		$f3 = F3::instance();

		// if user is logged, redirect to dashboard
		$security = new SessionManager();
		if ($security->is_logged()) {
			$f3->reroute("/dashboard");
		} else {

			// get number of transmedia and video-diaries
			$video_model = new VideoModel();
			$e = count($video_model->get_eastern_eggs());
			$d = count($video_model->get_diaries());
 			$f3->set("num_easterns",  $e+$d );
			$f3->set("num_transmedia", count($video_model->get_transmedia()));

			$f3->set('tem_header', 'site_header.html');

			$f3->set("lang_url_k_pass", ($f3->get("LANGUAGE")=="ES") ? $f3->get("URL_K_PASS_ES") : $f3->get("URL_K_PASS_EN"));
			$f3->set("lang_url_shop", ($f3->get("LANGUAGE")=="ES") ? $f3->get("URL_SHOP_ES") : $f3->get("URL_SHOP_EN"));
			$f3->set('tem_content', 'k_pass_template.html');

			$f3->set('tem_login', 'site_login.html');
			$f3->set('tem_footer', 'site_footer.html');

			$f3->set("extra_css", array("style.css", "jquery.validity.css"));

			$f3->set('external_js', array (
				"http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js",
				"http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"
			));

			$f3->set("extra_js", array (
				"lib/jquery.validity.min.js",
				"lib/modernizr-2.5.3-min.js",
				"lib/plugins.js",
				"src/cosmonaut.data.js",
				"src/cosmonaut.common.js",
				"src/cosmonaut.template.js",
				"src/cosmonaut.login.js",
				"src/cosmonaut.k_pass.js"
			));

			$f3->set("body_id", "about");
			$f3->set("k_pass_selected", true);
			$this->prepare_language_data();

			echo Template::instance()->render('site_template.html');
		}
	}


	/**
	 * licenses
	 *
	 * Render /licences page. It's a very simple static page.
	 */
	function licenses() {
		$f3 = F3::instance();
	
		$f3->set('tem_header', 'site_header.html');
		$template = strtolower($f3->get("LANGUAGE")=="es") ? 'site_licenses_es.html' : 'site_licenses_en.html';
		$f3->set('tem_content', $template);
		
		$f3->set('tem_footer', 'site_footer.html');
	
		$f3->set("extra_css", array("style.css"));
	
		$f3->set("about_selected", true);
	
		$this->prepare_language_data();

		echo Template::instance()->render('site_template.html');
	}


	/**
	 * live_experince
	 *
	 * Render live_experience page. It's a simple page with no database access
	 * required.
	 */
	function live_experience() {
		$f3 = F3::instance();
		// partial templates
		$f3->set('tem_header', 'site_header.html');
		$f3->set('tem_content', 'experience_template.html');
		$f3->set('tem_blog', 'site_blog.html');
		$f3->set('tem_footer', 'site_footer.html');
		$f3->set('map_template', 'map_template.html');
		$f3->set('map_overlay', 'map_overlay_experience.html');
		$f3->set('events_template', 'events_template.html');
		$f3->set('shop_template', 'home_shop.html');
		$f3->set('tem_infowindow', 'map_infowindow.html');
		$f3->set('demand_event', 'map_demand_event.html');
		$f3->set('demand_event_conf', 'map_demand_event_confirmation.html');
		$f3->set('organize_event', 'map_organize_event.html');
		$f3->set('organize_event_conf', 'map_organize_event_confirmation.html');
		
		$f3->set('external_js', array (
				"http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js",
				"http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js",
				"https://maps.googleapis.com/maps/api/js?key=AIzaSyBkkUAGV30LWjX_FzgJF2sj1W07iXYBLnM&sensor=true",
				"http://libs.cartocdn.com/cartodb.js/v2/cartodb.js"
		));

		$f3->set('extra_js', array (
				"lib/jquery.validity.min.js",
				"lib/jquery.validity.lang.".strtolower($f3->get("LANGUAGE")).".js",
				"src/cosmonaut.data.js",
				"src/cosmonaut.common.js",
				"src/cosmonaut.map.js",
				"src/cosmonaut.experience.js"
		));

		$f3->set("body_id", "main");
		$f3->set("external_css", array ( "http://libs.cartocdn.com/cartodb.js/v2/themes/css/cartodb.css"));
		$f3->set("extra_css", array("style.css", "jquery.validity.css"));
		
		$this->prepare_language_data();
		
		echo Template::instance()->render('site_template.html');
	}


	/**
	 * logout
	 *
	 * Destroy current session.
	 */
	function logout() {
		$f3 = F3::instance();
		$session = new SessionManager();
		$session->force_logout();
	}


  /**
   * nayik
   *
   * Render /nayik page.
   */
  function nayik() {
  	$f3 = F3::instance();

  	// partial templates
  	$f3->set('tem_header', 'site_header.html');
  	$f3->set('tem_content', 'nayik_template.html');
  	$f3->set('tem_footer', 'site_footer.html');
  	
  	$f3->set("extra_css", array("style.css"));
  	
  	$f3->set("nayik_selected", true);

  	// get all posts and it's responses
  	$nayik_model = new NayikModel();
  	$f3->set("plot", $nayik_model->getPosts());
  	// get all nayik characters to display it's avatars
  	$character_model = new CharacterModel();
  	$f3->set("characters", $character_model->getCharacters());
  	
  	$this->prepare_language_data();
  	
  	echo Template::instance()->render('site_template.html');
  }


  /**
   * organize
   *
	 * Set auto_open with the name of a jQuery dialog element that shoul be auto
	 * opened on /live_experience request.
   */
  function organize() {
  	$f3 = F3::instance();
  	$f3->set("auto_open", "lightbox-organize");
  	$this->live_experience();
  }


  /**
   * organize_show
   *
	 * Get an organize event form and send it (via e-mail) to the contact mail
	 * address.
	 *
	 * @param		POST.address String
	 * @param		POST.complete_name String
	 * @param		POST.date String
	 * @param		POST.email String
	 * @param		POST.id Integer
	 * @param		POST.extras_additional
	 * @param		POST.extras_merchandising
	 * @param		POST.extras_party
	 * @param		POST.extras_profit
	 * @param		POST.extras_q_and_a
	 * @param		POST.extras_snacks
	 * @param		POST.phone
	 * @param		POST.tech_35mm Boolean
	 * @param		POST.tech_dcp Boolean
	 * @param		POST.tech_dvd Boolean
	 * @param		POST.tech_streaning Boolean
	 * @param		POST.where_amateur Boolean
	 * @param		POST.where_descriptor Boolean
	 * @param		POST.where_people
	 * @param		POST.where_pro Boolean
   */
  function organize_show() {
  	$f3 = F3::instance();
  
  	$result = array ("success"=>TRUE, "msg"=>array());
  
  	$f3->scrub($_POST);
  
  	// at least one kind of event should be specified
  	$where_pro = $f3->get("POST.where_pro");
  	$where_pro = isset($where_pro) && $where_pro=="true" ? TRUE : NULL;
  	$where_amateur = $f3->get("POST.where_amateur");
  	$where_amateur = isset($where_amateur) && $where_amateur=="true" ? TRUE : NULL;
  	if ($where_amateur==NULL && $where_pro==NULL) {
  		$result["success"] = FALSE;
  		$result["msg"][] = $f3->get("dict_error_where");
  	}
  
  	// at least one format should be specified
  	$tech_streaming = $f3->get("POST.tech_streaming");
  	$tech_streaming = isset($tech_streaming) && $tech_streaming=="true" ? TRUE : NULL;
  	$tech_dcp = $f3->get("POST.tech_dcp");
  	$tech_dcp = isset($tech_dcp) && $tech_dcp=="true" ? TRUE : NULL;
  	$tech_dvd = $f3->get("POST.tech_dvd");
  	$tech_dvd = isset($tech_dvd) && $tech_dvd=="true" ? TRUE : NULL;
  	$tech_35mm = $f3->get("POST.tech_35mm");
  	$tech_35mm = isset($tech_35mm) && $tech_35mm=="true" ? TRUE : NULL;
  	if ($tech_streaming==NULL && $tech_dcp==NULL && $tech_dvd==NULL && $tech_35mm==NULL ) {
  		$result["success"] = FALSE;
  		$result["msg"][] = $f3->get("dict_tra_org_error_format");
  	}
  
  	// al menos un lenguaje debe estar seleccionado
  	$vo = $f3->get("POST.tech_streaming");
  	$vo = isset($vo) && $vo=="true" ? TRUE : NULL;
  	$vo_spanish = $f3->get("POST.vo_spanish");
  	$vo_spanish = isset($vo_spanish) && $vo_spanish=="true" ? TRUE : NULL;
  	$vo_english = $f3->get("POST.vo_english");
  	$vo_english = $f3->get("POST.tech_dvd");
  	if ($vo==NULL && $vo_spanish==NULL && $vo_english==NULL ) {
  		$result["success"] = FALSE;
  		$result["msg"][] = $f3->get("dict_tra_org_error_lang");
  	}
  
  	// fullname required
  	$personal_name = $f3->get("POST.complete_name");
  	$personal_name = isset($personal_name) && $personal_name!="" ? $personal_name : NULL;
  	if (!isset($personal_name) || empty($personal_name)) {
  		$result["success"] = FALSE;
  		$result["msg"][] = $f3->get("dict_tra_org_error_name");
  	}
  
  	// id required
  	$personal_id = $f3->get("POST.id");
  	$personal_id = isset($personal_id) && $personal_id!="" ? $personal_id : NULL;
  	if (!isset($personal_id) || empty($personal_id)) {
  		$result["success"] = FALSE;
  		$result["msg"][] = $f3->get("dict_tra_org_error_id");
  	}
  
  	// descripción del evento es un campo obligatorio
  	$where_descriptor = $f3->get("POST.where_descriptor");
  	$where_descriptor = isset($where_descriptor) && $where_descriptor!="" ? $where_descriptor : NULL;
  	if (!isset($where_descriptor) || empty($where_descriptor)) {
  		$result["success"] = FALSE;
  		$result["msg"][] = $f3->get("dict_tra_org_error_descriptor");
  	}
  
  	// la fecha es un campo obligatorio
  	$personal_date = $f3->get("POST.date");
  	$personal_date = isset($personal_date) && $personal_date!="" ? $personal_date : NULL;
  	if (!isset($personal_date) || empty($personal_date)) {
  		$result["success"] = FALSE;
  		$result["msg"][] = $f3->get("dict_tra_org_error_date");
  	}
  
  	// email es un campo obligatorio
  	$personal_email = $f3->get("POST.email");
  	$personal_email = isset($personal_email) && $personal_email!="" ? $personal_email : NULL;
  	if (!isset($personal_email) || empty($personal_email)) {
  		$result["success"] = FALSE;
  		$result["msg"][] = $f3->get("dict_error_email");
  	}
  
  	// everything ok?
  	if ($result["success"]==TRUE) {
  		// ready to send e-mail
  		$personal_address = $f3->get("POST.address");
  		$personal_phone = $f3->get("POST.phone");
  
  		$where_people = $f3->get("POST.where_people");
  
  		$extras_profit = $f3->get("POST.extras_profit");
  		$extras_profit = isset($extras_profit) && $extras_profit!="false" ? TRUE : NULL;
  		$extras_additional = $f3->get("POST.extras_additional");
  		$extras_additional = isset($extras_additional) && $extras_additional!="false" ? TRUE : NULL;
  		$extras_snacks = $f3->get("POST.extras_snacks");
  		$extras_snacks = isset($extras_snacks) && $extras_snacks!="false" ? TRUE : NULL;
  		$extras_party = $f3->get("POST.extras_party");
  		$extras_party = isset($extras_party) && $extras_party!="false" ? TRUE : NULL;
  		$extras_q_and_a = $f3->get("POST.extras_q_and_a");
  		$extras_q_and_a = isset($extras_q_and_a) && $extras_q_and_a!="false" ? TRUE : NULL;
  		$extras_merchandising = $f3->get("POST.extras_merchandising");
  		$extras_merchandising = isset($extras_merchandising) && $extras_merchandising!="false" ? TRUE : NULL;
  
  		// recalculate subtotal total costs
  		$total_value = 0;
  		if ($extras_merchandising==TRUE) {
  			$total_value += $f3->get("VALUE_MERCHANDISING");
  			$extras_value = $f3->get("VALUE_MERCHANDISING");
  			$extras_concept = $f3->get("dict_tra_org_merchandising");
  		} else {
  			$extras_value = $f3->get("dict_tra_org_free");
  			$extras_concept = "";
  		}
  
  		// recalculate subtotal format
  		if ($tech_streaming==TRUE) {
  			$total_value += 0;
  			$tech_value = "0<span>€</span>";
  			$tech_concept = "";
  		}
  		if ($tech_dcp==TRUE) {
  			$total_value += $f3->get("VALUE_DCP");
  			$tech_value = $f3->get("VALUE_DCP")."€";
  			$tech_concept = $f3->get("dict_dcp");
  		}
  		if ($tech_dvd==TRUE) {
  			$total_value += $f3->get("VALUE_DVD");
  			$tech_value = $f3->get("VALUE_DVD")."€";
  			$tech_concept = $f3->get("dict_dvd");
  		}
  		if ($tech_35mm==TRUE) {
  			$total_value += 0;
  			$tech_value = 0;
  			$tech_concept = $f3->get("dict_tra_org_tdb");
  		}
  
  		if ($total_value==0) {
  			$total_value = $f3->get("dict_tra_org_free");
  		} else {
  			$total_value .= "€";
  		}
  
  		// collect data
  		$f3->set('where_pro', $where_pro);
  		$f3->set('where_amateur', $where_amateur);
  		$f3->set('where_descriptor', $where_descriptor);
  		$f3->set('where_people', $where_people);
  
  		$f3->set('tech_streaming', $tech_streaming);
  		$f3->set('tech_dcp', $tech_dcp);
  		$f3->set('tech_dvd', $tech_dvd);
  		$f3->set('tech_35mm', $tech_35mm);
  
  		$f3->set('extras_profit', $extras_profit);
  		$f3->set('extras_additional', $extras_additional);
  		$f3->set('extras_snacks', $extras_snacks);
  		$f3->set('extras_party', $extras_party);
  		$f3->set('extras_q_and_a', $extras_q_and_a);
  		$f3->set('extras_merchandising', $extras_merchandising);
  
  		$f3->set('vo', $vo);
  		$f3->set('vo_english', $vo_english);
  		$f3->set('vo_spanish', $vo_spanish);
  
  		$f3->set('personal_name', $personal_name);
  		$f3->set('personal_id', $personal_id);
  		$f3->set('personal_address', $personal_address);
  		$f3->set('personal_date', $personal_date);
  		$f3->set('personal_phone', $personal_phone);
  		$f3->set('personal_email', $personal_email);
  
  		$f3->set("extras_value", $extras_value);
  		$f3->set("extras_concept", $extras_concept);
  		$f3->set("tech_value", $tech_value);
  		$f3->set("tech_concept", $tech_concept);
  		$f3->set("total_value", $tech_value);
  
  		// send e-mail
  		$boundary = uniqid("HTMLDEMO");
  		$header  = "From: ".$f3->get("dict_com_the_cosmonaut")." <".$f3->get("MAIL_NO_REPLY").">\r\n";
  		$header .= "MIME-Version: 1.0\r\n";
  		$header .= "Content-type: text/html; charset=utf-8'\r\n";
  		$send = mail(
  				$f3->get("MAIL_CONTACT"),
  				$f3->get("MAIL_ORGANIZE_SUBJECT"),
  				Template::instance()->render('mail_organize_event.html'),
  				$header);
  
  		if ($send==FALSE) {
  			$result["success"] = FALSE;
  			$result["msg"][] = $f3->get("dict_tra_org_error_sendmail");
  		}
  	}
  	echo json_encode($result);
  }
  


	/**
	 * paypal_ipn
	 *
	 * Callback function after Paypal donation has been made. This function is
	 * based on /classes/PaypalClass.php.
	 * If donation is higher than 5€, user will be automatically granted with
	 * backer credentials.
	 */
	function paypal_ipn() {
		$f3 = F3::instance();
		$p = new PaypalClass;
		$path = $f3->get("BASE_URL")."/watch_it_now";
		if ($p->validate_ipn(TRUE) && $p->ipn_data['payment_status']=='Completed') {
			// most intereseting values are: ipn_data['payer_email'] and
			// ipn_data['mc_gross]
			if ($p->ipn_data['mc_gross'] >= 5) {
				// create a new producer with ipn_data['payer_email'] and 
				// ipn_data['first_name'] data
				$customer_model = new CustomerModel();
				$email = $p->ipn_data['payer_email'];
				$name = $p->ipn_data['first_name'];
				if (empty($name)) {
				  $name = $p->ipn_data['payer_business_name'];
				}
				$result = $customer_model->new_cosmonaut($email, $name);
				$path = $f3->get("BASE_URL")."/thank_you";
			}
			// create a guest session for the moment. Later, user will be availbe to
			// access proptected contents using credential sent via e-mail
			SessionManager::set_guest($p->ipn_data['payer_email']);
		}
		$f3->reroute($path);
	}


	/**
	 * plan
	 *
	 * Set jump_to with the name of a DOM section in /about page. Once this page
	 * has been loaded, a scroll effect will be fired.
	 */
	function plan() {
		$f3 = F3::instance();
		$f3->set("jump_to", "three-principles");
		$this->about();
	}


	/**
	 * press
	 *
	 * Set jump_to with the name of a DOM section in /about page. Once this page
	 * has been loaded, a scroll effect will be fired.
	 *
	 */
	function press() {
		$f3 = F3::instance();
		$f3->set("jump_to", "press-area");
		$this->about();
	}


	/**
	 * producers
	 *
	 * Set jump_to with the name of a DOM section in /credits page. Once this
	 * page has been loaded, a scroll effect will be fired.
	 */
	function producers() {
		$f3 = F3::instance();
		$f3->set("jump_to", "credit_backers");
		$this->credits();
	}


	/**
	 * remix
	 *
	 * Render /remix template.
	 */
	function remix() {
		$f3 = F3::instance();
		
		$f3->set('tem_header', 'site_header.html');
		$f3->set('tem_content', 'remix_template.html');
		$f3->set('tem_footer', 'site_footer.html');

		$f3->set("extra_css", array("style.css"));

		$f3->set('external_js', array (
			"http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js",
			"http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js",
			"http://a.vimeocdn.com/js/froogaloop2.min.js"
		));

		$f3->set("extra_js", array (
			"lib/modernizr-2.5.3-min.js",
			"lib/plugins.js",
			"src/cosmonaut.data.js",
			"src/cosmonaut.common.js",
			"src/cosmonaut.login.js",
			"src/cosmonaut.template.js",
			"src/cosmonaut.remix.js",
			"src/cosmonaut.vimeo.js",
			"src/cosmonaut.video.js"
		));

		$this->prepare_language_data();

		$f3->set("about_selected", true);

		// get data of the highlight video remix.
		$remix_model = new RemixModel();
		$week_remix = $remix_model->get_highlight();
		$f3->set("vimeo_id", $week_remix['rem_url']);

		$video_model = new VideoModel();
		$movie = $video_model->get_the_movie();
		$release = new DateTime($movie['vid_release']);
		$today = new DateTime(date("Y-m-d H:i:s"));
		$countdown = $today->diff($release);
		if ($countdown->invert == 1) {
			$f3->set("released", TRUE );
		} else {
			$f3->set("released", FALSE );
		}
    	$f3->set("short_url", "");
    	$f3->set("body_id", "remix");
		$f3->set("week_remix", $week_remix );

		echo Template::instance()->render('site_template.html');
	}


	/**
	 * submit
	 *
	 * Set jump_to with the name of a DOM section in /remix page. Once this page
	 * has been loaded, a scroll effect will be fired.
	 *
	 * @access      public
	 */
	function submit() {
		$f3 = F3::instance();
		$f3->set("jump_to", "section-submit");
		$this->remix();
	}


	/**
	 * team
	 *
	 * Set jump_to with the name of a DOM section in /about page. Once this page
	 * has been loaded, a scroll effect will be fired.
	 */
	function team() {
		$f3 = F3::instance();
		$f3->set("jump_to", "meet-the-team");
		$this->about();
	}



	/**
	 * thank_you
	 *
	 * Prepare template to thank users which have donated more than 5€.
	 */
	function thank_you() {
		$f3 = F3::instance();

		$f3->set('tem_content', "site_new_cosmonaut.html");
		$f3->set('tem_header', 'site_header.html');
		$f3->set('tem_footer', 'site_footer.html');
		
		$f3->set("extra_css", array("style.css"));

		$f3->set("about_selected", true);

		$f3->set('external_js', array (
			"http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js",
			"http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"
		));

		$f3->set("extra_js", array (
			"lib/modernizr-2.5.3-min.js",
			"lib/plugins.js",
			"src/cosmonaut.data.js",
			"src/cosmonaut.common.js",
			"src/cosmonaut.template.js",
			"src/cosmonaut.thanks.js"
		));

		$this->prepare_language_data();
		
		echo Template::instance()->render('site_template.html');
	}



	/**
	 * tos
	 *
	 * Render /tos template (Terms of service).
	 */
	function tos() {
		$f3 = F3::instance();

		$content = strtolower($f3->get("LANGUAGE")=="es") ? 'map_tos_es.html' : 'map_tos_en.html';
		$f3->set('tem_content', $content);
		$f3->set('tem_header', 'site_header.html');
		$f3->set('tem_footer', 'site_footer.html');
		
		$f3->set("extra_css", array("style.css"));

		$f3->set("about_selected", true);

		$this->prepare_language_data();
		
		echo Template::instance()->render('site_template.html');
	}


	/**
	 * timeline
	 *
	 * Render timeline template. It's a very simple template with no access to
	 * database required.
	 */
	function timeline() {
		$f3 = F3::instance();

		$f3->set('tem_header', 'site_header.html');

		$f3->set('tem_content', 'timeline_template.html');

		$f3->set('tem_blog', 'site_blog.html');
		$f3->set('tem_footer', 'site_footer.html');

		$f3->set("extra_css", array("style.css"));

		$f3->set('external_js', array (
			"http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"
		));

		$f3->set("extra_js", array (
			"lib/modernizr-2.5.3-min.js",
			"lib/plugins.js",
			"src/cosmonaut.data.js",
			"src/cosmonaut.common.js",
			"src/cosmonaut.template.js",
			"src/cosmonaut.timeline.js"
		));

		$this->prepare_language_data();

		$f3->set("body_id", "main");
		$f3->set("body_class", "behind-section");
		$f3->set("about_selected", true);

		echo Template::instance()->render('site_template.html');
	}



	/**
	 * validate
	 *
	 * @param 	POST.login String
	 * @param 	POST.password String
	 *
	 * Validate user after login form.
	 */
	function validate() {
		$f3 = F3::instance();

		$f3->scrub($_POST);
		$login = $f3->get("POST.login");
		$password = $f3->get("POST.password");

		$security = new SessionManager();
		$result = $security->validate ( $login, $password );

		echo json_encode($result);
	}


	/**
	 * watch
	 *
	 * Set jump_to with the name of a DOM section in /film_universe page. Once
	 * this page has been loaded, a scroll effect will be fired.
	 */
	function watch() {
		$f3 = F3::instance();
		$f3->set("jump_to", "the-cosmonaut");
		$this->film_universe();
	}


	/**
	 * date2title
	 *
	 * Method to prepare title of video clips still not released.
	 *
	 * @access      private
	 * @param       String Date in yyyy/mm/dd hh:mm:ss format.
	 * @return      String Date with new format.
	 */
	private function date2title($date) {
		$f3 = F3::instance();

		$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
		$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

		$month_index = ltrim(strftime("%m", strtotime($date)), '0');
		$day_index = strftime("%e", strtotime($date));

		if ($f3->get("LANGUAGE")=="en") {
			if ($day_index==1) {
				$result = $f3->get("dict_uni_coming_1").$months[$month_index-1].", 1st";
			} else if($day_index==2) {
				$result = $f3->get("dict_uni_coming_1").$months[$month_index-1].", 2nd";
			} else {
				$result = $f3->get("dict_uni_coming_1").$months[$month_index-1].", ".$day_index."th";
			}
		} else {
			$result = $f3->get("dict_uni_coming_1").$day_index." de ".$meses[$month_index-1];
		}
		return $result;
	}



	/**
	 * prepare_language_data
	 *
	 * There's a lot of constants/variables which are based on the user's default
	 * language. This private method sets those kind of variables such as twitter
	 * accounts, facebook urls, etc...
	 */
	private function prepare_language_data() {
		$f3 = F3::instance();
		$f3->set("lang_twitter_account", strtoupper($f3->get("LANGUAGE"))=="ES" ? $f3->get("TWITTER_ACCOUNT_ES") : $f3->get("TWITTER_ACCOUNT_EN"));
		$f3->set("lang_twitter_video_hashtag", strtoupper($f3->get("LANGUAGE"))=="ES" ? $f3->get("TWITTER_VIDEO_HASHTAG_ES") : $f3->get("TWITTER_VIDEO_HASHTAG_EN"));
    $f3->set("lang_twitter_video_text", strtoupper($f3->get("LANGUAGE"))=="ES" ? $f3->get("TWITTER_VIDEO_TEXT_ES") : $f3->get("TWITTER_VIDEO_TEXT_EN"));
    $f3->set("lang_trailer", strtoupper($f3->get("LANGUAGE"))=="ES" ? $f3->get("URL_TRAILER_ES") : $f3->get("URL_TRAILER_EN"));
    $f3->set("lang_url_about", strtoupper($f3->get("LANGUAGE"))=="ES" ? $f3->get("VIMEO_ABOUT_ID_ES") : $f3->get("VIMEO_ABOUT_ID_EN"));
    $f3->set("lang_url_blog", strtoupper($f3->get("LANGUAGE"))=="ES" ? $f3->get("URL_BLOG_ES") : $f3->get("URL_BLOG_EN"));
		$f3->set("lang_url_blog_thanks", strtoupper($f3->get("LANGUAGE"))=="ES" ? $f3->get("URL_BLOG_THANKS_ES") : $f3->get("URL_BLOG_THANKS_EN"));
		$f3->set("lang_url_facebook", strtoupper($f3->get("LANGUAGE"))=="ES" ? $f3->get("URL_FACEBOOK_ES") : $f3->get("URL_FACEBOOK_EN"));
		$f3->set("lang_url_guide", strtoupper($f3->get("LANGUAGE"))=="ES" ? $f3->get("URL_GUIDE_ES") : $f3->get("URL_GUIDE_EN"));
		$f3->set("lang_url_k_pass", strtoupper($f3->get("LANGUAGE"))=="ES" ? $f3->get("URL_K_PASS_ES") : $f3->get("URL_K_PASS_EN"));
		$f3->set("lang_url_panorama", strtoupper($f3->get("LANGUAGE"))=="ES" ? $f3->get("URL_PANORAMA_ES") : $f3->get("URL_PANORAMA_EN"));
		$f3->set("lang_url_rss", strtoupper($f3->get("LANGUAGE"))=="ES" ? $f3->get("URL_RSS_ES") : $f3->get("URL_RSS_EN"));
		$f3->set("lang_url_screening_guide", strtoupper($f3->get("LANGUAGE"))=="ES" ? $f3->get("URL_GUIDE_ES") : $f3->get("URL_GUIDE_EN"));
		$f3->set("lang_url_scripts", strtoupper($f3->get("LANGUAGE"))=="ES" ? $f3->get("URL_SCRIPTS_ES") : $f3->get("URL_SCRIPTS_EN"));
		$f3->set("lang_url_shop", strtoupper($f3->get("LANGUAGE"))=="ES" ? $f3->get("URL_SHOP_ES") : $f3->get("URL_SHOP_EN"));
		$f3->set("lang_url_the_plan", strtoupper($f3->get("LANGUAGE"))=="ES" ? $f3->get("URL_THE_PLAN_ES") : $f3->get("URL_THE_PLAN_EN"));
		$f3->set("lang_url_trailer", strtoupper($f3->get("LANGUAGE"))=="ES" ? $f3->get("VIMEO_TRAILER_ID_ES") : $f3->get("VIMEO_TRAILER_ID_EN"));
		$f3->set("lang_url_twitter", strtoupper($f3->get("LANGUAGE"))=="ES" ? $f3->get("URL_TWITTER_ES") : $f3->get("URL_TWITTER_EN"));
	}

}
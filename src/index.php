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
 *   Tecnilógica Soluciones Avanzadas - initial API and implementation
 *
 * @package   cosmonautexperience
 * @author    Tecnilógica soluciones avanzadas
 * @copyright Copyright (c) 2003 - 2013, Tecnilógica soluciones avanzadas, S.A. (http://tecnilogica.com/)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link      http://cosmonautexperience.com
 */

// LOAD FATFREE 3 BASE LIBRARY
$f3 = require("./lib/base.php");

// GLOBAL VARIABLES
$f3->set("UI", "web/templates/");
$f3->set("TEMP", "temp/");
$f3->set("AUTOLOAD", "classes/; classes/php5_paypal_class/; web/code/; web/code/models/; models/;");
$f3->set("CACHE", false);

// LOAD CONFIG FILE
$f3->config("./config/config.cfg");
// LOAD CONSTANTS FILE
$f3->config("./config/constants.cfg");
// LOAD COMMON FUNCTIONS FOR EACH REQUEST
include_once("./config/common.php");

// ROUTES MAP
$f3->route('GET  /',                              "SiteController->home");
$f3->route("GET  /404",                           "SiteController->error_404");
$f3->route("POST /lang [ajax]",                   "SiteController->change_lang");
$f3->route("POST /posts [ajax]",                  "BlogController->recent_posts");

// ABOUT
$f3->route('GET  /about',                         "SiteController->about");
$f3->route('GET  /credits',                       "SiteController->credits");
$f3->route('GET  /downloads',                     "SiteController->downloads");
$f3->route('GET  /press',                         "SiteController->press");
$f3->route('GET  /producers',                     "SiteController->producers");
$f3->route('GET  /remix',                         "SiteController->remix");
$f3->route('GET  /submit',                        "SiteController->submit");
$f3->route('GET  /thank_you',                     "SiteController->thank_you");
$f3->route('GET  /the_plan',                      "SiteController->plan");
$f3->route('GET  /the_team',                      "SiteController->team");

// BEHIND THE SCENES
$f3->route('GET  /behind_the_scenes',             "SiteController->behind_scenes");
$f3->route('GET  /timeline',                      "SiteController->timeline");

// FILM UNIVERSE
$f3->route('GET  /film_universe',                 "SiteController->film_universe");

// HOME
$f3->route('GET  /watch_it_now',                  "SiteController->watch");

// K-PASS
$f3->route('GET  /dashboard',                     "SiteController->dashboard");
$f3->route('GET  /k_pass',                        "SiteController->k_pass");

// LIVE EXPERIENCE
$f3->route('POST /authorized',          			    "SiteController->authorized");
$f3->route('GET  /demand_a_show',				  	      "SiteController->demand");
$f3->route('GET  /find_a_premiere', 		  		    "SiteController->find_premiere");
$f3->route('GET  /guest',                         "SiteController->guest");
$f3->route('GET  /live_experience',               "SiteController->live_experience");
$f3->route('GET  /newsletter',					          "SiteController->newsletter");
$f3->route('GET  /organize_a_show',			  	      "SiteController->organize");
$f3->route('POST /show/demand [ajax]',            "SiteController->demand_show");
$f3->route('POST /show/organize [ajax]',          "SiteController->organize_show");
$f3->route('GET  /tos',               			      "SiteController->tos");
$f3->route('GET  /licenses',               		    "SiteController->licenses");

// NAYIK
$f3->route('GET  /nayik',                         "SiteController->nayik");

// PAYPAL
$f3->route("GET  /donations",                     "SiteController->paypal_donations");
$f3->route("POST /donations",                     "SiteController->paypal_donation");
$f3->route("POST /ipn",                     			"SiteController->paypal_ipn");

// USER
$f3->route('POST /access [ajax]',                 "SiteController->validate");
$f3->route('GET  /logout',                        "SiteController->logout");
$f3->route("POST /user/update [ajax]",            "CustomerController->user_update");
$f3->route("POST /user/delete [ajax]",            "CustomerController->user_delete");

// AJAX REQUSETS
$f3->route('POST /video/detail [ajax]',           "VideoController->get_video_detail");
$f3->route('POST /video/next [ajax]',             "VideoController->get_next_video");
$f3->route('POST /video/prev [ajax]',             "VideoController->get_prev_video");
$f3->route('POST /video/watched [ajax]',          "VideoController->video_watched");
$f3->route('POST /video/comment [ajax]',          "VideoController->comment_video");

// CONTENTS
$f3->route('GET  /download/book',    							"DownloadController->download_book");
$f3->route('GET  /download/clips',    						"DownloadController->download_clips");
$f3->route('GET  /download/materials',    				"DownloadController->download_materials");
$f3->route('GET  /download/photo',    						"DownloadController->download_photo");
$f3->route('GET  /download/plan',    							"DownloadController->download_plan");
$f3->route('GET  /download/press',    						"DownloadController->download_press");
$f3->route('GET  /download/soundtrack',    				"DownloadController->download_soundtrack");
$f3->route('GET  /download/torrent',    					"DownloadController->download_torrent");
$f3->route('GET  /download/transmedia',    				"DownloadController->download_transmedia");
$f3->route('GET  /download/voices',    						"DownloadController->download_voices");
$f3->route('GET  /download/web',    							"DownloadController->download_web");

// VIMEO
$f3->route('POST /vimeo/@id/thumbnail [ajax]',    "VimeoController->get_thumbnail");

$f3->run();

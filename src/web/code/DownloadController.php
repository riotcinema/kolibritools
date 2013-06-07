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
 * DownloadController
 *
 * Class to handle downloads of content files. Content files are located in
 * /web/contents folder.
 * Most of the operation will do nothing until file name is defined in the
 * constants file.
 * 
 */
class DownloadController {

	/**
	 * download_book
	 *
	 * The Book is only available for K-Pass users in remix section
	 */
 	public function download_book() {
 		$f3 = F3::instance();
 		$url = $f3->get("URL_BOOK");
 		if (SessionManager::is_logged() && !empty($url)) {
 			$this->force_download($url);
 		}
 	}


	/**
	 * download_clips
	 *
	 * Clips are available in remix section for every user but only after movie 
	 * has been released.
	 */
	public function download_clips() {
		$f3 = F3::instance();
		$url = $f3->get("URL_DOWN_REM_DIRECT");

		// get release date of the movie
		$video_model = new VideoModel();
		$movie = $video_model->get_the_movie();
		$release = new DateTime($movie['vid_release']);
		$today = new DateTime(date("Y-m-d H:i:s"));
		$countdown = $today->diff($release);

		// if movie has been released ...
		if (!empty($url) && $countdown->invert == 1) {
			$this->force_download($url);
		}
	}


	/**
	 * download_materials
	 *
	 * Material guide is available for every user in remix section but only after
	 * movie has been released.
	 */
	public function download_materials() {
		$f3 = F3::instance();
		$url = $f3->get("URL_DOWN_REM_MATERIAL");

		// get release date of the movie
		$video_model = new VideoModel();
		$movie = $video_model->get_the_movie();
		$release = new DateTime($movie['vid_release']);
		$today = new DateTime(date("Y-m-d H:i:s"));
		$countdown = $today->diff($release);

		// if movie has been released ...
		if (!empty($url) && $countdown->invert == 1) {
			$this->force_download($url);
		}
	}


	/**
	 * download_soundtrack
	 *
	 * The official soundtrack is only available to K-Pass users in "Behind the 
	 * scenes" section.
	 */
	public function download_soundtrack() {
		$f3 = F3::instance();
		$url = $f3->get("URL_DOWN_SOUNDTRACK");
		if (SessionManager::is_logged() && !empty($url)) {
			$this->force_download($url);
		}
	}


	/**
	 * download_torrent
	 *
	 * Torrent file is available for every user in remix section but only after
	 * movie has been released.
	 */
	public function download_torrent() {
		$f3 = F3::instance();
		$url = $f3->get("URL_DOWN_REM_TORRENT");

		// get release date of the movie
		$video_model = new VideoModel();
		$movie = $video_model->get_the_movie();
		$release = new DateTime($movie['vid_release']);
		$today = new DateTime(date("Y-m-d H:i:s"));
		$countdown = $today->diff($release);

		// if movie has been released ...
		if (!empty($url) && $countdown->invert == 1) {
			$this->force_download($url);
		}
	}


	/**
	 * download_transmedia
	 *
	 * Transmedia sessions are 23 music tracks only available to K-Pass users in
	 * "Behind the scenes" section.
	 */
	public function download_transmedia() {
		$f3 = F3::instance();
		$url = $f3->get("URL_DOWN_TRANS_SESS");
		if (SessionManager::is_logged() && !empty($url)) {
			$this->force_download($url);
		}
	}


	/**
	 * force_download
	 * 
	 * @param	$content_url String
	 * 
	 * Search for a file in the contents folder and force download of the file.
	 */
	private function force_download($content_url) {
		$f3 = F3::instance();
 		$url = $f3->get("BASE_URL")."/".$f3->get("_PATH")."contents/".$content_url;
		header('Content-type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary");
		header("Content-disposition: attachment; filename=\"".$url."\"");
 		header("Location: $url");
	}


	/**
	 * download_photo
	 *
	 * Film stills available for each user in the "About" section.
	 */
	public function download_photo() {
		$f3 = F3::instance();
		$url = $f3->get("URL_DOWN_PHOTO");
		if (!empty($url)) {
			$this->force_download($url);
		}
	}


	/**
	 * download_plan
	 *
	 * The Plan is a PDF document available for each user in the "About" section.
	 * There is an english version and a spanish version of the document.
	 */
	public function download_plan() {
		$f3 = F3::instance();
		$url = strtolower($f3->get("LANGUAGE"))=="es" ? $f3->get("URL_THE_PLAN_ES") : $f3->get("URL_THE_PLAN_EN");
		if (!empty($url)) {
			$this->force_download($url);
		}
	}


	/**
	 * download_press
	 *
	 * The complete press kit is available for each user in the "About" section.
	 */
	public function download_press() {
		$f3 = F3::instance();
		$url = $f3->get("URL_DOWN_PRESS");
		if (!empty($url)) {
			$this->force_download($url);
		}
	}


	/**
	 * download_voices
	 *
	 * I hear voices from space is a soundtrack file only available for K-Pass
	 * users.
	 */
	public function download_voices() {
		$f3 = F3::instance();
		$url = $f3->get("URL_DOWN_I_HEAR");
		if (SessionManager::is_logged() && !empty($url)) {
			$this->force_download($url);
		}
	}

}
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
 * BlogController
 *
 * Controller class to handle requests about external Blog website. Blog URL 
 * is defined in /config/constants.cfg file: URL_BLOG_EN and URL_BLOG_ES
 * 
 */
class BlogController {


  /**
   * recent_posts
   *
   * Get the most recent posts from the external Blog feed and return them,
   * via json.
   *
   * @return String json_encoded
   */
  function recent_posts() {
    $f3 = F3::instance();

    $limit =3;
    $url = strtolower($f3->get("LANGUAGE"))=="es" ? $f3->get("URL_RSS_ES") : $f3->get("URL_RSS_EN");
    $parser = new FeedParser();
    $posts = $parser->parse($url, $limit);
    echo json_encode($posts);
  }

}
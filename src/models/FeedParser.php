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
 * FeedParser
 *
 * Class to parse Blog feed.
 * 
 */
class FeedParser {

  /**
   * simpleRSSParse
   *
   * Acquire content of the param URL and pase it to XML format.
   *
   * @access      private
   * @param       String $url Feed url to parse.
   * @return      SimpleXMLElement
   */
  function parse($url, $limit=3) {

    $articles = array();
    $ns = array (
      'content' => 'http://purl.org/rss/1.0/modules/content/',
      'wfw' => 'http://wellformedweb.org/CommentAPI/',
      'dc' => 'http://purl.org/dc/elements/1.1/'
    );

    // step 1: get the feed
    try {
      $rawFeed = file_get_contents($url);
      $xml = new SimpleXmlElement($rawFeed);
    } catch (Exception $e) {
      return $articles;
    }

    // step 2: extract the channel metadata
    $channel = array();
    $channel['title']       = $xml->channel->title;
    $channel['link']        = $xml->channel->link;
    $channel['pubDate']     = $xml->pubDate;

    // step 3: extract the articles
    for ($i=0; $i<$limit; $i++) {
      $item = $xml->channel->item[$i];
      $article = array();
      $article['title'] = $item->title;
      $article['link'] = $item->link;
      $article['pubDate'] = $item->pubDate;
      // get data held in namespaces
      $content = $item->children($ns['content']);
      $dc      = $item->children($ns['dc']);
      $wfw     = $item->children($ns['wfw']);

      foreach ($dc->subject as $subject) {
        $article['subject'][] = (string)$subject;
      }

      $article['content'] = strip_tags((string)trim($content->encoded));
      $articles[] = $article;
    }

    return $articles;
  }

}
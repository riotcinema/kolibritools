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
 * cosmonaut.home.js
 */
cosmonaut.home=new Array();cosmonaut.home.ratio=2.03;cosmonaut.home.getCartoDBData=function(){var a=cosmonaut.data.cartodb.account;var q="SELECT * FROM elcosmonauta";$.getJSON('http://'+a+'.cartodb.com/api/v2/sql/?q='+q,function(data){}).success(function(data){cosmonaut.map.fillProjectionsData(data);}).error(function(){}).complete(function(){});};cosmonaut.home.launchParallax=function(){$('#explore-the-fiction').parallax("50%",0.2);$('#behind-the-scenes').parallax("50%",0.35);};cosmonaut.home.resizeVideoContainer=function(){var windowHeight=Math.floor($(window).width()/cosmonaut.home.ratio);var windowWidth=$(window).width();$("#video").height(windowHeight);$("#video").width(windowWidth);$("#video").css("min-height",windowHeight);var vimeo_controls_height=90;var top_limit=$("#sectionHeader").height();var diff=($("#video").height() - $("#video_wrapper").height() <= 0) ? 1 : $("#video").height() - $("#video_wrapper").height();var new_top=Math.floor(diff/2)+top_limit;$("#video_wrapper").offset({top:new_top});$("#vimeoTrailer").css("z-index","-1");$("#vimeoTrailer").height(windowHeight);$("#vimeoTrailer").width(windowWidth);};$('#controls ul li a').click(function(e){e.preventDefault();cosmonaut.highlightQuote($(this).parent().index());});$(window).resize(function(){cosmonaut.home.resizeVideoContainer();});$(document).ready(function(){$("body").attr('id','main');$("#video").css("background-color","#111");$("#video").css("background","");$("#video").css("background-size","100% 100%");$("#video").css("background-repeat","no-repeat");cosmonaut.home.getCartoDBData();cosmonaut.prepareQuoteCarrousel();cosmonaut.loadRecentPosts();cosmonaut.home.resizeVideoContainer();cosmonaut.home.launchParallax();$("#divCartoDBMap").addClass("small_map");});
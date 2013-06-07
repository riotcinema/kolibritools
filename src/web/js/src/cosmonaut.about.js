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
 * cosmonaut.about.js
 */
cosmonaut.about=new Array();cosmonaut.about.ratio=1.77;cosmonaut.about.closeVideoDialog=function(){cosmonaut.pauseVimeo("vimeo_learn_more");$("#vimeo_learn_more").remove();$("#video-learn-more").remove();$("#video_wrapper").fadeIn();};cosmonaut.about.openVideoDialog=function(){var w=$("#video_wrapper");$(w).fadeOut();var top=-$(w).height();var div=$(document.createElement('div')).attr("id","video-learn-more").addClass("lightbox").css("display","inline").appendTo($("#video"));var vu="http://player.vimeo.com/video/{{@lang_url_about}}?api=1&title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff&loop=0&autoplay=1;player_id=vimeo_learn_more";var frame=$(document.createElement('iframe')).addClass("vimeo").attr("id","vimeo_learn_more").attr("src",vu).attr("height",$(w).height()).attr("width",$(w).width()).attr("frameborder","0").appendTo($(div));var l=$(document.createElement('a')).attr("id","link_close_learn_more").css("top",top+"px").css("position","absolute").css("width","0px").attr("href","#").addClass("cerrar").appendTo($(div));$(l).bind('click','a',function(event){event.preventDefault();cosmonaut.about.closeVideoDialog();});};cosmonaut.about.resizeVideoContainer=function(){var w=$("#video_wrapper");$(w).height(Math.floor($(w).width()/cosmonaut.about.ratio));};$('#ftLinkPlan').click(function(e){e.preventDefault();cosmonaut.jumpTo("three-principles");});$('#ftLinkPress').click(function(e){e.preventDefault();cosmonaut.jumpTo("press-area");});$('#ftLinkTeam').click(function(e){e.preventDefault();cosmonaut.jumpTo("meet-the-team");});$("#link-learn-more").click(function(e){e.preventDefault();cosmonaut.about.openVideoDialog();});$("#link-learn-more-close").click(function(e){e.preventDefault();cosmonaut.about.closeVideoDialog();});$(document).ready(function(){$("#video_wrapper").find('h2').css("padding-top","200px");cosmonaut.about.resizeVideoContainer();$(".ui-dialog-titlebar-close").css("display","none");var j=$("#jump_to").val();if (j!=undefined){cosmonaut.jumpTo(j);}cosmonaut.loadRecentPosts();});
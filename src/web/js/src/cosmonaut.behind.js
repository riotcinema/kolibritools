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
 * cosmonaut.behind.js
 */

cosmonaut.behind=new Array();cosmonaut.behind.initCarousel=function(){$("#carousel-container").css("width","100%");$("#carousel-controls").css("z-index",10);var items=$(".step");$("#carousel-items").carouFredSel({auto:false,circular:true,infinite:true,items:{visible:3,start:-1},next:{button:"#link-next",key:"right"},pagination:{},prev:{button:"#link-prev",key:"left"},scroll:{items:1,duration:500,timeoutDuration:2000,onAfter:function(){var pos=$("#carousel-items").triggerHandler("currentPosition")+1;if (pos>=items.length){pos=pos-items.length;}$.each($(items).find('div:eq(0)'),function(i,v){$(v).addClass("lateral");$(v).find('h3:eq(0)').hide();$(v).find('p:eq(0)').hide();$(v).find('h2:eq(0)').hide();$(v).find('div.ico-play').hide();});var t=$(items[pos]).find('div:eq(0)');$(t).removeClass('lateral');$(t).addClass('central');$(t).find('h2:eq(0)').fadeIn();$(t).find('h3:eq(0)').fadeIn();$(t).find('p:eq(0)').fadeIn();$(t).find('div.ico-play').fadeIn();}},transition:true,wrapper:{element:"div",classname:"slider"},width:"100%"});};$(document).ready(function(){cosmonaut.behind.initCarousel();$("#link_to_broking_of").css("cursor","default");$("#common-header").addClass("border-header");$("#iframe-video-detail").hide();$("#carrousel ul").css("min-height","489px");$(".ui-dialog-titlebar-close").css("display","none");$.each($(".info-hover"),function(c,a){var b=$(a).height();var e=$(a).parent().find("img:eq(0)").height();var d=Math.floor((e-b)/2);$(a).css("padding-top",d);$(a).css("padding-bottom",d)});$("#next").hide();$("#back").hide()});$("#video-detail").dialog({autoOpen:false,close:function(){cosmonaut.video.closeVideoDialog();},modal:true,option:"closeOnEscape",show:"fade",width:"900px"});$("#close-video-detail").click(function(e){e.preventDefault();cosmonaut.video.closeVideoDialog();});$(".sign_in_link").click(function(e){e.preventDefault;cosmonaut.login.showLogin();});$(".ico-play").click(function(e){e.preventDefault();cosmonaut.video.openVideoDialog($(this).attr("data-slug"));});$(".icon-play").click(function(e){e.preventDefault();cosmonaut.video.playVideo();});$(".disabled").click(function(e){e.preventDefault();});$(".play").click(function(e){e.preventDefault();cosmonaut.video.openVideoDialog($(this).attr("data-slug"));});$("#link_to_hummingbird").click(function(e){e.preventDefault();cosmonaut.video.openVideoDialog($(this).attr("data-slug"));});$("#link_to_broking_of").click(function(e){e.preventDefault();});$(".unavailable").click(function(e){e.preventDefault();});$(".behind-thumbnail").hover(function(e){e.preventDefault();$(this).addClass('info-hover');},function(e){e.preventDefault();$(this).removeClass('info-hover');});
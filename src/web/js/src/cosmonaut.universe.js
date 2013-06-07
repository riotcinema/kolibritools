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
 * cosmonaut.universe.js
 */

// Functions
cosmonaut.universe=new Array();cosmonaut.universe.launchParallax=function(){$('.bg1').parallax("50%",0.6);$('.bg2').parallax("50%",0.4);$('.bg3').parallax("50%",0.3);$('.bg4').parallax("50%",0.4);$('.bg5').parallax("50%",0.2);$('.bg6').parallax("50%",0.4);$('.bg7').parallax("60px",0.2);};cosmonaut.universe.nextVideo=function(vid_slug){$.ajax({url:"{{@BASE_URL}}/video/next/",cache:false,type:"POST",dataType:"json",data:{vid_slug:vid_slug,vid_category:'_TRANSMEDIA_'},beforeSend:function(){cosmonaut.video.resetVideoDialog();},complete:function(json_complete){},success:function(json_success){if (json_success.status="ok"){cosmonaut.video.video2lightbox(json_success.result);}}});};cosmonaut.universe.prevVideo=function(e){$.ajax({url:"{{@BASE_URL}}/video/prev/",cache:false,type:"POST",dataType:"json",data:{vid_slug:e,vid_category:"_TRANSMEDIA_"},beforeSend:function(){cosmonaut.video.resetVideoDialog()},complete:function(e){},success:function(e){if(e.status="ok"){cosmonaut.video.video2lightbox(e.result)}}})};cosmonaut.universe.styleThumbnails=function(){$.each($(".inner-text"),function(e,t){var n=$(t);var r=$(t).parent();var i=Math.floor(($(r).height()-$(n).height())/2);if(i<0){i=0}$(n).css("padding-top",i);$(n).css("padding-bottom",i)})};$(document).ready(function(){$("#common-header").addClass("border-header");$("#iframe-video-detail").hide();$("#carrousel ul").css("min-height","489px");cosmonaut.universe.launchParallax();cosmonaut.loadRecentPosts();var e=$("#jump_to").val();if(e!=undefined){cosmonaut.jumpTo(e)}var t={data_track_addressbar:true};$(".gravatar").hover(function(){$(this).parent().find(".tooltip").fadeIn(300)},function(){$(this).parent().find(".tooltip").fadeOut(300)});setTimeout(function(){cosmonaut.universe.styleThumbnails()},2e3)});$("#get_your_k_pass_link").click(function(){event.preventDefault;$(this).target="_blank";window.open($(this).prop("href"));return false});$(".icon-play").click(function(e){e.preventDefault();cosmonaut.video.playVideo();var t=$(".icon-play").attr("data-vid-id");if(t!=undefined){cosmonaut.video.markAsWatched(t)}});$("#back").click(function(e){e.preventDefault();cosmonaut.video.resetVideoVimeo();cosmonaut.universe.prevVideo($("#vid_slug").val())});$(".btn-play").click(function(e){e.preventDefault();cosmonaut.video.openVideoDialog($(this).parent().parent().attr("id"))});$("#close-video-detail").click(function(e){e.preventDefault();cosmonaut.video.closeVideoDialog()});$(".disable-bg").click(function(e){e.preventDefault()});$("#link_facebook").click(function(e){e.preventDefault();console.log("Facebook")});$("#link_pay_paypal").click(function(e){e.preventDefault();console.log("Paypal")});$("#link_to_book").click(function(e){e.preventDefault;cosmonaut.requestDownload("{{@BASE_URL}}/download/book")});$("#link_twitter").click(function(e){e.preventDefault();console.log("Twitter")});$("#more_information_link").click(function(e){e.preventDefault;$(location).attr("href","{{@BASE_URL}}/k_pass")});$("#next").click(function(e){e.preventDefault();cosmonaut.video.resetVideoVimeo();cosmonaut.universe.nextVideo($("#vid_slug").val())});$("#video-detail").dialog({autoOpen:false,option:"closeOnEscape",dialogClass:"noclose",draggable:false,hide:"fadeOut",modal:true,show:"fade",width:900,close:function(){cosmonaut.video.closeVideoDialog()},open:function(){}});$(".video-thumbnail").hover(function(e){e.preventDefault();$(this).find("h3:first").fadeOut(200);$(this).find(".inner-text").fadeIn(500)},function(e){e.preventDefault();$(this).find("h3:first").fadeIn(500);$(this).find(".inner-text").fadeOut(200)});
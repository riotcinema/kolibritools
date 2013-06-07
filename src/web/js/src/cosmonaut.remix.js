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
 * cosmonaut.remix.js
 */
cosmonaut.remix=new Array;cosmonaut.remix.ratio=1.77;cosmonaut.remix.closeVideoDialog=function(){cosmonaut.pauseVimeo("vimeo_remix");$("#vimeo_remix").remove();$("#video-remix").remove();$("#remix_subwrapper").fadeIn();};cosmonaut.remix.disableLink=function(e){$(e).css("cursor","default");$(e).attr("href","#");$(e).bind("click",function(e){e.preventDefault();});};cosmonaut.remix.openVideoDialog=function(){var e=$("#remix_wrapper").width().toString();$("#remix_subwrapper").fadeOut();var top=-$("#remix_wrapper").height();var t=$(document.createElement("div")).attr("id", "video-remix").addClass("lightbox").css("display", "inline").appendTo($("#remix_wrapper"));var l=$(document.createElement('a')).attr("id","link_close_learn_more").css("top",top+"px").css("position","absolute").css("width","0px").attr("href","#").addClass("cerrar").appendTo($(t));$(l).bind('click', 'a',function(e){e.preventDefault();cosmonaut.remix.closeVideoDialog();});var n="http://player.vimeo.com/video/{{@vimeo_id}}?api=1&title=0&byline=0&portrait=0&color=ffffff&loop=0&autoplay=1;player_id=vimeo_remix";var r=$(document.createElement("iframe")).addClass("vimeo").attr("id", "vimeo_remix").attr("src", n).attr("height", Math.floor(e / cosmonaut.remix.ratio)).attr("width", e).attr("frameborder", "0").appendTo($(t));};$(document).ready(function(){$("#video-remix .cerrar").css("right","0px");$("#video-remix").offset({top:-105});cosmonaut.vimeo.getVimeoCover({{@vimeo_id}},'section_remix');$(".ui-dialog-titlebar-close").css("display","none");$('#press-area li').hover(function(){$(this).find('.download-arrow').css('opacity','1').css('top','45px');},function(){$(this).find('.download-arrow').css('opacity','0').css('top','10px');});var jump_to=$("#jump_to").val();if(jump_to!= undefined){cosmonaut.jumpTo(jump_to);}<check if="{{@released!=TRUE}}"><true>$.each($("#press-area a"),function(i,v){cosmonaut.remix.disableLink(v);});</true></check>cosmonaut.loadRecentPosts();});$("#video-remix").dialog({autoOpen:false,dialogClass:"noclose",draggable:false,hide:"fadeOut",modal:true,option:"closeOnEscape",resizable:false,show:"fade",width:"1024px",close:function(){cosmonaut.remix.closeVideoDialog();}});$("#link-play-remix").click(function(e){e.preventDefault();cosmonaut.remix.openVideoDialog();});$("#link-remix-close").click(function(e){e.preventDefault();cosmonaut.remix.closeVideoDialog();});$("#ftLinkDownloads").click(function(e){e.preventDefault();cosmonaut.jumpTo("press-area");});$("#ftLinkSubmit").click(function(e){e.preventDefault();cosmonaut.jumpTo("section-submit");});$(".unavailable").click(function(e){e.preventDefault();});
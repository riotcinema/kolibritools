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
 * cosmonaut.movie.js
 */
cosmonaut.movie=new Array;cosmonaut.ratio=1.77;cosmonaut.movie.addLikeButtonB=function(){var e=$(document.createElement("div")).css("margin-right","20px").css("display","inline-block").css("background","#eceef5").css("-webkit-border-radius","3px").css("border","1px solid #cad4e7").css("cursor","pointer").css("padding","2px 6px 4px").css("white-space","nowrap").css("color","#3b5998").css("width","80px").css("height","14px");var t=$(document.createElement("button")).html("Like").css("background","transparent").css("border","0").css("margin","-1px").css("padding","0").css("font","inherit").css("color","inherit").css("cursor","pointer").appendTo($(e));var n=$(document.createElement("i")).css("background-image","url('https://fbstatic-a.akamaihd.net/rsrc.php/v2/yi/r/oX3h85a2YJF.png')").css("background-size","auto").css("background-repeat","no-repeat").css("display","inline-block").css("height","14px").css("width","14px").appendTo($(t));var r=$(document.createElement("a")).attr("href","#").html(e).appendTo($("#div_dyn_cnt_comparte_before"));$(r).bind("click","a",function(e){function t(e){if(e!=undefined&&e["post_id"]!=undefined){cosmonaut.movie.oMD();cosmonaut.movie.sG()}}e.preventDefault();var n="{{html_entity_decode(@dict_twitter_text_movie,ENT_NOQUOTES,@ENCODING)}}";var r={method:"feed",name:n,link:"http://{{$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']}}",picture:"http://farm3.staticflickr.com/2469/buddyicons/1212051@N20.jpg?1257978349",caption:"{{@dict_com_the_cosmonaut}}",description:"{{@dict_uni_the_cosmonaut_p}}"};FB.ui(r,t)})};cosmonaut.movie.addTweetButtonB=function(){var e="{{html_entity_decode(@dict_twitter_text_movie,ENT_NOQUOTES,@ENCODING)}}";var t=$(document.createElement("a")).attr("href","https://twitter.com/share").attr("id","share_to_twitter").attr("data-lang","{{@LANGUAGE}}").attr("data-url","{{@short_url}}").attr("data-count-url","{{'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']}}").attr("data-related","{{@lang_twitter_account}}").attr("data-text",e).addClass("twitter-share-button").appendTo($("#div_dyn_cnt_comparte_before"));$.getScript("http://platform.twitter.com/widgets.js")};cosmonaut.movie.addLikeButtonA=function(){var e=$(document.createElement("div")).css("display","inline-block").css("background","#eceef5").css("-webkit-border-radius","3px").css("margin-right","20px").css("border","1px solid #cad4e7").css("cursor","pointer").css("padding","2px 6px 4px").css("white-space","nowrap").css("color","#3b5998").css("margin-right","20px").css("width","80px").css("height","14px");var t=$(document.createElement("button")).html("Like").css("background","transparent").css("border","0").css("margin","-1px").css("padding","0").css("font","inherit").css("color","inherit").css("cursor","pointer").appendTo($(e));var n=$(document.createElement("i")).css("background-image","url('https://fbstatic-a.akamaihd.net/rsrc.php/v2/yi/r/oX3h85a2YJF.png')").css("background-size","auto").css("background-repeat","no-repeat").css("display","inline-block").css("height","14px").css("width","14px").appendTo($(t));var r=$(document.createElement("a")).attr("href","#").html(e).appendTo($("#div_dyn_cnt_comparte_after"));$(r).bind("click","a",function(e){function t(e){if(e!=undefined&&e["post_id"]!=undefined){cosmonaut.movie.oMD();cosmonaut.movie.sG()}};e.preventDefault();var n="{{html_entity_decode(@dict_twitter_text_movie,ENT_NOQUOTES,@ENCODING)}}";var r={method:"feed",name:n,link:"http://{{$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']}}",picture:"http://farm3.staticflickr.com/2469/buddyicons/1212051@N20.jpg?1257978349",caption:"{{@dict_com_the_cosmonaut}}",description:"{{@dict_uni_the_cosmonaut_p}}"};FB.ui(r,t)})};cosmonaut.movie.addTweetButtonA=function(){var e="{{html_entity_decode(@dict_twitter_text_movie,ENT_NOQUOTES,@ENCODING)}}";var t=$(document.createElement("a")).attr("href","https://twitter.com/share").attr("id","share_to_twitter").attr("data-lang","{{@LANGUAGE}}").attr("data-url","{{@short_url}}").attr("data-count-url","{{'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']}}").attr("data-related","{{@lang_twitter_account}}").attr("data-text",e).addClass("twitter-share-button").appendTo($("#div_dyn_cnt_comparte_after"));$.getScript("http://platform.twitter.com/widgets.js")};cosmonaut.movie.closeMovieDialog=function(){$("#vimeo_the_movie").remove();$("#video-the-movie").dialog("close");$("#movie_play").fadeOut("slow");$("#movie_before").fadeOut("slow");$("#movie_after").fadeIn("slow")};cosmonaut.movie.oMD=function(){$(".ui-dialog").css("z-index",10);$("#vimeo-the-movie").parent().parent().css("background","#000000");$("#video-the-movie").dialog("open");var e="http://player.vimeo.com/video/{{@lang_url_the_movie}}?api=1&title=0&byline=0&portrait=0&color=ffffff&loop=0&autoplay=1;player_id=vimeo_the_movie";var t=$(document.createElement("iframe")).addClass("vimeo").attr("id","vimeo_the_movie").attr("src",e).attr("height",$(window).height()).attr("width",$(window).width()).attr("frameborder","0").appendTo($("#video-the-movie"))};cosmonaut.movie.openPWYWDialog=function(){$("#movie_after").fadeOut("slow");$("#movie_play").fadeOut("slow");$("#movie_before").fadeIn("slow")};cosmonaut.movie.sG=function(){$.ajax({url:"{{@BASE_URL}}/guest",contentType:"application/json",type:"GET",dataType:"json",success:function(e){}})};$("#link-the-movie-close").click(function(e){e.preventDefault();cosmonaut.movie.closeMovieDialog()});$("#submit_pwyw_before").click(function(e){e.preventDefault();$("#frm_pwyw_before").submit()});$("#submit_pwyw_after").click(function(e){e.preventDefault();$("#frm_pwyw_after").submit()});$(".btn_play").click(function(e){e.preventDefault();$.ajax({url:"{{@BASE_URL}}/authorized",contentType:"application/json",type:"POST",dataType:"json",success:function(e){if(e.result=="ok"){cosmonaut.movie.oMD()}else{cosmonaut.movie.openPWYWDialog()}}})});$(".btn_volver").click(function(e){e.preventDefault();cosmonaut.jumpTo("title")});$(".comparteFace").click(function(e){e.preventDefault()});$(".comparteTw").click(function(e){e.preventDefault()});$("#video-the-movie").dialog({autoOpen:false,dialogClass:"noclose",draggable:false,hide:"fadeOut",height:$(window).height(),modal:true,option:"closeOnEscape",resizable:false,show:"fade",width:$(window).width(),close:function(){cosmonaut.movie.closeMovieDialog()}});$(document).ready(function(){twttr.ready(function(e){e.events.bind("tweet",function(e){cosmonaut.movie.oMD();cosmonaut.movie.sG()})});cosmonaut.movie.addTweetButtonB();cosmonaut.movie.addLikeButtonB();cosmonaut.movie.addTweetButtonA();cosmonaut.movie.addLikeButtonA()});
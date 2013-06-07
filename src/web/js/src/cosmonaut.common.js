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
 * cosmonaut.common.js
 */
cosmonaut.cleanForm=function(e){$("form#"+e+" :input").each(function(){var e=$(this);switch(e.attr("type")){case"checkbox":e.attr("checked",false);break;case"radio":e.attr("checked",false);break;case"text":e.val("");break;case"textarea":e.val("");break}$(".validity-tooltip").hide()})};cosmonaut.highlightQuote=function(e){cosmonaut.data.quote_index=e;$("#controls ul li a").removeClass("selected");$("#span_quote").animate({opacity:0},2e3,function(){$("#span_quote").html(cosmonaut.data.quotes[cosmonaut.data.quote_index])}).animate({opacity:1},2e3);$("#span_author").animate({opacity:0},2e3,function(){$("#span_author").html(cosmonaut.data.authors[cosmonaut.data.quote_index])}).animate({opacity:1},2e3);var t=$("#controls ul li a").get(e);$(t).addClass("selected")};cosmonaut.jumpTo=function(e){var t=0;if(typeof $("#"+e)!="undefined"){if(typeof $("#"+e).offset=="function"){t=$("#"+e).offset().top}}$("html, body, .content").animate({scrollTop:t},300)};cosmonaut.loadRecentPosts=function(){$.ajax({url:"{{@BASE_URL}}/posts",cache:false,type:"POST",dataType:"json",data:{},beforeSend:function(){},complete:function(e){},success:function(e){cosmonaut.fillRecentPosts(e)}})};cosmonaut.fillRecentPosts=function(e){var t=$("#blog-subwrapper");$.each(e,function(n,r){var i=$(document.createElement("article")).appendTo($(t));var s=$(document.createElement("header")).appendTo($(i));var o=$(document.createElement("h3")).html(r.title[0]).appendTo($(s));var u=r.pubDate[0].substring(0,r.pubDate[0].length-5);var a=$(document.createElement("time")).attr("datetime",u).html(u).appendTo($(s));var f=r.content.substring(0,250);var l=$(document.createElement("p")).html(f+" ... ").appendTo($(i));var c=$(document.createElement("a")).attr("href",r.link[0]).html("{{@dict_com_read_more}}").appendTo($(l));if(n==e.length-1){$(i).addClass("last-child")}});var n=$(document.createElement("div")).addClass("clearfix").appendTo($(t))};cosmonaut.pauseVimeo=function(e){var t=$f($("#"+e)[0]);t.api("unload");t.api("pause")};cosmonaut.playVimeo=function(e){var t=$f($("#"+e)[0]);t.api("unload");t.api("play");window.setTimeout(function(){var t=$("#"+e);cosmonaut.video.markAsWatched($(t).attr("data-vid-id"))},2e4)};cosmonaut.requestDownload=function(e){$.ajax({url:e,cache:false,type:"POST",dataType:"json",data:{},beforeSend:function(){},complete:function(e){},success:function(e){}})};cosmonaut.finishVideo=function(e){console.log("cosmonaut.finishVideo()")};cosmonaut.prepareQuoteCarrousel=function(){var e=5e3;var t=false;cosmonaut.data.quote_index=1;setInterval(function(){cosmonaut.highlightQuote(cosmonaut.data.quote_index);cosmonaut.data.quote_index++;if(cosmonaut.data.quote_index>=$("#controls ul li").length){cosmonaut.data.quote_index=0}},3e3)}
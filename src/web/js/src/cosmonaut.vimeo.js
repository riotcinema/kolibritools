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
 * cosmonaut.vimeo.js
 */
cosmonaut.vimeo=new Array;cosmonaut.vimeo.getVimeoCover=function(e,t){$.ajax({url:"{{@BASE_URL}}/vimeo/"+e+"/thumbnail",cache:false,type:"POST",dataType:"json",data:{},beforeSend:function(){},complete:function(e){},success:function(e){if(e.result=="ok"){$("#"+t).css("background","url('"+e.msg+"')");$("#"+t).css("background-size","100% 100%");$("#"+t).css("background-repeat","no-repeat")}}})};cosmonaut.vimeo.oembed=function(e,t){var t=$("#"+t);var n=$(t).width().toString();var r="http://www.vimeo.com/"+e.toString();var i="http://www.vimeo.com/api/oembed.json";var s="cosmonaut.vimeo.embedVideo";var e=i+"?url="+encodeURIComponent(r)+"&callback="+s+"&width="+n;cosmonaut.vimeo.embedVideo=function(e){$(t).html(unescape(e.html))};var o=document.createElement("script");o.setAttribute("type","text/javascript");o.setAttribute("src",e);document.getElementsByTagName("head").item(0).appendChild(o)}
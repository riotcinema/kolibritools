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
 * cosmonaut.login.js
 */
cosmonaut.login=new Array();cosmonaut.callback=function(){$(location).attr('href',"{{@BASE_URL}}/dashboard");};cosmonaut.login.hideLogin=function(){$("#dialog-login").dialog("close");$(".validity-tooltip").hide()};cosmonaut.login.login=function(e,t){$.ajax({url:"{{@BASE_URL}}/access",cache:false,type:"POST",dataType:"json",data:{login:e,password:t},beforeSend:function(){},complete:function(e){},success:function(e){if(e.result=="ok"){location.reload()}else if(e.result=="error"){var t=$(document.createElement("div")).addClass("validity-tooltip").attr("style","top:0px; left:0px;").html("{{@dict_log_err}}").appendTo($(".box-input"));var n=$(document.createElement("div")).addClass("validity-tooltip-outer").appendTo($(t));var r=$(document.createElement("div")).addClass("validity-tooltip-inner").appendTo($(n))}}})};cosmonaut.login.showLogin=function(){$("#dialog-login").dialog("open")};cosmonaut.login.validateLoginForm=function(){$.validity.setup({scrollTo:false});$.validity.start();$("#input_login").require("{{@dict_log_err_required_login}}").match("email","{{@dict_log_err_format_login}}");$("#input_password").require("{{@dict_log_err_required_password}}");var e=$.validity.end();return e.valid};$(document).ready(function(){$(".ui-dialog-titlebar-close").css("display","none")});$("#dialog-login").dialog({autoOpen:false,close:function(){cosmonaut.login.hideLogin()},modal:true,option:"closeOnEscape",show:"fade",width:"455px"});$("#dialog-login-close").click(function(e){e.preventDefault();cosmonaut.login.hideLogin()});$("#link-sign-in").click(function(e){e.preventDefault();var t=cosmonaut.login.validateLoginForm();if(t){cosmonaut.login.login($("#input_login").val(),$("#input_password").val())}else{$(".validity-tooltip").each(function(e,t){$(t).offset({top:$(t).position().top-50,left:$(t).position().left-180})})}});$(".sign_in_link").click(function(e){e.preventDefault()})
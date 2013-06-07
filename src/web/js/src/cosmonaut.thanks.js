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
 * cosmonaut.thanks.js
 */
cosmonaut.thanks=new Array();cosmonaut.thanks.hideThanks=function(){$("#dialog-thanks").dialog("close");window.location.href="{{@BASE_URL}}/watch_it_now";};cosmonaut.thanks.showThanks=function(){$("#dialog-thanks").dialog("open");};$(document).ready(function(){cosmonaut.thanks.showThanks();});$("#dialog-thanks").dialog({autoOpen:false,closeOnEscape:false,close:function(){cosmonaut.thanks.hideThanks();},modal: true,open:function(event,ui){$(".ui-dialog-titlebar").hide();},show:"fade",width:"455px"});
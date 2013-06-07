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
 * cosmonaut.twitter.js
 */
cosmonaut.twitter=new Array;$(document).ready(function(){window.twttr=function(e,t,n){var r,i,s=e.getElementsByTagName(t)[0];if(e.getElementById(n))return;i=e.createElement(t);i.id=n;i.src="https://platform.twitter.com/widgets.js";s.parentNode.insertBefore(i,s);return window.twttr||(r={_e:[],ready:function(e){r._e.push(e)}})}(document,"script","twitter-wjs")})
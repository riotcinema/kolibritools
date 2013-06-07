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
 * cosmonaut.data.js
 */
var cosmonaut=new Array();cosmonaut.data=new Array();cosmonaut.data.quote_index=0;<check if="{{isset(@quotes) && is_array(@quotes)}}">cosmonaut.data.quotes=new Array();cosmonaut.data.authors = new Array();<repeat group="{{@quotes}}" key="{{@ikey}}" value="{{@quote}}">cosmonaut.data.quotes[{{@ikey}}]="{{@quote['quote']}}";cosmonaut.data.authors[{{@ikey}}]="{{@quote['author']}}";</repeat></check>cosmonaut.data.cartodb=new Array();cosmonaut.data.cartodb.account="{{@CARTODB_ACCOUNT}}";cosmonaut.data.cartodb.api_key="{{@CARTODB_API_KEY}}";cosmonaut.data.cartodb.table="{{@CARTODB_TABLE}}";cosmonaut.data.constants=new Array();cosmonaut.data.constants["_FB_APP_ID_"]="{{@FB_APP_ID}}";cosmonaut.data.constants["_FB_APP_ID_"]="{{@FB_APP_ID}}";
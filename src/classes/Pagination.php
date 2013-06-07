<?php
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
 * Pagination
 *
 * Pagination controls tool.
 * 
 */
class Pagination {

  private $page = null;
  private $field = '';
  private $order = '';
  private $itemsPerPage;
  private $numItems;


  /**
   * _construct
   *
   * @param $itemsPerPage_param Integer
   * @param $numItems_param Integer
   */
  public function __construct($itemsPerPage_param, $numItems_param) {
    $f3 = F3::instance();

    $this->itemsPerPage = $itemsPerPage_param;
    $this->numItems = $numItems_param;

    $this->page = $f3->get('GET.page');
    $this->field = $f3->get('GET.field');
    $this->order = strtolower($f3->get('GET.order'));

    if(!is_numeric($this->page) || $this->page < 1) {
      $this->page = 1;
    } elseif($this->numItems && (ceil($this->numItems/$this->itemsPerPage) < $this->page)) {
      $this->page = ceil($this->numItems/$this->itemsPerPage);
    }
  }


  /**
   * getPage
   *
   * @return  $this->page
   *
   * Get page attribute.
   */
  public function getPage() {
    return $this->page;
  }


  /**
   * getPage
   *
   * @return  $this->field
   *
   * Get field attribute.
   */
  public function getField() {
    return $this->field;
  }


  /**
   * getOrder
   *
   * @return  $this->page
   *
   * Get order attribute.
   */
  public function getOrder() {
    return $this->order;
  }


  /**
   * getFullOrder
   */
  public function getFullOrder() {
    $result = $this->field;
    if($this->field && $this->order)
      $result .= ' '.$this->order;
    return $result;
  }

  /**
   * getNumPages
   *
   * @return Number of pages required to display whole collection.
   */
  public function getNumPages(){
    return ceil($this->numItems/$this->itemsPerPage);
  }


  /**
   * getLimit
   *
   */
  public function getLimit(){
    return array($this->itemsPerPage*($this->page-1), $this->itemsPerPage);
  }



  /**
   * getPagination
   */
  public function getPagination($numPagesVisibles){

    $paginationArray = array();

    if ($this->numItems>0) {
      $inicio = 1;
      $numpaginas = ceil($this->numItems/$this->itemsPerPage);
      $fin = ceil($this->numItems/$this->itemsPerPage);
      if($fin>$numPagesVisibles && $this->page>1){
        if($this->page==$numpaginas){
          $inicio = ($fin-$numPagesVisibles)+1;
        }elseif($this->page>ceil($numPagesVisibles/2)){
          $inicio = ($this->page-ceil($numPagesVisibles/2))+1;
          $fin = ($this->page+ceil($numPagesVisibles/2))-1;
        }elseif($this->page<ceil($numPagesVisibles/2)){
          $inicio = 1;
          $fin = $numPagesVisibles;
        }elseif($this->page==ceil($numPagesVisibles/2)){
          $inicio = 1;
          $fin = $numPagesVisibles;
        }
        if($fin>$numpaginas){
          $inicio = $inicio-($fin-ceil($this->numItems/$this->itemsPerPage));
          $fin = $numpaginas;
        }
      }elseif($this->page==1 && $numPagesVisibles<$fin){
        $fin = $numPagesVisibles;
      }

      if($this->page>1){
        $paginationArray[0]["class_frontend"] = "";
        $paginationArray[0]["class_backend"] = "";
        $paginationArray[0]["page"] = $this->page-1;
        $paginationArray[0]["display"] = "&laquo; {{@dict_anterior}}";
        $paginationArray[0]["display_backend"] = "&laquo;";
      }
      for($f=$inicio; $f<=$fin; $f++){
        $paginationArray[$f]["class_frontend"] = "";
        $paginationArray[$f]["class_backend"] = "";
        $paginationArray[$f]["page"] = $f;
        $paginationArray[$f]["display"] = $f;
        $paginationArray[$f]["display_backend"] = $f;
      }
      if($this->page<$numpaginas){
        $paginationArray[$fin+1]["class_frontend"] = "";
        $paginationArray[$fin+1]["class_backend"] = "";
        $paginationArray[$fin+1]["page"] = $this->page+1;
        $paginationArray[$fin+1]["display"] = "{{@dict_siguiente}} &raquo;";
        $paginationArray[$fin+1]["display_backend"] = "&raquo;";
      }
      
      $paginationArray[$this->page]["class_frontend"]="class='selected'";
      $paginationArray[$this->page]["class_backend"]="class='active'";

    }

    return $paginationArray;
  }

}
?>
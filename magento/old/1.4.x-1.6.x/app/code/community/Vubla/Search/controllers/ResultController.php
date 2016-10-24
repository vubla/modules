<?php
/**
 * 
 * This source file handles the search queries.
 *
 * @package    Vubla_Search
 * @copyright  Copyright (c) 2010 Vubla
 * @license    ?
 */

class Vubla_Search_ResultController extends Mage_Core_Controller_Front_Action
{
    /**
     * Retrieve catalog session
     *
     * @return Mage_Catalog_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('catalog/session');
    }
    /**
     * Display search result
     */
    public function indexAction()
    {
      $this->loadLayout();
      $this->_initLayoutMessages('catalog/session');
      $this->_initLayoutMessages('checkout/session');
      $this->renderLayout();
    }

}
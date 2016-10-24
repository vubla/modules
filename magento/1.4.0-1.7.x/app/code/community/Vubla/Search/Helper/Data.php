<?php
/**
 * ############################################################################
 *  _           _  _            _  _  _  _  _     _                    _
 * (_)         (_)(_)          (_)(_)(_)(_)(_) _ (_)                 _(_)_
 * (_)         (_)(_)          (_) (_)        (_)(_)               _(_) (_)_
 * (_)_       _(_)(_)          (_) (_) _  _  _(_)(_)             _(_)     (_)_
 *   (_)     (_)  (_)          (_) (_)(_)(_)(_)_ (_)            (_) _  _  _ (_)
 *    (_)   (_)   (_)          (_) (_)        (_)(_)            (_)(_)(_)(_)(_)
 *     (_)_(_)    (_)_  _  _  _(_) (_)_  _  _ (_)(_) _  _  _  _ (_)         (_)
 *       (_)        (_)(_)(_)(_)  (_)(_)(_)(_)   (_)(_)(_)(_)(_)(_)         (_)
 * ############################ NOTICE OF LICENSE #############################
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you are unable to obtain a copy from the above URL, please don't
 * hesitate to contact info@vubla.com.
 *
 * @category    Vubla
 * @package     Vubla_Search
 * @copyright   Copyright (c) 2012 Vubla I/S. (http://www.vubla.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Class to retrive settings from
 */
class Vubla_Search_Helper_Data extends Mage_CatalogSearch_Helper_Data
{
    protected $_data;
    
    protected $_getSuggestions = false;
    protected $_useVubla = null;
    
    protected function _getData()
    {
        
        
        if(is_null($this->_data)){
            $queryURL = $this->_getVublaUrl();
            // Create the stream context
            $context = stream_context_create(array('http' => array('timeout' => 3))); // Timeout in seconds
         
            $contents = "";              
            try {
                // Fetch the URL's contents
               @$contents = file_get_contents($queryURL, 0, $context);
               if (empty($contents)) Throw new Exception("empty");
          
          
            } catch(Exception $e){
                $this->_useVubla = false;
                return null;
              
            }
            $decoded = json_decode($contents);
         
            if(!is_object($decoded) || !is_array($decoded->products))// || empty($decoded->products))
            {
                $this->_useVubla = false;
                return null;
            }
      
            $this->_data = $decoded;
            $this->_useVubla = true;
            $this->_saveProductsForSorting();
            $this->_addDidYouMean(); 
            $this->_addRelatedSearches(); 
        }
       
        return $this->_data;
    }
        
    public function getProductIds()
    {
        $products = $this->getProducts();
        if(!is_array($products)){
            return null;
        }
        $res = array();
        foreach ($products as $key => $value) {
            $res[] = $value->pid;
        }
        return $res;
    }
    
    public function displayLogo()
    {
        $data = $this->_getData();
        if(!isset($data->display_logo) || $data->display_logo == 1)
        {
            return $this->useVubla();
        }
        else 
        {
            return false;
        }
    }
    
    public function useVubla()
    {
        if(is_null($this->_useVubla))
        {
            $this->_getData();
        }
        if($this->_useVubla == true)
        {
            return true;
        } 
        else 
        {
            $this->_useVubla = false;
            return false;                
        }
    }
    
    public function getProducts()
    {
        $data = $this->_getData();
        if(isset($data->products))
        {
            return $data->products;
        }
    }
    
    public function getDidYouMean()
    {
        $data = $this->_getData();
        if(is_null($data) || !isset($data->did_you_mean))
        {
            return null;
        }
        return $data->did_you_mean;
    }
    
    public function getSuggestProductCollection()
    {
        return Mage::getModel('vubla/suggestion')->getProductCollection();
    }
    
    public function getSuggestCategoryCollection()
    {
        return Mage::getModel('vubla/suggestion')->getCategoryCollection();
    }
    
    public function useSuggestion()
    {
        $this->_getSuggestions = true;
    }
    
    protected function _addDidYouMean()
    {
        $didYouMean = $this->getDidYouMean();
        if(!is_array($didYouMean)) {
            return;
        }
        foreach ($didYouMean as $values) {
            $qs = array();
            foreach ($values as $value) {
                $qs[] = $value->word;
            }
            if(!empty($qs))
            {
                $q = implode(' ', $qs);
                $this->_addDidYouMeanMessage($q);
            }
        }
    }
    
    protected function _addDidYouMeanMessage($query)
    {
        Mage::helper('catalogsearch')->addNoteMessage(
        $this->__('Did you mean: %s?',
        '<a href="'.Mage::helper('catalogsearch')->getResultUrl($query).'">'.
            $query.
        '</a>'));
    }
    
    public function getRelatedSearches()
    {
        $data = $this->_getData();
        if(is_null($data) || !isset($data->related_searches))
        {
            return null;
        }
        return $data->related_searches;
    }
    
    protected function _addRelatedSearches()
    {
        $related = $this->getRelatedSearches();
        if(!is_array($related) or !empty($this->_getData()->products)) {
            return;
        }
        $rs = array();
        foreach ($related as $value) {
          
            $rs[] =  ' <a href="'.Mage::helper('catalogsearch')->getResultUrl($value->word).'">'.
            $value->word . '(' . $value->products . ')'.
                 '</a>';
        }
        if(!empty($rs))
        {
            $msg = implode(', ', $rs);
            $original = Mage::helper('catalogsearch')->getQueryText();;
            $this->_addRelatedSearchesMessage($msg, $original);
        }
      
    }
    
    protected function _addRelatedSearchesMessage($searches_string, $original)
    {
        Mage::helper('catalogsearch')->addNoteMessage(
        $this->__('We did not find any results on: %s, but we found something on: %s',  $original, 
        $searches_string));
    }
    
    protected function _saveProductsForSorting()
    {
        $productIds = $this->getProductids();
        if(is_null($productIds)) {
            return;
        }
        // Make temporary table for storing the sort order of results
        $sql = "CREATE TEMPORARY TABLE `vubla_relevance` (
            `product_id` INT UNSIGNED NOT NULL,
            `relevance` INT NOT NULL AUTO_INCREMENT,
            PRIMARY KEY (relevance)
        );
        
        INSERT INTO `vubla_relevance` (`product_id`) VALUES ('".implode("'),('",$productIds)."')";
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $connection->query($sql);
    }
    
    protected function _getVublaUrl()
    {
        if($this->_getSuggestions)
        {
            return Mage::helper('vubla/url')->getSuggestionUrl();
        }
        else 
        {
            return Mage::helper('vubla/url')->getUrl();
        }
    }
}
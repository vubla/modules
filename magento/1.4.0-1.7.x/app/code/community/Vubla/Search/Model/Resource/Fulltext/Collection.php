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
if(!class_exists('Mage_CatalogSearch_Model_Resource_Fulltext_Collection') && class_exists('Mage_CatalogSearch_Model_Mysql4_Fulltext_Collection'))
{
class Mage_CatalogSearch_Model_Resource_Fulltext_Collection extends Mage_CatalogSearch_Model_Mysql4_Fulltext_Collection
{}
}
class Vubla_Search_Model_Resource_Fulltext_Collection extends Mage_CatalogSearch_Model_Resource_Fulltext_Collection
{
    /**
     * Add search query filter
     *
     * @param string $query
     * @return Vubla_Search_Model_Resource_Collection this
     */
    public function addSearchFilter($query)
    {
        if(!Mage::helper('vubla/data')->useVubla())
        {
            return parent::addSearchFilter($query);
        }

        $productids = $this->getProductIds($query);
        return $this->addIdFilter($productids);
    }
    
    public function getProductIds($query)
    {  
        return Mage::helper('vubla/data')->getProductIds();
    }

      public function setOrder($attribute, $dir = 'desc')
    {
        if ($attribute == 'relevance' && Mage::helper('vubla/data')->useVubla())
        {
            //We never want to show the LEAST relevant product first:
            $dir = 'asc'; 
            $this->getSelect()->joinInner(
                array('vubla_search_result' => 'vubla_relevance'),
                    'vubla_search_result.product_id=e.entity_id',
                    array('relevance' => 'relevance')
            );
            $this->getSelect()->order("vubla_search_result.relevance {$dir}");
            
            return $this;
        }
        
        return parent::setOrder($attribute, $dir);
    }
}

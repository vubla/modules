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
class Vubla_Search_Model_Suggestion extends Mage_Catalog_Model_Layer
{
    const XML_PATH_DISPLAY_LAYER_COUNT = 'catalog/search/use_layered_navigation_count';
    
    /**
     * Get current layer product collection
     *
     * @return Mage_Catalog_Model_Resource_Eav_Resource_Product_Collection
     */
    public function getProductCollection()
    {
        if (isset($this->_productCollection)) {
            $collection = $this->_productCollection;
        } else {
            $collection = Mage::getResourceModel('vubla/suggestion_collection');
            $this->prepareProductCollection($collection);
            $this->_productCollection = $collection;
        }
        return $collection;
    }

    /**
     * Prepare product collection
     *
     * @param Mage_Catalog_Model_Resource_Eav_Resource_Product_Collection $collection
     * @return Mage_Catalog_Model_Layer
     */
    public function prepareProductCollection($collection)
    {
        $collection
            ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addSearchFilter($this->getQueryText())
            ->setStore(Mage::app()->getStore())
            ->addUrlRewrite()
            ->addTaxPercents()
            ->addStoreFilter();
       
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($collection);

        return $this;
    }
}

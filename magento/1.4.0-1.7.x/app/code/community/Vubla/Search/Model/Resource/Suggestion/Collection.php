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
if(!class_exists('Vubla_Search_Model_Resource_Fulltext_Collection'))
{
    $dir = Mage::getBaseDir().'/app/code/community/Vubla/Search/Model/Resource/Fulltext/Collection.php';
    include $dir;
}
class Vubla_Search_Model_Resource_Suggestion_Collection extends Vubla_Search_Model_Resource_FullText_Collection
{
    public function addSearchFilter($query)
    {
        $productids = $this->getProductIds($query);
        return $this->addIdFilter($productids);
    }
    
    public function getProductIds($query)
    {
        Mage::helper('vubla/data')->useSuggestion();  
        return Mage::helper('vubla/data')->getProductIds();
    }
}

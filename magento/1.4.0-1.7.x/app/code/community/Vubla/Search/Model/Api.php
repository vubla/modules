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
class Vubla_Search_Model_Api extends Mage_Api_Model_Resource_Abstract
{

    public function fetch()
    {
       $entries = Mage::getSingleton('core/resource')
                ->getConnection('core_read')
                ->select()
                ->from(array('catalogsearch_query'))
                ->query()
                ->fetchAll();
       
       return (array) $entries;

       
    }

   
}


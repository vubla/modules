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
class Vubla_Search_Helper_Url extends Mage_Core_Helper_Abstract
{
    private $_param = array();
    
    public function setParam($name, $value)
    {
        $this->_param[$name] = $value;
        return $this;
    }            
    
    public function getParam($name)
    {
        if(isset($this->_param[$name]))
        {
            return $this->_param[$name];
        }
    } 
    
    private function _addDefaultParams()
    {
        
        $devname= Mage::app()->getRequest()->getParam('devname');    
        if(!is_null($devname))
        {
            $this->setParam('devname', $devname);
        }
        $solr = Mage::app()->getRequest()->getParam('solr');    
        if(!is_null($solr))
        {
            $this->setParam('solr', $solr);
        }
        
        $q = Mage::helper('catalogsearch')->getQueryText();
    
        $this->setParam('q', $q)
                ->setParam('ip', $_SERVER['REMOTE_ADDR'])
                ->setParam('useragent', $_SERVER['HTTP_USER_AGENT'])
                ->setParam('host', $_SERVER['HTTP_HOST'])
                ->setParam('api_version', '1.0')
                ->setParam('locale', $locale = Mage::app()->getLocale()->getLocaleCode());
        return $this;
    }
    
    private function _getVublaHost()
    {
         return 'api.vubla.com';
    }
    
    public function getUrl()
    {
        $this->_addDefaultParams();
        
        $params = http_build_query($this->_param);
        
        $url = 'http://'. $this->_getVublaHost() . '/search/?' . $params;
        
        return $url;
    }
    
    public function getSuggestionUrl()
    {
        $this->_addDefaultParams();
        $this->setParam('api_suggestions', '1');
        
        $params = http_build_query($this->_param);
        
        $url = 'http://'. $this->_getVublaHost() . '/search/ajaxsearch.php?' . $params;
        
        return $url;
    }
}
        
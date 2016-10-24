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
class Vubla_Search_Block_Autocomplete extends Mage_CatalogSearch_Block_Autocomplete
{
    protected function _toHtml()
    {
        $html = '';

        if (!$this->_beforeToHtml()) 
        {
            return $html;
        }

        $suggestData = $this->getSuggestProducts();
        $count = count($suggestData);
        if ($count == 0) 
        {
            return parent::_toHtml();
        }
        $i = 0;
        $helper = $this->helper('catalog/output');
        $html .= '<ul><li style="display:none"></li>';
        $counter = 0;
        foreach ($suggestData as $item) 
        {
            if($item->isAvailable())
            {
                $prodName = $this->stripTags($item->getName(), null, true);
                $html .= '
                <li class="'.((++$i)%2?'odd':'even');
                if( $i == sizeof($suggestData) ){
                    $html .=  ' last'; 
                } 
                $html .= '" title="'.$prodName.'" link="' . $item->getProductUrl() . '">
    
                    <a href="' . $item->getProductUrl() . '" title="' . $this->stripTags($this->getImageLabel($item, 'small_image'), null, true) .'" class="product-image">
                        <img src="'.$this->helper('catalog/image')->init($item, 'small_image')->resize(50).'" width="50" height="50" alt="' .$this->stripTags($this->getImageLabel($item, 'small_image'), null, true). '" />
                    </a>
                    <div class="product-shop">
                        <div class="f-fix">
                            <h2 class="product-name">
                                <a href="' . $item->getProductUrl() . '" title="' . $prodName . '">' .
                                    $helper->productAttribute($item, $item->getName() , 'name').
                                '</a>
                            </h2>
                        </div>
                    </div>
                </li>';
                $counter++;
            }
        }
        if($counter == 0)
        {
            return '';
        }
        $html.= '</ul>';

        return $html;
    }

    public function getSuggestProducts()
    {
        if (!$this->_suggestData) 
        {
            $this->_suggestData  = $this->helper('vubla/data')->getSuggestProductCollection();
        }
        if(!$this->helper('vubla/data')->useVubla()) 
        {
            return parent::getSuggestData();
        }
        return $this->_suggestData;
    }

    public function getSuggestCategories()
    {
        if (!$this->_suggestData) 
        {
            $this->_suggestData  = $this->helper('vubla/data')->getSuggestProductCollection();
        }
        if(!$this->helper('vubla/data')->useVubla()) 
        {
            return parent::getSuggestData();
        }
        return $this->_suggestData;
    }
}

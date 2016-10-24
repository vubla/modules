<?php

/**
 * Vubla
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageWorx EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.mageworx.com/LICENSE-1.0.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.mageworx.com/ for more information
 *
 * @category   Vubla
 * @package    Vubla_SearchAutocomplete
 * @copyright  Copyright (c) 2011 MageWorx (http://www.mageworx.com/)
 * @license    http://www.mageworx.com/LICENSE-1.0.html
 */

/**
 * Search Autocomplete extension
 *
 * @category   Vubla
 * @package    Vubla_SearchAutocomplete
 * @author     MageWorx Dev Team
 */

class Vubla_SearchAutocomplete_Model_CatalogSearch_Query extends Mage_CatalogSearch_Model_Query {

    public function prepare() {
        if (!$this->getId()) {
            $this->setIsCmspageProcessed(0);
        }
        return parent::prepare();
    }

}

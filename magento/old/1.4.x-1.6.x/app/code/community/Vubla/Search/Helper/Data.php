<?php

/**
 * search helper
 * based on Mage_CatalogSearch_Helper_Data
 * Class to retrive settings from
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Vubla_Search_Helper_Data extends Mage_CatalogSearch_Helper_Data
{
    const QUERY_VAR_NAME = 'q';
    const MAX_QUERY_LEN  = 200;

    /**
     * Query object
     *
     * @var Mage_CatalogSearch_Model_Query
     */
    protected $_query;

    /**
     * Query string
     *
     * @var string
     */
    protected $_queryText;

    /**
     * Note messages
     *
     * @var array
     */
    protected $_messages = array();

    /**
     * Is a maximum length cut
     *
     * @var bool
     */
    protected $_isMaxLength = false;

    /**
     * Search engine model
     *
     * @var Mage_CatalogSearch_Model_Mysql4_Fulltext_Engine
     */
    protected $_engine;

    /**
     * Retrieve search query parameter name
     *
     * @return string
     */
    public function getQueryParamName()
    {
        return self::QUERY_VAR_NAME;
    }


    /**
     * Is a minimum query length
     *
     * @return bool
     */
    public function isMinQueryLength()
    {
        if (Mage::helper('core/string')->strlen($this->getQueryText()) < $this->getMinQueryLength()) {
            return true;
        }
        return false;
    }

    /**
     * Retrieve search query text
     *
     * @return string
     */
    public function getQueryText()
    {
        if (is_null($this->_queryText)) {
            $this->_queryText = $this->_getRequest()->getParam($this->getQueryParamName());
            if ($this->_queryText === null) {
                $this->_queryText = '';
            } else {
                if (is_array($this->_queryText)) {
                    $this->_queryText = null;
                }
                $this->_queryText = trim($this->_queryText);
                $this->_queryText = Mage::helper('core/string')->cleanString($this->_queryText);

                if (Mage::helper('core/string')->strlen($this->_queryText) > $this->getMaxQueryLength()) {
                    $this->_queryText = Mage::helper('core/string')->substr(
                        $this->_queryText,
                        0,
                        $this->getMaxQueryLength()
                    );
                    $this->_isMaxLength = true;
                }
            }
        }
		
        return $this->_queryText;
    }

    /**
     * Retrieve HTML escaped search query
     *
     * @return string
     */
    public function getEscapedQueryText()
    {
        return $this->htmlEscape($this->getQueryText());
    }

    /**
     * Retrieve suggest collection for query
     *
     * @return Mage_CatalogSearch_Model_Mysql4_Query_Collection
     */
    public function getSuggestProductCollection()
    {
        return $this->getQuery()->getSuggestProductCollection();
    }

    /**
     * Retrieve result page url and set "secure" param to avoid confirm
     * message when we submit form from secure page to unsecure
     *
     * @param   string $query
     * @return  string
     */
    public function getResultUrl($query = null)
    {
        return $this->_getUrl('vubla/result', array(
            '_query' => array(self::QUERY_VAR_NAME => $query),
            '_secure' => Mage::app()->getFrontController()->getRequest()->isSecure()
        ));
    }
    
     /**
     * Retrieve result page url and set "secure" param to avoid confirm
     * message when we submit form from secure page to unsecure
     *
     * @param   string $query
     * @return  string
     */
    public function getOldResultUrl($query = null)
    {
      return parent::getResultUrl($query);
    }

    /**
     * Retrieve suggest url
     *
     * @return string
     */
    public function getSuggestUrl()
    {
        return $this->_getUrl('catalogsearch/ajax/suggest', array(
            '_secure' => Mage::app()->getFrontController()->getRequest()->isSecure()
        ));
    }

    /**
     * Retrieve search term url
     *
     * @return string
     */
    public function getSearchTermUrl()
    {
        return $this->_getUrl('catalogsearch/term/popular');
    }

    /**
     * Retrieve advanced search URL
     *
     * @return string
     */
    public function getAdvancedSearchUrl()
    {
        return $this->_getUrl('vubla/result');
    }

    /**
     * Retrieve minimum query length
     *
     * @param mixed $store
     * @return int
     */
    public function getMinQueryLength($store = null)
    {
        return Mage::getStoreConfig(Mage_CatalogSearch_Model_Query::XML_PATH_MIN_QUERY_LENGTH, $store);
    }

    /**
     * Retrieve maximum query length
     *
     * @param mixed $store
     * @return int
     */
    public function getMaxQueryLength($store = null)
    {
        return Mage::getStoreConfig(Mage_CatalogSearch_Model_Query::XML_PATH_MAX_QUERY_LENGTH, $store);
    }

    /**
     * Retrieve maximum query words count for like search
     *
     * @param mixed $store
     * @return int
     */
    public function getMaxQueryWords($store = null)
    {
        return Mage::getStoreConfig(Mage_CatalogSearch_Model_Query::XML_PATH_MAX_QUERY_WORDS, $store);
    }

    /**
     * Add Note message
     *
     * @param string $message
     * @return Mage_CatalogSearch_Helper_Data
     */
    public function addNoteMessage($message)
    {
        $this->_messages[] = $message;
        return $this;
    }

    /**
     * Set Note messages
     *
     * @param array $messages
     * @return Mage_CatalogSearch_Helper_Data
     */
    public function setNoteMessages(array $messages)
    {
        $this->_messages = $messages;
        return $this;
    }

    /**
     * Retrieve Current Note messages
     *
     * @return array
     */
    public function getNoteMessages()
    {
        return $this->_messages;
    }

    /**
     * Check query of a warnings
     *
     * @param mixed $store
     * @return Mage_CatalogSearch_Helper_Data
     */
    public function checkNotes($store = null)
    {
        if ($this->_isMaxLength) {
            $this->addNoteMessage($this->__('Maximum Search query  length is %s. Your query was cut.', $this->getMaxQueryLength()));
        }

        $stringHelper = Mage::helper('core/string');
        /* @var $stringHelper Mage_Core_Helper_String */

        $searchType = Mage::getStoreConfig(Mage_CatalogSearch_Model_Fulltext::XML_PATH_CATALOG_SEARCH_TYPE);
        if ($searchType == Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_COMBINE ||
            $searchType == Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_LIKE) {

            $wordsFull = $stringHelper->splitWords($this->getQueryText(), true);
            $wordsLike = $stringHelper->splitWords($this->getQueryText(), true, $this->getMaxQueryWords());

            if (count($wordsFull) > count($wordsLike)) {
                $wordsCut = array_diff($wordsFull, $wordsLike);

                $wordsCut = array_map(array($this, 'htmlEscape'), $wordsCut);
                $this->addNoteMessage(
                    $this->__('Maximum words count is %1$s. In your search query was cut next part: %2$s.',
                        $this->getMaxQueryWords(),
                        join(' ', $wordsCut)
                    )
                );
            }
        }
    }

    /**
     * Join index array to string by separator
     * Support 2 level array gluing
     *
     * @param array $index
     * @param string $separator
     * @return string
     */
    public function prepareIndexdata($index, $separator = ' ')
    {
        $_index = array();
        foreach ($index as $key => $value) {
            if (!is_array($value)) {
                $_index[] = $value;
            }
            else {
                $_index = array_merge($_index, $value);
            }
        }
        return join($separator, $_index);
    }

    /**
     * Get current search engine resource model
     *
     * @return object
     */
    public function getEngine()
    {
        if (!$this->_engine) {
            $engine = Mage::getStoreConfig('catalog/search/engine');

            /**
             * This needed if there already was saved in configuration some none-default engine
             * and module of that engine was disabled after that.
             * Problem is in this engine in database configuration still set.
             */
            if ($engine && Mage::getConfig()->getResourceModelClassName($engine)) {
                $model = Mage::getResourceSingleton($engine);
                if ($model && $model->test()) {
                    $this->_engine = $model;
                }
            }
            if (!$this->_engine) {
                $this->_engine = Mage::getResourceSingleton('catalogsearch/fulltext_engine');
            }
        }

        return $this->_engine;
    }

    /**
     * Returns the URL of the Vubla server
     *
     * @return string
     */
    public function getVublaURL()
    {
            
        $api_url = 'http://api.vubla.com/';
        if(isset($_GET['vubla_url'])){
            $api_url = $_GET['vubla_url'];
        }
        $result = $api_url."search?host=".urlencode($_SERVER['HTTP_HOST'])."&store_id=".Mage::app()->getStore()->getCode()."&file=".urlencode($_SERVER["REDIRECT_URL"])."&ip=".urlencode($_SERVER['REMOTE_ADDR'])."&useragent=".urlencode($_SERVER['HTTP_USER_AGENT'])."&param=q".'&getvar='.urlencode(json_encode($_GET)).'&postvar='.urlencode(json_encode($_POST))."&q=";
        if(isset($_GET["debug_vubla_display_url"])){ echo $result.'<br />'; exit; }
        return $result;
    }

    
}
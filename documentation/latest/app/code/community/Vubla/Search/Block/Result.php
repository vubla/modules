<?php
/**
 * File that generates the contents used by template
 */
class Vubla_Search_Block_Result extends Mage_Core_Block_Template
{

   /**
     * Retrieve Search result list HTML output
     *
     * @return string
     */ 
    public function getResultHtml()
    {
        $query = Mage::helper('vubla')->getQueryText();
  
        $queryURL = Mage::helper('vubla')->getVublaURL() . urlencode($query);

        // Create the stream context
        $context = stream_context_create(array('http' => array('timeout' => 3)));   // Timeout in seconds
		
        $contents = "";			     
        try {
            // Fetch the URL's contents
	       @$contents = file_get_contents($queryURL, 0, $context);
	       if (empty($contents)) Throw new Exception("empty");
	  
	  
        } catch(Exception $e){
       
	       Mage::app()->getResponse()->setRedirect(
	       Mage::helper('vubla')->getOldResultUrl($query));
	       //Mage::getUrl("myrouter/mycontroller/noview"))

        }

        //$queryURL = "http://rasmus.vubla.com/dev/search/?q=QUERY&host=everlight.dk&enable=1";
        //$result = "searchquery to call: ".$queryURL;
        //$result= file_get_contents($queryURL);
        return $contents;
    }

}
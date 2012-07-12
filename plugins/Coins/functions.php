<?php

function coins()
{
	$coins = new Coins;
	echo $coins->getCoinsSpan();
}
 
function coins_multiple()
{
	while (loop_items()) {
		coins();
	}
}
 
class Coins
{
    // Required COinS values.
    const COINS_SPAN_CLASS = 'Z3988';
    const CTX_VER          = 'Z39.88-2004';
    const RFT_VAL_FMT      = 'info:ofi/fmt:kev:mtx:dc';
    const RFR_ID           = 'info:sid/omeka.org:generator';
    
    // The name of the Dublin Core element set.
    const ELEMENT_SET_DUBLIN_CORE = 'Dublin Core';
    
    // The default title element text, in case the element text is empty or 
    // does not exist.
    const ELEMENT_TITLE_DEFAULT = '[unknown title]';
    
    // The lenth to truncate long descriptions.
    const ELEMENT_DESCRIPTION_TRUNCATE_LENGTH = 500;
    
    // Array containing Dublin Core elements that do not need special 
    // processing.
    private $_elements = array('creator', 'subject', 'publisher', 
                               'contributor', 'date', 'format', 'source', 
                               'language', 'coverage', 'rights', 'relation');
    
    // Array containing the COinS elements.
    private $_coins = array();
    
    // The fully formatted COinS span.
    private $_coinsSpan;
        
    /**
     * Gather metadata and build the COinS span for the current item.
     */
    public function __construct()
    {
        // Set required COinS values.
        $this->_coins['ctx_ver']     = self::CTX_VER;
        $this->_coins['rft_val_fmt'] = self::RFT_VAL_FMT;
        $this->_coins['rfr_id']      = self::RFR_ID;
                
        // Set the Dublin Core elements.
        $this->_setElements();
        
        // Set the Dublin Core elements that need special processing.
        $this->_setTitle();
        $this->_setDescription();
        $this->_setType();
        $this->_setIdentifier();
        
        // Build the COinS span.
        $this->_buildCoinsSpan();
    }
    
    /**
     * Get the COinS span.
     */
    public function getCoinsSpan()
    {
        return $this->_coinsSpan;
    }
    
    /**
     * Set the Dublin Core elements.
     */
    private function _setElements()
    {
        foreach ($this->_elements as $element) {
            $elementText = $this->_getElementText(ucfirst($element));
            // item() returns false if no element text exists. Do not set a 
            // nonexistant element text to the _coins array.
            if (false === $elementText) {
                continue;
            }
            $this->_coins["rft.$element"] = $elementText;
        }
    }
    
    /**
     * Set the Dublin Core Title element.
     */
    private function _setTitle()
    {
        $title = $this->_getElementText('Title');
        if (false === $title || '' == trim($title)) {
            $title = self::ELEMENT_TITLE_DEFAULT;
        }
        $this->_coins['rft.title'] = $title;
    }
    
    /**
     * Set the Dublin Core Description element.
     */
    private function _setDescription()
    {
        $description = $this->_getElementText('Description');
        if (false === $description) {
            return;
        }
        // Truncate long descriptions when needed.
        if (self::ELEMENT_DESCRIPTION_TRUNCATE_LENGTH <= strlen($description)) {
            $description = substr($description, 
                                  0, 
                                  self::ELEMENT_DESCRIPTION_TRUNCATE_LENGTH);
        }
        $this->_coins['rft.description'] = $description;
    }
    
    /**
     * Set the Type. Use the type from the item type name, not the Dublin Core 
     * type name.
     * @todo: devise a better mapping scheme between Omeka and COinS/Zotero
     */
    private function _setType()
    {
        switch (item('item type name')) {
            case 'Oral History':
                $type = 'interview';
                break;
            case 'Moving Image':
                $type = 'videoRecording';
                break;
            case 'Sound':
                $type = 'audioRecording';
                break;
            case 'Email':
                $type = 'email';
                break;
            case 'Website':
            case 'Hyperlink':
                $type = 'webPage';
                break;
            case 'Document':
            case 'Event':
            case 'Lesson Plan':
            case 'Person':
            case 'Interactive Resource':
            case 'Still Image':
            default:
                $type = 'document';
                break;
        }
        $this->_coins['rft.type'] = $type;
    }
    
    /**
     * Use the current script URI instead of the Dublin Core identifier.
     */
    private function _setIdentifier()
    {
        // Set the identifier as the absolute URL of the current page.
        $this->_coins['rft.identifier'] = abs_uri();
    }
    
    /**
     * Get the unfiltered element text.
     */
    private function _getElementText($elementName)
    {
        $elementText = item(self::ELEMENT_SET_DUBLIN_CORE, 
                            $elementName, 
                            array('no_filter' => true,
                                  'no_escape' => true));
        return $elementText;
    }
    
    /**
     * Build the COinS span and URL-encode the _coins array.
     */
    private function _buildCoinsSpan()
    {
        $coinsSpan = '<span class="';
        $coinsSpan .= self::COINS_SPAN_CLASS;
        $coinsSpan .= '" title="';
        $coinsSpan .= html_escape(http_build_query($this->_coins));
        $coinsSpan .= '"></span>';
        
        $this->_coinsSpan = $coinsSpan;
    }
}

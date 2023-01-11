<?php
/**
 * COinS
 *
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * @package Coins\View\Helper
 */
class Coins_View_Helper_Coins extends Zend_View_Helper_Abstract
{
    /**
     * Return a COinS span tag for every passed item.
     *
     * @param array|Item An array of item records or one item record.
     * @return string
     */
    public function coins($items)
    {
        if (!is_array($items)) {
            return $this->_getCoins($items);
        }

        $coins = '';
        foreach ($items as $item) {
            $coins .= $this->_getCoins($item);
            release_object($item);
        }
        return $coins;
    }

    /**
     * Build and return the COinS span tag for the specified item.
     *
     * @param Item $item
     * @return string
     */
    protected function _getCoins(Item $item)
    {
        $coins = array();

        $coins['ctx_ver'] = 'Z39.88-2004';
        $coins['rft_val_fmt'] = 'info:ofi/fmt:kev:mtx:dc';
        $coins['rfr_id'] = 'info:sid/omeka.org:generator';

        // Handle Dubln Core elements that work well with multiple values in
        // COinS
        $multiElementNames = array('Creator', 'Contributor', 'Subject');
        foreach ($multiElementNames as $elementName) {
            $elementTexts = $this->_getElementText($item, $elementName, true);
            if (!$elementTexts) {
                continue;
            }
            $elementName = strtolower($elementName);
            $coins["rft.$elementName"] = $elementTexts;
        }

        // Set the Dublin Core elements that don't need special processing.
        $elementNames = array('Description', 'Publisher', 'Date', 'Format',
            'Source', 'Language', 'Coverage', 'Rights', 'Relation');
        foreach ($elementNames as $elementName) {
            $elementText = $this->_getElementText($item, $elementName);
            if (null === $elementText) {
                continue;
            }

            $elementName = strtolower($elementName);
            $coins["rft.$elementName"] = $elementText;
        }

        // Set the title key from Dublin Core:title.
        $title = $this->_getElementText($item, 'Title');
        if (null === $title || '' == trim($title)) {
            $title = '[unknown title]';
        }
        $coins['rft.title'] = $title;

        // Set the type key from item type, map to Zotero item types.
        $itemTypeName = metadata($item, 'item type name');
        switch ($itemTypeName) {
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
                $type = 'webpage';
                break;
            case 'Text':
            case 'Document':
                $type = 'document';
                break;
            default:
                if ($itemTypeName) {
                    $type = $itemTypeName;
                } else {
                    $type = $this->_getElementText($item, 'Type');
                }
        }
        if (null !== $type) {
            $coins['rft.type'] = $type;
        }

        // Set the identifier key as the absolute URL to the item
        $coins['rft.identifier'] = record_url($item, null, true);

        // Build and return the COinS span tag.
        $coinsSpan = '<span class="Z3988" title="';
        $coinsSpan .= html_escape($this->_buildQueryString($coins));
        $coinsSpan .= '" aria-hidden="true"></span>';
        return $coinsSpan;
    }

    /**
     * Get the unfiltered element text for the specified item.
     *
     * @param Item $item
     * @param string $elementName
     * @param bool $all If true, return array of all texts
     * @return string|array|bool
     */
    protected function _getElementText(Item $item, $elementName, $all = false)
    {
        $options = array('no_filter' => true, 'no_escape' => true, 'snippet' => 500);
        if ($all) {
            $options['all'] = true;
        }
        $elementText = metadata(
            $item,
            array('Dublin Core', $elementName),
            $options
        );
        return $elementText;
    }

    /**
     * Build a query string from the passed array of data
     *
     * Like the built-in http_build_query, but it supports multiple values
     * with the same key.
     *
     * @param array $data
     * @return string
     */
    protected function _buildQueryString($data)
    {
        $query = '';
        foreach ($data as $key => $values) {
            $encodedKey = urlencode($key);
            if (!is_array($values)) {
                $values = array($values);
            }
            foreach ($values as $value) {
                if (strlen($query)) {
                    $query .= '&';
                }
                $encodedValue = urlencode($value);
                $query .= $encodedKey . '=' . $encodedValue;
            }
        }
        return $query;
    }
}

<?php
/** Copyright (c) 2025, Regents of the University of Michigan.
* All rights reserved. See LICENSE.txt for details.
*/

class Metadata_MetadataController extends Omeka_Controller_AbstractActionController
{
    public function exportAction()
    {

        $db = get_db();
        $results = [];

        foreach ($db->getTable('Exhibit')->findBy(['public' => 1]) as $exhibit) {
            $results[] = [
                'title' => $exhibit->title,
                'url' => WEB_ROOT . "/exhibits/show/{$exhibit->slug}", // I need the absolute URL here.  Neither url() nor public_url() will work.
                'description' => $exhibit->description,
                "modified" => $exhibit->modified,
                'tags' => array_map(function($tag) { return $tag->name; }, $exhibit->getTags()),
            ];
        }
        $this->_helper->viewRenderer->setNoRender(true);
        $this->getResponse()->setHeader('Content-Type', 'application/json', true);
        $json = json_encode($results, JSON_PRETTY_PRINT);
        if ($json === false) {
             $errorMsg = [
                 'error' => 'JSON encoding failed',
                 'code' => json_last_error(),
                 'message' => json_last_error_msg(),
             ];
             $this->getResponse()->setHttpResponseCode(500);
             $json = json_encode($errorMsg, JSON_PRETTY_PRINT);
        }
        $this->getResponse()->setBody($json);
    }
}

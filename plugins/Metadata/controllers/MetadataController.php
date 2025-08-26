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
                'url' => WEB_ROOT . "/exhibits/show/{$exhibit->slug}",
                'description' => $exhibit->description,
                "modified" => $exhibit->modified,
                'tags' => array_map(function($tag) { return $tag->name; }, $exhibit->getTags()),
            ];
        }
        $this->_helper->viewRenderer->setNoRender(true);
        $this->getResponse()->setHeader('Content-Type', 'application/json', true);
        $this->getResponse()->setBody(json_encode($results, JSON_PRETTY_PRINT));
    }
}

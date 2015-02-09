<?php

/**
* Relationship table to store an image for each exhibit.
*/
class CosignImagexhibitrelationshipTable extends Omeka_Db_Table
{
    // Original table name was 'posters', not 'my_omeka_posters'
    protected $_name = 'image_exhibit_relationship';

}

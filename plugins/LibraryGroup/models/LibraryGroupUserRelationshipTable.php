<?php

/**
* Relationship table between User id and group id
*/
class LibraryGroupUserRelationshipTable extends Omeka_Db_Table
{
    // Original table name was 'posters', not 'my_omeka_posters'
    protected $_name = 'grouping_relationship';

}
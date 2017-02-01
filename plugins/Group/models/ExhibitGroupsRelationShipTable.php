<?php
 /**
  * Copyright (c) 2016, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */
 /**
  * Exhibit, Groups Relationship table.
 */
class ExhibitGroupsRelationShipTable extends Omeka_Db_Table
{
    // Original table name was 'posters', not 'my_omeka_posters'
    protected $_name = 'group_exhibit_relationship';
}

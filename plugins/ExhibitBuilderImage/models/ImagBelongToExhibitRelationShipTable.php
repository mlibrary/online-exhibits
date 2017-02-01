<?php
 /**
  * Copyright (c) 2016, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */
 /**
 /**
  * Relationship table to store an image for each exhibit.
  */
class ImagBelongToExhibitRelationShipTable extends Omeka_Db_Table
{
    // Original table name was 'posters', not 'my_omeka_posters'
    protected $_name = 'image_exhibit_relationship';

}

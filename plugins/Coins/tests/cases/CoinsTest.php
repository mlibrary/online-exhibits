<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 **/

class CoinsTest extends Omeka_Test_AppTestCase
{
    protected $_isAdminTest = false;

    public function setUp()
    {
        parent::setUp();
        
        $pluginHelper = new Omeka_Test_Helper_Plugin;
        $pluginHelper->setUp('Coins');
    }

    public function testCoinsOnItemPage()
    {
        $_SERVER['HTTP_HOST'] = 'localhost';

        $this->dispatch('/items/show/1');

        $identifierUrl = 'http://localhost/items/show/1';

        $coinsSpanExpected = '<span class="Z3988" title="ctx_ver=Z39.88-2004&amp;rft_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Adc&amp;rfr_id=info%3Asid%2Fomeka.org%3Agenerator&amp;rft.title=' . urlencode(Installer_Test::TEST_ITEM_TITLE) . '&amp;rft.type=document&amp;rft.identifier=' . urlencode($identifierUrl) . '"></span>';
 
        ob_start();
        coins();
        $coinsSpanActual = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($coinsSpanExpected, $coinsSpanActual);
    }
}

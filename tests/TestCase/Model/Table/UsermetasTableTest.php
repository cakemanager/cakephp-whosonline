<?php
namespace WhosOnline\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use WhosOnline\Model\Table\UsermetasTable;

/**
 * WhosOnline\Model\Table\UsermetasTable Test Case
 */
class UsermetasTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'Usermetas' => 'plugin.whos_online.usermetas',
        'Users' => 'plugin.whos_online.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Usermetas') ? [] : ['className' => 'WhosOnline\Model\Table\UsermetasTable'];
        $this->Usermetas = TableRegistry::get('Usermetas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Usermetas);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

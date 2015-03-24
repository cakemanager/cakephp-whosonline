<?php
/**
 * CakeManager (http://cakemanager.org)
 * Copyright (c) http://cakemanager.org
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) http://cakemanager.org
 * @link          http://cakemanager.org CakeManager Project
 * @since         1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace WhosOnline\Test\TestCase\Controller\Admin;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

/**
 * WhosOnline\Controller\Admin\WhosOnlineController Test Case
 */
class WhosOnlineControllerTest extends IntegrationTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'Users' => 'plugin.cake_manager.users',
        'Roles' => 'plugin.cake_manager.roles',
        'Usermetas' => 'plugin.whos_online.WhosonlineUsermetas',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Users = TableRegistry::get('CakeManager.Users');
        $this->Usermetas = TableRegistry::get('WhosOnline.Usermetas');
    }

    public function testAuthorization()
    {
        // index
        $this->get('/admin/whosonline/whosonline');
        $this->assertRedirect('/users/login');

        // view
        $this->get('/admin/whosonline/whosonline/view/1');
        $this->assertRedirect('/users/login');

        // setting a wrong role_id
        $this->session(['Auth' => ['User' => ['role_id' => 2]]]);

        // index
        $this->get('/admin/whosonline/whosonline');
        $this->assertResponseError();

        // view
        $this->get('/admin/whosonline/whosonline/view/1');
        $this->assertResponseError();
    }

    /**
     * test Index
     *
     * @return void
     */
    public function testIndex()
    {
        $this->_createUser();

        $this->session(['Auth.User' => [
                'id' => 5,
                'email' => 'info@cakemanager.org',
                'role_id' => 1,
        ]]);

        $this->get('/admin/whosonline/whosonline');
        $this->assertResponseOk();
    }

    /**
     * test View
     *
     * @return void
     */
    public function testView()
    {
        $this->_createUser();

        $this->session(['Auth.User' => [
                'id' => 5,
                'email' => 'info@cakemanager.org',
                'role_id' => 1,
        ]]);

        $this->get('/admin/whosonline/whosonline/view/5');
        $this->assertResponseOk();
    }

    /**
     * _createUser
     *
     * Creates a new user
     *
     * @return void
     */
    protected function _createUser()
    {
        $data = [
            'email' => 'newuser@email.nl',
            'password' => 'test',
        ];

        $user = $this->Users->newEntity($data);

        $user->set('active', true);
        $user->set('role_id', 1);

        $this->Users->save($user);
    }
}

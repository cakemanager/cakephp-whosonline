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
namespace WhosOnline\Test\TestCase\Controller\Component;

use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;
use WhosOnline\Controller\Component\WhosOnlineComponent;

/**
 * WhosOnline\Controller\Component\WhosOnlineComponent Test Case
 */
class WhosOnlineComponentTest extends IntegrationTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'Roles' => 'plugin.cake_manager.roles',
        'Users' => 'plugin.cake_manager.users',
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

        // Setup our component and fake test controller
        $collection = new ComponentRegistry();
        $this->WhosOnline = new WhosOnlineComponent($collection);

        $this->Controller = $this->getMock('Cake\Controller\Controller', ['redirect']);
        $this->WhosOnline->setController($this->Controller);

        $this->Users = TableRegistry::get('CakeManager.Users');
        $this->Usermetas = TableRegistry::get('WhosOnline.Usermetas');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->WhosOnline);
        unset($this->Controller);

        parent::tearDown();
    }

    /**
     * test testLoginEvent
     *
     * @return void
     */
    public function testAfterLoginEvent()
    {
        // creating a new user
        $this->_createUser();

        $login = [
            'email' => 'newuser@email.nl',
            'password' => 'test',
        ];

        $this->post('/users/login', $login);

        $this->assertRedirect();

        $user = $this->Usermetas->find('all')->first();

        $this->assertEquals($user->last_login, \Cake\I18n\Time::now());
        $this->assertEquals($user->passed_logins, 1);
        $this->assertEquals($user->failed_logins, 0);
    }

    /**
     * test testInvalidLoginEvent
     *
     * @return void
     */
    public function testInvalidLoginEvent()
    {
        // creating a new user
        $this->_createUser();

        $login = [
            'email' => 'newuser@email.nl',
            'password' => 'invalidpassword',
        ];

        $this->post('/users/login', $login);

        $this->assertNoRedirect();

        $user = $this->Usermetas->find('all')->first();

        $this->assertEquals(null, $user->passed_logins);
        $this->assertEquals(1, $user->failed_logins);
    }

    /**
     * test testPasswordRequestEvent
     *
     * @return void
     */
    public function testPasswordRequestEvent()
    {
        // creating a new user
        $this->_createUser();

        $data = [
            'email' => 'newuser@email.nl',
        ];

        $this->post('/users/forgotPassword', $data);

        $this->assertRedirect();

        $user = $this->Usermetas->find('all')->first();

        $this->assertEquals(null, $user->passed_logins);
        $this->assertEquals(null, $user->failed_logins);
        $this->assertEquals(1, $user->password_requests);
    }

    /**
     * test testLastSeenEvent
     *
     * @return void
     */
    public function testLastSeenEvent()
    {
        // creating a new user
        $this->_createUser();

        $login = [
            'email' => 'newuser@email.nl',
            'password' => 'test',
        ];

        $this->post('/users/login', $login);
        $this->assertRedirect();

        $this->session(['Auth.User' => [
                'id' => 5,
                'role_id' => 1,
        ]]);

        $this->get('/');
        $this->assertNoRedirect();

        $user = $this->Usermetas->find('all')->first();

        $this->assertEquals(1, $user->passed_logins);
        $this->assertEquals(\Cake\I18n\Time::now(), $user->last_seen);
        $this->assertEquals(null, $user->failed_logins);
        $this->assertEquals(null, $user->password_requests);
    }

    /**
     * test testImplementedEvents
     *
     * @return void
     */
    public function testImplementedEvents()
    {
        $this->assertContains("lastSeenEvent", $this->WhosOnline->implementedEvents());
        $this->assertContains("passwordRequestEvent", $this->WhosOnline->implementedEvents());
        $this->assertContains("afterLoginEvent", $this->WhosOnline->implementedEvents());
        $this->assertContains("invalidLoginEvent", $this->WhosOnline->implementedEvents());

        $this->WhosOnline->config('lastSeen', false);

        $this->assertNotContains("lastSeenEvent", $this->WhosOnline->implementedEvents());
        $this->assertContains("passwordRequestEvent", $this->WhosOnline->implementedEvents());
        $this->assertContains("afterLoginEvent", $this->WhosOnline->implementedEvents());
        $this->assertContains("invalidLoginEvent", $this->WhosOnline->implementedEvents());

        $this->WhosOnline->config('passwordRequests', false);

        $this->assertNotContains("lastSeenEvent", $this->WhosOnline->implementedEvents());
        $this->assertNotContains("passwordRequestEvent", $this->WhosOnline->implementedEvents());
        $this->assertContains("afterLoginEvent", $this->WhosOnline->implementedEvents());
        $this->assertContains("invalidLoginEvent", $this->WhosOnline->implementedEvents());

        $this->WhosOnline->config('lastLogin', false);

        $this->assertNotContains("lastSeenEvent", $this->WhosOnline->implementedEvents());
        $this->assertNotContains("passwordRequestEvent", $this->WhosOnline->implementedEvents());
        $this->assertContains("afterLoginEvent", $this->WhosOnline->implementedEvents());
        $this->assertContains("invalidLoginEvent", $this->WhosOnline->implementedEvents());

        $this->WhosOnline->config('passedLogins', false);

        $this->assertNotContains("lastSeenEvent", $this->WhosOnline->implementedEvents());
        $this->assertNotContains("passwordRequestEvent", $this->WhosOnline->implementedEvents());
        $this->assertNotContains("afterLoginEvent", $this->WhosOnline->implementedEvents());
        $this->assertContains("invalidLoginEvent", $this->WhosOnline->implementedEvents());

        $this->WhosOnline->config('failedLogins', false);

        $this->assertNotContains("lastSeenEvent", $this->WhosOnline->implementedEvents());
        $this->assertNotContains("passwordRequestEvent", $this->WhosOnline->implementedEvents());
        $this->assertNotContains("afterLoginEvent", $this->WhosOnline->implementedEvents());
        $this->assertNotContains("invalidLoginEvent", $this->WhosOnline->implementedEvents());
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

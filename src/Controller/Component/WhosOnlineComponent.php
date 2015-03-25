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
namespace WhosOnline\Controller\Component;

use Cake\Controller\Component;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

/**
 * WhosOnline component
 */
class WhosOnlineComponent extends Component
{
    /**
     * Default configuration.
     *
     * ### OPTIONS
     * - userId:            Path to the users id in the session
     * - userModel:         The model of the users. Default 'CakeManager.Users'
     * - usermetasModel     The model of the usermetas. Default 'WhosOnline.Usermetas'
     * - lastSeen           Boolean if we should save the lastSeen-status
     * - lastLogin          Boolean if we should save the lastLogin-status
     * - passedLogins       Boolean if we should save the passedLogins-status
     * - failedLogins       Boolean if we should save the failedLogins-status
     * - passwordRequests   Boolean if we should save the passwordRequests-status
     *
     * The statuss are default set to true, so everything will be watched.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'userId' => 'Auth.User.id',
        'userModel' => 'CakeManager.Users',
        'usermetasModel' => 'WhosOnline.Usermetas',
        'lastSeen' => true,
        'lastLogin' => true,
        'passedLogins' => true,
        'failedLogins' => true,
        'passwordRequests' => true,
    ];

    /**
     * The User-model
     *
     * @var type
     */
    protected $Users = null;

    /**
     * The Usermeta-model
     *
     * @var type
     */
    protected $Usermetas = null;

    /**
     * The controller.
     *
     * @var \Cake\Controller\Controller
     */
    private $Controller = null;

    /**
     * setController
     *
     * Setter for the Controller property.
     *
     * @param \Cake\Controller\Controller $controller Controller.
     * @return void
     */
    public function setController($controller)
    {
        $this->Controller = $controller;
    }

    /**
     * initialize
     *
     * @param array $config Config.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->Controller = $this->_registry->getController();

        $this->Users = TableRegistry::get($this->config('userModel'));

        $this->Usermetas = TableRegistry::get($this->config('usermetasModel'));

        try {
            TableRegistry::get('whosonline_usermetas')->schema();
            $this->tableExists = true;
        } catch (\Cake\Database\Exception $e) {
            $this->tableExists = false;
        }
    }

    /**
     * BeforeFilter
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function beforeFilter($event)
    {
        $event->subject()->Menu->add('Who Is Online', [
            'weight' => 20,
            'url' => [
                'prefix' => 'admin',
                'plugin' => 'WhosOnline',
                'controller' => 'whosonline',
                'action' => 'index',
            ]
        ]);
    }

    /**
     * _getUsermetas
     *
     * Returns the metadata from the selected user.
     * Use the first parameter for the id.
     * If it's empty it returns the logged in users metadata.
     *
     * @param mixed int|void $id ID of the user
     * @param array $options Options.
     * @return \Cake\ORM\Entity
     */
    protected function _getUsermetas($id = null, $options = [])
    {
        $_options = [
            'autoCreate' => true
        ];

        $options = array_merge($_options, $options);

        if (!$id) {
            $id = $this->Controller->request->Session()->read($this->config('userId'));
        }

        $data = $this->Usermetas->find()->where(['user_id' => $id]);

        if ($data->Count() > 0) {
            return $data->first();
        }

        $generated = null;

        if ($options['autoCreate']) {
            $generated = $this->_createUsermetas($id);
        }

        return $generated;
    }

    /**
     * _createUsermetas
     *
     * Creates an usermetas-row for the user if it doesnt exist
     * Use the first parameter for the id.
     * If it's empty it returns the logged in users metadata.
     *
     * @param mixed int|void $id ID of the user
     * @return \Cake\ORM\Entity|bool
     */
    protected function _createUsermetas($id = null)
    {
        if (!$this->_getUsermetas($id, ['autoCreate' => false])) {
            if (!$id) {
                $id = $this->Controller->request->Session()->read($this->config('userId'));
                if (is_null($id)) {
                    return false;
                }
            }

            $data = [
                'user_id' => $id,
            ];

            $this->Usermetas->save($this->Usermetas->newEntity($data));

            return $this->Usermetas->findByUserId($id)->first();
        }
        return false;
    }

    /**
     * loginEvent
     *
     * Event - triggered when an user logs in.
     *
     * @param \Cake\Event\Event $event Event.
     * @param array $user The user who just logged in.
     * @return void
     */
    public function afterLoginEvent($event, $user)
    {
        if ($user) {
            $_user = $this->_getUsermetas();
            if ($_user) {
                if ($this->config('passedLogins')) {
                    $_user->set('passed_logins', $_user->get('passed_logins') + 1);
                }
                if ($this->config('lastLogin')) {
                    $_user->set('last_login', Time::now());
                }
                $this->Usermetas->save($_user);
            }
        }
    }

    /**
     * invalidLoginEvent
     *
     * Event - triggered when an user logs in.
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function invalidLoginEvent($event)
    {
        if (key_exists('email', $event->subject()->request->data)) {
            $user = $this->Users->findByEmail($event->subject()->request->data['email'])->first();
        }
        if ($user) {
            $_user = $this->_getUsermetas($user->get('id'));
            if ($_user) {
                $_user->set('failed_logins', $_user->get('failed_logins') + 1);
                $this->Usermetas->save($_user);
            }
        }
    }

    /**
     * passwordRequestEvent
     *
     * Event - triggered when an user requests a new password
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function passwordRequestEvent($event)
    {
        if (key_exists('email', $event->subject()->request->data)) {
            $user = $this->Users->findByEmail($event->subject()->request->data['email'])->first();
        }

        if ($user) {
            $_user = $this->_getUsermetas($user->get('id'));
            if ($_user) {
                $_user->set('password_requests', $_user->get('password_requests') + 1);
                $this->Usermetas->save($_user);
            }
        }
    }

    /**
     * Event - triggered every beforeFilter-callback.
     * Sets the date and time when the user is last seen.
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function lastSeenEvent($event)
    {
        $user = $this->_getUsermetas();
        if ($user) {
            $user->set('last_seen', Time::now());
            $this->Usermetas->save($user);
        }
    }

    /**
     * Implemented events
     *
     * @return array
     */
    public function implementedEvents()
    {
        if ($this->tableExists) {
            $_events = parent::implementedEvents();

            $events = [];

            if ($this->config('lastSeen')) {
                $events['Component.Manager.beforeFilter'] = 'lastSeenEvent';
            }

            if ($this->config('passwordRequests')) {
                $events['Controller.Users.afterForgotPassword'] = 'passwordRequestEvent';
            }

            if ($this->config('lastLogin') || $this->config('passedLogins')) {
                $events['Controller.Users.afterLogin'] = 'afterLoginEvent';
            }

            if ($this->config('failedLogins')) {
                $events['Controller.Users.afterInvalidLogin'] = 'invalidLoginEvent';
            }
        }

        return array_merge($_events, $events);
    }
}

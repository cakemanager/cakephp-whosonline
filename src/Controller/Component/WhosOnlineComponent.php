<?php

namespace WhosOnline\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

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
        'userId'           => 'Auth.User.id',
        'userModel'        => 'CakeManager.Users',
        'usermetasModel'   => 'WhosOnline.Usermetas',
        'lastSeen'         => true,
        'lastLogin'        => true,
        'passedLogins'     => true,
        'failedLogins'     => true,
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
     * Holder for the controller
     *
     * @var type
     */
    protected $_Controller = null;

    /**
     * Initialize
     *
     * @param array $config
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->_Controller = $this->_registry->getController();

        $this->Users = TableRegistry::get($this->config('userModel'));

        $this->Usermetas = TableRegistry::get($this->config('usermetasModel'));
    }

    /**
     * BeforeFilter Event
     *
     * @param type $event
     */
    public function beforeFilter($event) {

        $event->subject()->Menu->add('Who Is Online', [
            'weight' => 20,
            'url'    => [
                'prefix'     => 'admin',
                'plugin'     => 'WhosOnline',
                'controller' => 'whosonline',
                'action'     => 'index',
            ]
        ]);
    }

    /**
     * Returns the metadata from the selected user.
     * Use the first parameter for the id.
     * If it's empty it returns the logged in users metadata.
     *
     * @param type $id
     * @return type
     */
    protected function _getUsermetas($id = null, $options = []) {

        $_options = [
            'autoCreate' => true
        ];

        $options = array_merge($_options, $options);

        if (!$id) {
            $id = $this->_Controller->request->Session()->read($this->config('userId'));
        }

        $data = $this->Usermetas->find()->where(['user_id' => $id]);

        if ($data->Count() > 0) {
            return $data->first();
        }

        $generated = null;

        if ($options['autoCreate']) {
            $generated = $this->_createUsermetas();
        }

        return $generated;
    }

    /**
     * Creates an usermetas-row for the user if it doesnt exist
     * Use the first parameter for the id.
     * If it's empty it returns the logged in users metadata.
     *
     * @param type $id
     * @return boolean
     */
    protected function _createUsermetas($id = null) {

        if (!$this->_getUsermetas($id, ['autoCreate' => false])) {

            if (!$id) {
                $id = $this->_Controller->request->Session()->read($this->config('userId'));

                if (!$id) {
                    return false;
                }
            }


            $data = [
                'user_id' => $id,
            ];

            return $this->Usermetas->save($this->Usermetas->newEntity($data));
        }

        return false;
    }

    /**
     * Event - triggered when an user logs in.
     * To check if the login passed or failed, check the session!
     *
     * NOT IMPLEMENTED YET!
     *
     * @param type $event
     */
    public function loginEvent($event) {

    }

    /**
     * Event - triggered when an user requests a new password
     *
     * NOT IMPLEMENTED YET!
     *
     * @param type $event
     */
    public function passwordRequestEvent($event) {

    }

    /**
     * Event - triggered every beforeFilter-callback.
     * Sets the date and time when the user is last seen.
     * @param type $event
     */
    public function lastSeenEvent($event) {
        $user = $this->_getUsermetas();

        $user->set('last_seen', Time::now());

        $this->Usermetas->save($user);
    }

    /**
     * Implemented events
     *
     * @return type
     */
    public function implementedEvents() {

        $_events = parent::implementedEvents();

        if ($this->config('lastSeen')) {
            $events['Component.Manager.beforeFilter'] = 'lastSeenEvent';
        }

        if ($this->config('passwordRequests')) {
            $events['Component.Users.request'] = 'passwordRequestEvent';
        }

        if ($this->config('lastLogin')) {
            $events['Component.Users.login'] = 'loginEvent';
        }

        return array_merge($_events, $events);
    }

}

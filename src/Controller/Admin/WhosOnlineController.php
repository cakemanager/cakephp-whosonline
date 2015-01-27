<?php

namespace WhosOnline\Controller\Admin;

use WhosOnline\Controller\AppController;

/**
 * WhosOnline Controller
 *
 * @property \WhosOnline\Model\Table\WhosOnlineTable $WhosOnline
 */
class WhosOnlineController extends AppController
{

    /**
     * Initializer
     *
     */
    public function initialize() {
        parent::initialize();


        $this->loadModel('WhosOnline.Usermetas');
    }

    /**
     * Pagination settings
     *
     * @var type
     */
    public $paginate = [
        'limit' => 25,
        'order' => [
            'Usermetas.last_seen' => 'desc'
        ],
        'contain' => ['Users']
    ];

    /**
     * IsAuthorized method
     * Authorizes the controller
     *
     * @param type $user
     * @return type
     */
    public function isAuthorized($user) {

        $this->Authorizer->action(['*'], function($auth) {
            $auth->allowRole(1);
        });

        return $this->Authorizer->authorize();
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index() {
        $this->set('usermetas', $this->paginate($this->Usermetas));
    }

    /**
     * View method
     *
     * @param string|null $id Whos Online id
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException
     */
    public function view($id = null) {
        $whosOnline = $this->WhosOnline->get($id, [
            'contain' => []
        ]);
        $this->set('whosOnline', $whosOnline);
    }

}

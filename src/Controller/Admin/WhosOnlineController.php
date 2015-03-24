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
     * initialize
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Utils.Search');

        $this->loadModel('WhosOnline.Usermetas');

        $this->helpers['Utils.Search'] = [];
    }

    /**
     * IsAuthorized
     *
     * Authorizes the controller.
     *
     * @param array $user User.
     * @return bool
     */
    public function isAuthorized($user)
    {
        $this->Authorizer->action(['*'], function ($auth) {
            $auth->allowRole(1);
        });

        return $this->Authorizer->authorize();
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->Search->addFilter('email');

        $query = $this->Search->search($this->Usermetas->find('all'));

        $this->set('usermetas', $this->paginate($query));
    }

    /**
     * View method
     *
     * @param string|null $id ID of the user
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException
     */
    public function view($id = null)
    {
        $usermeta = $this->Usermetas->findByUserId($id)->contain(['Users' => ['Roles']])->first();
        $this->set(compact('usermeta'));
    }
}

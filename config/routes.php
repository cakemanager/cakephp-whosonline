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
use Cake\Routing\Router;

Router::prefix('admin', function ($routes) {
    $routes->plugin('WhosOnline', ['path' => '/whosonline'], function ($routes) {
        
        $routes->connect('/whosonline', [
            'prefix'     => 'admin',
            'plugin'     => 'WhosOnline',
            'controller' => 'whosOnline',
            'action'     => 'index',
        ]);
        
        $routes->fallbacks('InflectedRoute');
    });
    $routes->fallbacks('InflectedRoute');
});

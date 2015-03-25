# WhosOnline plugin for CakePHP

The WhosOnline plugin for CakePHP 3.0 and the CakeManager helps you to watch your registered users. 
You are able to see the following:

- Last login timestamp
- Last seen timestamp
- Total passed logins
- Total failed logins
- Total new password requests

Note that you need the [CakeManager Plugin](https://github.com/cakemanager/cakephp-cakemanager) for this plugin!

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require cakemanager/cakephp-whosonline
```

## Configuration

You will need to add the following line to your application's bootstrap.php file:

```php
Plugin::load('WhosOnline', ['bootstrap' => true, 'routes' => true]);
```

Next you need migrations to create the table for the plugin in your shell:

```bash
cake migrations migrate --plugin="WhosOnline"
```

## Usage

### Component

Add the following to your `AppController` to use the plugin-callbacks:

```php
$this->loadComponent('WhosOnline.WhosOnline', []);
```

Options for the component:

- `userId` - Path to the users id in the session
- `userModel` - The model of the users. Default `CakeManager.Users`
- `usermetasModel` - The model of the usermetas. Default `WhosOnline.Usermetas`
- `lastSeen` - Boolean if we should save the lastSeen-status
- `lastLogin` - Boolean if we should save the lastLogin-status
- `passedLogins` - Boolean if we should save the passedLogins-status
- `failedLogins` - Boolean if we should save the failedLogins-status
- `passwordRequests` - Boolean if we should save the passwordRequests-status

All statuses are default set to `true`.

Example:

```php
$this->loadComponent('WhosOnline.WhosOnline', [
  'lastSeen' => true,
  'failedLogins' => false,
]);
```

From now on the component will save all statuses automatically!

### Watching

If you want to watch the usermetas, watch the menu-item "Who Is Online" and click on it. You will see a list of recent online users and its data.

Support
-------

- [CakeManager Website](http://cakemanager.org/) - Website of the CakeManager Team. Here you can find everything about us and our plugins.

- [Gitter](https://gitter.im/cakemanager/cakephp-cakemanager) - Chat Tool for GitHub to talk about issues and new features.

- [GitHub](https://github.com/cakemanager/cakephp-whosonline/issues) - When there's something wrong, please open a new issue!

- [CakeManager Docs](http://cakemanager.org/docs/1.0/) - Documentation about the CakeManager Plugin.


Contributing
------------

If you have a good idea for a new feature, feel free to pull or open a new  [issue](https://github.com/cakemanager/cakephp-whosonline/issues). Pull requests are always more than welcome.

License
-------

The MIT License (MIT)

Copyright (c) 2014 CakeManager by bobmulder

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

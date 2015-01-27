# WhosOnline plugin for CakePHP

This is a pre-alpha version of a WhosOnline plugin for CakePHP 3.0 and the CakeManager. It is currently under development and should be considered experimental.

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


## Usage

### Component

Add the following to your `AppController` to use the plugin-callbacks:

```php
$this->loadComponent('WhosOnline.WhosOnline', []);
```

Options for the component:

- userId:            Path to the users id in the session
- userModel:         The model of the users. Default `CakeManager.Users`
- usermetasModel     The model of the usermetas. Default `WhosOnline.Usermetas`
- lastSeen           Boolean if we should save the lastSeen-status
- lastLogin          Boolean if we should save the lastLogin-status
- passedLogins       Boolean if we should save the passedLogins-status
- failedLogins       Boolean if we should save the failedLogins-status
- passwordRequests   Boolean if we should save the passwordRequests-status

All statuss are default set to `true`.

Example:

```php
$this->loadComponent('WhosOnline.WhosOnline', [
  'lastSeen' => true,
  'failedLogins' => false,
]);
```

From now on the component will save all statuss automatically!

### Watching

If you want to watch the usermetas, watch the menu-item "Who Is Online" and click on it. You will see a list of recent online users and its data.

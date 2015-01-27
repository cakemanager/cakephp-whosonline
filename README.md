# WhosOnline plugin for CakePHP

This is a pre-alpha version of a WhosOnline plugin for CakePHP 3.0. It is currently under development and should be considered experimental.

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require cakemanager/cakephp-whospnline
```

## Configuration

You will need to add the following line to your application's bootstrap.php file:

```
Plugin::load('WhosOnline', ['bootstrap' => false, 'routes' => true]);
```


## Usage

Add the following to your `AppController` to use the plugin-callbacks:

```
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

```
$this->loadComponent('WhosOnline.WhosOnline', [
  'lastSeen' => true,
  'failedLogins' => false,
]);
```

From now on the component will save all statuss automatically!

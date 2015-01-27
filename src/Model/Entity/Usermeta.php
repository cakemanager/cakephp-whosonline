<?php
namespace WhosOnline\Model\Entity;

use Cake\ORM\Entity;

/**
 * Usermeta Entity.
 */
class Usermeta extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => true,
        'last_seen' => true,
        'passed_logins' => true,
        'failed_logins' => true,
        'password_requests' => true,
        'user' => true,
    ];
}

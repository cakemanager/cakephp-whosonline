<h3>User: <?= $usermeta->user->email ?></h3>

<?= $this->Html->link('Overview', ['action' => 'index']) ?>

<table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('user_id') ?></th>
            <th><?= $this->Paginator->sort('last_seen') ?></th>
            <th><?= $this->Paginator->sort('last_login') ?></th>
            <th><?= $this->Paginator->sort('passed_logins', 'Logins') ?></th>
            <th><?= $this->Paginator->sort('failed_logins', 'Failed Logins') ?></th>
            <th><?= $this->Paginator->sort('password_requests', 'Pass requests') ?></th>
        </tr>
    </thead>
    <tbody>
            <tr>
                <td><?= $usermeta->user->get('email') ?></td>
                <td><?=
                    (($usermeta->last_seen) ? h($usermeta->last_seen->timeAgoInWords([
                                'accuracy' => ['second' => 'second'],
                                'end' => '1 day'
                            ])
                        ) : 'Never')
                    ?></td>
                <td><?=
                    (($usermeta->last_login) ? h($usermeta->last_login->timeAgoInWords([
                                'accuracy' => ['hour' => 'hour'],
                                'end' => '1 day'
                            ])
                        ) : 'Never')
                    ?></td>
                <td><?= $this->Number->format($usermeta->passed_logins) ?></td>
                <td><?= $this->Number->format($usermeta->failed_logins) ?></td>
                <td><?= $this->Number->format($usermeta->password_requests) ?></td>
            </tr>
    </tbody>
</table>
<hr>
<div class="row">
    <div class="large-2 columns numbers end">
        <h6 class="subheader"><?= __('User Id') ?></h6>
        <p><?= $this->Number->format($usermeta->user->id) ?></p>
        <h6 class="subheader"><?= __('Email') ?></h6>
        <p><?= h($usermeta->user->email) ?></p>
        <h6 class="subheader"><?= __('Active') ?></h6>
        <p><?= $usermeta->user->active ? "Yes" : "No" ?></p>
        <h6 class="subheader"><?= __('Role') ?></h6>
        <p><?= h($usermeta->user->role->name) ?></p>
    </div>
    <div class="large-2 columns dates end">
        <h6 class="subheader"><?= __('Created') ?></h6>
        <p><?= h($usermeta->user->created) ?></p>
    </div>
    <div class="large-2 columns dates end">
        <h6 class="subheader"><?= __('Modified') ?></h6>
        <p><?= h($usermeta->user->modified) ?></p>
    </div>
</div>
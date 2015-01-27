<?php ?>

<h3>Who is Online?</h3>

<table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('user_id') ?></th>
            <th><?= $this->Paginator->sort('last_seen') ?></th>
            <th><?= $this->Paginator->sort('last_login') ?></th>
            <th><?= $this->Paginator->sort('passed_logins') ?></th>
            <th><?= $this->Paginator->sort('failed_logins') ?></th>
            <th><?= $this->Paginator->sort('password_requests') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usermetas as $usermeta): ?>
            <tr>
                <td><?= $usermeta->user->get('email') ?></td>
                <td><?=
                    h($usermeta->last_seen->timeAgoInWords([
                                'accuracy' => ['second' => 'second'],
                                'end'      => '1 day'
                            ])
                    )
                    ?></td>
                <td><?=
                    h($usermeta->last_login->timeAgoInWords([
                                'accuracy' => ['hour' => 'hour'],
                                'end'      => '1 day'
                            ])
                    )
                    ?></td>
                <td><?= $this->Number->format($usermeta->passed_logins) ?></td>
                <td><?= $this->Number->format($usermeta->failed_logins) ?></td>
                <td><?= $this->Number->format($usermeta->password_requests) ?></td>
            </tr>

<?php endforeach; ?>
    </tbody>
</table>
<div class="paginator">
    <ul class="pagination">
        <?= $this->Paginator->prev('< ' . __('previous')); ?>
        <?= $this->Paginator->numbers(); ?>
<?= $this->Paginator->next(__('next') . ' >'); ?>
    </ul>
    <p><?= $this->Paginator->counter(); ?></p>
</div>

<?php debug($usermetas); ?>
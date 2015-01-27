<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('Edit Usermeta'), ['action' => 'edit', $usermeta->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Usermeta'), ['action' => 'delete', $usermeta->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usermeta->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Usermetas'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Usermeta'), ['action' => 'add']) ?> </li>
    </ul>
</div>
<div class="usermetas view large-10 medium-9 columns">
    <h2><?= h($usermeta->id) ?></h2>
    <div class="row">
        <div class="large-2 columns numbers end">
            <h6 class="subheader"><?= __('Id') ?></h6>
            <p><?= $this->Number->format($usermeta->id) ?></p>
            <h6 class="subheader"><?= __('User Id') ?></h6>
            <p><?= $this->Number->format($usermeta->user_id) ?></p>
            <h6 class="subheader"><?= __('Passed Logins') ?></h6>
            <p><?= $this->Number->format($usermeta->passed_logins) ?></p>
            <h6 class="subheader"><?= __('Failed Logins') ?></h6>
            <p><?= $this->Number->format($usermeta->failed_logins) ?></p>
            <h6 class="subheader"><?= __('Password Requests') ?></h6>
            <p><?= $this->Number->format($usermeta->password_requests) ?></p>
        </div>
        <div class="large-2 columns dates end">
            <h6 class="subheader"><?= __('Last Seen') ?></h6>
            <p><?= h($usermeta->last_seen) ?></p>
        </div>
    </div>
</div>

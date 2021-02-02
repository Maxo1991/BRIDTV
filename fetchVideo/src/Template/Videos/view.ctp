<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Video $video
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Video'), ['action' => 'edit', $video->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Video'), ['action' => 'delete', $video->id], ['confirm' => __('Are you sure you want to delete # {0}?', $video->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Videos'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Get Video'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Videos'), '/videos/index', ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Edit User details'), ['controller' => 'users', 'action' => 'edit', $this->Session->read('Auth.User.id')]) ?></li>
    </ul>
</nav>
<div class="videos view large-9 medium-8 columns content">
    <h3><?= h($video->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <video width="50%" style="margin-left: 25%;" controls autoplay muted><source src="<?= h($video->url) ?>" type="video/mp4"></video>
        </tr>
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($video->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Duration') ?></th>
            <td><?= h($video->duration) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Download Link') ?></th>
            <td><?= h($video->download_link) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Website') ?></th>
            <td><?= h($video->website) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($video->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Bitrate') ?></th>
            <td><?= $this->Number->format($video->bitrate) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Size') ?></th>
            <td><?= $this->Number->format($video->size) ?></td>
        </tr>
    </table>
</div>

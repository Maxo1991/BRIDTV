<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Video[]|\Cake\Collection\CollectionInterface $videos
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Get Video'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Videos'), '/videos/index', ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Edit User details'), ['controller' => 'users', 'action' => 'edit', $this->Session->read('Auth.User.id')]) ?></li>
    </ul>
</nav>
<div class="videos index large-9 medium-8 columns content">
    <h3><?= __('Videos') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                <th scope="col"><?= $this->Paginator->sort('url') ?></th>
                <th scope="col"><?= $this->Paginator->sort('bitrate') ?></th>
                <th scope="col"><?= $this->Paginator->sort('duration') ?></th>
                <th scope="col"><?= $this->Paginator->sort('size') ?></th>
                <th scope="col"><?= $this->Paginator->sort('download_link') ?></th>
                <th scope="col"><?= $this->Paginator->sort('website') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($videos as $video): ?>
            <tr>
                <td><?= $this->Number->format($video->id) ?></td>
                <td><?= h($video->title) ?></td>
                <td><video width="100%" data-id="<?= $this->Number->format($video->id) ?>" controls muted><source src="<?= h($video->url) ?>" type="video/mp4"></video></td>
                <td><?= $this->Number->format($video->bitrate) ?></td>
                <td><?= gmdate("H:i:s", h($video->duration)); ?></td>
                <td><?= round($video->size / 1048567, 2) . "MB" ?></td>
                <td><?= h($video->download_link) ?></td>
                <td><?= h($video->website) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $video->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $video->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $video->id], ['confirm' => __('Are you sure you want to delete # {0}?', $video->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
<script src="http://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script>
    (function($){
        $(document).ready(function(){
            $("video").bind('play', function () {
                $(this).css('width', '500%');
                $(this).css('margin-top', '100%');
            });
            $("video").bind('pause', function(){
                $(this).css('width', '100%');
                $(this).css('margin-top', '0');
            });
        });
    })(jQuery);
</script>

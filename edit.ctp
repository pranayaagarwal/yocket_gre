<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $greOption->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $greOption->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Gre Options'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Gre Questions'), ['controller' => 'GreQuestions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Gre Question'), ['controller' => 'GreQuestions', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Gre Responses'), ['controller' => 'GreResponses', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Gre Response'), ['controller' => 'GreResponses', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="greOptions form large-9 medium-8 columns content">
    <?= $this->Form->create($greOption) ?>
    <fieldset>
        <legend><?= __('Edit Gre Option') ?></legend>
        <?php
            echo $this->Form->input('gre_question_id', ['options' => $greQuestions]);
            echo $this->Form->input('text');
            echo $this->Form->input('is_answer');
            echo $this->Form->input('label');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

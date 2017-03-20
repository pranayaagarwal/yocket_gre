<h3>Showing all visited questions</h3>
<?php $questionIds=$this->request->session()->read('questionIds');?>
<?php $i=0;?>
<table class="table table-striped">
  <thead>
    <tr>
        <td>#</td>
        <td>Question</td>
        <td>Status</td>
    </tr>
  </thead>
  <tbody>
    <?php foreach($marked as $m):?>
      <tr>
      <td><?= $i+1?></td>
      <td><?= $this->Html->link(__($questions[$i]->text), ['controller'=>'GreQuestions','action' => 'view',$questionIds[$i]]);?></td>
      <td><?php if($m->is_marked):?>
                <?="marked";?>
                <?php else: ?>
                  <?="unmarked";?>
                <?php endif;?>
    </td>
    </tr>
  </tbody>
  <?php $i++;?>
<?php endforeach;?>
</table>
<?= $this->Html->link(__('Back'), ['controller'=>'GreQuestions','action' => 'view',$current_ques],["class" => "btn btn-default"]);?>

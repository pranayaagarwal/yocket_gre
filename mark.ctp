<h3>Showing all marked questions</h3>
<?php $i=0;?>
<?php $questionIds=$this->request->session()->read('questionIds');?>

<table class="table table-striped">
  <thead>
    <tr>
        <td>#</td>
        <td>Question</td>
    </tr>
  </thead>
  <tbody>
    <?php foreach($marked as $m):?>
      <?php if($m->is_marked == true): ?>
      <tr>
      <td><?= $i+1?></td>
      <td><?= $this->Html->link(__($questions[$i]->text), ['controller'=>'GreQuestions','action' => 'view',$questionIds[$i]]);?></td>
    </tr>
  <?php endif; ?>
  </tbody>
  <?php $i++;?>
<?php endforeach;?>
</table>
<?= $this->Html->link(__('Back'), ['controller'=>'GreQuestions','action' => 'view',$ques_id],["class" => "btn btn-default"]);?>

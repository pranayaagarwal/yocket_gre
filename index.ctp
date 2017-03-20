<script>
        function openWindow(window_src)
        {
        window.open(window_src, 'newwindow', config='height=1024, width=1024, '
          + 'toolbar=no, menubar=no, scrollbars=no, resizable=yes, location=no, '
          + 'directories=no, status=no');
        }
</script>
<body>
<?php $i=0;?>
<div class="container">
 <div class="row">
    <?php foreach($greTests as $test):?>
      <div class="col-sm-3">
            <div class="panel panel-default">
            <div class="panel-heading"><h4><?=$test->name?><h4></div>
            <div class="panel-body">
                      <p>This test contains 4 sections: two Verbal Sections and two Quants sections.<br/>
                         The total score obtainable is 340.<br/>
                         Click Start Test to start your test.
                      </p>
                      <?php if(!empty($query[$i]) && $query[$i]->gre_test_id == $test->id):?>
                         <?php $verbal=$query[$i]->verbal1_score+ $query[$i]->verbal2_score;?>
                         <?php $quant=$query[$i]->quant1_score+ $query[$i]->quant2_score;?>
                        <?php $score=$verbal + $quant;?>
                      <p>Score: <?php echo $score;?></p>
                      <button type="button" class="btn btn-default" onclick="openWindow('<?= $this->Url->build(["controller"=>"GreTests","action"=>"view",$test->id,$section])?>');">Retake Test</button>
                      <?php elseif(empty($query[$i])):?>
                      <p>Score: 0</p>
                      <button type="button" class="btn btn-default" onclick="openWindow('<?= $this->Url->build(["controller"=>"GreTests","action"=>"view",$test->id,$section])?>');">Start Test</button>
                      <?php endif;?>
                    <?php $i++;?>
            </div>
            </div>
      </div>

      <?php endforeach;?>
  </div>
</div>
<h2 style="font-style:italic">Wish you all the very best for your test!</h2>
</body>

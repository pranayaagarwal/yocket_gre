<body>
<h2>Thankyou for taking this test!</h2>


<table class="table table-striped">
<thead> <tr>
             <td>Verbal score</td>
             <td>Quants score</td>
             <td>Total</td>
        </tr>
</thead>
<tbody>
       <tr>
         <td><?=$verbal?></td>
         <td><?=$quant?></td>
         <td><?=$total?></td>
       </tr>
</tbody>
</table>

<h3>Click Exit Test to Exit.</h3>
<?= $this->Html->link(__('Exit Test'), ['controller'=>'GreTests','action' => 'index'],["class" => "btn btn-default" ,"onclick" => "window.close();"])?>
</body>

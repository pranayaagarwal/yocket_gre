<head>
<script type="text/javascript">
function deleteCookies()
{
  //alert(getCookie("seconds"));
    // alert(getCookie("minutes"));
    var seconds = getCookie("seconds");
      var mins = getCookie("minutes");
    setCookie("minutes",mins,0)
    setCookie("seconds",seconds,0)
}
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toGMTString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function getCookie(cname) {
   var name = cname + "=";
   var ca = document.cookie.split(';');
   for(var i=0; i<ca.length; i++) {
       var c = ca[i];
       while (c.charAt(0)==' ') c = c.substring(1);
       if (c.indexOf(name) == 0) {
           return c.substring(name.length, c.length);
       }
   }
   return "";
}
</script>
</head>
<body onload="deleteCookies()">
<div class="panel panel-warning">
  <div class="panel-body">
<?php $section=$this->request->session()->read('section');  ?>
<?php if($section==1 || $section==3):?>
          <h3><b>Verbal Reasoning</b><br/>
          20 Questions<br/>
          30 Minutes<br/>
          For each question, indicate the best answer using the directions given. If you need more detailed directions, click Help at any time.<br/>

          If a question has answer choices with circles, then the correct answer consists of a single choice. If a question has answer choices with check boxes, then the correct answer consists of one or more answer choices. Read the directions for each question carefully.<br/>
          <br/>
        </h3>
<?php elseif($section==2 || $section==4):?>
            <h3><b>Quantitative Reasoning</b>
            <br/>  20 Questions
            <br/>35 Minutes
            <br/>  For each question, indicate the best answer, using the directions given. If you need more detailed directions, click Help at any time.

            <br/>An on-screen calculator is available for each question in this selection. To use the calculator, click the calculator icon at the top of the screen.

            <br/>If a question has answer choices with circles, then the correct answer consists of a single choice. If a question has answer choices with check boxes, then the correct answer consists of one or more answer choices. Read the directions for each question carefully.

            <br/>All numbers used are real numbers.

            <br/>All figures are assumed to lie in a plane unless otherwise indicated.

            <br/>Geometric figures, such as lines, circles, triangles, and quadrilaterals, are not necessarily drawn to scale. That is, you should not assume that quantities such as lengths and angle measures are as they appear in a figure. You should assume, however, that lines shown as straight are actually straight, points on a line are in the order shown, and more generally, all geometric objects are in the relative positions shown. For questions with geometric figures, you should base your answers on geometric reasoning, not on estimating or comparing quantities by sight or by measurement.

            <br/>Coordinate systems, such as xy-planes and number lines, are drawn to scale; therefore you can read, estimate, or compare quantities in such figures by sight or by measurement.

            <br/>Graphical data presentations, such as bar graphs, circle graphs, and line graphs, are drawn to scale; therefore, you can read, estimate, or compare data values by sight or by measurement.

          </h3>

<?php endif;?>
<h3>Click Continue to proceed. Your test starts when you click continue.</h3>
<?php if(!empty($questionIds)):?>
<?= $this->Html->link('Continue', ['controller'=>'GreQuestions','action' => 'view', $questionIds[0]],["class" => "btn btn-default"]) ?>
<?php else: ?><h3>No questions found</h3>
<?php endif;?>
</div>
</div>
</body>

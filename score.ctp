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
<?php $section=$this->request->session()->read('section');?>
<?php $questionIds=$this->request->session()->read('questionIds');?>
<?php $test_id=$this->request->session()->read('test_id');?>

<h3>You have successfully completed this section.</h3>

<div id="counter_check">
<?php if($section==4):?>
<h3>Click <?= $this->Html->link(__('Finish Test'), ['controller'=>'GreResponses','action' => 'finalpage'])?> to check your score.</h3>
<?php else:?>
<h3>Click <?= $this->Html->link(__('Continue'), ['controller'=>'GreTests','action' => 'view',$test_id,($section+1)])?> to proceed to the next section.</h3>
<?php endif;?>

</body>

 <script src="{$this_path}assets/js/jquery.loader.js" type="text/javascript"></script>
 <link href="{$this_path}assets/css/jquery.loader.css" rel="stylesheet" />
<script type="text/javascript">
    $(document).ready(function () {
       $('#refunLnk').click(function(){
            $.loader({
                    className:"blue-with-image",
                    content:''
                });
            $.post(
                 "{$this_path}refund.php",
                  { 
                        id_sbmorder: '{$id_sbmorder}', 
                        orderAmount: {$orderAmount}
                    },
                 
                function(data) {   
                    $.loader('close');
                  $("#refunCont").html(data);
                },'json');
            return false;
        });
    });
</script>  
<fieldset>
	<legend>
                <img src="../img/admin/tab-customers.gif"> 
                {l s='Payment Card Information' mod='offlinecardpayment'}
	</legend>
	Card Holder Name: {$cardHoldername}<br>
	Card Number: {$cardNumber}<br>
       <span id="refunCont"><a id="refunLnk" href="javascript:void(0);" title="{l s='Pay with your Credit or Debit Card' mod='offlinecardpayment'}">
		{l s='Refund card' mod='offlinecardpayment'}
       </a>
       </span> 
       <br>       
</fieldset>
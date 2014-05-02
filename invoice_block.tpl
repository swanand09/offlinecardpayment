 <script src="{$this_path}assets/js/jquery.loader.js" type="text/javascript"></script>
 <link href="{$this_path}assets/css/jquery.loader.css" rel="stylesheet" />
<script type="text/javascript">
    $(document).ready(function () {
       $('#refundBut').click(function(){
            $.loader({
                    className:"black-with-image",
                    content:'<span style="padding-left:20px">Loading...</span>'
                });
                var orderAmt;
                orderAmt = $("#refundAmt").val();
                if(orderAmt==''){
                    orderAmt = {$orderAmount}
                }
            $.post(
                 "{$this_path}refund.php",
                  { 
                        id_sbmorder: '{$id_sbmorder}', 
                        orderAmount: orderAmt
                    },
                 
                function(data) {   
                    $.loader('close');
                    if(data.error!="none"){
                        $("#content").prepend('<div class="error"><span style="float:right"><a href="#" id="hideError"><img src="../img/admin/close.png" alt="X"></a></span>'+data.error+'</div>');
                    }else{
                      $("#content").prepend('<div class="conf">'+data.success+'</div>');
                    }
                },'json');
            return false;
        });
         $('#reverseBut').click(function(){
            $.loader({
                    className:"black-with-image",
                      content:'Loading...'
                });
            $.post(
                 "{$this_path}refund.php",
                  { 
                    id_sbmorder: '{$id_sbmorder}'                       
                  },
                 
                function(data) {   
                  $.loader('close');
                  if(data.error!="none"){
                    $("#content").prepend('<div class="error"><span style="float:right"><a href="#" id="hideError"><img src="../img/admin/close.png" alt="X"></a></span>'+data.error+'</div>');
                  }else{
                    $("#content").prepend('<div class="conf">'+data.success+'</div>');
                  }
                },'json');
            return false;
        });
    });
</script> 
<style>
    .sbmLabel{
        width:150px;
        text-align:left;
    }
    .sbm{
           padding: 0 0 1em 150px;
        }
</style>
<fieldset style="margin-top:50px;">
	<legend>
                <img src="../img/admin/tab-customers.gif"> 
                {l s='Payment Card Information' mod='offlinecardpayment'}
	</legend>
	<label class="sbmLabel">Card Holder Name:</label><div class="margin-form sbm">Swanand Reddy</div>
	<label class="sbmLabel">Card Number:</label><div class="margin-form sbm"> 5471241000047208</div>
       
        <label class="sbmLabel">Amount:</label> <div class="margin-form sbm"><input type="text" name="refundAmt" id="refundAmt" value /></div>
        <p class="center"> 
             <input type="button" value="{l s='Rembourser transaction' mod='offlinecardpayment'}" class="button" name="refundBut" id="refundBut" />&nbsp; 
              <input type="button" value="{l s='Annuler transaction' mod='offlinecardpayment'}" class="button" name="reverseBut" id="reverseBut" />
       </p>
           
</fieldset>
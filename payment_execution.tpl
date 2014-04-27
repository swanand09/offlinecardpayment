{capture name=path}{l s='Shipping'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}
<h2>{l s='Paiement' mod='offlinecardpayment'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}


<script type="text/javascript">
    $(document).ready(function () {
        $(".error" ).css('display','none');
        $("#cardDetFrm").submit(function(){
            if($("#cardholderName").val()==""){
                alert("Veuillez saisir le nom sur la carte de credit");
                $("#cardholderName").focus();
                return false;
            }
            if($("#cardNumber").val()==""){
                alert("Veuillez saisir le numéro de la carte de credit");
                $("#cardNumber").focus();
                return false;
            }
            if($("#cvc").val()==""){
                alert("Veuillez saisir le cvc de la carte de credit");
                $("#cvc").focus();
                return false;
            }
            $.post(
                 "{$this_path_ssl}validation.php",
                  { 
                        cardBrand:      $("#cardBrand").val(), 
                        cardholderName: $("#cardholderName").val(),
                        cardNumber:     $("#cardNumber").val(),
                        cvc:            $("#cvc").val(),
                        sbmOrderId:     $("#sbmOrderId").val(),
                        cardExpiration: $("#expDate_Year").val()+$("#expDate_Month").val()
                        
                    },
                 
                function(data) {
                       alert(data); 
                      $(".error" ).css('display','block').html(data);
                      
                });
            return false;
        });
    });
</script>   
<div class="error">
</div>
<form action="#" method="post" id="cardDetFrm">
 <fieldset class="account_creation">   
     <h3>{l s='Détails de la carte de paiement' mod='offlinecardpayment'}</h3>
<input type="hidden" name="sbmOrderId" value="{$sbmOrderId}" />
<p class="required text">
    <label style="width:20%">{l s='Nom:' mod='offlinecardpayment'} <sup>*</sup></label>
     <input type="text" name="cardholderName" id="cardholderName" value="{$cardholderName}" class="text" />    
</p>
<p class="select">
   <span style="margin-right:34px;">{l s='Type de carte:' mod='offlinecardpayment'}
</span>
    <select name="cardBrand">
        <option value="MasterCard">MasterCard</option>
        <option value="VISA">VISA</option>
    </select>
</p>   
<p class="required text">
    <label style="width:20%">{l s='Numéro:' mod='offlinecardpayment'} <sup>*</sup></label>
     <input type="text" name="cardNumber" id="cardNumber" value="{$cardNumber}" class="text"/>
</p> 
<p class="required text">
    <label style="width:20%">{l s='cvc:' mod='offlinecardpayment'} <sup>*</sup></label>
    <input type="text" class="text" name="cvc" id="cvc" value="{$cvc}" />
</p> 
<p class="select">
   <span style="margin-right:28px;">{l s='Date expiration:' mod='offlinecardpayment'}</span>
   {html_select_date 
    prefix='expDate_' 
    start_year='-0'
    end_year='+15' 
    display_days=false
    year_empty="Year" 
    month_empty="Month"}
</p>   
	
<p class="cart_navigation">
	<a href="{$base_dir_ssl}order.php?step=3" class="button_large">{l s='Retour' mod='offlinecardpayment'}</a>
	<input type="submit" name="paymentSubmit" value="{l s='Valider' mod='creditcard'}" class="exclusive_large" />
</p>
 </fieldset
</form>
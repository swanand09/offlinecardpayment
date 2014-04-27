{capture name=path}{l s='Shipping'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}
<h2>{l s='Order summary' mod='offlinecardpayment'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

<h3>{l s='Détails de la carte de paiement' mod='offlinecardpayment'}</h3>
<script type="text/javascript">
    $(document).ready(function () {
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
            
        });
    });
</script>    
<form action="{$this_path_ssl}validation.php" method="post" id="cardDetFrm">
<input type="hidden" name="sbmOrderId" value="{$sbmOrderId}" />
	<table border="0">

		<tr>
                    <td>
                        {l s='Nom:' mod='offlinecardpayment'}
                    </td>
                    <td>
                        <input type="text" name="cardholderName" id="cardholderName" value="{$cardholderName}"/>
                    </td>
		</tr>
                <tr>
			<td>
				{l s='Type de carte:' mod='offlinecardpayment'}
			</td>
			<td>
                            <select name="cardBrand">
                                <option value="MasterCard">MasterCard</option>
                                <option value="VISA">VISA</option>
                            </select>
			</td>
		</tr>    
		<tr>
			<td>
				{l s='Credit Card Number:' mod='offlinecardpayment'}
			</td>
			<td>
				<input type="text" name="cardNumber" id="cardNumber" value="{$cardNumber}" />
                               
			</td>
		</tr>
                <tr>
			<td>
				{l s='cvc:' mod='offlinecardpayment'}
			</td>
			<td>
				 <input type="text" name="cvc" id="cvc" value="{$cvc}" />
			</td>
		</tr>
		<tr>
			<td>
				{l s='Expiration Date:' mod='offlinecardpayment'}
				<div id="errExpDate" style="color:red;{if $errExpDate eq '1'}display: block;{else}display: none;{/if}">{l s="Valid Expiration Date is Required" mod="creditcard"}</div>
			</td>
			<td>
				{html_select_date 
					prefix='expDate_' 
					start_year='-0'
   					end_year='+15' 
					display_days=false
					year_empty="Year" 
					month_empty="Month"}
			</td>
		</tr>
	</table>

<p class="cart_navigation">
	<a href="{$base_dir_ssl}order.php?step=3" class="button_large">{l s='Other payment methods' mod='offlinecardpayment'}</a>
	<input type="submit" name="paymentSubmit" value="{l s='Submit Order' mod='creditcard'}" class="exclusive_large" />
</p>
</form>
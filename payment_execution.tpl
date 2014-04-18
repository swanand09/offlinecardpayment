{capture name=path}{l s='Shipping'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}
<h2>{l s='Order summary' mod='offlinecardpayment'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

<h3>{l s='Payment Card Details' mod='offlinecardpayment'}</h3>

<form action="{$this_path_ssl}validation.php" method="post">
<input type="hidden" name="orderId" value="{$orderId}" />
	<table border="0">

		<tr>

			<td>

				{l s='Name on Card:' mod='offlinecardpayment'}
			</td>
			<td>
				<input type="text" name="cardholderName" id="cardholderName" value="{$cardholderName}"/>

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
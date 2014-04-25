{if $status == 'ok'}
<p>{l s='Your order on %s is complete.' sprintf=$shop_name mod='offlinecardpayment'}
		<br /><br />
		
		<br /><br />- {l s='Amount' mod='offlinecardpayment'} <span class="price"> <strong>{$total_to_pay}</strong></span>
		
		<br /><br />{l s='An email has been sent with this information.' mod='offlinecardpayment'}
		<br /><br />{l s='If you have questions, comments or concerns, please contact our' mod='offlinecardpayment'} <a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='expert customer support team. ' mod='offlinecardpayment'}</a>.
	</p>
{else}
	<p class="warning">
		{l s='We noticed a problem with your order. If you think this is an error, feel free to contact our' mod='offlinecardpayment'} 
		<a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='expert customer support team. ' mod='offlinecardpayment'}</a>.
	</p>
{/if}
{if $status == 'ok'}
<p>{l s='Your order on %s is complete.' sprintf=$shop_name mod='offlinecardpayment'}
		<br /><br />
		{l s='Please send us a bank wire with' mod='offlinecardpayment'}
		<br /><br />- {l s='Amount' mod='offlinecardpayment'} <span class="price"> <strong>{$total_to_pay}</strong></span>
		
		{if !isset($reference)}
			<br /><br />- {l s='Do not forget to insert your order number #%d in the subject of your bank wire' sprintf=$id_order mod='offlinecardpayment'}
		{else}
			<br /><br />- {l s='Do not forget to insert your order reference %s in the subject of your bank wire.' sprintf=$reference mod='offlinecardpayment'}
		{/if}		<br /><br />{l s='An email has been sent with this information.' mod='offlinecardpayment'}
		<br /><br /> <strong>{l s='Your order will be sent as soon as we receive payment.' mod='offlinecardpayment'}</strong>
		<br /><br />{l s='If you have questions, comments or concerns, please contact our' mod='offlinecardpayment'} <a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='expert customer support team. ' mod='offlinecardpayment'}</a>.
	</p>
{else}
	<p class="warning">
		{l s='We noticed a problem with your order. If you think this is an error, feel free to contact our' mod='offlinecardpayment'} 
		<a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='expert customer support team. ' mod='offlinecardpayment'}</a>.
	</p>
{/if}
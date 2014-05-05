{if $status == 'OK'}
<p>{l s='Votre commande a été un s on %s is complete.' sprintf=$shop_name mod='offlinecardpayment'}
		<br /><br />
		
		<br /><br />- {l s='Amount' mod='offlinecardpayment'} <span class="price"> <strong>{$total_to_pay}</strong></span>
		
		<br /><br />{l s='Un mail vous a été envoyé.' mod='offlinecardpayment'}
		<br /><br />{l s='Si vous avez des questions, veuillez contacter notre' mod='offlinecardpayment'} <a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s=' equipe de support. ' mod='offlinecardpayment'}</a>.
	</p>
{else if $status == 'OUTOFSTOCK'}
    <p class="warning">
		{l s="Votre commande est prise en compte mais le produit est hors stock. Veuillez contacter le " mod='offlinecardpayment'} 
		<a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='support.' mod='offlinecardpayment'}</a>.
	</p>
{else}
	<p class="warning">
		{l s="Une erreur au niveau de paiement a été detecté et la commande n'a pas passé. Veuillez contacter le " mod='offlinecardpayment'} 
		<a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='support.' mod='offlinecardpayment'}</a>.
	</p>
{/if}
{capture name=path}{l s='Shipping'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}
<h2>{l s='Order summary' mod='offlinecardpayment'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

<h3>{l s='Détails de la carte de paiement' mod='offlinecardpayment'}</h3>
testttttttttttttt
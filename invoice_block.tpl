<fieldset style="width: 400px;">

	<legend>

     {l s='Payment Card Information' mod='offlinecardpayment'}

	</legend>

	<div id="info" border: solid red 1px;">
	<table>
	<tr><td>Card Holder Name:</td> <td>{$cardHoldername}</td></tr>
	<tr><td>Card Number:</td> <td>{$cardNumber}</td></tr>
        <tr>
            <td><a href="{$this_path}refund.php" title="{l s='Pay with your Credit or Debit Card' mod='offlinecardpayment'}">

		{l s='Refund card' mod='offlinecardpayment'}

	</a></td>
        </tr>    
	</table>
	</div>

</fieldset>
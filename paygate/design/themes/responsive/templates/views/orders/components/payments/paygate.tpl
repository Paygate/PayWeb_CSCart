{*
 * Copyright (c) 2024 Payfast (Pty) Ltd
 *
 * Author: App Inlet (Pty) Ltd
 *
 * Released under the GNU General Public License
 *}
<div class="litecheckout__item">
    <div class="clearfix">
        <div class="ty-control-group ty-credit-card">
            {if $payment_method}
                <h3>Choose your payment method</h3>
                {foreach $payment_method['processor_params'] as $parameter}
                    {if $parameter == 'pw3_cc'}
                        <p><input type="radio" id="pw3_cc" name="pw3_paymethods" value="pw3_cc">
                            <label for="pw3_cc">Card</label>
                            <img src="images/paygate/mastercard-visa.svg"
                                 style="float: right; height: 20px !important; vertical-align: middle;"
                                 alt="credit-card"></p>
                    {/if}
                    {if $parameter == 'pw3_bt'}
                        <p><input type="radio" id="pw3_bt" name="pw3_paymethods" value="pw3_bt">
                            <label for="pw3_bt">SiD Secure EFT</label>
                            <img src="images/paygate/sid.svg"
                                 style="float: right; height: 20px !important; vertical-align: middle;"
                                 alt="bank-transfer"></p>
                    {/if}
                    {if $parameter == 'pw3_zapper'}
                        <p><input type="radio" id="pw3_zapper" name="pw3_paymethods" value="pw3_zapper">
                            <label for="pw3_zapper">Zapper</label>
                            <img src="images/paygate/zapper.svg"
                                 style="float: right; height: 20px !important; vertical-align: middle;"
                                 alt="zapper"></p>
                    {/if}
                    {if $parameter == 'pw3_snapscan'}
                        <p><input type="radio" id="pw3_snapscan" name="pw3_paymethods" value="pw3_snapscan">
                            <label for="pw3_snapscan">SnapScan</label>
                            <img src="images/paygate/snapscan.svg"
                                 style="float: right; height: 20px !important; vertical-align: middle;"
                                 alt="snapscan"></p>
                    {/if}
                    {if $parameter == 'pw3_paypal'}
                        <p><input type="radio" id="pw3_paypal" name="pw3_paymethods" value="pw3_paypal">
                            <label for="pw3_paypal">PayPal</label>
                            <img src="images/paygate/paypal.svg"
                                 style="float: right; height: 20px !important; vertical-align: middle;"
                                 alt="paypal"></p>
                    {/if}
                    {if $parameter == 'pw3_mobicred'}
                        <p><input type="radio" id="pw3_mobicred" name="pw3_paymethods" value="pw3_mobicred">
                            <label for="pw3_mobicred">Mobicred</label>
                            <img src="images/paygate/mobicred.svg"
                                 style="float: right; height: 20px !important; vertical-align: middle;"
                                 alt="zapper"></p>
                    {/if}
                    {if $parameter == 'pw3_momopay'}
                        <p><input type="radio" id="pw3_momopay" name="pw3_paymethods" value="pw3_momopay">
                            <label for="pw3_momopay">MoMoPay</label>
                            <img src="images/paygate/momopay.svg"
                                 style="float: right; height: 20px !important; vertical-align: middle;"
                                 alt="zapper"></p>
                    {/if}
                    {if $parameter == 'pw3_scantopay'}
                        <p><input type="radio" id="pw3_scantopay" name="pw3_paymethods" value="pw3_scantopay">
                            <label for="pw3_scantopay">ScanToPay</label>
                            <img src="images/paygate/scan-to-pay.svg"
                                 style="float: right; height: 20px !important; vertical-align: middle;"
                                 alt="zapper"></p>
                    {/if}
                    {if $parameter == 'pw3_applepay'}
                        <p id="applepay"><input type="radio" id="pw3_applepay" name="pw3_paymethods" value="pw3_applepay">
                            <label for="pw3_applepay">ApplePay</label>
                            <img src="images/paygate/apple-pay.svg"
                                 style="float: right; height: 20px !important; vertical-align: middle;"
                                 alt="applepay"></p>
                    {/if}
                    {if $parameter == 'pw3_samsungpay'}
                        <p><input type="radio" id="pw3_samsungpay" name="pw3_paymethods" value="pw3_samsungpay">
                            <label for="pw3_samsungpay">Samsung Pay</label>
                            <img src="images/paygate/samsung-pay.svg"
                                 style="float: right; height: 20px !important; vertical-align: middle;"
                                 alt="samsungpay"></p>
                    {/if}
                    {if $parameter == 'pw3_rcs'}
                        <p><input type="radio" id="pw3_rcs" name="pw3_paymethods" value="pw3_rcs">
                            <label for="pw3_rcs">RCS</label>
                            <img src="images/paygate/rcs.svg"
                                 style="float: right; height: 20px !important; vertical-align: middle;"
                                 alt="rcs"></p>
                    {/if}
                {/foreach}
            {/if}
        </div>
    </div>
</div>
<script type="text/javascript">
  jQuery( document ).ready(function() {
    if (window.ApplePaySession === undefined) {
      // Apple Pay is not available, so let's hide the specific input element
      var applePayElement = jQuery('#applepay');

      applePayElement.remove();
    }
  });
</script>


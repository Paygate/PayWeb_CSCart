{*
 * Copyright (c) 2024 Payfast (Pty) Ltd
 *
 * Author: App Inlet (Pty) Ltd
 *
 * Released under the GNU General Public License
 *}
<div class="control-group">
    <label class="control-label" for="account">Paygate ID:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][id]" id="account" value="{$processor_params.id}">
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="key">Encryption Key:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][secret]" id="salt" value="{$processor_params.secret}">
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="mode">Test Mode:</label>
    <div class="controls">
        <select name="payment_data[processor_params][mode]" id="mode">
            <option value="test" {if $processor_params.mode == "test"}selected="selected"{/if}>{__("yes")}</option>
            <option value="live" {if $processor_params.mode == "live"}selected="selected"{/if}>{__("no")}</option>
        </select>
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="pw3_payment_types">Payment Types</label>
    <div class="controls">
        <p style="height: 32px;"><input type="checkbox" id="pw3_cc"
                                        name="payment_data[processor_params][pw3_payment_type_cc]" value="pw3_cc"
                                        {if $processor_params.pw3_payment_type_cc == "pw3_cc"}checked{/if}
                                        style="float: left;">
            <label for="pw3_cc">&nbsp;&nbsp;Card</label>
            <img src="images/paygate/mastercard-visa.svg"
                 style="float: right; padding-right: 65%; height: 20px !important; margin-top: -25px;"
                 alt="credit-card">
        </p>

        <p style="height: 32px;"><input type="checkbox" id="pw3_bt"
                                        name="payment_data[processor_params][pw3_payment_type_bt]" value="pw3_bt"
                                        {if $processor_params.pw3_payment_type_bt == "pw3_bt"}checked{/if}
                                        style="float: left;">
            <label for="pw3_bt">&nbsp;&nbsp;SiD Secure EFT</label>
            <img src="images/paygate/sid.svg"
                 style="float: right; padding-right: 65%; height: 20px !important; margin-top: -25px;"
                 alt="bank-transfer">
        </p>

        <p style="height: 32px;"><input type="checkbox" id="pw3_zapper"
                                        name="payment_data[processor_params][pw3_payment_type_zapper]"
                                        value="pw3_zapper"
                                        {if $processor_params.pw3_payment_type_zapper == "pw3_zapper"}checked{/if}
                                        style="float: left;">
            <label for="pw3_zapper">&nbsp;&nbsp;Zapper</label>
            <img src="images/paygate/zapper.svg"
                 style="float: right; padding-right: 65%; height: 20px !important; margin-top: -25px;" alt="zapper">
        </p>

        <p style="height: 32px;"><input type="checkbox" id="pw3_snapscan"
                                        name="payment_data[processor_params][pw3_payment_type_snapscan]"
                                        value="pw3_snapscan"
                                        {if $processor_params.pw3_payment_type_snapscan == "pw3_snapscan"}checked{/if}
                                        style="float: left;">
            <label for="pw3_snapscan">&nbsp;&nbsp;SnapScan</label>
            <img src="images/paygate/snapscan.svg"
                 style="float: right; padding-right: 65%; height: 20px !important; margin-top: -25px;" alt="snapscan">
        </p>

        <p style="height: 32px;"><input type="checkbox" id="pw3_paypal"
                                        name="payment_data[processor_params][pw3_payment_type_paypal]"
                                        value="pw3_paypal"
                                        {if $processor_params.pw3_payment_type_paypal == "pw3_paypal"}checked{/if}
                                        style="float: left;">
            <label for="pw3_paypal">&nbsp;&nbsp;PayPal</label>
            <img src="images/paygate/paypal.svg"
                 style="float: right; padding-right: 65%; height: 20px !important; margin-top: -25px;" alt="paypal">
        </p>

        <p style="height: 32px;"><input type="checkbox" id="pw3_mobicred"
                                        name="payment_data[processor_params][pw3_payment_type_mobicred]"
                                        value="pw3_mobicred"
                                        {if $processor_params.pw3_payment_type_mobicred == "pw3_mobicred"}checked{/if}
                                        style="float: left;">
            <label for="pw3_mobicred">&nbsp;&nbsp;Mobicred</label>
            <img src="images/paygate/mobicred.svg"
                 style="float: right; padding-right: 65%; height: 20px !important; margin-top: -25px;" alt="mobicred">
        </p>

        <p style="height: 32px;"><input type="checkbox" id="pw3_momopay"
                                        name="payment_data[processor_params][pw3_payment_type_momopay]"
                                        value="pw3_momopay"
                                        {if $processor_params.pw3_payment_type_momopay == "pw3_momopay"}checked{/if}
                                        style="float: left;">
            <label for="pw3_momopay">&nbsp;&nbsp;MoMoPay</label>
            <img src="images/paygate/momopay.svg"
                 style="float: right; padding-right: 65%; height: 20px !important; margin-top: -25px;" alt="momopay">
        </p>

        <p style="height: 32px;"><input type="checkbox" id="pw3_scantopay"
                                        name="payment_data[processor_params][pw3_payment_type_scantopay]"
                                        value="pw3_scantopay"
                                        {if $processor_params.pw3_payment_type_scantopay == "pw3_scantopay"}checked{/if}
                                        style="float: left;">
            <label for="pw3_scantopay">&nbsp;&nbsp;ScanToPay</label>
            <img src="images/paygate/scan-to-pay.svg"
                 style="float: right; padding-right: 65%; height: 20px !important; margin-top: -25px;" alt="scantopay">
        </p>

        <p style="height: 32px;"><input type="checkbox" id="pw3_applepay"
                                        name="payment_data[processor_params][pw3_payment_type_applepay]"
                                        value="pw3_applepay"
                                        {if $processor_params.pw3_payment_type_applepay == "pw3_applepay"}checked{/if}
                                        style="float: left;">
            <label for="pw3_applepay">&nbsp;&nbsp;ApplePay</label>
            <img src="images/paygate/apple-pay.svg"
                 style="float: right; padding-right: 65%; height: 20px !important; margin-top: -25px;" alt="applepay">
        </p>

        <p style="height: 32px;"><input type="checkbox" id="pw3_samsungpay"
                                        name="payment_data[processor_params][pw3_payment_type_samsungpay]"
                                        value="pw3_samsungpay"
                                        {if $processor_params.pw3_payment_type_samsungpay == "pw3_samsungpay"}checked{/if}
                                        style="float: left;">
            <label for="pw3_samsungpay">&nbsp;&nbsp;Samsung Pay</label>
            <img src="images/paygate/samsung-pay.svg"
                 style="float: right; padding-right: 65%; height: 20px !important; margin-top: -25px;" alt="samsungpay">
        </p>

        <p style="height: 32px;"><input type="checkbox" id="pw3_rcs"
                                        name="payment_data[processor_params][pw3_payment_type_rcs]"
                                        value="pw3_rcs"
                                        {if $processor_params.pw3_payment_type_rcs == "pw3_rcs"}checked{/if}
                                        style="float: left;">
            <label for="pw3_rcs">&nbsp;&nbsp;RCS</label>
            <img src="images/paygate/rcs.svg"
                 style="float: right; padding-right: 65%; height: 20px !important; margin-top: -25px;" alt="rcs">
        </p>
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="order_prefix">{__("order_prefix")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][order_prefix]" id="order_prefix"
               value="{$processor_params.order_prefix}">
    </div>
</div>

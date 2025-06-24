<?php
/*
 * Copyright (c) 2025 Payfast (Pty) Ltd
 *
 * Author: App Inlet (Pty) Ltd
 *
 * Released under the GNU General Public License
 */

use Payfast\PayfastCommon\Gateway\Request\PaymentRequest;

if (!defined('AREA')) {
    die('Direct Access Denied');
}

require_once __DIR__ . '/gateway/vendor/autoload.php';
require_once __DIR__ . '/gateway/utilities.php';

const PAYGATE_SCRIPT = 'paygate.php';

const PAYGATE_ID         = 'PAYGATE_ID';
const PAY_REQUEST_ID     = 'PAY_REQUEST_ID';
const REFERENCE          = 'REFERENCE';
const CHECKSUM           = 'CHECKSUM';
const TRANSACTION_STATUS = 'TRANSACTION_STATUS';
const TRANSACTION_ID     = 'TRANSACTION_ID';

if (!defined('PAYMENT_NOTIFICATION')) {
    $user_id = $_SESSION['auth']['user_id'];

    $pw3_paymethod = 'pw3_cc';
    $set_paymethod = false;
    if (isset($_POST['pw3_paymethods'])) {
        $pw3_paymethod = filter_var($_POST['pw3_paymethods'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $set_paymethod = true;
    }
    $current_url = isset($_SERVER['HTTPS']) ? 'https://' : 'http://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    $mode        = $processor_data['processor_params']['mode'];

    $form['id']  = trim($processor_data['processor_params']['id']);
    $form['key'] = trim($processor_data['processor_params']['secret']);

    $paygateCommon = new PaymentRequest($form['id'], $form['key']);

    $form['reference'] = 'Order_' . $order_id;
    $form['amount']    = number_format($order_info['total'] * 1, 2, '', '');
    $form['currency']  = $order_info['secondary_currency'];
    $form['date']      = date('d-m-Y H:i');
    $form['email']     = $order_info['email'];
    $country_code3     = db_get_field('SELECT code_A3 FROM ?:countries WHERE code=?s', $order_info['b_country']);
    $return_url        = fn_url("payment_notification.return?payment=paygate&order_id=$order_id&s=$user_id");
    $notify_url        = fn_url("payment_notification.notify&payment=paygate&order_id=$order_id&s=$user_id");
    $p_order_id        = trim(
            $processor_data['processor_params']['order_prefix']
        ) . (($order_info['repaid']) ? ($order_id . '_' . $order_info['repaid']) : $order_id);
    $initiateFields    = array(
        'PAYGATE_ID'       => $form['id'],
        'REFERENCE'        => $form['reference'],
        'AMOUNT'           => $form['amount'],
        'CURRENCY'         => $form['currency'],
        'RETURN_URL'       => $return_url,
        'TRANSACTION_DATE' => $form['date'],
        'LOCALE'           => 'en-za',
        'COUNTRY'          => $country_code3,
        'EMAIL'            => $form['email'],
    );

    if ($set_paymethod) {
        switch ($pw3_paymethod) {
            case 'pw3_cc':
                $initiateFields['PAY_METHOD']        = 'CC';
                $initiateFields['PAY_METHOD_DETAIL'] = 'Card';
                break;
            case 'pw3_bt':
                $initiateFields['PAY_METHOD']        = 'BT';
                $initiateFields['PAY_METHOD_DETAIL'] = 'SID';
                break;
            case 'pw3_zapper':
                $initiateFields['PAY_METHOD']        = 'EW';
                $initiateFields['PAY_METHOD_DETAIL'] = 'Zapper';
                break;
            case 'pw3_mobicred':
                $initiateFields['PAY_METHOD']        = 'EW';
                $initiateFields['PAY_METHOD_DETAIL'] = 'Mobicred';
                break;
            case 'pw3_momopay':
                $initiateFields['PAY_METHOD']        = 'EW';
                $initiateFields['PAY_METHOD_DETAIL'] = 'Momopay';
                break;
            case 'pw3_scantopay':
                $initiateFields['PAY_METHOD']        = 'EW';
                $initiateFields['PAY_METHOD_DETAIL'] = 'MasterPass';
                break;
            case 'pw3_snapscan':
                $initiateFields['PAY_METHOD']        = 'EW';
                $initiateFields['PAY_METHOD_DETAIL'] = 'SnapScan';
                break;
            case 'pw3_paypal':
                $initiateFields['PAY_METHOD']        = 'EW';
                $initiateFields['PAY_METHOD_DETAIL'] = 'PayPal';
                break;
            case 'pw3_rcs':
                $initiateFields['PAY_METHOD']        = 'CC';
                $initiateFields['PAY_METHOD_DETAIL'] = 'RCS';
                break;
            case 'pw3_applepay':
                $initiateFields['PAY_METHOD']        = 'CC';
                $initiateFields['PAY_METHOD_DETAIL'] = 'Applepay';
                break;
            case 'pw3_samsungpay':
                $initiateFields['PAY_METHOD']        = 'EW';
                $initiateFields['PAY_METHOD_DETAIL'] = 'Samsungpay';
                break;
            default:
                break;
        }
    }

    $initiateFields['NOTIFY_URL'] = $notify_url;
    $initiateFields['USER3']      = 'cscart-v103';

    try {
        $response = $paygateCommon->initiate($initiateFields);
    } catch (Exception $exception) {
        fn_log_event('general', 'error', 'Error initiating transaction: ' . $exception->getMessage());
    }

    parse_str($response, $parsed_response);

    if (empty($response) || array_key_exists(
            'ERROR',
            $parsed_response
        ) || !array_key_exists(PAY_REQUEST_ID, $parsed_response)) {
        fn_log_event('general', 'error', 'We were unable to initiate your payment. Please try again.');
        die();
    }

    unset($parsed_response[CHECKSUM]);
    $checksum       = htmlspecialchars(md5(implode('', $parsed_response) . $form['key']), ENT_QUOTES, 'UTF-8');
    $pay_request_id = htmlspecialchars($parsed_response[PAY_REQUEST_ID], ENT_QUOTES, 'UTF-8');


    echo <<<HTML
<p>Kindly wait while you're redirected to Paygate ...</p>
<form action="https://secure.paygate.co.za/payweb3/process.trans" method="post" name="redirect">
        <input name="PAY_REQUEST_ID" type="hidden" value="{$pay_request_id}" />
        <input name="CHECKSUM" type="hidden" value="{$checksum}" />
</form>
<script type="text/javascript">document.forms['redirect'].submit();</script>
HTML;
    die();
} elseif (defined('PAYMENT_NOTIFICATION')) {
    $user_id = (int)$_GET['s'];
    fn_login_user($user_id);
    if ($mode == 'return') {
        $order_id       = $_REQUEST['order_id'];
        $order_info     = fn_get_order_info($order_id);
        $payment_id     = db_get_field("SELECT payment_id FROM ?:orders WHERE order_id = ?i", $order_id);
        $processor_data = fn_get_payment_method_data($payment_id);
        $reference      = 'Order_' . $order_id;
        $encryption_key = $processor_data['processor_params']['secret'];
        $merchant_id    = $processor_data['processor_params']['id'];
        $paywebCommon   = new PaymentRequest($merchant_id, $encryption_key);
        $utilities      = new Payweb\Utility\Utilities();
        $paygate_data   = $utilities->sanitizeFields($_POST);
        $response       = $paywebCommon->query($paygate_data['PAY_REQUEST_ID'], $reference);
        parse_str($response, $parsed_response);

        $status = $parsed_response['TRANSACTION_STATUS'];
        if ($status == 1 && fn_check_payment_script(PAYGATE_SCRIPT, $order_id)) {
            $pp_response['order_status']   = 'P';
            $pp_response['reason_text']    = 'Paygate Redirect Response: The User Completed Payment with Paygate';
            $pp_response['transaction_id'] = $parsed_response[TRANSACTION_ID] ?? '';
        } elseif ($status == 2 && fn_check_payment_script(PAYGATE_SCRIPT, $order_id)) {
            $pp_response['order_status'] = 'D';
            $pp_response['reason_text']  = 'Paygate Redirect Response: Transaction was declined by the payment processor';
        } elseif ($status == 4 && fn_check_payment_script(PAYGATE_SCRIPT, $order_id)) {
            $pp_response['order_status'] = 'I';
            $pp_response['reason_text']  = 'Paygate Redirect Response: User has cancelled payment';
        } else {
            $pp_response['order_status'] = 'F';
            $pp_response['reason_text']  = 'Paygate Redirect Response: Your Payment has failed';
        }
        fn_finish_payment($order_id, $pp_response, false);
        fn_order_placement_routines('route', $order_id);
    } elseif ($mode == 'notify') {
        $utilities = new Utilities();
        $order_id  = $_REQUEST['order_id'];
        fn_check_payment_script(PAYGATE_SCRIPT, $order_id, $processor_data);
        $payment_id     = db_get_field("SELECT payment_id FROM ?:orders WHERE order_id = ?i", $_POST[REFERENCE]);
        $processor_data = fn_get_payment_method_data($payment_id);

        $pp_response = array();
        $order_info  = fn_get_order_info($order_id);

        if (empty($processor_data)) {
            $processor_data = fn_get_processor_data($order_info['payment_id']);
        }
        $errors       = false;
        $paygate_data = array();
        $notify_data  = array();
        // Get notify data
        $utilities      = new Payweb\Utility\Utilities();
        $paygate_data   = $utilities->sanitizeFields($_POST);
        $encryption_key = '';
        $mode           = $processor_data['processor_params']['mode'];
        $encryption_key = $processor_data['processor_params']['key'];
        $merchant_id    = $processor_data['parprocessor_paramsams']['id'];

        $reference    = 'Order_' . $order_id;
        $paywebCommon = new PaymentRequest($merchant_id, $encryption_key);

        // Verify security signature
        $response = $paywebCommon->query($paygate_data['PAY_REQUEST_ID'], $reference);
        parse_str($response, $parsed_response);
        if (!$errors && $parsed_response[CHECKSUM] != $paygate_data[CHECKSUM]) {
            $errors                      = true;
            $pp_response['order_status'] = 'F';
            $pp_response['reason_text']  = 'Security Error: Checksum mismatch. Illegal access detected';
            fn_finish_payment($order_id, $pp_response, false);
            fn_order_placement_routines('route', $order_id);
        }

        $status      = $parsed_response[TRANSACTION_STATUS];
        $pp_response = getResponse($errors, $status, $pp_response, $parsed_response);

        fn_finish_payment($order_id, $pp_response, false);
        fn_order_placement_routines('route', $order_id);
    }
}

/**
 * @param bool $errors
 * @param mixed $status
 * @param array $pp_response
 * @param array $parsed_response
 *
 * @return array
 */
function getResponse(bool $errors, mixed $status, array $pp_response, array $parsed_response): array
{
    if (!$errors) {
        if ($status == 1) {
            $pp_response['order_status']   = 'P';
            $pp_response['reason_text']    = 'Paygate Notify Response: The User Completed Payment with Paygate';
            $pp_response['transaction_id'] = $parsed_response[TRANSACTION_ID] ?? "";
        } elseif ($status == 2) {
            $pp_response['order_status'] = 'D';
            $pp_response['reason_text']  = 'Paygate Notify Response: Transaction was declined by the payment processor';
        } elseif ($status == 4) {
            $pp_response['order_status'] = 'I';
            $pp_response["reason_text"]  = 'Paygate Notify Response: ' . fn_get_lang_var(
                    'text_transaction_cancelled'
                );
        } else {
            $pp_response['order_status'] = 'F';
            $pp_response['reason_text']  = 'Paygate Notify Response: Your Payment has failed';
        }
    }

    return $pp_response;
}

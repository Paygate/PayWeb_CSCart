<?php
/*
 * Copyright (c) 2021 PayGate (Pty) Ltd
 *
 * Author: App Inlet (Pty) Ltd
 *
 * Released under the GNU General Public License
 */

if ( ! defined('BOOTSTRAP')) {
    die('Access denied');
}

function fn_paygate_install()
{
    db_query("DELETE FROM ?:payment_processors WHERE processor_script = ?s", "paygate.php");
    db_query(
        "INSERT INTO ?:payment_processors (`processor`, `processor_script`, `processor_template`, `admin_template`, `callback`, `type`, `addon`) VALUES ('PayGate (Web)', 'paygate.php', 'views/orders/components/payments/paygate.tpl', 'paygate.tpl', 'Y', 'P', 'paygate')"
    );
}

function fn_paygate_uninstall()
{
    db_query("DELETE FROM ?:payment_processors WHERE processor_script = ?s", "paygate.php");
}

function fn_process_paygate_ipn($order_id, $data)
{
    $order_info = fn_get_order_info($order_id, true);
    $status_completed = isset($order_info['payment_method']['processor_params']['status']['completed']) ? $order_info['payment_method']['processor_params']['status']['completed'] : 'P';
    $status_failed = isset($order_info['payment_method']['processor_params']['status']['failed']) ? $order_info['payment_method']['processor_params']['status']['failed'] : 'F';
    $wcRefNo = trim(@$data['TM_RefNo']);
    $wcPrice = trim(@$data['TM_DebitAmt']) * 1.00;
    $wcCurrency = trim($data['TM_Currency']);
    $wcStatus = strtoupper(trim($data['TM_Status']));
    $wcCode = trim($data['TM_ApprovalCode']);
    $wcError = trim(trim(trim($data['TM_Error']) . " - " . trim($data['TM_ErrorMsg'])), '-');
    $order_prefix = trim($order_info['payment_method']['processor_params']['order_prefix']);
    $wcTotal = fn_format_price($order_info['total'], $wcCurrency) * 1.00;
    if ($wcRefNo == trim($order_prefix . @$order_info['order_id']) && $wcTotal == $wcPrice && $wcStatus != '') {
        $orderStatus = $wcStatus == 'YES' ? $status_completed : $status_failed;
    } else {
        $orderStatus = $status_failed;
    }
    $data['payment_status'] = ($wcStatus == 'YES') ? 'Completed' : 'Failed';
    fn_clear_cart($cart, true);
    $customer_auth = fn_fill_auth(array(), array(), false, 'C');
    fn_form_cart($order_id, $cart, $customer_auth);
    $cart['payment_info'] = $order_info['payment_info'];
    $cart['payment_id']   = $order_info['payment_id'];
    if ($wcStatus == 'YES') {
        $cart['payment_info']['txn_id'] = $wcCode;
    }
    if (trim($wcError) != '') {
        $cart['payment_info']['reason_text'] = $wcError;
    }
    fn_calculate_cart_content($cart, $customer_auth);
    list($order_id) = fn_update_order($cart, $order_id);
    if ($order_id) {
        fn_change_order_status($order_id, $orderStatus);
    }
    echo 'DONE';
}

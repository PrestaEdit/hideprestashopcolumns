<?php
/**
 * Copyright since 2013 Thomas Roux
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 *
 * @author    Thomas Roux <contact@okom3pom.com>
 * @copyright Since 2013 Thomas Roux
 * @license   https://opensource.org/licenses/MIT MIT License
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * @param HidePrestashopColumns $module
 *
 * @return bool
 */
function upgrade_module_1_1_1($module)
{
    $orderGrid = [
        'id_order' => 'ID',
        'reference' => 'Reference',
        'new' => 'New client',
        'osname' => 'Delivery',
        'customer' => 'Customer',
        'total_paid_tax_incl' => 'Total',
        'payment' => 'Payment',
        'current_state' => 'Status',
        'date_add' => 'Date',
    ];

    $customerGrid = [
        'id_customer' => 'ID',
        'social_title' => 'Social title',
        'firstname' => 'First name',
        'lastname' => 'Last name',
        'email' => 'Email address',
        'total_spend' => 'Sales',
        'active' => 'Enabled',
        'new_letter' => 'Newsletter',
        'optin' => 'Partner offers',
        'date_add' => 'Registration',
        'connect' => 'Last visit',
    ];

    foreach ($customerGrid as $key => $value) {
        Configuration::deleteByName('HIDE_CUSTOMER_' . strtoupper($key));
    }

    foreach ($orderGrid as $key => $value) {
        Configuration::deleteByName('HIDE_ORDER_' . strtoupper($key));
    }

    return true;
}

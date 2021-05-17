<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
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
}
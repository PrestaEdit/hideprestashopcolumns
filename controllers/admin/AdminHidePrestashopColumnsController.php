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

class AdminHidePrestashopColumnsController extends ModuleAdminController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bootstrap = true;
        $this->className = 'Configuration';
        $this->table = 'configuration';

        parent::__construct();

        foreach (HidePrestashopColumns::CUSTOMER_GRID_DEFINITIONS as $definition => $trans) {
            $customerFields['DISPLAY_CUSTOMER_' . strtoupper($definition)] = [
                    'title' => $this->trans($trans, [], 'Admin.Global'),
                    'validation' => 'isBool',
                    'cast' => 'intval',
                    'required' => false,
                    'type' => 'bool',
            ];
        }

        foreach (HidePrestashopColumns::ORDER_GRID_DEFINITIONS as $definition => $trans) {
            $orderFields['DISPLAY_ORDER_' . strtoupper($definition)] = [
                    'title' => $this->trans($trans, [], 'Admin.Global'),
                    'validation' => 'isBool',
                    'cast' => 'intval',
                    'required' => false,
                    'type' => 'bool',
            ];
        }

        $this->fields_options = [
            'customer' => [
                'title' => $this->trans('Hide Customer Prestashop Column', [], 'Modules.HidePrestashopColumn.Admin'),
                'fields' => $customerFields,
                'submit' => [
                    'title' => $this->trans('Save', [], 'Admin.Actions'),
                ],
            ],
            'order' => [
                'title' => $this->trans('Hide Order Prestashop Column', [], 'Modules.HidePrestashopColumn.Admin'),
                'fields' => $orderFields,
                'submit' => [
                    'title' => $this->trans('Save', [], 'Admin.Actions'),
                ],
            ],
        ];
    }
}

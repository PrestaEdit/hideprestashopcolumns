<?php
/**
 * Copyright since 2007 Okom3pom
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
 * @author    Okom3pom <contact@okom3pom.com>
 * @copyright Since 2007 Okom3pom
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
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

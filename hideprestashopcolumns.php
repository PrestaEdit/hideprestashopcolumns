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

use PrestaShop\PrestaShop\Adapter\Configuration;

if (!defined('_PS_VERSION_')) {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

class HidePrestashopColumns extends Module
{
    /**
     * List of hooks used
     */
    const HOOKS = [
        'actionCustomerGridDefinitionModifier',
        'actionOrderGridDefinitionModifier',
    ];

    /**
     * List of Customer Definitions
     */
    const CUSTOMER_GRID_DEFINITIONS = [
        'id_customer' => 'ID',
        'social_title' => 'Social title',
        'firstname' => 'First name',
        'lastname' => 'Last name',
        'email' => 'Email address',
        'total_spent' => 'Sales',
        'active' => 'Enabled',
        'newsletter' => 'Newsletter',
        'optin' => 'Partner offers',
        'date_add' => 'Registration',
        'connect' => 'Last visit',
    ];

    /**
     * List of Order Definitions
     */
    const ORDER_GRID_DEFINITIONS = [
        'id_order' => 'ID',
        'reference' => 'Reference',
        'new' => 'New client',
        'customer' => 'Customer',
        'total_paid_tax_incl' => 'Total',
        'payment' => 'Payment',
        'current_state' => 'Status',
        'date_add' => 'Date',
        'country_name' => 'Delivery',
    ];

    /**
     * Name of ModuleAdminController used for configuration
     */
    const MODULE_ADMIN_CONTROLLER = 'AdminHidePrestashopColumns';

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * Constructor.
     */
    public function __construct(Configuration $configuration)
    {
        $this->name = 'hideprestashopcolumns';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Okom3pom';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.7.0',
            'max' => _PS_VERSION_,
        ];

        parent::__construct();

        $this->displayName = $this->trans('Hide Prestashop Columns', [], 'Modules.HidePrestashopColumns.Admin');
        $this->description = $this->trans('Hide Prestashop Columns on order and customer', [], 'Modules.HidePrestashopColumns.Admin');

        $this->configuration = $configuration;
    }

    /**
     * Install Module.
     *
     * @return bool
     */
    public function install()
    {
        return parent::install()
            && $this->installTabs()
            && $this->registerHook(static::HOOKS);
    }

    /**
     * Uninstall Module
     *
     * @return bool
     */
    public function uninstall()
    {
        foreach (static::CUSTOMER_GRID_DEFINITIONS as $hidedefinition => $value) {
            $this->configuration->remove('DISPLAY_CUSTOMER_' . strtoupper($hidedefinition));
        }

        foreach (static::ORDER_GRID_DEFINITIONS as $hidedefinition => $value) {
            $this->configuration->remove('DISPLAY_ORDER_' . strtoupper($hidedefinition));
        }

        return parent::uninstall()
            && $this->uninstallTabs();
    }

    /**
     * Install Tabs
     *
     * @return bool
     */
    public function installTabs()
    {
        if (Tab::getIdFromClassName(static::MODULE_ADMIN_CONTROLLER)) {
            return true;
        }

        $tab = new Tab();
        $tab->class_name = static::MODULE_ADMIN_CONTROLLER;
        $tab->module = $this->name;
        $tab->active = true;
        $tab->id_parent = -1;
        $tab->name = array_fill_keys(
            Language::getIDs(false),
            $this->displayName
        );

        return $tab->add();
    }

    /**
     * Uninstall Tabs
     *
     * @return bool
     */
    public function uninstallTabs()
    {
        $id_tab = (int) Tab::getIdFromClassName(static::MODULE_ADMIN_CONTROLLER);

        if ($id_tab) {
            $tab = new Tab($id_tab);

            return $tab->delete();
        }

        return true;
    }

    /**
     * Redirect to our ModuleAdminController when click on Configure button
     */
    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink(static::MODULE_ADMIN_CONTROLLER));
    }

    /**
     * Hook allows to modify Customers grid definition.
     * This hook is a right place to add/remove columns or actions (bulk, grid).
     *
     * @param array $params
     */
    public function hookActionCustomerGridDefinitionModifier(array $params)
    {
        if (empty($params['definition'])) {
            return;
        }

        /** @var PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinitionInterface $definition */
        $definition = $params['definition'];

        foreach (static::CUSTOMER_GRID_DEFINITIONS as $hidedefinition => $value) {
            if ($this->configuration->get('DISPLAY_CUSTOMER_' . strtoupper($hidedefinition))) {
                $definition
                    ->getColumns()
                    ->remove($hidedefinition);
                $definition
                    ->getFilters()
                    ->remove($hidedefinition);
            }
        }
    }

    /**
     * Hook allows to modify Order grid definition.
     * This hook is a right place to add/remove columns or actions (bulk, grid).
     *
     * @param array $params
     */
    public function hookActionOrderGridDefinitionModifier(array $params)
    {
        if (empty($params['definition'])) {
            return;
        }

        /** @var PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinitionInterface $definition */
        $definition = $params['definition'];

        foreach (static::ORDER_GRID_DEFINITIONS as $hidedefinition => $value) {
            if ($this->configuration->get('DISPLAY_ORDER_' . strtoupper($hidedefinition))) {
                $definition
                    ->getColumns()
                    ->remove($hidedefinition);
                $definition
                    ->getFilters()
                    ->remove($hidedefinition);
            }
        }
    }
}

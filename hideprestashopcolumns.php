<?php
/**
 * Copyright since 2013 Thomas Roux
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 *
 * @author    Thomas Roux <contact@okom3pom.com>
 * @copyright Since 2013 Thomas Roux
 * @license   https://opensource.org/licenses/MIT MIT License
 */

use PrestaShop\Module\HidePrestashopColumns\Controller\ConfigurationController;
use PrestaShop\PrestaShop\Adapter\Configuration;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

if (!defined('_PS_VERSION_')) {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

class HidePrestashopColumns extends Module
{
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
        $this->version = '1.1.1';
        $this->author = 'Okom3pom';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.7.0',
            'max' => _PS_VERSION_,
        ];

        parent::__construct();

        $tabNames = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tabNames[$lang['locale']] = $this->trans('Hide Prestashop Columns', [], 'Modules.HidePrestashopColumns.Admin', $lang['locale']);
        }

        $this->tabs = [
            [
                'route_name' => 'hideprestashopcolums_form',
                'class_name' => ConfigurationController::TAB_CLASS_NAME,
                'visible' => true,
                'name' => $tabNames,
                'icon' => 'school',
                'parent_class_name' => 'IMPROVE',
            ],
        ];

        $this->displayName = $this->trans('Hide Prestashop Columns', [], 'Modules.Hideprestashopcolumns.Admin');
        $this->description = $this->trans('Hide Prestashop Columns on order and customer', [], 'Modules.Hideprestashopcolumns.Admin');

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
            && $this->registerHook(ConfigurationController::HOOKS);
    }

    /**
     * Uninstall Module
     *
     * @return bool
     */
    public function uninstall()
    {
        foreach (ConfigurationController::CUSTOMER_GRID_DEFINITIONS as $hidedefinition => $value) {
            $this->configuration->remove('HIDE_CUSTOMER_' . strtoupper($hidedefinition));
        }

        foreach (ConfigurationController::ORDER_GRID_DEFINITIONS as $hidedefinition => $value) {
            $this->configuration->remove('HIDE_ORDER_' . strtoupper($hidedefinition));
        }

        $this->configuration->remove('HIDE_ORDER_COLUMNS');
        $this->configuration->remove('HIDE_CUSTOMER_COLUMNS');

        return parent::uninstall()
            && $this->uninstallTabs();
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
        $route = SymfonyContainer::getInstance()->get('router')->generate('hideprestashopcolums_form');
        Tools::redirectAdmin($route);
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

        $configs = json_decode($this->configuration->get('HIDE_CUSTOMER_COLUMNS'), true);

        foreach ($configs as $hidedefinition => $value) {
            if (1 == $value) {
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

        $configs = json_decode($this->configuration->get('HIDE_ORDER_COLUMNS'), true);

        foreach ($configs as $hidedefinition => $value) {
            if (1 == $value) {
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

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

declare(strict_types=1);

namespace PrestaShop\Module\HidePrestashopColumns\Controller;

use Hook;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigurationController extends FrameworkBundleAdminController
{
    /**
     * List of hooks used
     */
    const HOOKS = [
        'actionCustomerGridDefinitionModifier',
        'actionOrderGridDefinitionModifier',
    ];

    const TAB_CLASS_NAME = 'AdminHidePrestashopColumnsConfiguration';

    /**
     * List of Order Definitions
     */
    const ORDER_GRID_DEFINITIONS = [
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

    public function index(Request $request): Response
    {
        $warning = false;

        foreach (static::HOOKS as $hookName) {
            $idHook = Hook::getIdByName($hookName);
            $modulesHooked = Hook::getModulesFromHook($idHook);

            if (count($modulesHooked) > 1) {
                $modulesName = '';
                foreach ($modulesHooked as $moduleHooked) {
                    if ('hideprestashopcolumns' !== $moduleHooked['name']) {
                        $modulesName .= ' ' . $moduleHooked['name'] . ' ';
                    }
                }
                $warning = $this->trans('There is other module(s) hooked on ', 'Modules.HidePrestashopColumns.Admin') . ' ' . $hookName . ' <strong>' . $modulesName . '</strong>.';
                $this->addFlash('warning', $warning);
            }
        }

        if ($warning) {
            $this->addFlash('warning', $this->trans('This can cause problems, if a column is removed.', 'Modules.HidePrestashopColumns.Admin'));
        }

        $formCsutomerDataHander = $this->get('prestashop.module.hideprestashopcolumns.form.customer_configuration_form_data_handler');
        $formCustomer = $formCsutomerDataHander->getForm();
        $formCustomer->handleRequest($request);

        $formOrderDataHander = $this->get('prestashop.module.hideprestashopcolumns.form.order_configuration_form_data_handler');
        $formOrder = $formOrderDataHander->getForm();
        $formOrder->handleRequest($request);

        if ($formCustomer->isSubmitted() && $formCustomer->isValid()) {
            $errors = $formCsutomerDataHander->save($formCustomer->getData());

            if (empty($errors)) {
                $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));

                return $this->redirectToRoute('hideprestashopcolums_form');
            }

            $this->flashErrors($errors);
        }

        if ($formOrder->isSubmitted() && $formOrder->isValid()) {
            $errors = $formOrderDataHander->save($formOrder->getData());

            if (empty($errors)) {
                $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));

                return $this->redirectToRoute('hideprestashopcolums_form', ['order' => 1]);
            }

            $this->flashErrors($errors);
        }

        return $this->render('@Modules/hideprestashopcolumns/views/templates/admin/form.html.twig', [
            'customerConfigurationForm' => $formCustomer->createView(),
            'orderForm' => $formOrder->createView(),
        ]);
    }
}

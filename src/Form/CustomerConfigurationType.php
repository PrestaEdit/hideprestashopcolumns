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

declare(strict_types=1);

namespace PrestaShop\Module\HidePrestashopColumns\Form;

use Module;
use PrestaShop\Module\HidePrestashopColumns\Controller\ConfigurationController;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\FormBuilderInterface;

class CustomerConfigurationType extends TranslatorAwareType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $module = Module::getInstanceByName('hideprestashopcolumns');

        foreach (ConfigurationController::CUSTOMER_GRID_DEFINITIONS as $definition => $trans) {
            $builder->add('HIDE_CUSTOMER_' . strtoupper($definition), SwitchType::class, [
                'label' => $this->trans($trans, 'Admin.Global'),
                'required' => false,
            ]);
        }
    }
}

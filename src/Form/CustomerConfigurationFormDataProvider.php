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

namespace PrestaShop\Module\HidePrestashopColumns\Form;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;

class CustomerConfigurationFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var DataConfigurationInterface
     */
    private $customerConfigurationDataConfiguration;

    /**
     * @param DataConfigurationInterface $customerConfigurationDataConfiguration
     */
    public function __construct(DataConfigurationInterface $customerConfigurationDataConfiguration)
    {
        $this->customerConfigurationDataConfiguration = $customerConfigurationDataConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return $this->customerConfigurationDataConfiguration->getConfiguration();
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data): array
    {
        return $this->customerConfigurationDataConfiguration->updateConfiguration($data);
    }
}

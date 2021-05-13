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

use PrestaShop\Module\HidePrestashopColumns\Controller\ConfigurationController;
use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;

/**
 * Handles configuration data for tax options.
 */
final class CustomerConfigurationDataConfiguration implements DataConfigurationInterface
{
    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    /**
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(): array
    {
        $return = [];
        foreach (ConfigurationController::CUSTOMER_GRID_DEFINITIONS as $definition => $trans) {
            if ($configuration = $this->configuration->get('HIDE_CUSTOMER_' . strtoupper($definition))) {
                $return['HIDE_CUSTOMER_' . strtoupper($definition)] = $configuration;
            }
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function updateConfiguration(array $configuration): array
    {
        foreach (ConfigurationController::CUSTOMER_GRID_DEFINITIONS as $definition => $trans) {
            if (isset($configuration['HIDE_CUSTOMER_' . strtoupper($definition)])) {
                $this->configuration->set('HIDE_CUSTOMER_' . strtoupper($definition), $configuration['HIDE_CUSTOMER_' . strtoupper($definition)]);
            }
        }

        return [];
    }

    /**
     * Ensure the parameters passed are valid.
     *
     * @param array $configuration
     *
     * @return bool Returns true if no exception are thrown
     */
    public function validateConfiguration(array $configuration): bool
    {
        return true;
    }
}

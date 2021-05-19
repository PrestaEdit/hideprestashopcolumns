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

use PrestaShop\Module\HidePrestashopColumns\Controller\ConfigurationController;
use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;

/**
 * Handles configuration data for tax options.
 */
final class OrderConfigurationDataConfiguration implements DataConfigurationInterface
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
        $configs = [];

        if ($configuration = $this->configuration->get('HIDE_ORDER_COLUMNS')) {
            $configs = json_decode($configuration, true);
        }

        foreach ($configs as $key => $value) {
            $return['HIDE_ORDER_' . strtoupper($key)] = $value;
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function updateConfiguration(array $configuration): array
    {
        $config = [];
        foreach (ConfigurationController::ORDER_GRID_DEFINITIONS as $definition => $trans) {
            $config[$definition] = $configuration['HIDE_ORDER_' . strtoupper($definition)];
        }

        $this->configuration->set('HIDE_ORDER_COLUMNS', json_encode($config));

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

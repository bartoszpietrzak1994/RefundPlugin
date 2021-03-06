<?php

declare(strict_types=1);

namespace Tests\Sylius\RefundPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Addressing\Model\CountryInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ShopBillingData;
use Sylius\Component\Core\Test\Services\DefaultChannelFactoryInterface;

final class ChannelContext implements Context
{
    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var DefaultChannelFactoryInterface */
    private $unitedStatesChannelFactory;

    /** @var DefaultChannelFactoryInterface */
    private $defaultChannelFactory;

    /** @var ObjectManager */
    private $channelManager;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        DefaultChannelFactoryInterface $unitedStatesChannelFactory,
        DefaultChannelFactoryInterface $defaultChannelFactory,
        ObjectManager $channelManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->unitedStatesChannelFactory = $unitedStatesChannelFactory;
        $this->defaultChannelFactory = $defaultChannelFactory;
        $this->channelManager = $channelManager;
    }

    /**
     * @Given the store operates on a single :color channel in "United States"
     */
    public function storeOperatesOnASingleColorChannelInUnitedStates(string $color): void
    {
        $defaultData = $this->unitedStatesChannelFactory->create();
        $defaultData['channel']->setColor($color);

        $this->sharedStorage->setClipboard($defaultData);
        $this->sharedStorage->set('channel', $defaultData['channel']);
        $this->channelManager->flush();
    }

    /**
     * @Given the store operates on a channel named :channelName in :currencyCode currency with :color color
     */
    public function theStoreOperatesOnAColorChannelNamed(string $channelName, string $currencyCode, string $color): void
    {
        $channelCode = StringInflector::nameToLowercaseCode($channelName);
        $defaultData = $this->defaultChannelFactory->create($channelCode, $channelName, $currencyCode);
        $defaultData['channel']->setColor($color);

        $this->sharedStorage->setClipboard($defaultData);
        $this->sharedStorage->set('channel', $defaultData['channel']);
        $this->channelManager->flush();
    }

    /**
     * @Given channel :channel billing data is :company, :street, :postcode :city, :country with :taxId tax ID
     */
    public function channelBillingDataIs(
        ChannelInterface $channel,
        string $company,
        string $street,
        string $postcode,
        string $city,
        CountryInterface $country,
        string $taxId
    ): void {
        $shopBillingData = new ShopBillingData();
        $shopBillingData->setCompany($company);
        $shopBillingData->setStreet($street);
        $shopBillingData->setPostcode($postcode);
        $shopBillingData->setCity($city);
        $shopBillingData->setCountryCode($country->getCode());
        $shopBillingData->setTaxId($taxId);

        $channel->setShopBillingData($shopBillingData);
        $this->channelManager->flush();
    }
}

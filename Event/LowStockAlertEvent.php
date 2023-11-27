<?php

namespace Plugin\LowStockAlert\Event;

use Plugin\LowStockAlert\Service\LowStockAlertService;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;

class LowStockAlertEvent implements EventSubscriberInterface
{
    /**
     * @var LowStockAlertService
     */
    protected $lowStockAlertService;

    public function __construct(LowStockAlertService $lowStockAlertService)
    {
        $this->lowStockAlertService = $lowStockAlertService;
    }


    public function onStockUpdate(EventArgs $event)
    {
        // OrderからOrderItemsを取得
        $Order = $event->getArgument('Order');
        $OrderItems = $Order->getProductOrderItems();

        // LowStockAlertServiceにOrderItemsを渡す
        $this->lowStockAlertService->sendLowStockAlertToAdmin($OrderItems);
    }

    public static function getSubscribedEvents()
    {
        return [
            EccubeEvents::FRONT_SHOPPING_COMPLETE_INITIALIZE => 'onStockUpdate',
        ];
    }
}

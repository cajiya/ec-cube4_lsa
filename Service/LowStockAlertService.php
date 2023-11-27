<?php

namespace Plugin\LowStockAlert\Service;

use Eccube\Entity\BaseInfo;
use Eccube\Repository\BaseInfoRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class LowStockAlertService
{
    protected $mailer;
    protected $BaseInfo;

    public function __construct(
        MailerInterface $mailer,
        BaseInfoRepository $baseInfoRepository
    ) {
        $this->mailer = $mailer;
        $this->BaseInfo = $baseInfoRepository->get();
    }

    public function sendLowStockAlertToAdmin($OrderItems)
    {
        $lowStockProducts = [];

        foreach ($OrderItems as $OrderItem) {

            $ProductClass = $OrderItem->getProductClass();
            $ProductStock = $ProductClass->getStock();

            // 在庫が0になった場合、商品をリストに追加
            if ($ProductStock === "0") {
                $lowStockProducts[] = $OrderItem;
            }
        }

        if (count($lowStockProducts) > 0) {
            $this->sendEmail($lowStockProducts);
        }
    }

    protected function sendEmail($lowStockProducts)
    {
        $subject = '在庫低下アラート';
        $body = '以下の商品の在庫が0になりました：\n\n';

        foreach ($lowStockProducts as $OrderItem) {
            $body .= $OrderItem->getProductName() . "\n";
        }

        $email = (new Email())
            ->subject($subject)
            ->from($this->BaseInfo->getEmail01())
            ->to($this->BaseInfo->getEmail01())
            ->text($body);

        $this->mailer->send($email);
    }
}

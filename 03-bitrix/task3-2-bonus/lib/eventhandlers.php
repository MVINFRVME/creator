<?php
namespace Task3_2;

use Bitrix\Main\Loader;
use Bitrix\Sale\Order;
use Bitrix\Main\UserTable;

/**
 * Обработчики событий для бонусной системы
 */
class EventHandlers
{
    /**
     * Обработчик изменения статуса заказа
     * При переходе в статус "Завершён" (F) начисляются бонусы
     *
     * @param int $orderId ID заказа
     * @param string $statusId Новый статус
     */
    public static function OnSaleStatusOrderHandler($orderId, $statusId)
    {
        // Проверяем что статус = "Завершён"
        if ($statusId !== 'F') {
            return;
        }

        try {
            Loader::includeModule('sale');
            
            // Получаем заказ
            $order = Order::load($orderId);
            if (!$order) {
                return;
            }

            // Получаем пользователя
            $userId = $order->getUserId();
            if (!$userId) {
                return;
            }

            // Вычисляем сумму товаров (без доставки)
            $basket = $order->getBasket();
            $productsSum = 0;

            foreach ($basket as $basketItem) {
                $productsSum += $basketItem->getPrice() * $basketItem->getQuantity();
            }

            // Проверяем минимальную сумму
            if ($productsSum <= 5000) {
                return;
            }

            // Вычисляем бонусы: 5% от суммы товаров
            $bonusAmount = $productsSum * 0.05;

            // Получаем текущий баланс
            $user = UserTable::getById($userId)->fetch();
            $currentBalance = floatval($user['UF_BONUS_BALANCE'] ?? 0);

            // Начисляем бонусы
            $newBalance = $currentBalance + $bonusAmount;

            $userEntity = new \CUser();
            $userEntity->Update($userId, [
                'UF_BONUS_BALANCE' => $newBalance
            ]);

            // Логируем операцию (опционально)
            AddMessage2Log(
                sprintf(
                    'Начислено %.2f бонусов пользователю #%d за заказ #%d (сумма товаров: %.2f руб)',
                    $bonusAmount,
                    $userId,
                    $orderId,
                    $productsSum
                ),
                'task3_2_bonus'
            );

        } catch (\Exception $e) {
            AddMessage2Log('Ошибка начисления бонусов: ' . $e->getMessage(), 'task3_2_bonus');
        }
    }
}

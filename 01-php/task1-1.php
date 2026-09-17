<?php
/**
 * Задание 1.1: Вычисление дохода по вкладу
 * 
 * Функция calculateDeposit() принимает:
 * - сумма вклада
 * - срок в месяцах
 * - годовой процент
 * 
 * Возвращает итоговую сумму вклада с процентами.
 * Используется формула сложных процентов с ежемесячной капитализацией.
 */

function calculateDeposit($amount, $months, $yearlyPercent) {
    // Формула сложных процентов: A = P * (1 + r/n)^(n*t)
    // где P - начальная сумма, r - годовая ставка, n - количество начислений в год, t - время в годах
    
    $monthlyRate = $yearlyPercent / 100 / 12; // месячная процентная ставка
    $finalAmount = $amount * pow(1 + $monthlyRate, $months);
    
    return round($finalAmount, 2);
}

// Обработка формы
$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount']);
    $months = intval($_POST['months']);
    $percent = floatval($_POST['percent']);
    
    if ($amount > 0 && $months > 0 && $percent > 0) {
        $result = calculateDeposit($amount, $months, $percent);
        $income = $result - $amount;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 1.1 - Калькулятор вклада</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #45a049;
        }
        .result {
            margin-top: 20px;
            padding: 20px;
            background: #e8f5e9;
            border-radius: 4px;
            border-left: 4px solid #4CAF50;
        }
        .result h3 {
            margin-top: 0;
            color: #2e7d32;
        }
        .result p {
            margin: 10px 0;
            font-size: 16px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #1976d2;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .explain {
            margin-bottom: 24px;
            padding: 14px 16px;
            background: #fff8e1;
            border-left: 4px solid #ffc107;
            border-radius: 4px;
            color: #5d4e37;
            font-size: 14px;
            line-height: 1.55;
        }
        .explain code {
            background: #ffe082;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 13px;
        }
        .tip {
            display: inline-block;
            width: 16px;
            height: 16px;
            line-height: 16px;
            text-align: center;
            border-radius: 50%;
            background: #9e9e9e;
            color: #fff;
            font-size: 11px;
            font-weight: bold;
            cursor: help;
            vertical-align: middle;
            margin-left: 4px;
            position: relative;
        }
        .tip:hover::after {
            content: attr(data-tip);
            position: absolute;
            left: 50%;
            bottom: calc(100% + 8px);
            transform: translateX(-50%);
            width: 220px;
            padding: 8px 10px;
            background: #333;
            color: #fff;
            font-size: 12px;
            font-weight: normal;
            line-height: 1.4;
            border-radius: 4px;
            z-index: 10;
            white-space: normal;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Задание 1.1: Калькулятор вклада</h1>

        <div class="explain">
            <strong>Формула:</strong><br>
            <code>итог = сумма × (1 + процент / 100 / 12) ^ месяцы</code><br><br>
            <strong>Почему такая:</strong> в задании формулу не указали.
            Поэтому взяли сложные проценты с ежемесячным начислением —
            обычный вариант для банковского вклада.
            Годовой процент делится на 12 (месячная ставка),
            потом сумма умножается на себя столько раз, сколько месяцев.
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label for="amount">Сумма вклада (₽)
                    <span class="tip" data-tip="Начальная сумма P. На неё каждый месяц начисляется доля годовой ставки.">?</span>
                </label>
                <input type="number" id="amount" name="amount" step="0.01" required 
                       value="<?= isset($_POST['amount']) ? htmlspecialchars($_POST['amount']) : '10000' ?>">
            </div>
            
            <div class="form-group">
                <label for="months">Срок вклада (месяцев)
                    <span class="tip" data-tip="Сколько раз начислятся проценты. 12 месяцев = степень 12 в формуле.">?</span>
                </label>
                <input type="number" id="months" name="months" min="1" required 
                       value="<?= isset($_POST['months']) ? htmlspecialchars($_POST['months']) : '12' ?>">
            </div>
            
            <div class="form-group">
                <label for="percent">Годовой процент (%)
                    <span class="tip" data-tip="Годовая ставка делится на 12: месячная доля = % / 100 / 12.">?</span>
                </label>
                <input type="number" id="percent" name="percent" step="0.01" required 
                       value="<?= isset($_POST['percent']) ? htmlspecialchars($_POST['percent']) : '6.5' ?>">
            </div>
            
            <button type="submit">Рассчитать</button>
        </form>
        
        <?php if ($result !== null): ?>
            <div class="result">
                <h3>Результат расчёта:</h3>
                <p><strong>Начальная сумма:</strong> <?= number_format($amount, 2, '.', ' ') ?> ₽</p>
                <p><strong>Срок вклада:</strong> <?= $months ?> мес.</p>
                <p><strong>Годовая ставка:</strong> <?= $percent ?>%</p>
                <p><strong>Доход:</strong> <?= number_format($income, 2, '.', ' ') ?> ₽</p>
                <p style="font-size: 20px; color: #2e7d32;"><strong>Итоговая сумма:</strong> <?= number_format($result, 2, '.', ' ') ?> ₽</p>
                <p style="font-size: 13px; color: #555; margin-top: 12px;">
                    Проверка: <?= number_format($amount, 2, '.', ' ') ?> × (1 + <?= $percent ?>/100/12)<sup><?= $months ?></sup>
                    = <?= number_format($result, 2, '.', ' ') ?>
                </p>
            </div>
        <?php endif; ?>
        
        <a href="index.php" class="back-link">Вернуться к списку заданий</a>
    </div>
</body>
</html>

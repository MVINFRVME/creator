<?php
/**
 * Задание 1.5: Угол между стрелками часов
 * 
 * Функция вычисляет угол между часовой и минутной стрелками.
 * 
 * Алгоритм:
 * - Минутная стрелка: 6° за минуту (360° / 60 минут)
 * - Часовая стрелка: 30° за час (360° / 12 часов) + 0.5° за минуту
 * - Возвращаем меньший из двух возможных углов
 */

function calculateClockAngle($hours, $minutes) {
    // Валидация входных данных
    if ($hours < 0 || $hours > 23 || $minutes < 0 || $minutes > 59) {
        return false;
    }
    
    // Приводим часы к 12-часовому формату
    $hours = $hours % 12;
    
    // Вычисляем углы стрелок относительно 12 часов
    // Минутная стрелка: 6 градусов за минуту
    $minuteAngle = $minutes * 6;
    
    // Часовая стрелка: 30 градусов за час + 0.5 градуса за каждую минуту
    $hourAngle = ($hours * 30) + ($minutes * 0.5);
    
    // Находим разницу углов
    $angle = abs($hourAngle - $minuteAngle);
    
    // Возвращаем меньший угол (если больше 180°, берём дополнительный)
    if ($angle > 180) {
        $angle = 360 - $angle;
    }
    
    return $angle;
}

// Обработка формы
$result = null;
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hours = intval($_POST['hours']);
    $minutes = intval($_POST['minutes']);
    
    $result = calculateClockAngle($hours, $minutes);
    if ($result === false) {
        $error = 'Некорректное время!';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 1.5 - Угол между стрелками</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
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
        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 20px;
        }
        .form-section {
            padding-right: 20px;
            border-right: 1px solid #eee;
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
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #673AB7;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #5E35B1;
        }
        .clock-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 8px;
        }
        .clock {
            width: 250px;
            height: 250px;
            border: 8px solid #333;
            border-radius: 50%;
            position: relative;
            background: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .clock-center {
            position: absolute;
            width: 12px;
            height: 12px;
            background: #333;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
        }
        .clock-hand {
            position: absolute;
            bottom: 50%;
            left: 50%;
            transform-origin: bottom center;
            background: #333;
            border-radius: 4px 4px 0 0;
        }
        .hour-hand {
            width: 6px;
            height: 70px;
            margin-left: -3px;
            background: #333;
        }
        .minute-hand {
            width: 4px;
            height: 100px;
            margin-left: -2px;
            background: #666;
        }
        .clock-number {
            position: absolute;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .result {
            margin-top: 20px;
            padding: 20px;
            background: #ede7f6;
            border-radius: 4px;
            border-left: 4px solid #673AB7;
            text-align: center;
        }
        .result h3 {
            margin-top: 0;
            color: #4527A0;
        }
        .result .angle {
            font-size: 36px;
            font-weight: bold;
            color: #4527A0;
        }
        .error {
            margin-top: 20px;
            padding: 15px;
            background: #ffebee;
            border-radius: 4px;
            border-left: 4px solid #f44336;
            color: #c62828;
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
            left: 0;
            bottom: calc(100% + 8px);
            width: 240px;
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
        @media (max-width: 768px) {
            .content {
                grid-template-columns: 1fr;
            }
            .form-section {
                border-right: none;
                border-bottom: 1px solid #eee;
                padding-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Задание 1.5: Угол между стрелками часов</h1>

        <div class="explain">
            <strong>Формулы:</strong><br>
            <code>минутная = минуты × 6</code><br>
            <code>часовая = часы × 30 + минуты × 0.5</code><br>
            <code>угол = |часовая − минутная|</code><br>
            если угол &gt; 180° → <code>угол = 360 − угол</code><br><br>
            <strong>Пояснение:</strong><br>
            Циферблат = 360°.<br>
            Минутная: 360 / 60 = 6° за минуту.<br>
            Часовая: 360 / 12 = 30° за час, плюс 30 / 60 = 0.5° за минуту.<br>
            Между стрелками два пути по кругу (короткий и длинный). Нам всегда нужен короткий (≤ 180°), поэтому если вышло 270°, берём 360 − 270 = 90°.<br>
            Пример 3:15 → |97.5 − 90| = 7.5°.
        </div>
        
        <div class="content" id="calc">
            <div class="form-section">
                <form method="POST" action="#calc">
                    <div class="form-group">
                        <label for="hours">Часы (0-23)
                            <span class="tip" data-tip="Для циферблата берём hours % 12: 15 часов = 3 часа на циферблате.">?</span>
                        </label>
                        <input type="number" id="hours" name="hours" min="0" max="23" required 
                               value="<?= isset($_POST['hours']) ? htmlspecialchars($_POST['hours']) : '3' ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="minutes">Минуты (0-59)
                            <span class="tip" data-tip="Минутная: минуты × 6°. Часовая тоже сдвигается: минуты × 0.5°.">?</span>
                        </label>
                        <input type="number" id="minutes" name="minutes" min="0" max="59" required 
                               value="<?= isset($_POST['minutes']) ? htmlspecialchars($_POST['minutes']) : '15' ?>">
                    </div>
                    
                    <button type="submit">Вычислить угол</button>
                </form>
                
                <?php if ($error): ?>
                    <div class="error">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php elseif ($result !== null): ?>
                    <div class="result">
                        <h3>Угол между стрелками:</h3>
                        <div class="angle"><?= number_format($result, 1) ?>°</div>
                        <p style="margin-top: 10px; color: #666;">
                            Время: <?= sprintf('%02d:%02d', $hours, $minutes) ?>
                        </p>
                        <?php
                        $h12 = $hours % 12;
                        $mAngle = $minutes * 6;
                        $hAngle = ($h12 * 30) + ($minutes * 0.5);
                        ?>
                        <p style="font-size: 13px; color: #555; margin-top: 12px; text-align: left;">
                            Часовая: <?= $h12 ?>×30 + <?= $minutes ?>×0.5 = <?= number_format($hAngle, 1) ?>°<br>
                            Минутная: <?= $minutes ?>×6 = <?= number_format($mAngle, 1) ?>°<br>
                            |<?= number_format($hAngle, 1) ?> − <?= number_format($mAngle, 1) ?>| → меньший угол = <?= number_format($result, 1) ?>°
                        </p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="clock-section">
                <?php
                $displayHours = isset($_POST['hours']) ? intval($_POST['hours']) : 3;
                $displayMinutes = isset($_POST['minutes']) ? intval($_POST['minutes']) : 15;
                $h = $displayHours % 12;
                $m = $displayMinutes;
                $hourAngle = ($h * 30) + ($m * 0.5);
                $minuteAngle = $m * 6;
                ?>
                <div class="clock">
                    <div class="clock-number" style="top: 10px; left: 50%; transform: translateX(-50%);">12</div>
                    <div class="clock-number" style="right: 10px; top: 50%; transform: translateY(-50%);">3</div>
                    <div class="clock-number" style="bottom: 10px; left: 50%; transform: translateX(-50%);">6</div>
                    <div class="clock-number" style="left: 10px; top: 50%; transform: translateY(-50%);">9</div>
                    
                    <div class="clock-hand hour-hand" style="transform: rotate(<?= $hourAngle ?>deg);"></div>
                    <div class="clock-hand minute-hand" style="transform: rotate(<?= $minuteAngle ?>deg);"></div>
                    <div class="clock-center"></div>
                </div>
                <p style="margin-top: 15px; color: #666; text-align: center;">
                    <?= sprintf('%02d:%02d', $displayHours, $displayMinutes) ?>
                </p>
            </div>
        </div>
        
        <a href="index.php" class="back-link">Вернуться к списку заданий</a>
    </div>
</body>
</html>

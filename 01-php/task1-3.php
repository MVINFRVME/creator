<?php
/**
 * Задание 1.3: Простейший калькулятор
 * 
 * Калькулятор с двумя полями и выбором операции (+, -, *, /).
 * Выполняет базовые арифметические операции с валидацией.
 */

function calculate($num1, $num2, $operation) {
    switch ($operation) {
        case '+':
            return $num1 + $num2;
        case '-':
            return $num1 - $num2;
        case '*':
            return $num1 * $num2;
        case '/':
            if ($num2 == 0) {
                throw new Exception('Деление на ноль невозможно');
            }
            return $num1 / $num2;
        default:
            throw new Exception('Неизвестная операция');
    }
}

/** Красивый вывод: 15 вместо 15.0000, 2.5 без лишних нулей */
function formatCalcResult($n) {
    if (round($n, 10) == round($n)) {
        return (string)(int)round($n);
    }
    return rtrim(rtrim(sprintf('%.10f', $n), '0'), '.');
}

// Обработка формы
$result = null;
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num1 = floatval($_POST['num1']);
    $num2 = floatval($_POST['num2']);
    $operation = $_POST['operation'];
    
    try {
        $result = calculate($num1, $num2, $operation);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 1.3 - Калькулятор</title>
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
        .calculator {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .calculator input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 18px;
            text-align: center;
        }
        .calculator select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 18px;
            background: white;
            cursor: pointer;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #FF9800;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #F57C00;
        }
        .result {
            margin-top: 20px;
            padding: 20px;
            background: #fff3e0;
            border-radius: 4px;
            border-left: 4px solid #FF9800;
            text-align: center;
        }
        .result h3 {
            margin-top: 0;
            color: #e65100;
        }
        .result .value {
            font-size: 32px;
            font-weight: bold;
            color: #e65100;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Задание 1.3: Калькулятор</h1>
        
        <form method="POST">
            <div class="calculator">
                <input type="number" name="num1" step="any" required 
                       value="<?= isset($_POST['num1']) ? htmlspecialchars($_POST['num1']) : '10' ?>"
                       placeholder="Число 1">
                
                <select name="operation" required>
                    <?php
                    $ops = ['+' => '+', '-' => '-', '*' => '×', '/' => '÷'];
                    $selected = $_POST['operation'] ?? '+';
                    foreach ($ops as $value => $label) {
                        $sel = ($value === $selected) ? 'selected' : '';
                        echo "<option value=\"$value\" $sel>$label</option>";
                    }
                    ?>
                </select>
                
                <input type="number" name="num2" step="any" required 
                       value="<?= isset($_POST['num2']) ? htmlspecialchars($_POST['num2']) : '5' ?>"
                       placeholder="Число 2">
            </div>
            
            <button type="submit">Вычислить</button>
        </form>
        
        <?php if ($error): ?>
            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php elseif ($result !== null): ?>
            <div class="result">
                <h3>Результат:</h3>
                <div class="value"><?= htmlspecialchars(formatCalcResult($result)) ?></div>
                <p style="margin-top: 10px; color: #666;">
                    <?= htmlspecialchars(formatCalcResult($num1)) ?>
                    <?= $ops[$operation] ?>
                    <?= htmlspecialchars(formatCalcResult($num2)) ?>
                    = <?= htmlspecialchars(formatCalcResult($result)) ?>
                </p>
            </div>
        <?php endif; ?>
        
        <a href="index.php" class="back-link">Вернуться к списку заданий</a>
    </div>
</body>
</html>

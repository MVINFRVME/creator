<?php
/**
 * Задание 1.2: Форматирование даты на русском языке
 * 
 * Функция formatRussianDate() принимает:
 * - день (1..31)
 * - номер месяца (1..12)
 * 
 * Возвращает дату в формате «1 января» или «9 мая».
 * Используются склонения месяцев в родительном падеже.
 */

function formatRussianDate($day, $month) {
    // Массив названий месяцев в родительном падеже
    $months = [
        1 => 'января',
        2 => 'февраля',
        3 => 'марта',
        4 => 'апреля',
        5 => 'мая',
        6 => 'июня',
        7 => 'июля',
        8 => 'августа',
        9 => 'сентября',
        10 => 'октября',
        11 => 'ноября',
        12 => 'декабря'
    ];
    
    // Валидация входных данных
    if ($day < 1 || $day > 31 || $month < 1 || $month > 12) {
        return false;
    }
    
    // Проверка корректности дня для конкретного месяца
    $daysInMonth = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    if ($day > $daysInMonth[$month - 1]) {
        return false;
    }
    
    return $day . ' ' . $months[$month];
}

// Обработка формы
$result = null;
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $day = intval($_POST['day']);
    $month = intval($_POST['month']);
    
    $result = formatRussianDate($day, $month);
    if ($result === false) {
        $error = 'Некорректная дата!';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 1.2 - Форматирование даты</title>
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
        input, select {
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
            background: #2196F3;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #0b7dda;
        }
        .result {
            margin-top: 20px;
            padding: 20px;
            background: #e3f2fd;
            border-radius: 4px;
            border-left: 4px solid #2196F3;
            text-align: center;
        }
        .result h3 {
            margin-top: 0;
            color: #1565c0;
        }
        .result .date {
            font-size: 24px;
            font-weight: bold;
            color: #0d47a1;
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
        <h1>Задание 1.2: Форматирование даты</h1>
        
        <form method="POST">
            <div class="form-group">
                <label for="day">День (1-31):</label>
                <input type="number" id="day" name="day" min="1" max="31" required 
                       value="<?= isset($_POST['day']) ? htmlspecialchars($_POST['day']) : '9' ?>">
            </div>
            
            <div class="form-group">
                <label for="month">Месяц:</label>
                <select id="month" name="month" required>
                    <?php
                    $monthNames = [
                        1 => 'Январь',
                        2 => 'Февраль',
                        3 => 'Март',
                        4 => 'Апрель',
                        5 => 'Май',
                        6 => 'Июнь',
                        7 => 'Июль',
                        8 => 'Август',
                        9 => 'Сентябрь',
                        10 => 'Октябрь',
                        11 => 'Ноябрь',
                        12 => 'Декабрь'
                    ];
                    $selectedMonth = isset($_POST['month']) ? intval($_POST['month']) : 5;
                    foreach ($monthNames as $num => $name) {
                        $selected = ($num === $selectedMonth) ? 'selected' : '';
                        echo "<option value=\"$num\" $selected>$num - $name</option>";
                    }
                    ?>
                </select>
            </div>
            
            <button type="submit">Форматировать дату</button>
        </form>
        
        <?php if ($error): ?>
            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php elseif ($result !== null): ?>
            <div class="result">
                <h3>Результат:</h3>
                <div class="date"><?= htmlspecialchars($result) ?></div>
            </div>
        <?php endif; ?>
        
        <a href="index.php" class="back-link">Вернуться к списку заданий</a>
    </div>
</body>
</html>

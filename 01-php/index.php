<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Задания</title>
    <link rel="stylesheet" href="../assets/task-links.css">
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 10px; }
        .subtitle { color: #666; margin-bottom: 30px; }
        .task-list { list-style: none; padding: 0; }
        .task-item { margin-bottom: 20px; padding: 20px; background: #f9f9f9; border-radius: 6px; border-left: 4px solid #5eb8c9; }
        .task-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .task-title { font-size: 18px; font-weight: bold; color: #333; }
        .task-points { background: #5eb8c9; color: white; padding: 4px 12px; border-radius: 12px; font-size: 13px; }
        .task-desc { color: #666; margin-bottom: 12px; font-size: 14px; }
        .task-link { display: inline-block; padding: 8px 16px; background: #5eb8c9; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; }
        .task-link:hover { background: #4aa0b0; }
        .home-link { display: inline-block; margin-top: 30px; color: #4aa0b0; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Раздел 1: PHP</h1>
        <p class="subtitle">8 баллов — функции и алгоритмы</p>
        <ul class="task-list">
            <li class="task-item">
                <div class="task-header">
                    <span class="task-title">Задание 1.1: Калькулятор вклада</span>
                    <span class="task-points">1 балл</span>
                </div>
                <p class="task-desc">Сумма вклада, срок в месяцах, годовой процент → итог к концу срока.</p>
                <a href="task1-1.php" class="task-link">Демо</a>
                <span data-task="1.1" data-code></span>
            </li>
            <li class="task-item">
                <div class="task-header">
                    <span class="task-title">Задание 1.2: Форматирование даты</span>
                    <span class="task-points">1 балл</span>
                </div>
                <p class="task-desc">День и месяц → дата по-русски («1 января», «9 мая»).</p>
                <a href="task1-2.php" class="task-link">Демо</a>
                <span data-task="1.2" data-code></span>
            </li>
            <li class="task-item">
                <div class="task-header">
                    <span class="task-title">Задание 1.3: Калькулятор</span>
                    <span class="task-points">1 балл</span>
                </div>
                <p class="task-desc">Два числа и операция +, −, /, *.</p>
                <a href="task1-3.php" class="task-link">Демо</a>
                <span data-task="1.3" data-code></span>
            </li>
            <li class="task-item">
                <div class="task-header">
                    <span class="task-title">Задание 1.4: Фотогалерея</span>
                    <span class="task-points">2 балла</span>
                </div>
                <p class="task-desc">Галерея из папки + загрузка файла.</p>
                <a href="task1-4.php" class="task-link">Демо</a>
                <span data-task="1.4" data-code></span>
            </li>
            <li class="task-item">
                <div class="task-header">
                    <span class="task-title">Задание 1.5: Угол между стрелками</span>
                    <span class="task-points">3 балла</span>
                </div>
                <p class="task-desc">Часы и минуты → угол между стрелками.</p>
                <a href="task1-5.php" class="task-link">Демо</a>
                <span data-task="1.5" data-code></span>
            </li>
        </ul>
        <a href="/index.html" class="home-link">Вернуться на главную</a>
    </div>
    <script src="../assets/links.js"></script>
</body>
</html>

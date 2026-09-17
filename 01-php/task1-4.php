<?php
/**
 * Задание 1.4: Фотогалерея
 * 
 * Скрипт читает список файлов из папки uploads/,
 * отбирает изображения и выводит галерею.
 * Форма позволяет загружать новые изображения с валидацией.
 */

// Создаём папку для загрузок, если её нет
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$message = null;
$error = null;

// Обработка загрузки файла
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $file = $_FILES['photo'];
    
    // Проверка на ошибки загрузки
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'Ошибка при загрузке файла';
    } elseif ($file['size'] > 5 * 1024 * 1024) {
        $error = 'Размер файла не должен превышать 5 МБ';
    } else {
        // Проверяем, что это реальное изображение (без mime_content_type —
        // на Windows/PHP без fileinfo эта функция часто недоступна)
        $imageInfo = @getimagesize($file['tmp_name']);
        $allowedTypes = [
            IMAGETYPE_JPEG => 'jpg',
            IMAGETYPE_PNG  => 'png',
            IMAGETYPE_GIF  => 'gif',
            IMAGETYPE_WEBP => 'webp',
        ];

        if ($imageInfo === false || !isset($allowedTypes[$imageInfo[2]])) {
            $error = 'Разрешены только изображения (JPEG, PNG, GIF, WebP)';
        } else {
            // Расширение берём из типа файла, а не из имени (безопаснее)
            $extension = $allowedTypes[$imageInfo[2]];
            $safeFilename = uniqid('photo_') . '.' . $extension;
            $destination = $uploadDir . $safeFilename;
            
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                header('Location: ' . $_SERVER['PHP_SELF'] . '?success=1');
                exit;
            } else {
                $error = 'Ошибка при сохранении файла';
            }
        }
    }
}

// Проверяем параметр успешной загрузки после редиректа
if (isset($_GET['success'])) {
    $message = 'Изображение успешно загружено!';
}

// Получаем список изображений
function getImages($dir) {
    $images = [];
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, $allowedExtensions)) {
                $images[] = $file;
            }
        }
    }
    
    // Сортируем по дате изменения (новые первыми)
    usort($images, function($a, $b) use ($dir) {
        return filemtime($dir . $b) - filemtime($dir . $a);
    });
    
    return $images;
}

$images = getImages($uploadDir);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 1.4 - Фотогалерея</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        .upload-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .upload-form {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .file-input-wrapper {
            flex: 1;
            position: relative;
        }
        .file-input-wrapper input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 2px dashed #ddd;
            border-radius: 4px;
            cursor: pointer;
        }
        .file-input-wrapper input[type="file"]:hover {
            border-color: #9C27B0;
        }
        button {
            padding: 12px 30px;
            background: #9C27B0;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            white-space: nowrap;
        }
        button:hover {
            background: #7B1FA2;
        }
        .message {
            padding: 15px;
            background: #e8f5e9;
            border-radius: 4px;
            border-left: 4px solid #4CAF50;
            color: #2e7d32;
            margin-bottom: 20px;
        }
        .error {
            padding: 15px;
            background: #ffebee;
            border-radius: 4px;
            border-left: 4px solid #f44336;
            color: #c62828;
            margin-bottom: 20px;
        }
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .gallery-item {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .gallery-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            display: block;
        }
        .gallery-item .info {
            padding: 15px;
        }
        .gallery-item .filename {
            font-size: 12px;
            color: #666;
            word-break: break-all;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 8px;
            color: #999;
        }
        .empty-state svg {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            opacity: 0.5;
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
        <h1>Задание 1.4: Фотогалерея</h1>
        
        <div class="upload-section">
            <h2 style="margin-bottom: 20px;">Загрузить изображение</h2>
            
            <?php if ($message): ?>
                <div class="message"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" class="upload-form">
                <div class="file-input-wrapper">
                    <input type="file" name="photo" accept="image/*" required>
                </div>
                <button type="submit">Загрузить</button>
            </form>
            
            <p style="margin-top: 15px; color: #666; font-size: 14px;">
                Разрешены: JPEG, PNG, GIF, WebP. Максимальный размер: 5 МБ.
            </p>
        </div>
        
        <?php if (count($images) > 0): ?>
            <h2 style="margin-bottom: 20px;">Галерея (<?= count($images) ?> изображений)</h2>
            <div class="gallery">
                <?php foreach ($images as $image): ?>
                    <div class="gallery-item">
                        <a href="uploads/<?= htmlspecialchars($image) ?>" target="_blank">
                            <img src="uploads/<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($image) ?>">
                        </a>
                        <div class="info">
                            <div class="filename"><?= htmlspecialchars($image) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                </svg>
                <h3>Галерея пуста</h3>
                <p>Загрузите первое изображение, чтобы начать</p>
            </div>
        <?php endif; ?>
        
        <a href="index.php" class="back-link">Вернуться к списку заданий</a>
    </div>
</body>
</html>

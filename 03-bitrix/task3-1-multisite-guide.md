# Задание 3.1: Настройка многосайтовости на разных доменах

**Баллы:** 1  
**Требование:** Настроить VM с двумя сайтами на разных доменах, разными шаблонами. На главной каждого сайта — список новостей из общего инфоблока.

**Скринкаст (готово):** https://disk.yandex.ru/i/kJU1-ibNPcon3g

---

## Шаг 1: Подготовка доменов

### 1.1. Настройка hosts (для локальной разработки)

Откройте файл hosts с правами администратора:
- **Windows:** `C:\Windows\System32\drivers\etc\hosts`
- **Linux/Mac:** `/etc/hosts`

Добавьте строки:
```
127.0.0.1 site1.local
127.0.0.1 site2.local
```

### 1.2. Настройка веб-сервера (Apache)

Откройте конфигурацию виртуальных хостов Apache (обычно `/etc/apache2/sites-available/` или `httpd-vhosts.conf`).

Добавьте два виртуальных хоста:

```apache
<VirtualHost *:80>
    ServerName site1.local
    DocumentRoot /var/www/bitrix
    
    <Directory /var/www/bitrix>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/site1-error.log
    CustomLog ${APACHE_LOG_DIR}/site1-access.log combined
</VirtualHost>

<VirtualHost *:80>
    ServerName site2.local
    DocumentRoot /var/www/bitrix
    
    <Directory /var/www/bitrix>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/site2-error.log
    CustomLog ${APACHE_LOG_DIR}/site2-access.log combined
</VirtualHost>
```

Перезапустите Apache:
```bash
sudo systemctl restart apache2
# или
sudo service apache2 restart
```

---

## Шаг 2: Настройка сайтов в Битрикс

### 2.1. Создание сайтов

1. Войдите в административную панель Битрикс
2. Перейдите: **Настройки** → **Настройки продукта** → **Сайты**
3. Нажмите **Добавить сайт**

**Первый сайт:**
- **ID:** s1
- **Название:** Сайт 1
- **Домен:** site1.local
- **Папка:** /
- **Активность:** Да

**Второй сайт:**
- **ID:** s2
- **Название:** Сайт 2
- **Домен:** site2.local
- **Папка:** /
- **Активность:** Да

4. Сохраните оба сайта

---

## Шаг 3: Создание шаблонов сайтов

### 3.1. Создание первого шаблона

1. Перейдите: **Контент** → **Шаблоны сайтов**
2. Нажмите **Добавить шаблон**
3. Заполните:
   - **Название:** Шаблон сайта 1
   - **Папка:** /bitrix/templates/site1/

Создайте файл `/bitrix/templates/site1/header.php`:

```php
<!DOCTYPE html>
<html lang="ru">
<head>
    <?$APPLICATION->ShowHead();?>
    <title><?$APPLICATION->ShowTitle()?></title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0;
            background: #f0f0f0;
        }
        .header { 
            background: #2196F3; 
            color: white; 
            padding: 20px; 
            text-align: center;
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 20px;
            background: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>САЙТ 1 - Синий</h1>
    </div>
    <div class="container">
```

Создайте файл `/bitrix/templates/site1/footer.php`:

```php
    </div>
    <div style="text-align: center; padding: 20px; color: #999;">
        © <?=date('Y')?> Сайт 1
    </div>
</body>
</html>
```

### 3.2. Создание второго шаблона

Создайте файл `/bitrix/templates/site2/header.php`:

```php
<!DOCTYPE html>
<html lang="ru">
<head>
    <?$APPLICATION->ShowHead();?>
    <title><?$APPLICATION->ShowTitle()?></title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0;
            background: #fff3e0;
        }
        .header { 
            background: #FF9800; 
            color: white; 
            padding: 20px; 
            text-align: center;
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 20px;
            background: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>САЙТ 2 - Оранжевый</h1>
    </div>
    <div class="container">
```

Создайте файл `/bitrix/templates/site2/footer.php`:

```php
    </div>
    <div style="text-align: center; padding: 20px; color: #999;">
        © <?=date('Y')?> Сайт 2
    </div>
</body>
</html>
```

---

## Шаг 4: Привязка шаблонов к сайтам

1. Перейдите: **Настройки** → **Настройки продукта** → **Сайты**
2. Редактируйте **Сайт 1**:
   - **Шаблон по умолчанию:** Шаблон сайта 1
3. Редактируйте **Сайт 2**:
   - **Шаблон по умолчанию:** Шаблон сайта 2

---

## Шаг 5: Создание общего инфоблока новостей

### 5.1. Создание типа инфоблока

1. Перейдите: **Контент** → **Типы инфоблоков**
2. Нажмите **Добавить тип**
3. Заполните:
   - **ID:** common
   - **Название:** Общий контент

### 5.2. Создание инфоблока

1. Перейдите: **Контент** → **Инфоблоки** → **Типы инфоблоков**
2. Выберите тип "Общий контент"
3. Нажмите **Добавить инфоблок**
4. Заполните:
   - **Название:** Новости (общие)
   - **Код:** news_common
   - **Сайты:** отметьте оба сайта (s1 и s2)

### 5.3. Добавление новостей

1. Откройте инфоблок "Новости (общие)"
2. Добавьте 3-5 новостей с заполненными полями:
   - Название
   - Анонс
   - Детальный текст

---

## Шаг 6: Вывод новостей на главных страницах

### 6.1. Главная страница для Сайта 1

Создайте файл `/index.php` или измените существующий:

```php
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Главная страница");

// Определяем текущий сайт
$currentSite = SITE_ID;
?>

<h2>Новости</h2>

<?$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "",
    [
        "IBLOCK_TYPE" => "common",
        "IBLOCK_ID" => "news_common", // или ID инфоблока
        "NEWS_COUNT" => "10",
        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_ORDER1" => "DESC",
        "FILTER_NAME" => "",
        "FIELD_CODE" => ["NAME", "PREVIEW_TEXT"],
        "PROPERTY_CODE" => [],
        "DISPLAY_PICTURE" => "N",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "AJAX_MODE" => "N",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
    ]
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
```

**Примечание:** Замените `"IBLOCK_ID" => "news_common"` на числовой ID инфоблока, который можно посмотреть в списке инфоблоков.

---

## Шаг 7: Проверка работы

### 7.1. Откройте сайты в браузере

- **Сайт 1:** http://site1.local
- **Сайт 2:** http://site2.local

### 7.2. Что должно быть видно:

✅ Сайт 1 отображается с синей шапкой  
✅ Сайт 2 отображается с оранжевой шапкой  
✅ На обоих сайтах выводится список новостей из общего инфоблока  
✅ Новости одинаковые на обоих сайтах

---

## Шаг 8: Создание скринкаста

Для выполнения задания требуется скринкаст. Запишите:

1. Открытие site1.local — демонстрация синего шаблона и новостей
2. Открытие site2.local — демонстрация оранжевого шаблона и тех же новостей
3. Административную панель:
   - Раздел с настройками сайтов
   - Раздел с шаблонами
   - Инфоблок с новостями
4. Добавление новой новости и её появление на обоих сайтах

**Рекомендуемые инструменты:**
- OBS Studio (бесплатно, для всех ОС)
- ShareX (Windows)
- Kazam (Linux)
- QuickTime Player (Mac)

---

## Возможные проблемы и решения

### Проблема: Сайты не открываются

**Решение:**
- Проверьте файл hosts
- Убедитесь что Apache перезапущен
- Проверьте права на папку /var/www/bitrix

### Проблема: Открывается только один сайт

**Решение:**
- Проверьте настройку виртуальных хостов
- Убедитесь что директива ServerName правильная для каждого хоста

### Проблема: Новости не выводятся

**Решение:**
- Проверьте ID инфоблока в коде компонента
- Убедитесь что инфоблок привязан к обоим сайтам
- Проверьте что новости активны и опубликованы

### Проблема: Шаблоны не применяются

**Решение:**
- Очистите кэш Битрикс (Настройки → Производительность → Очистить кэш)
- Проверьте привязку шаблонов к сайтам
- Убедитесь что файлы header.php и footer.php созданы

---

## Итоговая структура файлов

```
/var/www/bitrix/
├── index.php (главная страница)
├── bitrix/
│   └── templates/
│       ├── site1/
│       │   ├── header.php
│       │   └── footer.php
│       └── site2/
│           ├── header.php
│           └── footer.php
```

---

## Итог

После выполнения всех шагов у вас будет:

✅ Два сайта на разных доменах  
✅ Разные шаблоны оформления  
✅ Общий инфоблок новостей  
✅ Одинаковый список новостей на обоих сайтах  
✅ Готовый материал для скринкаста

**Время выполнения:** 1-2 часа  
**Сложность:** Начальная

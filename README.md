# Тестовое задание

PHP · Frontend · 1С-Битрикс · Битрикс24.

Ссылки на код и скринкасты — в [`assets/links.js`](assets/links.js) (на страницах разделов).

**Скринкасты 3.x:** [3.1](https://disk.yandex.ru/i/kJU1-ibNPcon3g) · [3.2](https://disk.yandex.ru/i/NnQW2r_sr7IGmg) · [3.3](https://disk.yandex.ru/i/nMpvNGN_YSCeXA) · [3.4](https://disk.yandex.ru/i/FRRjyPLNrFsp1w) · [3.5](https://disk.yandex.ru/i/0yPiXrdyEyavyQ)

**Скринкасты 4.x:** [4.1](https://disk.yandex.ru/i/xc-DiS1McG-Bmg) · [4.2](https://disk.yandex.ru/i/y-oxhfkaqyT5GA) · [4.3](https://disk.yandex.ru/i/BEWppKSLfWNJPA)

## Структура

```
index.html          # главная
01-php/             # демо PHP
02-frontend/        # демо вёрстка/JS
03-bitrix/          # код модулей/компонента + скринкасты
04-bitrix24/        # скринкасты (облако)
assets/links.js     # URL GitHub и видео
docker-compose.yml  # деплой демо
deploy/             # nginx
```

## Запуск

```bash
php -S localhost:8000
# или
docker compose up -d
# WEB_PORT=8081 docker compose up -d
```

Сайт: http://localhost:8000/ (или `http://IP:PORT/` на VPS).

Раздел 3 — код + видео (CMS на VM). Раздел 4 — только скринкасты.

## Баллы

| Раздел | Баллы | Сдача |
|--------|------:|-------|
| PHP 1.1–1.5 | 8 | демо + код |
| Frontend 2.1–2.4 | 12 | демо + код |
| Битрикс 3.1–3.5 | 11 | код + скринкасты |
| Битрикс24 4.1–4.3 | 6 | скринкасты |
| **Всего** | **37** | |

Секреты — только в `.credentials.local.md` (в `.gitignore`).

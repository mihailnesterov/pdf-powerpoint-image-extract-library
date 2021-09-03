# PDF/PowerPoint extract library

## Описание задачи:

### Библиотека должна: 
1. Разобрать PDF
2. Разобрать PowerPoint
3. На выходе получить набор файлов-картинок

### На тестовом стенде:
* Возможность загрузить любой файл
* Возможность нажать кнопку и запустить обработку 
* На выходе: список слайдов
* На выходе: порядок слайдов
* Обработка ошибок

## Библиотека: 
    src/library:

## Системные требования (Debian/Ubuntu):
1. sudo apt install poppler-utils
2. sudo apt install unoconv

## Тестовый стенд: 
    public

* права на запись для каталогов:
1. public/input
2. public/output

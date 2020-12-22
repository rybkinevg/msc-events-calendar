# MSC Events Calendar

Специально разработанный плагин для портала "Мой семейный центр"

# Актуальная версия
`1.0.7`
# Описание

Плагин создаёт тип записи `Мероприятия` с необходимыми мета полями, возможностью импорта и связи с организатором.
Создаёт шаблон вывода мероприятий с календарём, поиском и фильтрами по типу мероприятия и организатору.

# Использованные библиотеки и плагины

### TGM

Плагин WordPress для создания зависимостей от других плагинов

### Carbon Fields

Плагин WordPress для создания мета-полей

### Air Datepicker

Плагин для создания красивого календаря с большими возможностями кастоматизации

### Choices

Плагин для создания выпадающих списков с возможностью поиска и других классных вещей


# Установка

1. Загрузите `msc-events-calendar.zip` в `/wp-content/plugins/`
1. Активируйте плагин через установщик плагинов WordPress
1. Установите зависимости плагина, если есть необходимость

# Список изменений

Раздел содержит весь список изменений плагина

## 1.0.7 - 23.12.2020
### Добавлено
* Бесконечная прокрутка мероприятий

### Изменено
* Кличество выводимых мероприятий (было -1, стало 1)

## 1.0.6 - 22.12.2020
### Добавлено
* Мета поля организатора (имя, ссылка)
* Фильтр мероприятий (название, дата, организатор, тип)
* Прелоадер во время ajax

### Изменено
* Внешний вид вывода мероприятий
* Внешний вид отдельного мероприятия

## 1.0.5 - 16.12.2020
### Добавлено
* Поле - Открытость мероприятия
* Проверка на обязательное заполнение названия и описания при импорте

### Изменено
* Поле - Тип мероприятия

## 1.0.4 - 08.12.2020
### Добавлено
* Тип записи - Организаторы
* Связано поле организатор у мероприятия с новым типом записи
* Таблица в базе, которая хранит историю импортов
* Удаление таблицы импортов при удалении плагина
* Отмена регистрации типов записи Мероприятия и Организаторы при деактивации плагина

## 1.0.3 - 04.12.2020
### Исправлено
* Импорт csv файла с ключами, в которых было больше одного слова с пробелами

## 1.0.2 - 02.12.2020
### Добавлено
* Механизм загрузки csv-файлов на сервер
* Выбор какие данные импортировать из файла
* Импорт в базу данных мероприятий из файла
* Подсчёт сколько мероприятий импортировано и сколько не импортировано

### Изменено
* Внешний вид страницы импорта
* Ссылка на файл теперь хранится в скрытом поле формы импорта

## 1.0.1 - 01.12.2020
### Добавлено
* Создан тип записи - Мероприятия
* Добавлен плагин TGM
* Создана зависимость плагина от Carbon Fields
* Добавлены дополнительные поля мероприятий
* Добавлены новые колонки в таблицу вывода мероприятий
* Добавлен блок со статистикой над таблицей вывода мероприятий
* Созданы шаблоны вывода архива мероприятий и одного мероприятия
* Добавлен фильтр по типу мероприятия в административной части сайта
* Создана страница импорта мероприятий
* Создана страница настроек плагина
## 1.0.0 - 30.11.2020
### Добавлено
* Создана базовая структура файлов плагина
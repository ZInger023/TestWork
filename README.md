# TestWork
Развертка проекта
1. Склонировать проект git clone

2. Ввести команду "composer update"

3. Изменить настройки в файле .env.example на собственные, затем переименовать файл в .env

4. Ввести команды "php artisan key:generate"

"php artisan migrate"

5. Запустить локальный сервер, вы можете использовать встроенный от Laravel, введя команду "php artisan serve"



Наполнение тестовыми данными
Для создания user и manager в количестве 2 и 1, использовать команду "php artisan db:seed --class=UsersTableSeeder"

Данные: user {name: user$i, email: user$i@gmail.com, password:31567199}

Данные: manager {name: Manager, email: manager123@gmail.com, password:31567199}


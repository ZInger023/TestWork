<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мой блог</title>
    <style>
        .layout {
            width: 100%;
            max-width: 1024px;
            margin: auto;
            background-color: white;
            border-collapse: collapse;
        }

        .layout tr td {
            padding: 20px;
            vertical-align: top;
            border: solid 1px gray;
        }

        .header {
            font-size: 30px;
        }

        .footer {
            text-align: center;
        }

        .sidebarHeader {
            font-size: 20px;
        }

        .sidebar ul {
            padding-left: 20px;
        }

        a, a:visited {
            color: darkgreen;
        }
    </style>

</head>
<body>
<table class="layout">
    <tr>
        <td colspan="2" class="header">
            Заявочки
        </td>
    </tr>
    <tr>
    </tr>
    <tr>
        <td>


            <form action="/admin/show" method ="post">
                @csrf
                Упорядочить заявки по:
                <br>
                <label>
                    <input type="radio" name="status" value="answered">
                    Наличию ответа менеджера
                </label>
                <br>
                <label>
                    <input type="radio" name="status" value="unanswered">
                    Отсутствию ответа менеджера
                </label>
                <br>
                <label>
                    <input type="radio" name="status" value="viewed">
                    Просмотренные менеджером
                </label>
                <br>
                <label>
                    <input type="radio" name="status" value="unviewed">
                   Непросмотренные менеджером
                </label>
                <br>
                <label>
                    <input type="radio" name="status" value="open">
                    Открытые заявки
                </label>
                <br>
                <label>
                    <input type="radio" name="status" value="closed">
                    Закрытые заявки
                </label>
                <br>
                <br>
                <br>
                <input type="submit" value="Отсортировать">
            </form>


        </td>

        <td width="300px" class="sidebar">
            <div class="sidebarHeader">Меню</div>
            <ul>
                <li><a href="/dashboard">Главная страница</a></li>
            </ul>
        </td>
    </tr>
    <tr>
        <td class="footer" colspan="2">Все права защищены (c) Мой блог</td>
    </tr>
</table>
</body>
</html>

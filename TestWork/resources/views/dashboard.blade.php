<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заявочник</title>
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
            @if (Auth::user()->role == 'user')
                <h2><a href="/myMessages">Мои заявки</a></h2>
                <h2><a href="/makeMessage">Оставить новую заявку</a></h2>
            @endif
                @if (Auth::user()->role == 'manager')
                    <h2><a href="/admin/show/all">Посмотреть все заявки</a></h2>
                @endif
            <hr>
        </td>

        <td width="300px" class="sidebar">
            <div class="sidebarHeader">Меню</div>
            <ul>
                @if (Auth::user()->role == 'manager')
                <li><a href="/admin">Отсортировать заявки</a></li>
                @endif
                <li><a href="/logout">Выйти из аккаунта</a></li>
            </ul>
        </td>
    </tr>
    <tr>
        <td class="footer" colspan="2">Все права защищены (c) Мой блог</td>
    </tr>
</table>

</body>
</html>

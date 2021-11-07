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

            @foreach ($messages as $message)
            <h2><a href="/message/{{$message->id}}">{{$message->name}}</a></h2>
            <p>{{$message->text}}</p>
            @if(($message->author_id==Auth::id())&&($message->status!='closed'))
                <a href="/delete/{{$message->id}}">Закрыть заявку!</a>
                @endif
            <hr>
            @endforeach
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

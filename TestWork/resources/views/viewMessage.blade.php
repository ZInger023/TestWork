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
                <h2>{{$message->name}}</h2>
                <p>{{$message->text}}</p>
                <hr>
            @endforeach
            @if(!empty($chats))
                @foreach ($chats as $chat)
                    @if($chat->author_id == Auth::id())
                    <p>{{$chat->text}}</p>
                        @else
                            <p>{{$chat->text}}</p>
                        @endif
                @endforeach
            @endif
                @if((($message->author_id==Auth::id())||(Auth::user()->role == 'manager'))&&($message->status !== 'closed'))
            <form method="post" action="/{{$message->id}}/sendToChat">
                @csrf
                <input type="text" name="text" id="text" class="form-control" placeholder="Чат с менеджером" required="" autofocus="">
                <button class="btn btn-lg btn-primary btn-block" type="submit">Отправить</button>
            </form>
                    @endif
                @if((Auth::user()->role == 'manager')&&($message->status =='open'))
                    <a href="/admin/setViewed/{{$message->id}}">Принять заявку на рассмотрение!</a>
                @endif
                @if((($message->author_id==Auth::id())||($message->manager_id==Auth::id()))&&($message->status!='closed'))
                    <a href="/delete/{{$message->id}}">Закрыть заявку!</a>
                @endif
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

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>
            {{$message->name}}
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-RlQMpV89NqWGGjWth6DEZCr3lYNcsw8aHpMbz2jvOds+TkHqCxsZFme0A33WvgJ2Qk5jDs6ybs4Qb1q/fez2w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <style>
        .MyMessages {
            text-align: right ;
        }
        .PartnerMessages {
            text-align: left;
        }
        #Chat {
            margin: auto;
        }
    </style>
</head>
<body class="d-flex h-100 text-center text-white bg-dark">

<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header class="mb-auto">
        <div>
            <h3 class="float-md-start mb-0">Cover</h3>
            <nav class="nav nav-masthead justify-content-center float-md-end">
                <a class="nav-link" href="/dashboard">На главную</a>
                @if (Auth::user()->role == 'user')
                    <a class="nav-link" href="/makeMessage">Оставить новую заявку</a>
                    <a class="nav-link" href="/myMessages">Мои заявки</a>
                @endif
                @if (Auth::user()->role == 'manager')
                    <a class="nav-link" href="/admin/show/all">Посмотреть все заявки</a>
                @endif
                <a class="nav-link" href="/logout">Выйти из аккаунта</a>
            </nav>
        </div>
    </header>

    <main class="px-3">
            <h2>{{$message->name}}</h2>
            <p>{{$message->text}}</p>
            <br>
        @foreach ($images as $image)
            <img src="/storage/@php echo $image['path']@endphp" alt=""  width="250" height="250" border="2">
        @endforeach
        <hr>
        @if(!empty($chats))

                <table id="Chat" border="3" width="40%" cellpadding="5">
                    @foreach ($chats as $chat)
                    <tr>
                        <th>@if($chat->author_id == Auth::id())
                                <h1 style="font:12px verdana; text-align: right; color:blue">Вы:</h1>
                                <p class="MyMessages">{{$chat->text}}</p>
                                <h1 style="font:8px verdana; text-align: left">{{$chat->created_at}}</h1>
                                <a style="float:right" href="/updateChat/ @php echo $chat->id @endphp">Редактировать сообщение. </a>
                                <a style="float:left" href="/deleteChat/{{$chat->id}}">Удалить сообщение</a>
                            </th>
                        <th></th>
                    </tr>
                    <tr>
                        <td></td>
                        <td> @else
                                <h1 style="font:12px verdana; text-align: left; color:blue">@if(Auth::user()->role !== 'user'){{$userName}}@else{{$managerName}}@endif</h1>
                                <p class="PartnerMessages">{{$chat->text}}</p>
                                <h1 style="font:8px verdana; text-align: right">{{$chat->created_at}}</h1><hr>
                            @endif</td>
                    </tr>
                    @endforeach
                </table>
            <br>
        @endif
        @if((($message->author_id==Auth::id()) || ((Auth::user()->role == 'manager')&&($message->manager_id==Auth::id()))&&($message->status !== 'closed')))
            <form method="post" action="/{{$message->id}}/sendToChat">
                @csrf
                <input type="text" name="text" id="text" class="form-control" placeholder="Введите сообщение" required="" autofocus="">
                <button class="btn btn-lg btn-primary btn-block" type="submit">Отправить</button>
            </form>
        @endif
        @if((Auth::user()->role == 'manager')&&($message->status =='open'))
            <a href="/admin/setViewed/{{$message->id}}">Принять заявку на рассмотрение!</a>
        @endif
        @if((($message->author_id==Auth::id())||($message->manager_id==Auth::id()))&&($message->status!='closed'))
            <a href="/close/{{$message->id}}">Закрыть заявку!</a>
        @endif
        @if(($message->author_id==Auth::id())&&($message->status!='closed'))
            <br>
            <a href="/updateMessageForm/{{$message->id}}">Редактировать заявку.</a>
        @endif
        @if((($message->author_id==Auth::id())||($message->manager_id==Auth::id())))
            <br>
            <a href="/delete/{{$message->id}}">Удалить заявку!</a>
        @endif
    </main>

    <footer class="mt-auto text-white-50">
        <p>Cover template for <a href="https://getbootstrap.com/" class="text-white">Bootstrap</a>, by <a href="https://twitter.com/mdo" class="text-white">@mdo</a>.</p>
    </footer>
</div>
</body>
</html>

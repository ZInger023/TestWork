<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title> @foreach ($messages as $message)
            {{$message->name}}
        @endforeach</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   <style>
   .MyMessages {
                 text-align: left;
               }
   .PartnerMessages {
                text-align: right;
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
         <a class="nav-link" href="/admin">Отсортировать заявки</a>
         @endif
         <a class="nav-link" href="/logout">Выйти из аккаунта</a>
      </nav>
    </div>
  </header>

  <main class="px-3">
                @foreach ($messages as $message)
                    <h2>{{$message->name}}</h2>
                    <p>{{$message->text}}</p>
                    <hr>
                @endforeach
                @if(!empty($chats))
                    @foreach ($chats as $chat)
                        @if($chat->author_id == Auth::id())
                        <p class="MyMessages">{{$chat->text}}</p>
                            @else
                                <p class="PartnerMessages">{{$chat->text}}</p>
                            @endif
                    @endforeach
                @endif
                    @if((($message->author_id==Auth::id())||(Auth::user()->role == 'manager'))&&($message->status !== 'closed')&&($message->status !== 'open'))
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
  </main>

  <footer class="mt-auto text-white-50">
    <p>Cover template for <a href="https://getbootstrap.com/" class="text-white">Bootstrap</a>, by <a href="https://twitter.com/mdo" class="text-white">@mdo</a>.</p>
  </footer>
</div>
</body>
</html>

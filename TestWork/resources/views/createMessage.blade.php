<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Создание заявки</title>
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

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="/TryMessage">
        @csrf
        <input type="text" name="name" id="name" class="form-control" placeholder="Введите имя заявки" required="" autofocus="">
            <textarea name="text" class="form-control" rows="10" cols="100" placeholder="Введите текст заявки"></textarea>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Отправить</button>
    </form>

  </main>

  <footer class="mt-auto text-white-50">
    <p>Cover template for <a href="https://getbootstrap.com/" class="text-white">Bootstrap</a>, by <a href="https://twitter.com/mdo" class="text-white">@mdo</a>.</p>
  </footer>
</div>
</body>
</html>

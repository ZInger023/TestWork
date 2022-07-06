<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление завками</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script></head>
</head>
<body class="d-flex h-100 text-center text-white bg-dark">

<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
  <header class="mb-auto">
    <div>
      <h3 class="float-md-start mb-0">Cover</h3>
      <nav class="nav nav-masthead justify-content-center float-md-end">
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
    <form action="/admin/show" method ="post" align="left">
        @csrf
        Режим сортировки заявок: <select name="status" onchange="form.submit()" autofocus>
            <option disabled selected="selected">@php echo $selectString @endphp</option>
            <option value="@php echo $statuses[0] @endphp|@php echo $prevStatus @endphp">@php echo $statusesRu[0] @endphp</option>
            <option value="@php echo $statuses[1] @endphp|@php echo $prevStatus @endphp">@php echo $statusesRu[1] @endphp</option>
            <option value="@php echo $statuses[2] @endphp|@php echo $prevStatus @endphp">@php echo $statusesRu[2] @endphp</option>
        </select>
    </form>
  <main class="px-3" align="center">
      @if($ups->isEmpty())
      <h2>Заявок такого типа нет.<h2>
      @endif

               @foreach ($ups as $up)
          @if($up->status == 'open')
              <h2><a  style="color: green" href="/message/{{$up->id}}">{{$up->name}}</a></h2>
              <p style="color: green; white-space: nowrap; overflow: hidden; text-overflow: ellipsis">{{$up->text}}</p>
          @endif
          @if(((($up->status == 'answered')||($up->status == 'viewed'))&&(!empty($up->manager_id))&&($up->manager_id == Auth::id())))
              <h2><a  style="color:yellow" href="/message/{{$up->id}}">{{$up->name}}</a></h2>
              <p style="color:yellow; white-space: nowrap; overflow: hidden; text-overflow: ellipsis">{{$up->text}}</p>
          @endif
          @if((($up->status == 'answered')||($up->status == 'viewed'))&&(!empty($up->manager_id))&&($up->manager_id !== Auth::id()))
              <h2><a  style="color: red" href="/message/{{$up->id}}">{{$up->name}}</a></h2>
              <p style="color: red; white-space: nowrap; overflow: hidden; text-overflow: ellipsis">{{$up->text}}</p>
          @endif
          @if($up->status == 'closed')
              <h2><a  style="color: white" href="/message/{{$up->id}}">{{$up->name}}</a></h2>
              <p style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis">{{$up->text}}</p>
          @endif
               @endforeach
  </main>

  <footer class="mt-auto text-white-50">
    <p>Cover template for <a href="https://getbootstrap.com/" class="text-white">Bootstrap</a>, by <a href="https://twitter.com/mdo" class="text-white">@mdo</a>.</p>
  </footer>
</div>
</body>
</html>

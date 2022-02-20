@if ( $user->role == 'manager')
    <h2>Сотрудник поддержки "{{ $user->name }}" дал ответ по вашей заявке "{{ $messageName}}"</h2>
@endif

@if ( $user->role == 'user')
    <h2>Пользователь "{{ $user->name }}" прислал вам сообщение по заявке "{{ $messageName}}"</h2>
@endif
<a {{url('/')}}/{{$messageId}}">Перейти к заявке</a>

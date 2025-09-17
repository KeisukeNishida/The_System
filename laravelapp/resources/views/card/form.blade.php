{{-- resources/views/card/form.blade.php --}}
@extends('layouts.app')

@section('content')
  <h1>カードを作る</h1>

  @if ($errors->any())
    <div style="color:#c00;">
      <ul>
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ url('/card') }}" method="post" enctype="multipart/form-data">
    @csrf
    <p><input type="file" name="image" accept="image/*" required></p>
    <p><input type="text" name="name" value="{{ old('name') }}" placeholder="カード名（任意）" maxlength="30"></p>
    <p><button type="submit">カード化する</button></p>
  </form>
@endsection

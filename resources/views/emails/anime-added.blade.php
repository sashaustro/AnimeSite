<h2>Привіт, Адміне!</h2>
<p>На сайт щойно було додано нове аніме:</p>
<ul>
    <li><strong>Назва:</strong> {{ $anime->title }}</li>
    <li><strong>Жанри:</strong> 
        @foreach($anime->genres as $genre)
            {{ $genre->name }}@if(!$loop->last), @endif
        @endforeach
    </li>
</ul>
<p>Зайди в каталог, щоб перевірити!</p>
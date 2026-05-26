@extends('adminlte::page')

@section('title', 'Каталог Аніме')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
        <h1 class="text-white font-weight-bold">Каталог Аніме</h1>
        <a href="{{ route('anime.create') }}" class="btn btn-pink neon-shadow">
            <i class="fas fa-plus"></i> Додати тайтл
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        @forelse($animes as $anime)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="anime-card">
                    <div class="anime-poster">
                        @if($anime->image)
                            <img src="{{ asset('storage/' . $anime->image) }}" alt="{{ $anime->title }}">
                        @else
                            <img src="https://via.placeholder.com/300x450/222/ff3366?text=No+Poster" alt="No Image">
                        @endif
                        
                        <div class="anime-overlay">
                            <h5 class="anime-title text-truncate" title="{{ $anime->title }}">{{ $anime->title }}</h5>
                            
                            <div class="anime-genres mb-3">
                                @foreach($anime->genres->take(3) as $genre)
                                    <span class="badge badge-pink">{{ $genre->name }}</span>
                                @endforeach
                            </div>

                            <div class="anime-actions">
                                <a href="{{ route('anime.show', $anime->id) }}" class="btn btn-sm btn-outline-light rounded-circle mx-1"><i class="fas fa-play"></i></a>
                                <a href="{{ route('anime.edit', $anime->id) }}" class="btn btn-sm btn-outline-info rounded-circle mx-1"><i class="fas fa-pen"></i></a>
                                
                                <form action="{{ route('anime.destroy', $anime->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Видалити тайтл?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle mx-1"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <h3 class="text-muted">Каталог порожній...</h3>
            </div>
        @endforelse
    </div>
    
    @if($animes->hasPages())
        <div class="d-flex justify-content-center mt-4 custom-pagination">
            {{ $animes->links() }}
        </div>
    @endif
@stop

@section('css')
    <style>
        /* Глобальний темний фон для контейнера AdminLTE */
        .content-wrapper { background-color: #0f0f13 !important; }
        
        /* Кнопки та акценти */
        .btn-pink { background-color: #ff3366; color: white; border: none; }
        .btn-pink:hover { background-color: #e62e5c; color: white; }
        .neon-shadow { box-shadow: 0 0 10px rgba(255, 51, 102, 0.5); }
        .badge-pink { background-color: transparent; border: 1px solid #ff3366; color: #ff3366; font-weight: normal;}

        /* Аніме Картка */
        .anime-card {
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            background-color: #1a1a1d;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .anime-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(255, 51, 102, 0.3);
        }
        
        .anime-poster {
            position: relative;
            padding-top: 140%; /* Пропорція постера */
        }
        .anime-poster img {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        .anime-card:hover .anime-poster img {
            transform: scale(1.1);
        }

        /* Оверлей при наведенні */
        .anime-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to top, rgba(15, 15, 19, 0.95) 0%, rgba(15, 15, 19, 0.5) 50%, rgba(15, 15, 19, 0.1) 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .anime-card:hover .anime-overlay { opacity: 1; }
        
        .anime-title { color: white; font-weight: bold; font-size: 1.1rem; text-shadow: 1px 1px 5px #000; }
        .anime-actions .btn { backdrop-filter: blur(5px); }
        
        /* Темна пагінація */
        .custom-pagination .page-link { background-color: #1a1a1d; border-color: #333; color: white; }
        .custom-pagination .page-item.active .page-link { background-color: #ff3366; border-color: #ff3366; }
    </style>
@stop
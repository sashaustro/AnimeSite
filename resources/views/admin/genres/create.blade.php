@extends('layouts.admin')

@section('title', 'Додати жанр')
@section('page_title', 'Додати новий жанр')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h3 class="card-title m-0 font-weight-bold text-primary">Інформація про жанр</h3>
            </div>
            
            <form action="{{ route('admin.genres.store') }}" method="POST">
                @csrf
                <div class="card-body bg-light">
                    <div class="form-group">
                        <label>Назва жанру</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Наприклад: Сьонен">
                        @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="card-footer bg-white border-top">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Зберегти</button>
                    <a href="{{ route('admin.genres.index') }}" class="btn btn-default">Скасувати</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

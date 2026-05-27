@extends('layouts.admin')

@section('title', 'Додати Аніме')
@section('page_title', 'Додати нове аніме в каталог')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Основна інформація</h3>
    </div>
    
    <form action="{{ route('anime.store') }}" method="POST" enctype="multipart/form-data">
        @csrf 
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Назва аніме</label>
                        <input type="text" class="form-control" name="title" required placeholder="Введіть назву">
                    </div>
                    <div class="form-group">
                        <label>Опис</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Введіть опис"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Постер (Зображення)</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image" accept="image/*">
                                <label class="custom-file-label">Оберіть файл</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Рік випуску</label>
                        <input type="number" class="form-control" name="year" placeholder="2024" min="1900" max="2100">
                    </div>
                    <div class="form-group">
                        <label>Формат</label>
                        <input type="text" list="formats" class="form-control" name="format" placeholder="Серіал, Фільм, OVA">
                        <datalist id="formats">
                            <option value="Серіал">
                            <option value="Фільм">
                            <option value="OVA">
                            <option value="ONA">
                            <option value="Спешл">
                        </datalist>
                    </div>
                    <div class="form-group">
                        <label>Країна</label>
                        <input type="text" class="form-control" name="country" placeholder="Японія">
                    </div>
                    <div class="form-group">
                        <label>Студія</label>
                        <input type="text" list="studios" class="form-control" name="studio" placeholder="Mappa, Ufotable, etc.">
                        <datalist id="studios">
                            <option value="Mappa">
                            <option value="Ufotable">
                            <option value="Kyoto Animation">
                            <option value="Madhouse">
                            <option value="Bones">
                            <option value="A-1 Pictures">
                            <option value="CloverWorks">
                            <option value="УкрАніме">
                        </datalist>
                    </div>
                    <div class="form-group">
                        <label>Озвучка</label>
                        <input type="text" list="voices" class="form-control" name="voice_acting" placeholder="FanVoxUA, Оригінал">
                        <datalist id="voices">
                            <option value="Оригінал">
                            <option value="FanVoxUA">
                            <option value="Amanogawa">
                            <option value="Inari">
                            <option value="Студійна Толока">
                            <option value="AniUA">
                        </datalist>
                    </div>
                    <div class="form-group">
                        <label>Статус</label>
                        <select name="status" class="form-control">
                            <option value="ongoing">Онгоїнг (Ongoing)</option>
                            <option value="completed">Завершено (Completed)</option>
                            <option value="announced">Анонс (Announced)</option>
                            <option value="paused">Призупинено (Paused)</option>
                            <option value="cancelled">Скасовано (Cancelled)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Зберегти Аніме</button>
        </div>
    </form>
</div>
@endsection
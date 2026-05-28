@extends('layouts.public')

@section('title', 'AniHub - Головна')

@section('content')
    <div class="breadcrumb" style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap; padding: 0 1rem;">
        <a href="{{ route('home') }}" style="color: var(--text-muted); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--text-muted)'">AniHub</a>
        @if(isset($pageTitle) && $pageTitle !== 'Аніме Каталог' && $pageTitle !== 'Аніме')
            <i class="fas fa-chevron-right" style="font-size: 0.7rem; color: #555;"></i>
            <span style="color: var(--text-primary); font-weight: 600;">{{ $pageTitle }}</span>
        @else
            <i class="fas fa-chevron-right" style="font-size: 0.7rem; color: #555;"></i>
            <span style="color: var(--text-primary); font-weight: 600;">Аніме</span>
        @endif
    </div>
<!-- Hero Section / Featured Anime -->
@if(isset($heroAnimes) && $heroAnimes->count() > 0)
    <div class="swiper hero-swiper" style="margin-bottom: 3rem; border-radius: var(--radius-lg); overflow: hidden; background: transparent; height: 460px;">
        <div class="swiper-wrapper">
            @foreach($heroAnimes as $index => $hero)
                <div class="swiper-slide" style="height: 100%;">
                    <div style="position: relative; display: flex; align-items: center; height: 100%; padding: 3rem;">
                        <div style="flex: 1; z-index: 10;">
                            @if(isset($pageTitle) && $pageTitle == 'Топ рейтингу')
                                <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; align-items: center; flex-wrap: wrap;">
                                    <span style="background: #f1c40f; color: #000; font-weight: bold; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">{{ $index + 1 }}</span>
                                    <span style="background: rgba(255,255,255,0.1); padding: 0.2rem 0.6rem; border-radius: var(--radius-full); font-size: 0.8rem; display: flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-star" style="color: #f1c40f;"></i> {{ number_format($hero->ratings_avg_score ?? 0, 2) }}
                                    </span>
                                    <span style="background: var(--bg-dark); padding: 0.2rem 0.6rem; border-radius: var(--radius-full); font-size: 0.8rem;">{{ $hero->year ?? '2024' }}</span>
                                    <span style="background: var(--bg-dark); padding: 0.2rem 0.6rem; border-radius: var(--radius-full); font-size: 0.8rem; text-transform: uppercase;">{{ $hero->format ?? 'Серіал' }}</span>
                                </div>
                            @else
                                <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; align-items: center; flex-wrap: wrap;">
                                    <span style="background: var(--bg-dark); padding: 0.2rem 0.6rem; border-radius: var(--radius-full); font-size: 0.8rem;">{{ $hero->year ?? '2024' }}</span>
                                    <span style="background: var(--bg-dark); padding: 0.2rem 0.6rem; border-radius: var(--radius-full); font-size: 0.8rem; text-transform: uppercase;">{{ $hero->format ?? 'Серіал' }}</span>
                                    @if(isset($hero->genres) && $hero->genres->count() > 0)
                                        @foreach($hero->genres->take(3) as $genre)
                                            <span style="background: rgba(255,255,255,0.05); padding: 0.2rem 0.6rem; border-radius: var(--radius-full); font-size: 0.8rem; border: 1px solid rgba(255,255,255,0.1);">{{ $genre->name }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            @endif

                            <h1 style="font-size: 3rem; font-weight: 800; margin-bottom: 0.5rem; line-height: 1.1; text-shadow: 0 2px 10px rgba(0,0,0,0.5); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="{{ $hero->title }}">{{ $hero->title }}</h1>
                            @if($hero->original_title)
                                <h2 style="font-size: 1.1rem; font-weight: 400; color: #a0aec0; margin-bottom: 1.5rem; font-style: italic; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;" title="{{ $hero->original_title }}">{{ $hero->original_title }}</h2>
                            @endif
                            
                            <p style="color: var(--text-muted); max-width: 600px; margin-bottom: 2rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-shadow: 0 1px 5px rgba(0,0,0,0.5);">
                                {{ $hero->description }}
                            </p>
                            
                            <div style="display: flex; gap: 1rem;">
                                <a href="{{ route('anime.show', $hero->id) }}" class="btn btn-primary" style="padding: 0.8rem 2rem; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-play"></i> Дивитись зараз</a>
                            </div>
                        </div>
                        @if($hero->image)
                            <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 100%; mask-image: linear-gradient(to right, rgba(0,0,0, 0.25) 0%, black 70%); -webkit-mask-image: linear-gradient(to right, rgba(0,0,0, 0.25) 0%, black 70%); z-index: 1;">
                                <img src="{{ asset('storage/' . $hero->image) }}" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.5;" alt="poster">
                            </div>
                            <div style="position: relative; z-index: 10; width: 40%; display: flex; justify-content: flex-end; align-items: center; padding-right: 2rem;">
                                <img src="{{ asset('storage/' . $hero->image) }}" style="width: 240px; height: 340px; object-fit: cover; border-radius: 12px; box-shadow: 0 15px 35px rgba(0,0,0,0.8);" alt="poster">
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="swiper-pagination custom-pagination"></div>
    </div>
@endif

    @push('styles')
    <style>
        .custom-pagination {
            position: absolute;
            bottom: 20px !important;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            z-index: 20;
        }
        .swiper-pagination-bullet {
            width: 8px;
            height: 8px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transition: all 0.3s ease;
            opacity: 1;
            margin: 0 !important;
            cursor: pointer;
        }
        .swiper-pagination-bullet-active {
            width: 24px;
            background: #fff;
            border-radius: 4px;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swiper !== 'undefined' && document.querySelector('.hero-swiper')) {
                new Swiper('.hero-swiper', {
                    loop: true,
                    speed: 1500,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    }
                });
            }
        // Filter Panel Logic
        const filterForm = document.getElementById('filterForm');
        const gridContainer = document.getElementById('animeGridContainer');
        const activeFiltersContainer = document.getElementById('activeFiltersContainer');
        const activeFiltersList = document.getElementById('activeFiltersList');
        const clearAllFiltersBtn = document.getElementById('clearAllFilters');
        const searchInput = filterForm.querySelector('input[name="search"]');
        const sortSelect = filterForm.querySelector('select[name="sort"]');
        const studioSearch = document.getElementById('studioSearch');
        const debounce = (func, delay) => {
            let timeoutId;
            return (...args) => {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => func.apply(this, args), delay);
            };
        };

        function updateActiveTags() {
            activeFiltersList.innerHTML = '';
            let hasActive = false;

            // Search tag
            if (searchInput.value.trim() !== '') {
                hasActive = true;
                addTag('Пошук: ' + searchInput.value, '#a855f7', () => {
                    searchInput.value = '';
                    fetchFilters();
                });
            }

            // Checkboxes
            const checkboxes = filterForm.querySelectorAll('input[type="checkbox"]:checked');
            checkboxes.forEach(cb => {
                hasActive = true;
                const filterBox = cb.closest('.filter-box');
                const filterColor = filterBox ? getComputedStyle(filterBox).getPropertyValue('--filter-color').trim() : '#a855f7';
                addTag(cb.dataset.label, filterColor, () => {
                    cb.checked = false;
                    fetchFilters();
                });
            });

            // Year range tag (only if changed from min/max)
            const yearMin = filterForm.querySelector('input[name="year_min"]').value;
            const yearMax = filterForm.querySelector('input[name="year_max"]').value;
            const defaultMin = '{{ $minYear }}';
            const defaultMax = '{{ $maxYear }}';
            if (yearMin !== defaultMin || yearMax !== defaultMax) {
                hasActive = true;
                const yearColor = '#8b5cf6';
                addTag(`${yearMin} - ${yearMax}`, yearColor, () => {
                    filterForm.querySelector('input[name="year_min"]').value = defaultMin;
                    filterForm.querySelector('input[name="year_max"]').value = defaultMax;
                    document.getElementById('yearMinSlider').value = defaultMin;
                    document.getElementById('yearMaxSlider').value = defaultMax;
                    fetchFilters();
                });
            }

            activeFiltersContainer.style.display = hasActive ? 'block' : 'none';
        }

        function addTag(label, color, onRemove) {
            const tag = document.createElement('div');
            tag.className = 'active-tag pill-label';
            // Convert hex color to rgba for background
            const hexToRgba = (hex, alpha) => {
                let r = parseInt(hex.slice(1, 3), 16),
                    g = parseInt(hex.slice(3, 5), 16),
                    b = parseInt(hex.slice(5, 7), 16);
                return `rgba(${r}, ${g}, ${b}, ${alpha})`;
            };
            let bgColor = 'rgba(168, 85, 247, 0.2)';
            try { bgColor = hexToRgba(color, 0.2); } catch(e) {}
            
            tag.style.cssText = `background: ${bgColor}; border-color: ${color}; color: #fff; padding: 0.3rem 0.6rem; cursor: pointer; display: flex; align-items: center; gap: 0.4rem;`;
            tag.innerHTML = `<span>${label}</span> <i class="fas fa-times"></i>`;
            tag.addEventListener('click', onRemove);
            activeFiltersList.appendChild(tag);
        }

        function fetchFilters() {
            updateActiveTags();
            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData);
            
            // Remove empty params
            for(let [key, value] of [...params.entries()]) {
                if(!value || value === 'default' || (key === 'year_min' && value === '{{ $minYear }}') || (key === 'year_max' && value === '{{ $maxYear }}')) {
                    params.delete(key);
                }
            }

            const url = `${window.location.pathname}?${params.toString()}`;
            window.history.pushState({}, '', url);

            // Fetch AJAX
            gridContainer.style.opacity = '0.5';
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                gridContainer.innerHTML = html;
                gridContainer.style.opacity = '1';
                
                gridContainer.innerHTML = html;
                gridContainer.style.opacity = '1';
                setupInfiniteScroll();
            });
        }

        let isFetchingNextPage = false;
        let infiniteObserver = null;

        function setupInfiniteScroll() {
            if (infiniteObserver) {
                infiniteObserver.disconnect();
            }

            const trigger = document.querySelector('.infinite-scroll-trigger');
            if (!trigger) return;

            infiniteObserver = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting && !isFetchingNextPage) {
                    loadNextPage(trigger.dataset.nextPage);
                }
            }, { rootMargin: '300px' });

            infiniteObserver.observe(trigger);
        }

        function loadNextPage(url) {
            if (!url) return;
            isFetchingNextPage = true;
            
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    const newItems = doc.querySelectorAll('.anime-card');
                    const grid = document.querySelector('.anime-grid');
                    if(grid) {
                        newItems.forEach(item => grid.appendChild(item));
                    }
                    
                    const newTrigger = doc.querySelector('.infinite-scroll-trigger');
                    const oldTrigger = document.querySelector('.infinite-scroll-trigger');
                    
                    if (newTrigger && oldTrigger) {
                        oldTrigger.dataset.nextPage = newTrigger.dataset.nextPage;
                    } else if (oldTrigger) {
                        oldTrigger.remove();
                    }
                    
                    const newPagination = doc.querySelector('.pagination-wrapper');
                    const oldPagination = document.querySelector('.pagination-wrapper');
                    if (newPagination && oldPagination) {
                        oldPagination.innerHTML = newPagination.innerHTML;
                    }
                    
                    isFetchingNextPage = false;
                })
                .catch(() => {
                    isFetchingNextPage = false;
                });
        }

        const debouncedFetchFilters = debounce(fetchFilters, 500);

        // Attach event listeners
        filterForm.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            cb.addEventListener('change', fetchFilters);
        });
        sortSelect.addEventListener('change', fetchFilters);
        searchInput.addEventListener('input', debouncedFetchFilters);
        
        // Year Sliders Sync
        const yMinInput = document.getElementById('yearMinInput');
        const yMaxInput = document.getElementById('yearMaxInput');
        const yMinSlider = document.getElementById('yearMinSlider');
        const yMaxSlider = document.getElementById('yearMaxSlider');
        const yearSliderFill = document.getElementById('yearSliderFill');

        function updateSliderFill() {
            if (!yearSliderFill) return;
            const min = parseInt(yMinSlider.min);
            const max = parseInt(yMinSlider.max);
            const val1 = parseInt(yMinSlider.value);
            const val2 = parseInt(yMaxSlider.value);
            
            const start = Math.min(val1, val2);
            const end = Math.max(val1, val2);
            
            const leftPercent = ((start - min) / (max - min)) * 100;
            const widthPercent = ((end - start) / (max - min)) * 100;
            
            yearSliderFill.style.left = leftPercent + '%';
            yearSliderFill.style.width = widthPercent + '%';
        }

        [yMinSlider].forEach(el => el.addEventListener('input', (e) => {
            let val = parseInt(e.target.value);
            if(val > parseInt(yMaxSlider.value)) { val = parseInt(yMaxSlider.value); yMinSlider.value = val; }
            yMinInput.value = val;
            updateSliderFill();
            debouncedFetchFilters();
        }));
        
        [yMaxSlider].forEach(el => el.addEventListener('input', (e) => {
            let val = parseInt(e.target.value);
            if(val < parseInt(yMinSlider.value)) { val = parseInt(yMinSlider.value); yMaxSlider.value = val; }
            yMaxInput.value = val;
            updateSliderFill();
            debouncedFetchFilters();
        }));

        [yMinInput, yMaxInput].forEach(el => el.addEventListener('input', (e) => {
            // Restrict to 4 digits
            if(e.target.value.length > 4) e.target.value = e.target.value.slice(0, 4);
            
            let val = parseInt(e.target.value);
            const minAllowed = parseInt(yMinSlider.min);
            const maxAllowed = parseInt(yMaxSlider.max);
            
            // Only update slider and fetch if it's a valid year
            if(!isNaN(val) && val >= 1900 && val <= new Date().getFullYear()) {
                if(el === yMinInput && val > parseInt(yMaxInput.value || maxAllowed)) val = parseInt(yMaxInput.value || maxAllowed);
                if(el === yMaxInput && val < parseInt(yMinInput.value || minAllowed)) val = parseInt(yMinInput.value || minAllowed);
                
                if(el === yMinInput) yMinSlider.value = val;
                if(el === yMaxInput) yMaxSlider.value = val;
                
                updateSliderFill();
                debouncedFetchFilters();
            }
        }));

        [yMinInput, yMaxInput].forEach(el => el.addEventListener('blur', (e) => {
            let val = parseInt(e.target.value);
            const minAllowed = parseInt(yMinSlider.min);
            const maxAllowed = parseInt(yMaxSlider.max);
            
            if(isNaN(val) || val < 1900 || val > new Date().getFullYear()) {
                // reset to slider value if invalid on blur
                e.target.value = el === yMinInput ? yMinSlider.value : yMaxSlider.value;
            }
        }));
        
        // Initial fill update
        updateSliderFill();

        clearAllFiltersBtn.addEventListener('click', () => {
            filterForm.reset();
            // Reset selects/hidden explicitly
            sortSelect.value = 'default';
            yMinInput.value = '{{ $minYear }}';
            yMaxInput.value = '{{ $maxYear }}';
            yMinSlider.value = '{{ $minYear }}';
            yMaxSlider.value = '{{ $maxYear }}';
            fetchFilters();
        });

        // Studio Search
        studioSearch.addEventListener('input', function() {
            const val = this.value.toLowerCase();
            document.querySelectorAll('.studio-item').forEach(item => {
                const name = item.querySelector('.studio-name').textContent.toLowerCase();
                item.style.display = name.includes(val) ? 'flex' : 'none';
            });
        });

        // Accordion Toggle
        document.querySelectorAll('.accordion-toggle').forEach(header => {
            header.addEventListener('click', () => {
                const body = header.nextElementSibling;
                const icon = header.querySelector('i');
                if (body.style.display === 'none') {
                    body.style.display = 'block';
                    icon.classList.replace('fa-chevron-right', 'fa-chevron-down');
                } else {
                    body.style.display = 'none';
                    icon.classList.replace('fa-chevron-down', 'fa-chevron-right');
                }
            });
        });

        // Init tags on load
        updateActiveTags();

        // Pagination init
        const paginationLinks = gridContainer.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const pageUrl = this.href;
                window.history.pushState({}, '', pageUrl);
                gridContainer.style.opacity = '0.5';
                fetch(pageUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(res => res.text())
                    .then(html => {
                        gridContainer.innerHTML = html;
                        gridContainer.style.opacity = '1';
                    });
            });
        });
        
            setupInfiniteScroll();

    }); // Close DOMContentLoaded
    </script>
    <style>
        .catalog-layout {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 2rem;
            align-items: start;
        }
        @media (max-width: 900px) {
            .catalog-layout { grid-template-columns: 1fr; }
            .filter-sidebar { display: none; /* Add mobile toggle later if needed */ }
        }
        
        .filter-sidebar {
            background: transparent;
        }
        
        .filter-box {
            background: var(--bg-card);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: var(--radius-md);
            margin-bottom: 1rem;
            padding: 1.2rem;
        }
        
        .filter-header {
            font-weight: 600;
            color: #fff;
            text-transform: uppercase;
            font-size: 0.9rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .accordion-toggle {
            cursor: pointer;
            user-select: none;
        }
        
        .filter-content {
            margin-top: 1rem;
        }
        
        .search-input-wrapper {
            position: relative;
        }
        .search-input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }
        .search-input-wrapper input {
            width: 100%;
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: var(--radius-md);
            color: #fff;
            outline: none;
        }
        
        .custom-select {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            padding: 0.4rem 0.8rem;
            border-radius: var(--radius-md);
            outline: none;
            width: 100%;
        }
        .custom-select option { background: var(--bg-card); }
        
        .pill-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .pill-checkbox input { display: none; }
        .pill-label {
            padding: 0.4rem 0.8rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: var(--radius-md);
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-block;
        }
        .pill-checkbox input:checked + .pill-label {
            background: var(--filter-color, var(--primary-color, #a855f7));
            border-color: var(--filter-color, var(--primary-color, #a855f7));
            color: #fff;
        }
        
        .scrollable-list {
            max-height: 250px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            padding-right: 0.5rem;
        }
        .scrollable-list::-webkit-scrollbar { width: 5px; }
        .scrollable-list::-webkit-scrollbar-track { background: transparent; }
        .scrollable-list::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        
        .custom-checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            cursor: pointer;
            color: #a0aec0;
            transition: color 0.2s;
        }
        .custom-checkbox-label:hover { color: #fff; }
        .custom-checkbox-label input { display: none; }
        .custom-checkbox {
            width: 16px;
            height: 16px;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 3px;
            position: relative;
            flex-shrink: 0;
            transition: all 0.2s;
        }
        .custom-checkbox-label input:checked + .custom-checkbox {
            background: var(--filter-color, var(--primary-color, #a855f7));
            border-color: var(--filter-color, var(--primary-color, #a855f7));
        }
        .custom-checkbox-label input:checked + .custom-checkbox::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: #fff;
            font-size: 10px;
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
        }
        .custom-checkbox-label input:checked ~ .studio-name { color: #fff; }
        
        .item-count {
            margin-left: auto;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.3);
        }
        
        .year-input {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            padding: 0.4rem;
            border-radius: var(--radius-sm);
            text-align: center;
            outline: none;
            -moz-appearance: textfield; /* Firefox */
        }
        .year-input::-webkit-outer-spin-button,
        .year-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        .range-slider-container {
            position: relative;
            height: 4px;
            background: rgba(255,255,255,0.1);
            border-radius: 2px;
            margin-top: 1rem;
        }
        .slider-fill {
            position: absolute;
            height: 100%;
            background: var(--filter-color, var(--primary-color, #a855f7));
            border-radius: 2px;
            top: 0;
            z-index: 1;
        }
        .range-slider-container input[type=range] {
            position: absolute;
            width: 100%;
            -webkit-appearance: none;
            background: transparent;
            pointer-events: none;
            top: -6px;
            z-index: 2;
        }
        .range-slider-container input[type=range]::-webkit-slider-thumb {
            pointer-events: all;
            width: 16px;
            height: 16px;
            -webkit-appearance: none;
            background: #fff;
            border-radius: 50%;
            border: 2px solid var(--filter-color, var(--primary-color, #a855f7));
            cursor: pointer;
        }
        #animeGridContainer {
            transition: opacity 0.3s ease;
        }
    </style>
@endpush

<!-- Catalog Section with Sidebar -->
<div class="catalog-layout">
    <!-- Filter Sidebar -->
    <aside class="filter-sidebar">
        <form id="filterForm">
            <!-- Active Filters -->
            <div id="activeFiltersContainer" style="display: none; margin-bottom: 1rem; background: var(--bg-card); padding: 1.2rem; border-radius: var(--radius-md); border: 1px solid rgba(255,255,255,0.05);">
                <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.8rem; text-transform: uppercase; font-weight: 600;">Активні фільтри:</div>
                <div id="activeFiltersList" style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem;"></div>
                <button type="button" id="clearAllFilters" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; cursor: pointer; font-size: 0.8rem; padding: 0.4rem 1rem; border-radius: var(--radius-full); transition: all 0.2s; width: 100%; text-align: center;">
                    <i class="fas fa-trash-alt" style="margin-right: 0.4rem;"></i> Очистити все
                </button>
            </div>

            <!-- Search & Sort Box -->
            <div class="filter-box">
                <div class="filter-header">ПОШУК</div>
                <div class="filter-content">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" placeholder="Назва аніме..." value="{{ request('search') }}">
                    </div>
                    <div class="sort-wrapper" style="margin-top: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <select name="sort" class="custom-select" style="width: 100%;">
                            <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>За замовчуванням</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Спочатку нові</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Спочатку старі</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>За популярністю</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>За рейтингом</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Type -->
            <div class="filter-box accordion-box" style="--filter-color: #8b5cf6;">
                <div class="filter-header accordion-toggle">ТИП <i class="fas fa-chevron-down"></i></div>
                <div class="filter-content accordion-body">
                    <div class="pill-group">
                        @foreach($filterFormats as $format)
                            <label class="pill-checkbox">
                                <input type="checkbox" name="formats[]" value="{{ $format }}" {{ in_array($format, request('formats', [])) ? 'checked' : '' }} data-label="{{ $format }}">
                                <span class="pill-label">{{ $format }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="filter-box accordion-box" style="--filter-color: #ec4899;">
                <div class="filter-header accordion-toggle">СТАТУС <i class="fas fa-chevron-down"></i></div>
                <div class="filter-content accordion-body">
                    <div class="pill-group">
                        @foreach($filterStatuses as $status)
                            @php
                                $statusLabel = $status;
                                if($status == 'ongoing') $statusLabel = "Онгоїнг";
                                if($status == 'completed') $statusLabel = "Завершено";
                                if($status == 'announced') $statusLabel = "Анонс";
                                if($status == 'paused') $statusLabel = "Призупинено";
                                if($status == 'cancelled') $statusLabel = "Скасовано";
                            @endphp
                            <label class="pill-checkbox">
                                <input type="checkbox" name="statuses[]" value="{{ $status }}" {{ in_array($status, request('statuses', [])) ? 'checked' : '' }} data-label="{{ $statusLabel }}">
                                <span class="pill-label">{{ $statusLabel }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Season (Mockup) -->
            <div class="filter-box accordion-box" style="--filter-color: #06b6d4;">
                <div class="filter-header accordion-toggle">ПОРА РОКУ <i class="fas fa-chevron-down"></i></div>
                <div class="filter-content accordion-body">
                    <div class="pill-group">
                        @foreach(['Зима', 'Весна', 'Літо', 'Осінь'] as $season)
                            <label class="pill-checkbox">
                                <input type="checkbox" name="seasons[]" value="{{ $season }}" {{ in_array($season, request('seasons', [])) ? 'checked' : '' }} data-label="{{ $season }}">
                                <span class="pill-label">{{ $season }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Genres -->
            <div class="filter-box accordion-box" style="--filter-color: #6366f1;">
                <div class="filter-header accordion-toggle">ЖАНРИ <i class="fas fa-chevron-down"></i></div>
                <div class="filter-content accordion-body scrollable-list">
                    @foreach($filterGenres as $genre)
                        <label class="custom-checkbox-label">
                            <input type="checkbox" name="genres[]" value="{{ $genre->id }}" {{ in_array($genre->id, request('genres', [])) ? 'checked' : '' }} data-label="{{ $genre->name }}">
                            <span class="custom-checkbox"></span>
                            {{ $genre->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Studio -->
            <div class="filter-box accordion-box" style="--filter-color: #3b82f6;">
                <div class="filter-header accordion-toggle">СТУДІЯ АНІМАЦІЇ <i class="fas fa-chevron-down"></i></div>
                <div class="filter-content accordion-body">
                    <div class="search-input-wrapper" style="margin-bottom: 1rem;">
                        <i class="fas fa-search"></i>
                        <input type="text" id="studioSearch" placeholder="Пошук студії...">
                    </div>
                    <div class="scrollable-list" id="studioList">
                        @foreach($filterStudios as $studio)
                            <label class="custom-checkbox-label studio-item">
                                <input type="checkbox" name="studios[]" value="{{ $studio->studio }}" {{ in_array($studio->studio, request('studios', [])) ? 'checked' : '' }} data-label="{{ $studio->studio }}">
                                <span class="custom-checkbox"></span>
                                <span class="studio-name">{{ $studio->studio }}</span>
                                <span class="item-count">{{ $studio->total }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Country -->
            <div class="filter-box accordion-box" style="--filter-color: #10b981;">
                <div class="filter-header accordion-toggle">КРАЇНА <i class="fas fa-chevron-down"></i></div>
                <div class="filter-content accordion-body">
                    <div class="pill-group">
                        @foreach($filterCountries as $country)
                            <label class="pill-checkbox">
                                <input type="checkbox" name="countries[]" value="{{ $country }}" {{ in_array($country, request('countries', [])) ? 'checked' : '' }} data-label="{{ $country }}">
                                <span class="pill-label">{{ $country }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Year -->
            <div class="filter-box accordion-box" style="--filter-color: #8b5cf6;">
                <div class="filter-header accordion-toggle">РІК ВИПУСКУ <i class="fas fa-chevron-down"></i></div>
                <div class="filter-content accordion-body">
                    <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                        <input type="number" id="yearMinInput" name="year_min" value="{{ request('year_min', $minYear) }}" class="year-input">
                        <span style="color: #a0aec0; display: flex; align-items: center;">-</span>
                        <input type="number" id="yearMaxInput" name="year_max" value="{{ request('year_max', $maxYear) }}" class="year-input">
                    </div>
                    <!-- simple range inputs for mockup, or use nouislider later -->
                    <div class="range-slider-container" style="--filter-color: #8b5cf6;">
                        <div class="slider-fill" id="yearSliderFill"></div>
                        <input type="range" id="yearMinSlider" min="{{ $minYear }}" max="{{ $maxYear }}" value="{{ request('year_min', $minYear) }}">
                        <input type="range" id="yearMaxSlider" min="{{ $minYear }}" max="{{ $maxYear }}" value="{{ request('year_max', $maxYear) }}">
                    </div>
                </div>
            </div>
        </form>
    </aside>

    <!-- Main Content Grid -->
    <div class="catalog-content">
        <section style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <h2 class="section-title" style="margin-bottom: 0;">
                    @if(request()->routeIs('anime.top'))
                        ТОП-100 АНІМЕ
                    @else
                        {{ $pageTitle ?? 'Аніме каталог' }}
                    @endif
                </h2>
                @if(isset($animes))
                    <span style="color: var(--text-muted); font-size: 0.95rem; background: rgba(255,255,255,0.05); padding: 0.3rem 0.8rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                        Знайдено: <strong style="color: var(--text-primary);">{{ method_exists($animes, 'total') ? $animes->total() : $animes->count() }}</strong> аніме
                    </span>
                @endif
            </div>
            @if(request()->routeIs('anime.top'))
                <a href="{{ route('home', ['sort' => 'rating']) }}" style="color: var(--accent-color); text-decoration: none; font-size: 0.95rem; font-weight: 500; display: flex; align-items: center; gap: 0.5rem; transition: color 0.2s;">
                    Переглянути всі <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
                </a>
            @endif
        </section>

        <div id="animeGridContainer">
            @include('Anime.partials.grid')
        </div>
    </div>
</div>
@endsection

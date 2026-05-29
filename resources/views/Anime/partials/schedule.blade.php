@if(isset($schedule) && count($schedule) > 0)
    <div class="schedule-container" style="background: rgba(30, 32, 45, 0.6); border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; padding: 1.5rem; margin-bottom: 2rem; position: relative;">
        <div id="scheduleHeader" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="color: #fff; font-size: 1.2rem; font-weight: 600; margin: 0 0 0.2rem 0;">Розклад виходу</h3>
                <p style="color: var(--text-muted); font-size: 0.8rem; margin: 0;">Дата виходу в оригіналі (Японія)</p>
            </div>
            <button id="toggleScheduleBtn" style="background: rgba(255,255,255,0.08); border: none; color: #a0aec0; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; font-size: 1.1rem;">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>

        <div id="scheduleContent" style="display: none; margin-top: 1.5rem;">
            <!-- Дні тижня -->
            <div class="schedule-days" style="display: flex; gap: 0.8rem; overflow-x: auto; padding-bottom: 1rem; margin-bottom: 1rem; scrollbar-width: none;">
                @foreach($schedule as $index => $day)
                    <div class="schedule-day {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}" style="min-width: 50px; text-align: center; cursor: pointer; position: relative;">
                        @if($index === 0)
                            <div class="active-dot" style="width: 6px; height: 6px; background: #a855f7; border-radius: 50%; position: absolute; top: -10px; left: 50%; transform: translateX(-50%); box-shadow: 0 0 8px #a855f7;"></div>
                        @endif
                        <div class="day-bubble" style="background: {{ $index === 0 ? 'rgba(168, 85, 247, 0.1)' : 'rgba(255,255,255,0.03)' }}; border: 1px solid {{ $index === 0 ? 'rgba(168, 85, 247, 0.3)' : 'transparent' }}; border-radius: 12px; padding: 0.5rem; transition: all 0.2s ease;">
                            <div style="color: {{ $index === 0 ? '#a855f7' : 'var(--text-muted)' }}; font-size: 0.7rem; font-weight: 600; text-transform: uppercase;">{{ $day['day_name'] }}</div>
                            <div style="color: #fff; font-size: 1.1rem; font-weight: 700;">{{ $day['day_number'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Епізоди -->
            <div class="schedule-episodes-container">
                @foreach($schedule as $index => $day)
                    <div class="schedule-episodes-list" id="episodes-list-{{ $index }}" style="display: {{ $index === 0 ? 'grid' : 'none' }}; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem;">
                        @forelse($day['episodes'] as $ep)
                            <a href="{{ route('anime.show', $ep['anime_id']) }}" class="episode-card" style="display: flex; align-items: center; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.02); border-radius: 12px; padding: 0.8rem; text-decoration: none; transition: all 0.2s ease; gap: 1rem;">
                                <div style="color: #fff; font-weight: 600; font-size: 0.95rem; min-width: 45px;">{{ $ep['time'] }}</div>
                                @if($ep['image'])
                                    <img src="{{ asset('storage/' . $ep['image']) }}" alt="{{ $ep['title'] }}" style="width: 40px; height: 50px; border-radius: 6px; object-fit: cover;">
                                @else
                                    <div style="width: 40px; height: 50px; border-radius: 6px; background: #26293d; display: flex; align-items: center; justify-content: center; color: #666; font-size: 0.7rem;">Нет</div>
                                @endif
                                <div style="flex: 1; overflow: hidden;">
                                    <div style="color: #fff; font-size: 0.9rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 0.2rem;">{{ $ep['title'] }}</div>
                                    <div style="color: var(--text-muted); font-size: 0.75rem; background: rgba(255,255,255,0.05); padding: 0.1rem 0.4rem; border-radius: 4px; display: inline-block;">Еп. {{ $ep['episode'] }}</div>
                                </div>
                                <i class="fas fa-chevron-right" style="color: var(--text-muted); font-size: 0.8rem; opacity: 0.5;"></i>
                            </a>
                        @empty
                            <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 2rem; background: rgba(255,255,255,0.02); border-radius: 12px;">
                                На цей день немає запланованих випусків.
                            </div>
                        @endforelse
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        .schedule-days::-webkit-scrollbar { display: none; }
        .episode-card:hover { background: rgba(255,255,255,0.06) !important; border-color: rgba(255,255,255,0.1) !important; transform: translateY(-2px); }
        .episode-card:hover .fa-chevron-right { opacity: 1 !important; color: #a855f7 !important; }
        .day-bubble:hover { background: rgba(255,255,255,0.08) !important; }
        #toggleScheduleBtn:hover { background: rgba(255,255,255,0.15) !important; color: #fff !important; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggleScheduleBtn');
            const content = document.getElementById('scheduleContent');
            const days = document.querySelectorAll('.schedule-day');
            const lists = document.querySelectorAll('.schedule-episodes-list');

            if(toggleBtn && content) {
                toggleBtn.addEventListener('click', function() {
                    if (content.style.display === 'none') {
                        content.style.display = 'block';
                        toggleBtn.innerHTML = '<i class="fas fa-chevron-up"></i>';
                    } else {
                        content.style.display = 'none';
                        toggleBtn.innerHTML = '<i class="fas fa-chevron-down"></i>';
                    }
                });
            }

            days.forEach(day => {
                day.addEventListener('click', function() {
                    // Remove active from all
                    days.forEach(d => {
                        d.classList.remove('active');
                        let dot = d.querySelector('.active-dot');
                        if(dot) dot.remove();
                        let bubble = d.querySelector('.day-bubble');
                        bubble.style.background = 'rgba(255,255,255,0.03)';
                        bubble.style.borderColor = 'transparent';
                        bubble.querySelector('div').style.color = 'var(--text-muted)';
                    });

                    // Hide all lists
                    lists.forEach(l => l.style.display = 'none');

                    // Set active
                    this.classList.add('active');
                    this.innerHTML += '<div class="active-dot" style="width: 6px; height: 6px; background: #a855f7; border-radius: 50%; position: absolute; top: -10px; left: 50%; transform: translateX(-50%); box-shadow: 0 0 8px #a855f7;"></div>';
                    
                    let bubble = this.querySelector('.day-bubble');
                    bubble.style.background = 'rgba(168, 85, 247, 0.1)';
                    bubble.style.borderColor = 'rgba(168, 85, 247, 0.3)';
                    bubble.querySelector('div').style.color = '#a855f7';

                    // Show target list
                    let index = this.getAttribute('data-index');
                    document.getElementById('episodes-list-' + index).style.display = 'grid';
                });
            });
        });
    </script>
@endif

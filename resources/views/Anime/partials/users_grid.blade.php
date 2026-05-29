<div class="users-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
    @forelse($users as $user)
        <div class="user-card" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s ease;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: #26293d; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #a0aec0; overflow: hidden;">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    {{ mb_strtoupper(mb_substr($user->username, 0, 1)) }}
                @endif
            </div>
            <h3 style="font-size: 1.1rem; font-weight: 600; color: #fff; margin-bottom: 0.2rem;">{{ $user->name }}</h3>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem;">{{ '@' . $user->username }}</p>
            @if($user->isAdmin())
                <span class="badge badge-danger" style="background: rgba(220, 53, 69, 0.2); color: #ff6b6b; padding: 0.2rem 0.6rem; font-size: 0.75rem; border-radius: 12px; border: 1px solid rgba(220, 53, 69, 0.3);">Адміністратор</span>
            @else
                <span class="badge badge-secondary" style="background: rgba(108, 117, 125, 0.2); color: #adb5bd; padding: 0.2rem 0.6rem; font-size: 0.75rem; border-radius: 12px; border: 1px solid rgba(108, 117, 125, 0.3);">Користувач</span>
            @endif
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; color: var(--text-muted); background: rgba(255,255,255,0.02); border-radius: 12px;">
            <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <h3 style="color: #fff; margin-bottom: 0.5rem;">Користувачів не знайдено</h3>
            <p>За запитом "<strong>{{ request('search') }}</strong>" нікого не знайдено.</p>
        </div>
    @endforelse
</div>

@if($users->hasPages())
    <div class="pagination-wrapper" style="margin-top: 3rem; display: flex; justify-content: center;">
        {{ $users->links() }}
    </div>
    @if($users->hasMorePages())
        <div class="infinite-scroll-trigger" data-next-page="{{ $users->nextPageUrl() }}" style="height: 20px;"></div>
    @endif
@endif

<style>
    .pagination .page-item {
        margin: 0 5px;
    }

    .pagination a {
        padding: 0.5rem;
        border-radius: 0.25rem;
        text-decoration: none;
    }

    .pagination .active {
        font-weight: bold;
}
</style>
<div class="flex flex-wrap justify-center gap-4">
    @forelse ($users as $user)
        <div class="text-center user-item" data-name="{{ $user->awardee->fullname }}" data-year="{{ $user->awardee->year }}" data-faculty="{{ $user->awardee->studyProgram->faculty }}">
            <a href="{{ route('landing-page.awardee.show', ['user' => $user->id]) }}" class="inline-block">
                <div class="mx-auto overflow-hidden rounded-full w-52 h-52">
                    <img src="{{ $user->pp_url ? asset('storage/' . $user->pp_url) : asset('img/undraw_profile.svg') }}" alt="{{ $user->awardee->fullname }}" class="object-cover w-full h-full">
                </div>
                <p class="mt-2">{{ $user->awardee->username }}</p>
            </a>
        </div>
    @empty
        <p class="font-semibold text-center">No awardees found.</p>
    @endforelse
</div>

<div class="mt-4">
    {{ $users->links() }}  <!-- Menampilkan pagination -->
</div>

@if (auth()->check() == true)
    @if (auth()->user()->partner && auth()->user()->partner->is_active == false)
        <x-filament::badge color="warning" icon="heroicon-m-sparkles">
            <h1>Akun mitra sedang ditinjau, beberapa akses sepertinya dikunci, mohon menunggu proses verifikasi.</h1>
        </x-filament::badge>
    @endif
@endif

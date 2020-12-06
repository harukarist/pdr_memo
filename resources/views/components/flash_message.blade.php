        {{-- フラッシュメッセージ --}}
        @if (session('content_flash_message'))
            <div class="alert alert-primary text-center my-2" role="alert">
                {{ session('content_flash_message') }}
            </div>
        @endif

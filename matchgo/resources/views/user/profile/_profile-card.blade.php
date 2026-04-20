<div class="mg-card text-center">

    {{-- Avatar --}}
    <div class="flex justify-center mb-5">
        @if($user->photo)
            <img src="{{ asset('storage/' . $user->photo) }}" 
                 class="mg-avatar"
                 id="avatarPreview">
        @else
            <div class="mg-avatar flex items-center justify-center" id="avatarPreview">
                {{ strtoupper(substr($user->name,0,2)) }}
            </div>
        @endif
    </div>

    <h2 class="font-bold">{{ $user->name }}</h2>
    <p class="text-gray-400">{{ $user->email }}</p>

    {{-- FORM FOTO (AUTO SUBMIT) --}}
    <form id="photoForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="file" 
               name="photo" 
               id="photoInput"
               accept="image/*"
               hidden>

        <button type="button"
            onclick="document.getElementById('photoInput').click()" 
            class="btn-outline-mg mt-4 w-full">
            Ubah Foto
        </button>
    </form>

</div>
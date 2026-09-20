@props([
    'title' => 'Are you sure?',
    'text' => 'This action cannot be undone.',
    'confirmText' => 'Confirm',
    'confirmClass' => 'bg-red-600 hover:bg-red-700 text-white',
    'action' => '#'
])

<form action="{{ $action }}" method="POST"
      x-data
      @submit.prevent="
        Swal.fire({
            title: '{{ $title }}',
            text: '{{ $text }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ $confirmText }}',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b'
        }).then((result) => {
            if (result.isConfirmed) {
                $el.submit();
            }
        })
      ">
    @csrf
    {{ $slot }}
</form>

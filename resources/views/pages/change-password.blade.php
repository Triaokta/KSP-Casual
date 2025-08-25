@extends('layout.main')

@section('content')
{{-- File ini sudah tidak digunakan karena fungsi ubah password sudah dipindahkan ke halaman profile.blade.php --}}
<script>
    window.location.href = '{{ route("profile.show") }}';
</script>
@endsection

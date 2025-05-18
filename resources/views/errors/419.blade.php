@extends('errors::minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message')
<div style="text-align: center;">
    <p>
        <a href="{{ route('login') }}" style="color: #008B8B; text-decoration: underline;">Back to login</a>
    </p>
    <script>
        // Redirect to login page after 5 seconds
        setTimeout(function(){
            window.location.href = "{{ route('login') }}";
        }, 5000);
    </script>
    <p>{{ __('You will be redirected in 5 seconds.') }}</p>
</div>
@endsection

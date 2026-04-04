<h1>Leave.Admin.page</h1>
@php
dump(asset('/'));
dump(asset('modules/leave/resources/js/script.js'));
@endphp


{{-- <script src="{{asset('modules/Leave/resources/js/script.js')}}"></script>
<script src="{{asset('js/main.js')}}"></script> --}}
<script src="{{ asset('modules/leave/js/script.js') }}"></script>
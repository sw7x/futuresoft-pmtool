<h1>Reporting.Admin.page</h1>
@php
dump(asset('/'));
dump(asset('modules/Reporting/resources/js/script.js'));
@endphp


{{-- <script src="{{asset('modules/Reporting/resources/js/script.js')}}"></script>
<script src="{{asset('js/main.js')}}"></script> --}}
<script src="{{ asset('modules/reporting/js/script.js') }}"></script>
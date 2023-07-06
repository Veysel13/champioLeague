@push('footer')
    @if(session('errors'))
        <script type="text/javascript">
            $.toast({
                heading: 'Hata',
                text: '{{ is_object(session('errors')) ? session('errors')->first() : session('errors') }}',
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'error',
                hideAfter: 3500,
                stack: 10
            });
        </script>
    @endif
@endpush

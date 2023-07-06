@push('footer')
    @if(session('success'))
    <script type="text/javascript">
            $.toast({
                heading: 'Success',
                text: '{{ session('success') }}',
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'success',
                hideAfter: 3500,
                stack: 6
            });
    </script>
    @endif
@endpush

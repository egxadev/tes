<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') &mdash; EXAMPLE</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">

    @stack('style')

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <style>
        #primary-table_processing div div {
            background: red !important;
        }
    </style>
</head>
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <!-- Header -->
            @include('components.header')

            <!-- Sidebar -->
            @include('components.sidebar')

            <!-- Content -->
            <div class="main-content">
                <section class="section">
                    @include('components.breadcrumb')

                    <div class="section-body">
                        @yield('main')
                    </div>
                </section>
            </div>

            <!-- Footer -->
            @include('components.footer')
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/popper.js/dist/umd/popper.js') }}"></script>
    <script src="{{ asset('library/tooltip.js/dist/umd/tooltip.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('library/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/stisla.js') }}"></script>

    @stack('scripts')

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>

    <script>
        @if (session()->has('success'))
            iziToast.success({
                title: 'Success!',
                message: '{{ session('success') }}',
                position: 'topRight'
            });
        @elseif (session()->has('error'))
            iziToast.error({
                title: 'Gagal!',
                message: '{{ session('error') }}',
                position: 'topRight'
            });
        @endif
    </script>

    <script>
        function deleteAction(id, url) {
            var id = id;
            var token = $("meta[name='csrf-token']").attr("content");
            swal({
                    title: 'Apakah Anda yakin?',
                    text: 'Setelah dihapus, Anda tidak akan dapat memulihkan data ini!',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((result) => {
                    if (result) {
                        jQuery.ajax({
                            url: url,
                            data: {
                                "id": id,
                                "_token": token
                            },
                            type: 'DELETE',
                            success: function(response) {
                                if (response.status == "success") {
                                    iziToast.success({
                                        timeout: 2000,
                                        title: 'Success!',
                                        message: 'Data Berhasil Dihapus',
                                        position: 'topRight',
                                        onClosed: function() {
                                            $('#primary-table').DataTable().ajax.reload();
                                        }
                                    });
                                } else {
                                    iziToast.error({
                                        timeout: 2000,
                                        title: 'Error!',
                                        message: 'Data Gagal Dihapus',
                                        position: 'topRight',
                                    });
                                }
                            }
                        });
                    } else {
                        return true;
                    }
                });
        }
    </script>
</body>

</html>

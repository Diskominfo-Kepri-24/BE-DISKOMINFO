<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email Berhasil</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Verifikasi Email Berhasil') }}</div>

                    <div class="card-body">
                        <p>Email Anda telah berhasil diverifikasi. Silahkan menunggu email pengunguman oleh Diskominfo Provinsi Kepulauan Riau.</p>
                        {{-- <a href="{{ url('/') }}" class="btn btn-primary">Kembali ke Beranda</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

@component('mail::message')
# Halo, {{ $user->nama }}

Terima kasih telah mendaftar magang di Diskominfo Provinsi Kepulauan Riau. Untuk keamanan anda, mohon verifikasi alamat email Anda dengan mengklik tombol di bawah ini:

@component('mail::button', ['url' => $url])
Verifikasi Email
@endcomponent

Jika Anda tidak membuat akun ini, Anda dapat mengabaikan email ini.
<hr>
Email akan kadaluarsa dalam satu jam.

Hormat kami,<br>
DISKOMINFO KEPRI

<hr>

Jika Anda mengalami kesulitan dalam mengklik tombol "Verifikasi Email", salin dan tempel URL di bawah ini ke browser web Anda:
[{{ $url }}]({{ $url }})

&copy; 2024 DISKOMINFO KEPRI.
@endcomponent

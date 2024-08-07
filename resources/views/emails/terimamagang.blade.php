<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman Penerimaan Magang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            /* background-color: #4CAF50; */
            /* color: white; */
            padding: 10px 0;
        }

        .content {
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #777777;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            background-color: #4CAF50;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Pengumuman Penerimaan Magang Diskominfo Provinsi Kepulauan Riau</h1>
        </div>
        <div class="content">
            <p>Yth. <strong>{{ $name }}</strong>,</p>
            <p>Kami dengan senang hati mengumumkan bahwa Anda telah diterima untuk mengikuti program magang di
                <strong>Diskominfo Provinsi Kepulauan Riau</strong>. Kami sangat antusias menyambut Anda di tim kami dan berharap
                pengalaman ini akan bermanfaat bagi pengembangan karir Anda.</p>
            <p>Detail program magang adalah sebagai berikut:</p>
            <ul>
                <li><strong>Periode Magang:</strong> {{ $tanggal_mulai }} - {{ $tanggal_selesai }}</li>
                <li><strong>Lokasi:</strong> Pusat Pemerintahan Provinsi Kepulauan Riau Istana Kota Piring Gedung Sultan Mahmud Riayat Syah, Dompak, Bukit Bestari, 29124 Tanjungpinang - Kepulauan Riau - Indonesia</li>
            </ul>
            <hr>
            Silakan gunakan akun ini untuk login <br><br>
            email: {{ $email }}
            <br>
            password: {{ $password }}
            <hr>
            <p>Terima kasih dan kami menantikan kehadiran Anda.</p>
            <p>Salam,</p>
            <p><strong>Diskominfo Kepulauan Riau</strong></p>
        </div>
        <div class="footer">
            <p>&copy; 2024 Dinas Komunikasi dan Informatika Provinsi Kepulauan Riau.</p>
        </div>
    </div>
</body>

</html>

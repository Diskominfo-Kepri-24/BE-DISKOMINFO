<?php 

namespace App\Enums;

enum RoleEnum: string {
    case admin = 'admin';
    case mahasiswa = 'mahasiswa';
    case siswa = 'siswa';
    case pembimbing = 'pembimbing';
    case guest = 'guest';
}

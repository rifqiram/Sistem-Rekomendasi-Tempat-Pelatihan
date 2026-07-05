<?php
$content = file_get_contents('resources/views/welcome.blade.php');

// 1. REFACTOR: PROGRAM PELATIHAN -> KATEGORI BIDANG PELATIHAN
$oldPrograms = <<<'PHP'
                    @php
                        $programs = [
                            ['title' => 'Web Development dengan Laravel', 'desc' => 'Mempelajari pengembangan aplikasi web modern menggunakan framework Laravel mulai dari konsep MVC, database, migration, authentication, hingga deployment.', 'color' => 'bg-red-50 text-red-600'],
                            ['title' => 'Backend API Development', 'desc' => 'Pelatihan pembuatan REST API menggunakan Laravel untuk integrasi data antar sistem.', 'color' => 'bg-orange-50 text-orange-600'],
                            ['title' => 'Database Design dan SQL', 'desc' => 'Memahami perancangan basis data, relasi tabel, query SQL, normalisasi, dan optimasi database.', 'color' => 'bg-yellow-50 text-yellow-600'],
                            ['title' => 'UI/UX Design dengan Figma', 'desc' => 'Mendesain antarmuka aplikasi yang modern, interaktif, dan mudah digunakan menggunakan Figma.', 'color' => 'bg-purple-50 text-purple-600'],
                            ['title' => 'Android Development dengan Kotlin', 'desc' => 'Mengembangkan aplikasi Android modern menggunakan bahasa pemrograman Kotlin.', 'color' => 'bg-green-50 text-green-600'],
                            ['title' => 'Data Analysis dengan Python', 'desc' => 'Belajar pengolahan dan analisis data menggunakan Python dan library data science.', 'color' => 'bg-blue-50 text-blue-600'],
                            ['title' => 'Cyber Security Dasar', 'desc' => 'Memahami konsep keamanan sistem, jaringan, dan perlindungan data digital.', 'color' => 'bg-rose-50 text-rose-600'],
                            ['title' => 'Cloud Computing dan DevOps', 'desc' => 'Pengenalan Docker, deployment, cloud services, serta otomatisasi pengembangan perangkat lunak.', 'color' => 'bg-sky-50 text-sky-600'],
                        ];
                    @endphp
PHP;

$newCategories = <<<'PHP'
                    @php
                        $programs = [
                            ['title' => 'IT & Teknologi', 'desc' => 'Rekomendasi tempat pelatihan untuk pemrograman web, mobile, keamanan siber, dan ilmu data.', 'color' => 'bg-blue-50 text-blue-600'],
                            ['title' => 'Bisnis & Manajemen', 'desc' => 'Temukan lembaga pelatihan untuk digital marketing, akuntansi, dan strategi bisnis.', 'color' => 'bg-orange-50 text-orange-600'],
                            ['title' => 'Desain & Kreatif', 'desc' => 'Lembaga yang fokus pada UI/UX Design, desain grafis, editing video, dan animasi.', 'color' => 'bg-purple-50 text-purple-600'],
                            ['title' => 'Bahasa Asing', 'desc' => 'Pusat pelatihan bahasa Inggris, Mandarin, Jepang, dll untuk persiapan karir internasional.', 'color' => 'bg-green-50 text-green-600'],
                            ['title' => 'Pengembangan Karir', 'desc' => 'Pelatihan *soft-skill*, kepemimpinan, dan komunikasi profesional untuk dunia kerja.', 'color' => 'bg-rose-50 text-rose-600'],
                            ['title' => 'Lainnya / Vokasi', 'desc' => 'Pelatihan umum, teknis vokasi, dan sertifikasi BNSP di berbagai bidang.', 'color' => 'bg-slate-100 text-slate-600'],
                        ];
                    @endphp
PHP;
$content = str_replace($oldPrograms, $newCategories, $content);
$content = str_replace('Daftar Program Pelatihan', 'Cakupan Kategori Pelatihan', $content);
$content = str_replace('<p class="text-slate-500">Program Pelatihan</p>', '<p class="text-slate-500">Sistem kami dapat memetakan lembaga berdasarkan kategori berikut</p>', $content);


// 2. REFACTOR: STATISTIK
$oldStats = <<<'PHP'
                    @php
                        $stats = [
                            ['value' => '250+', 'label' => 'Peserta Aktif', 'color' => 'text-blue-600'],
                            ['value' => '20+', 'label' => 'Program Pelatihan', 'color' => 'text-emerald-600'],
                            ['value' => '15+', 'label' => 'Mentor Profesional', 'color' => 'text-purple-600'],
                            ['value' => '500+', 'label' => 'Sertifikat Diterbitkan', 'color' => 'text-orange-600'],
                        ];
                    @endphp
PHP;

$newStats = <<<'PHP'
                    @php
                        $stats = [
                            ['value' => '100%', 'label' => 'Akurasi Kriteria', 'color' => 'text-blue-600'],
                            ['value' => 'Multi', 'label' => 'Kategori Bidang', 'color' => 'text-emerald-600'],
                            ['value' => 'GPS', 'label' => 'Kalkulasi Jarak', 'color' => 'text-purple-600'],
                            ['value' => 'Top 5', 'label' => 'Hasil Rekomendasi', 'color' => 'text-orange-600'],
                        ];
                    @endphp
PHP;
$content = str_replace($oldStats, $newStats, $content);


// 3. REFACTOR: MENTOR -> MITRA TRAINING CENTER
$oldMentorsSection = <<<'PHP'
            {{-- MENTOR --}}
            <section id="mentors" class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-12">
                    <span class="inline-block text-xs font-bold tracking-widest text-blue-600 uppercase mb-3">Tim Pengajar</span>
                    <h2 class="hero-heading text-3xl md:text-4xl font-bold text-slate-900">Mentor Profesional</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $mentors = [
                            ['name' => 'Ahmad Fauzan, S.Kom', 'role' => 'Spesialis Backend Development dan Laravel Framework.', 'initials' => 'AF'],
                            ['name' => 'Dinda Pramesti, M.Kom', 'role' => 'UI/UX Designer dan Figma Specialist.', 'initials' => 'DP'],
                            ['name' => 'Rizky Saputra, S.Kom', 'role' => 'Database Engineer dan SQL Specialist.', 'initials' => 'RS'],
                            ['name' => 'Muhammad Arif, M.T', 'role' => 'Android Developer dan Kotlin Mentor.', 'initials' => 'MA'],
                            ['name' => 'Nabila Putri, S.Kom', 'role' => 'Data Science dan Python Analyst.', 'initials' => 'NP'],
                        ];
                        $avatarColors = ['bg-blue-100 text-blue-700', 'bg-purple-100 text-purple-700', 'bg-emerald-100 text-emerald-700', 'bg-orange-100 text-orange-700', 'bg-rose-100 text-rose-700'];
                    @endphp
                    @foreach($mentors as $idx => $mentor)
                    <div class="card-hover flex items-start gap-4 bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-extrabold text-sm flex-shrink-0 {{ $avatarColors[$idx % count($avatarColors)] }}">
                            {{ $mentor['initials'] }}
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 mb-1">{{ $mentor['name'] }}</h3>
                            <p class="text-slate-500 text-sm leading-relaxed">{{ $mentor['role'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
PHP;

$newMitraSection = <<<'PHP'
            {{-- MITRA TRAINING CENTER --}}
            <section id="mentors" class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-12">
                    <span class="inline-block text-xs font-bold tracking-widest text-blue-600 uppercase mb-3">Jejaring</span>
                    <h2 class="hero-heading text-3xl md:text-4xl font-bold text-slate-900">Lembaga Pelatihan Terdaftar</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $mitras = [
                            ['name' => 'PT. Inovasi Digital Nusantara', 'role' => 'Fokus pada pengembangan IT, Cyber Security, dan Data Science.', 'initials' => 'IDN'],
                            ['name' => 'LPK Bangun Karir Cemerlang', 'role' => 'Lembaga pelatihan bisnis, bahasa asing, dan sertifikasi manajemen.', 'initials' => 'BKC'],
                            ['name' => 'Kreatif Media Academy', 'role' => 'Pusat pembelajaran UI/UX, desain grafis, dan multimedia.', 'initials' => 'KMA'],
                            ['name' => 'Balai Pelatihan Vokasi', 'role' => 'Menyediakan metode pembelajaran hybrid dan tatap muka intensif.', 'initials' => 'BPV'],
                            ['name' => 'Tech Hub Indonesia', 'role' => 'Training center modern dengan instruktur industri bersertifikat.', 'initials' => 'THI'],
                        ];
                        $avatarColors = ['bg-blue-100 text-blue-700', 'bg-purple-100 text-purple-700', 'bg-emerald-100 text-emerald-700', 'bg-orange-100 text-orange-700', 'bg-rose-100 text-rose-700'];
                    @endphp
                    @foreach($mitras as $idx => $mitra)
                    <div class="card-hover flex items-start gap-4 bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-blue-600 text-lg flex-shrink-0 {{ $avatarColors[$idx % count($avatarColors)] }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 mb-1">{{ $mitra['name'] }}</h3>
                            <p class="text-slate-500 text-sm leading-relaxed">{{ $mitra['role'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
PHP;
$content = str_replace($oldMentorsSection, $newMitraSection, $content);

// Update Navbar Link for Mentors
$content = str_replace('<a href="#mentors"  class="nav-link">Mentor</a>', '<a href="#mentors"  class="nav-link">Lembaga Mitra</a>', $content);
$content = str_replace('<a href="#mentors" class="hover:text-blue-600 transition">Mentor</a>', '<a href="#mentors" class="hover:text-blue-600 transition">Lembaga Mitra</a>', $content);


// 4. REFACTOR: CARA KERJA SISTEM (PROSES)
$oldSteps = <<<'PHP'
                    @php
                        $steps = [
                            ['num' => '01', 'title' => 'Pilih Program', 'desc' => 'Pilih pelatihan sesuai minat dan kebutuhan kompetensi.'],
                            ['num' => '02', 'title' => 'Lakukan Pendaftaran', 'desc' => 'Lengkapi data diri untuk proses registrasi peserta.'],
                            ['num' => '03', 'title' => 'Ikuti Pembelajaran', 'desc' => 'Mengikuti pelatihan bersama mentor profesional.'],
                            ['num' => '04', 'title' => 'Dapatkan Sertifikat', 'desc' => 'Sertifikat diberikan setelah peserta menyelesaikan program pelatihan.'],
                        ];
                    @endphp
PHP;

$newSteps = <<<'PHP'
                    @php
                        $steps = [
                            ['num' => '01', 'title' => 'Lengkapi Profil', 'desc' => 'Buat akun dan tentukan titik lokasi Anda pada peta untuk fitur pencarian jarak.'],
                            ['num' => '02', 'title' => 'Isi Kuesioner', 'desc' => 'Tentukan preferensi bidang, skill, metode, dan batas jarak tempuh maksimal Anda.'],
                            ['num' => '03', 'title' => 'Sistem Menganalisis', 'desc' => 'Algoritma mesin pencari kami akan mengkalkulasi kecocokan dan jarak lokasi.'],
                            ['num' => '04', 'title' => 'Pilih & Daftar', 'desc' => 'Dapatkan Top 5 rekomendasi lembaga terbaik dan daftarkan diri Anda langsung.'],
                        ];
                    @endphp
PHP;
$content = str_replace($oldSteps, $newSteps, $content);
$content = str_replace('Cara Mengikuti Pelatihan', 'Cara Kerja Sistem Rekomendasi', $content);


// 5. Update Footer & Copywriting Minor Lainnya
$content = str_replace('Bergabung bersama program pelatihan teknologi informasi untuk meningkatkan kompetensi digital dan kesiapan menghadapi dunia kerja modern.', 'Bergabunglah sekarang, tentukan preferensi Anda, dan biarkan algoritma cerdas kami menemukan tempat pelatihan yang paling optimal untuk karir Anda.', $content);

file_put_contents('patch_welcome.php_run', $content);
file_put_contents('resources/views/welcome.blade.php', $content);
?>
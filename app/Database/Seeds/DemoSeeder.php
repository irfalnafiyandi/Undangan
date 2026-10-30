<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $db  = $this->db;

        $db->table('users')->insertBatch([
            ['name' => 'Administrator', 'email' => 'admin@undangan.test', 'password' => password_hash('admin123', PASSWORD_DEFAULT), 'role' => 'admin', 'created_at' => $now],
            ['name' => 'Klien Demo', 'email' => 'klien@undangan.test', 'password' => password_hash('klien123', PASSWORD_DEFAULT), 'role' => 'client', 'created_at' => $now],
        ]);
        $clientId = (int) $db->table('users')->where('email', 'klien@undangan.test')->get()->getRow()->id;

        $demos = [
            ['slug' => 'rina-dan-adit', 'theme' => 'sage', 'g' => ['Aditya Pratama', 'Adit', 'Putra dari Bapak Hendra Pratama & Ibu Sri Wahyuni'], 'b' => ['Rina Maharani', 'Rina', 'Putri dari Bapak Surya Maharani & Ibu Lestari Dewi'], 'days' => 60],
            ['slug' => 'dimas-dan-sinta', 'theme' => 'rose', 'g' => ['Dimas Anggara', 'Dimas', 'Putra dari Bapak Bambang Anggara & Ibu Rukmini'], 'b' => ['Sinta Aulia', 'Sinta', 'Putri dari Bapak Rahmat Hidayat & Ibu Aminah'], 'days' => 90],
            ['slug' => 'fajar-dan-nadia', 'theme' => 'navy', 'g' => ['Fajar Nugraha', 'Fajar', 'Putra dari Bapak Yusuf Nugraha & Ibu Tuti Handayani'], 'b' => ['Nadia Putri', 'Nadia', 'Putri dari Bapak Anwar Saleh & Ibu Mariam'], 'days' => 120],
            ['slug' => 'bagas-dan-laras', 'theme' => 'minimal', 'g' => ['Bagas Saputra', 'Bagas', 'Putra dari Bapak Teguh Saputra & Ibu Wulan'], 'b' => ['Laras Ayu', 'Laras', 'Putri dari Bapak Gunawan & Ibu Sari Indah'], 'days' => 150],
            ['slug' => 'arjuna-dan-sekar', 'theme' => 'javanese', 'g' => ['Arjuna Wicaksono', 'Arjuna', 'Putra dari Bapak Wicaksono & Ibu Siti Rahayu'], 'b' => ['Sekar Ayuningtyas', 'Sekar', 'Putri dari Bapak Suharto & Ibu Endang Lestari'], 'days' => 180],
        ];

        foreach ($demos as $i => $d) {
            $date = date('Y-m-d', strtotime("+{$d['days']} days"));
            $db->table('invitations')->insert([
                'user_id'       => $clientId,
                'slug'          => $d['slug'],
                'theme'         => $d['theme'],
                'groom_name'    => $d['g'][0], 'groom_nick' => $d['g'][1], 'groom_parents' => $d['g'][2],
                'groom_photo'   => 'assets/img/dummy/groom.svg',
                'bride_name'    => $d['b'][0], 'bride_nick' => $d['b'][1], 'bride_parents' => $d['b'][2],
                'bride_photo'   => 'assets/img/dummy/bride.svg',
                'cover_photo'   => 'assets/img/dummy/cover.svg',
                'wedding_date'  => "{$date} 08:00:00",
                'opening_text'  => 'Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan pernikahan putra-putri kami. Merupakan kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu.',
                'quote'         => 'Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu pasangan hidup dari jenismu sendiri, supaya kamu merasa tenteram kepadanya, dan dijadikan-Nya di antaramu rasa kasih dan sayang.',
                'quote_source'  => 'QS. Ar-Rum: 21',
                'music_url'     => 'assets/audio/canon-demo.mp3',
                'gifts'         => "BCA|1234567890|{$d['b'][0]}\nMandiri|9876543210|{$d['g'][0]}",
                'live_url'      => '',
                'gift_address'  => "Jl. Melati Raya No. 25, Jakarta Selatan\nPenerima: {$d['b'][0]} (0812-0000-0000)",
                'is_published'  => 1,
                'created_at'    => $now, 'updated_at' => $now,
            ]);
            $id = (int) $db->insertID();

            $db->table('events')->insertBatch([
                ['invitation_id' => $id, 'title' => 'Akad Nikah', 'event_at' => "{$date} 08:00:00", 'end_time' => '10:00 WIB', 'venue' => 'Masjid Agung Al-Ikhlas', 'address' => 'Jl. Merdeka No. 10, Jakarta Selatan', 'map_url' => 'https://maps.google.com/?q=Masjid+Istiqlal+Jakarta'],
                ['invitation_id' => $id, 'title' => 'Resepsi', 'event_at' => "{$date} 11:00:00", 'end_time' => '14:00 WIB', 'venue' => 'Gedung Serbaguna Mawar', 'address' => 'Jl. Melati Raya No. 25, Jakarta Selatan', 'map_url' => 'https://maps.google.com/?q=Monas+Jakarta'],
            ]);

            foreach (range(1, 6) as $n) {
                $db->table('gallery')->insert(['invitation_id' => $id, 'file' => "assets/img/dummy/gallery{$n}.svg"]);
            }

            $db->table('guests')->insertBatch([
                ['invitation_id' => $id, 'name' => 'Budi Santoso', 'code' => substr(md5($d['slug'] . 'budi'), 0, 8), 'phone' => '081234567890', 'quota' => 2],
                ['invitation_id' => $id, 'name' => 'Keluarga Besar Wijaya', 'code' => substr(md5($d['slug'] . 'wijaya'), 0, 8), 'phone' => '', 'quota' => 5],
            ]);

            if ($i === 0) {
                $db->table('rsvps')->insertBatch([
                    ['invitation_id' => $id, 'name' => 'Budi Santoso', 'attendance' => 'hadir', 'pax' => 2, 'message' => 'Selamat menempuh hidup baru, semoga sakinah mawaddah warahmah!', 'created_at' => $now],
                    ['invitation_id' => $id, 'name' => 'Citra', 'attendance' => 'ragu', 'pax' => 0, 'message' => 'Barakallahu lakuma, bahagia selalu ya!', 'created_at' => $now],
                ]);
            }
        }
    }
}

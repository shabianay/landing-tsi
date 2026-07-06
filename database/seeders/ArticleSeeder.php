<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => '7 Strategi Digital Marketing untuk Bisnis UMKM di Tahun 2026',
                'slug' => 'strategi-digital-marketing-umkm-2026',
                'content' => "Digital marketing telah menjadi kebutuhan utama bagi setiap bisnis, terutama UMKM yang ingin berkembang di era digital. Tahun 2026 membawa berbagai tren baru yang sayang untuk dilewatkan.\n\n1. Personalisasi Konten\nKonsumen saat ini menginginkan pengalaman yang personal. Gunakan data pelanggan untuk mengirimkan konten yang relevan dan tepat sasaran. Email marketing dengan segmentasi yang baik bisa meningkatkan conversion rate hingga 50%.\n\n2. Video Pendek\nTikTok, Instagram Reels, dan YouTube Shorts masih menjadi raja konten. Buat video pendek yang informatif dan menghibur untuk menarik perhatian audiens target Anda.\n\n3. AI dalam Marketing\nGunakan AI untuk menganalisis data pelanggan, mengotomatiskan email campaign, dan membuat konten yang lebih efektif. Chatbot juga bisa membantu melayani pelanggan 24/7.\n\n4. SEO Lokal\nOptimalkan website bisnis Anda untuk pencarian lokal. Google My Business, review pelanggan, dan konten yang relevan secara geografis akan membantu bisnis Anda muncul di hasil pencarian teratas.\n\n5. Social Commerce\nJual langsung dari platform media sosial. Fitur shop di Instagram dan Facebook memudahkan pelanggan untuk membeli tanpa meninggalkan aplikasi.\n\n6. Influencer Marketing\nKolaborasi dengan micro-influencer yang relevan dengan niche bisnis Anda. Mereka memiliki engagement rate yang lebih tinggi dibandingkan mega-influencer.\n\n7. Email Marketing Otomatis\nBangun email list dari awal dan kirimkan newsletter berkala. Gunakan automation untuk welcome email, abandoned cart, dan rekomendasi produk.\n\nKesimpulan: Digital marketing bukan lagi pilihan, melainkan keharusan. Mulai terapkan strategi di atas secara konsisten dan ukur hasilnya secara berkala.",
                'excerpt' => 'Temukan 7 strategi digital marketing terbaru tahun 2026 yang wajib diterapkan UMKM untuk meningkatkan penjualan dan brand awareness secara online.',
                'author' => 'The Solution ID',
                'category' => 'Digital Marketing',
                'tags' => 'digital marketing, umkm, strategi, 2026, tips bisnis',
                'seo_title' => '7 Strategi Digital Marketing UMKM 2026',
                'seo_description' => 'Panduan lengkap 7 strategi digital marketing untuk UMKM di tahun 2026: personalisasi konten, video pendek, AI, SEO lokal, social commerce, influencer, email marketing.',
                'seo_keywords' => 'digital marketing umkm, strategi pemasaran digital, tips digital marketing 2026',
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Cara Membuat Website Profesional dengan Biaya Minim',
                'slug' => 'cara-membuat-website-profesional-biaya-minim',
                'content' => "Banyak pengusaha UMKM berpikir bahwa membuat website profesional membutuhkan biaya besar. Faktanya, dengan pendekatan yang tepat, Anda bisa memiliki website berkualitas tanpa menguras kantong.\n\n1. Tentukan Tujuan Website\nWebsite untuk toko online berbeda dengan website portofolio atau company profile. Tentukan tujuan utama Anda sebelum memulai.\n\n2. Pilih Platform yang Tepat\nUntuk pemula, gunakan CMS seperti WordPress atau Laravel (untuk yang lebih advance). Pastikan platform yang dipilih mendukung kebutuhan bisnis Anda.\n\n3. Gunakan Template Premium\nTemplate premium berkualitas bisa didapatkan dengan harga terjangkau. Pilih template yang responsif, cepat, dan SEO-friendly.\n\n4. Optimalkan Kecepatan Website\nGunakan hosting yang cepat, kompres gambar, dan minimalisir penggunaan plugin yang tidak perlu. Kecepatan website mempengaruhi SEO dan user experience.\n\n5. Desain Mobile-First\nPastikan website Anda tampil sempurna di smartphone. Lebih dari 70% traffic internet berasal dari perangkat mobile.\n\n6. SEO Dasar\nTerapkan SEO dasar: meta deskripsi, judul halaman, heading structure, alt text pada gambar, dan internal linking.\n\n7. Fitur Penting\nPastikan website memiliki: halaman about, kontak, portfolio/layanan, testimonial, dan form kontak. Integrasikan juga Google Analytics dan Google My Business.\n\nDengan strategi yang tepat, website profesional bisa dibuat mulai dari 2-5 jutaan saja. Yang terpenting adalah kualitas konten dan user experience.",
                'excerpt' => 'Panduan langkah demi langkah membuat website profesional berkualitas dengan biaya terjangkau untuk UMKM. Cocok untuk pemula yang ingin go digital.',
                'author' => 'The Solution ID',
                'category' => 'Website',
                'tags' => 'website, pembuatan website, biaya murah, umkm, digital',
                'seo_title' => 'Cara Membuat Website Profesional Murah untuk UMKM',
                'seo_description' => 'Panduan lengkap cara membuat website profesional berkualitas dengan biaya minim untuk UMKM. Tips memilih platform, template, hosting, dan optimasi SEO.',
                'seo_keywords' => 'buat website murah, website profesional, website umkm, jasa pembuatan website',
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Panduan Lengkap SEO untuk Website Bisnis Pemula',
                'slug' => 'panduan-seo-website-bisnis-pemula',
                'content' => "SEO (Search Engine Optimization) adalah kunci untuk membuat website bisnis Anda ditemukan di Google. Berikut panduan lengkap SEO untuk pemula.\n\nApa itu SEO?\nSEO adalah serangkaian teknik untuk meningkatkan peringkat website di hasil pencarian mesin pencari seperti Google. Semakin tinggi peringkat, semakin banyak traffic organik yang didapat.\n\n1. Riset Kata Kunci\nGunakan Google Keyword Planner atau Ubersuggest untuk menemukan kata kunci yang relevan dengan bisnis Anda. Fokus pada long-tail keywords yang memiliki persaingan lebih rendah.\n\n2. On-Page SEO\nOptimasi elemen di dalam halaman website:\n- Title tag: maksimal 60 karakter\n- Meta description: maksimal 160 karakter\n- Heading structure (H1, H2, H3): gunakan secara hierarkis\n- URL structure: gunakan slug yang deskriptif\n- Internal linking: hubungkan artikel yang saling terkait\n- Image alt text: deskripsikan gambar dengan kata kunci relevan\n\n3. Technical SEO\nPastikan website Anda:\n- Cepat diakses (Google PageSpeed Insights minimal 80)\n- Mobile-friendly (gunakan Google Mobile-Friendly Test)\n- Memiliki SSL/HTTPS\n- Memiliki sitemap.xml\n- Menggunakan schema markup untuk rich snippets\n\n4. Off-Page SEO\nBangun backlink berkualitas dari website terpercaya. Guest posting, directory listing, dan kolaborasi konten adalah cara efektif mendapatkan backlink.\n\n5. Konten Berkualitas\nGoogle menyukai konten yang original, informatif, dan bermanfaat. Buat konten secara konsisten minimal 2-3 artikel per minggu.\n\n6. Pantau dan Evaluasi\nGunakan Google Search Console dan Google Analytics untuk memantau performa SEO. Evaluasi kata kunci mana yang bekerja dan optimasi secara berkelanjutan.\n\nSEO bukanlah sprint, melainkan marathon. Butuh waktu 3-6 bulan untuk melihat hasil yang signifikan. Tapi dengan konsistensi, hasilnya akan sebanding.",
                'excerpt' => 'Panduan SEO lengkap untuk pemula yang ingin website bisnisnya muncul di halaman pertama Google. Pelajari riset keyword, on-page SEO, technical SEO, dan konten.',
                'author' => 'The Solution ID',
                'category' => 'SEO',
                'tags' => 'seo, search engine optimization, panduan seo, tips seo, google ranking',
                'seo_title' => 'Panduan Lengkap SEO Website untuk Pemula 2026',
                'seo_description' => 'Pelajari SEO dari dasar hingga mahir. Panduan lengkap riset kata kunci, on-page SEO, technical SEO, off-page SEO, dan strategi konten untuk pemula.',
                'seo_keywords' => 'panduan seo, belajar seo, seo pemula, optimasi website, tips seo google',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Mengapa Bisnis Anda Membutuhkan Website di Era Digital?',
                'slug' => 'pentingnya-website-untuk-bisnis',
                'content' => "Di era digital saat ini, memiliki website bukan lagi sebuah kemewahan, melainkan kebutuhan. Berikut alasan mengapa bisnis Anda harus memiliki website.\n\n1. Kredibilitas dan Profesionalisme\nWebsite memberikan kesan profesional dan terpercaya. Pelanggan cenderung lebih percaya pada bisnis yang memiliki website dibandingkan yang hanya memiliki akun media sosial.\n\n2. Akses 24/7\nWebsite bisa diakses kapan saja, di mana saja. Pelanggan bisa mendapatkan informasi tentang produk atau layanan Anda bahkan di luar jam kerja.\n\n3. Jangkauan Pasar yang Lebih Luas\nDengan website, bisnis Anda bisa dijangkau oleh pelanggan dari seluruh Indonesia, bahkan dunia. Tidak terbatas oleh lokasi fisik.\n\n4. Biaya Marketing yang Lebih Efisien\nDibandingkan iklan konvensional seperti brosur atau billboard, website menawarkan biaya marketing yang jauh lebih efisien dengan jangkauan yang lebih luas.\n\n5. Data dan Analytics\nWebsite memungkinkan Anda melacak perilaku pengunjung: dari mana mereka datang, halaman apa yang dilihat, berapa lama mereka bertahan, dan banyak lagi. Data ini sangat berharga untuk strategi bisnis.\n\n6. Komunikasi Dua Arah\nWebsite memfasilitasi komunikasi antara bisnis dan pelanggan melalui form kontak, chat, atau komentar. Ini membangun hubungan yang lebih baik dengan pelanggan.\n\n7. Keunggulan Kompetitif\nJika kompetitor Anda sudah memiliki website dan Anda belum, Anda akan tertinggal. Website memberikan keunggulan kompetitif di pasar yang semakin ketat.\n\n8. Platform Marketing Terintegrasi\nWebsite menjadi pusat dari semua aktivitas marketing Anda. Media sosial, email marketing, iklan, semuanya bisa diarahkan ke website.\n\nJangan tunda lagi. Mulai bangun website bisnis Anda sekarang juga dan rasakan manfaatnya untuk pertumbuhan bisnis.",
                'excerpt' => 'Ketahui 8 alasan mengapa website sangat penting untuk bisnis Anda di era digital. Dari kredibilitas, jangkauan pasar, hingga efisiensi biaya marketing.',
                'author' => 'The Solution ID',
                'category' => 'Website',
                'tags' => 'pentingnya website, bisnis digital, go digital, website bisnis, online presence',
                'seo_title' => '8 Alasan Kenapa Bisnis Harus Punya Website',
                'seo_description' => 'Website bukan lagi opsional, tapi keharusan. Simak 8 alasan penting mengapa bisnis Anda harus memiliki website profesional di era digital.',
                'seo_keywords' => 'pentingnya website untuk bisnis, manfaat website, website perusahaan, go online',
                'is_published' => true,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Tips Memilih Jasa Pembuatan Website yang Tepat',
                'slug' => 'tips-memilih-jasa-pembuatan-website',
                'content' => "Memilih jasa pembuatan website yang tepat adalah keputusan penting untuk bisnis Anda. Berikut tips yang perlu diperhatikan.\n\n1. Cek Portofolio\nLihat portofolio penyedia jasa. Apakah website yang mereka buat memiliki desain yang modern, responsif, dan sesuai dengan standar industri?\n\n2. Baca Testimoni dan Review\nCari tahu pengalaman klien sebelumnya melalui testimoni di website mereka atau platform seperti Google Reviews.\n\n3. Tanyakan Teknologi yang Digunakan\nPastikan mereka menggunakan teknologi yang tepat dan modern. Tanyakan apakah menggunakan CMS, framework, atau custom development.\n\n4. Pastikan SEO-Friendly\nWebsite yang baik harus dioptimasi untuk mesin pencari. Tanyakan apakah mereka menerapkan SEO dasar dalam pembuatan website.\n\n5. Perhatikan Kecepatan Website\nWebsite yang lambat akan membuat pengunjung pergi dan mempengaruhi peringkat SEO. Tanyakan tentang optimasi kecepatan.\n\n6. Layanan After-Sales\nApakah mereka menyediakan maintenance, update, dan support setelah website selesai? Pastikan ada garansi dan dukungan teknis.\n\n7. Harga yang Transparan\nWaspadai harga yang terlalu murah atau terlalu mahal. Minta rincian biaya secara detail agar tidak ada biaya tersembunyi.\n\n8. Komunikasi yang Baik\nPilih penyedia jasa yang komunikatif dan responsif. Proses pembuatan website membutuhkan kerjasama yang baik antara kedua belah pihak.\n\n9. Minta Proposal\nMinta proposal tertulis yang mencakup timeline, fitur, teknologi, dan biaya. Ini akan membantu Anda membandingkan dengan penyedia jasa lain.\n\n10. Cek Support dan Maintenance\nPastikan penyedia jasa menawarkan layanan maintenance berkala, backup data, dan update keamanan untuk website Anda.\n\nDengan mempertimbangkan tips di atas, Anda bisa memilih jasa pembuatan website yang tepat dan menghindari penyesalan di kemudian hari.",
                'excerpt' => 'Panduan memilih jasa pembuatan website yang tepat untuk bisnis Anda. Simak 10 tips penting sebelum memutuskan bekerja sama dengan penyedia jasa website.',
                'author' => 'The Solution ID',
                'category' => 'Tips',
                'tags' => 'jasa website, pembuatan website, tips memilih, web developer, website agency',
                'seo_title' => '10 Tips Memilih Jasa Pembuatan Website Profesional',
                'seo_description' => 'Cari jasa pembuatan website? Simak 10 tips penting memilih penyedia jasa website yang profesional, terpercaya, dan sesuai kebutuhan bisnis Anda.',
                'seo_keywords' => 'jasa pembuatan website, tips memilih jasa website, website agency, web developer indonesia',
                'is_published' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => '5 Strategi Digital Marketing Efektif untuk Bisnis di Indonesia',
                'slug' => '5-strategi-digital-marketing-efektif-untuk-bisnis-di-indonesia',
                'content' => "Di era digital seperti sekarang, memiliki strategi digital marketing yang tepat sangat penting untuk kesuksesan bisnis Anda. Berikut adalah 5 strategi digital marketing efektif yang bisa Anda terapkan untuk bisnis di Indonesia.\n\n1. Manfaatkan Media Sosial untuk Meningkatkan Engagement\nMedia sosial seperti Instagram, Facebook, dan TikTok adalah platform yang sangat efektif untuk menjangkau target pasar Anda. Gunakan konten yang menarik dan relevan, seperti video pendek, gambar, atau infografis, untuk meningkatkan engagement dengan pelanggan.\n\n2. Optimalkan SEO untuk Meningkatkan Visibilitas Website\nSearch Engine Optimization (SEO) adalah kunci untuk membuat website bisnis Anda muncul di halaman pertama hasil pencarian Google. Fokus pada penggunaan kata kunci yang relevan, konten berkualitas, dan struktur website yang mudah dinavigasi.\n\n3. Gunakan Email Marketing untuk Membangun Hubungan dengan Pelanggan\nEmail marketing masih menjadi salah satu strategi digital marketing yang paling efektif. Kirim newsletter, promo, atau konten eksklusif kepada pelanggan Anda untuk menjaga hubungan dan meningkatkan loyalitas merek.\n\n4. Investasikan dalam Iklan Berbayar (PPC)\nIklan berbayar seperti Google Ads atau Facebook Ads bisa membantu Anda menjangkau audiens yang lebih spesifik. Dengan budget yang tepat, Anda bisa mendapatkan ROI yang tinggi dari kampanye iklan ini.\n\n5. Manfaatkan Konten Blog untuk Mendidik dan Menarik Pelanggan\nBlog adalah alat yang powerful untuk membangun otoritas di industri Anda. Buat konten yang informatif dan bermanfaat untuk menarik traffic organik dan membangun kepercayaan pelanggan.\n\nDengan menerapkan kelima strategi digital marketing ini, bisnis Anda bisa lebih kompetitif di pasar Indonesia. Mulailah dengan satu strategi terlebih dahulu, ukur hasilnya, dan terus tingkatkan upaya pemasaran digital Anda.",
                'excerpt' => 'Temukan 5 strategi digital marketing yang terbukti efektif untuk meningkatkan penjualan bisnis Anda di Indonesia.',
                'author' => 'The Solution ID',
                'category' => 'SEO',
                'tags' => 'digital marketing, bisnis online, strategi pemasaran, bisnis Indonesia, pemasaran digital',
                'seo_title' => '5 Strategi Digital Marketing Efektif untuk Bisnis Indonesia',
                'seo_description' => 'Pelajari 5 strategi digital marketing yang bisa langsung Anda terapkan untuk meningkatkan penjualan bisnis di Indonesia.',
                'seo_keywords' => 'digital marketing, strategi pemasaran, bisnis online, pemasaran digital, bisnis Indonesia',
                'is_published' => true,
                'published_at' => now()->subDays(0),
            ],
        ];

        foreach ($articles as $data) {
            Article::create($data);
        }

        $this->command->info('6 articles seeded successfully!');
    }
}

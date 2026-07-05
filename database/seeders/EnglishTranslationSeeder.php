<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Faq;
use App\Models\Popup;
use App\Models\Project;
use App\Models\Testimonial;
use App\Models\Service;
use App\Models\ProcessStep;
use Illuminate\Database\Seeder;

class EnglishTranslationSeeder extends Seeder
{
    public function run(): void
    {
        $locale = 'en';

        // 1. POPUP
        foreach (Popup::all() as $m) {
            $m->setTranslation('title', $locale, 'Get 20% Discount on Your First Project!');
            $m->setTranslation('content', $locale, 'We offer a special deal for new clients. Free consultation + 20% discount on your first website project. Limited promotion!');
            $m->setTranslation('button_text', $locale, 'Contact Us Now');
        }

        // 2. TESTIMONIALS
        $testiMap = [
            1 => ['client_name'  => 'Ahmad Fauzi', 'company_name' => 'Online Fashion Store', 'content'      => 'The website created is very professional and perfectly matches our business identity. The results exceeded our expectations!'],
            2 => ['client_name'  => 'Sari Dewi', 'company_name' => 'Fashion Brand Founder', 'content'      => 'Excellent service. The free consultation was very helpful for us who were new to websites.'],
            3 => ['client_name'  => 'Budi Santoso', 'company_name' => 'Restaurant Owner', 'content'      => 'The SEO really delivers results. After the website launched, new customers started coming in from Google.'],
        ];
        foreach (Testimonial::all() as $m) {
            if (!isset($testiMap[$m->id])) continue;
            foreach ($testiMap[$m->id] as $field => $value) {
                $m->setTranslation($field, $locale, $value);
            }
        }

        // 3. FAQS
        $faqMap = [
            1 => ['question' => 'How long does the website creation process take?', 'answer' => 'The website creation process typically takes 1-3 weeks depending on the complexity of the features required. We provide a clear timeline estimate at the start of the project.'],
            2 => ['question' => 'Can I see the design before the website is finished?', 'answer' => 'Of course! We provide design mockups for you to review and revise before entering the development stage. Your satisfaction is our priority.'],
            3 => ['question' => 'Is there a warranty after the website is launched?', 'answer' => 'Yes, we provide free repair services during the warranty period to ensure the website runs optimally after launch.'],
            4 => ['question' => 'What technology is used to build websites?', 'answer' => 'We use the latest technologies such as Laravel, Tailwind CSS, and MySQL to ensure your website is fast, secure, and easy to maintain.'],
            5 => ['question' => 'How much does it cost to build a website?', 'answer' => 'Costs depend on the complexity of the features required. Contact us for a free consultation and get a price quote tailored to your business needs.'],
        ];
        foreach (Faq::all() as $m) {
            if (!isset($faqMap[$m->id])) continue;
            foreach ($faqMap[$m->id] as $field => $value) {
                $m->setTranslation($field, $locale, $value);
            }
        }

        // 4. PROJECTS
        $projectMap = [
            3 => 'Virlab Blood Typing Lab', 4 => 'Vokasi Reporting System', 5 => 'Bismika Interior Design',
            6 => 'Sibatur - Batik Tourism App', 7 => 'Lateevadinamika Engineering', 8 => 'Mindful - Mental Health App',
            9 => 'Wedding Invitation V1', 10 => 'Shellfish - Seafood Restaurant', 11 => 'RS Sampang Hospital System',
            12 => 'Cibrievco Architecture', 13 => 'Serenity - Wellness & Spa', 14 => 'Seroom.id - Room Rental',
            15 => 'Toothscan Dental Clinic', 16 => 'UBARU.ID Platform', 17 => 'Lo-Fi SIM UKM System',
            18 => 'Lo-Fi Seserahan by Pings', 19 => 'Berdonor - Blood Donation', 20 => 'GoodLiving Lifestyle',
            21 => 'Basdig Platform', 22 => "Donasiyuk - Let's Donate", 23 => 'Freshfusion Brand',
            24 => 'Artcol Creative', 25 => 'Innovating Farming', 26 => 'SPK Smart Decision System',
            27 => 'UD Sumber Barokah', 28 => 'Masa Depan Hukum - Future of Law', 29 => 'Angkot Transportation',
            30 => 'Mail Archive V2', 31 => 'Mail Archive System', 32 => 'Agrios Agriculture',
            33 => 'Amazing Mount Adventure', 34 => 'Pakar CF - Expert System', 35 => "Koepilihanku - My Choice",
            36 => 'PT. SMNTAX INDONESIA', 37 => 'NAVIGO.ID', 38 => 'Kampung Inggris TOEFL (Choice TOEFL)',
            39 => 'DPD RI Collective', 40 => 'Safety Work Permit System', 41 => 'Mount Ntuy (ROBLOX Map)',
        ];
        foreach (Project::all() as $m) {
            if (!isset($projectMap[$m->id])) continue;
            $m->setTranslation('title', $locale, $projectMap[$m->id]);
            $m->setTranslation('description', $locale, $m->description);
        }

        // 5. SERVICES (keep existing)
        $serviceMap = [
            'Web Development' => ['title' => 'Web Development', 'description' => 'Building professional and responsive websites with the latest technology for your business.'],
            'UI/UX Design' => ['title' => 'UI/UX Design', 'description' => 'Designing intuitive interfaces and memorable user experiences.'],
            'SEO Optimization' => ['title' => 'SEO Optimization', 'description' => 'Optimizing your website to be easily found on search engines like Google.'],
            'Digital Marketing' => ['title' => 'Digital Marketing', 'description' => 'Measurable digital marketing strategies to grow your business.'],
        ];
        foreach (Service::all() as $s) {
            if (isset($serviceMap[$s->title])) {
                $s->setTranslation('title', $locale, $serviceMap[$s->title]['title']);
                $s->setTranslation('description', $locale, $serviceMap[$s->title]['description']);
            }
        }

        // 6. PROCESS STEPS (keep existing)
        $stepMap = [
            'Konsultasi' => ['title' => 'Consultation', 'description' => 'Discuss your business needs and vision with our team.'],
            'Perencanaan' => ['title' => 'Planning', 'description' => 'We develop the right strategy and design for your project.'],
            'Eksekusi' => ['title' => 'Execution', 'description' => 'Our team works on the project with precision and on schedule.'],
            'Peluncuran & Maintenance' => ['title' => 'Launch & Maintenance', 'description' => 'Website ready to launch with ongoing maintenance support.'],
        ];
        foreach (ProcessStep::all() as $p) {
            if (isset($stepMap[$p->title])) {
                $p->setTranslation('title', $locale, $stepMap[$p->title]['title']);
                $p->setTranslation('description', $locale, $stepMap[$p->title]['description']);
            }
        }
    }
}

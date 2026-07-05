<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleTranslationSeeder extends Seeder
{
    public function run(): void
    {
        $locale = 'en';

        // Article 1
        $article1 = Article::find(1);
        if ($article1) {
            $article1->setTranslation('title', $locale, '7 Digital Marketing Strategies for SMEs in 2026');
            $article1->setTranslation('excerpt', $locale, 'Discover 7 latest digital marketing strategies in 2026 that SMEs must implement to boost sales and brand awareness online.');
            $article1->setTranslation('seo_title', $locale, '7 Digital Marketing Strategies for SMEs 2026');
            $article1->setTranslation('seo_description', $locale, 'Complete guide to 7 digital marketing strategies for SMEs in 2026: content personalization, short videos, AI, local SEO, social commerce, influencers, email marketing.');
            $article1->setTranslation('content', $locale, 'Digital marketing has become a primary necessity for every business, especially SMEs that want to grow in the digital era. 2026 brings various new trends that are too good to miss.

1. Content Personalization
Today\'s consumers want a personalized experience. Use customer data to deliver relevant and targeted content. Email marketing with proper segmentation can increase conversion rates by up to 50%.

2. Short Videos
TikTok, Instagram Reels, and YouTube Shorts dominate user attention. Create short, informative, and entertaining videos that showcase your products or services. Consistency in posting is key to building an audience.

3. AI-Based Marketing Automation
Artificial intelligence makes marketing more efficient. Use AI chatbots for customer service, automated email campaigns, and predictive analytics to understand customer behavior.

4. Local SEO
Optimize your Google My Business profile, use local keywords, and collect online reviews. Local SEO helps your business appear in local searches, especially for SMEs with physical locations.

5. Social Commerce
Selling directly on social media platforms is becoming increasingly important. Leverage Instagram Shopping, Facebook Shop, and TikTok Shop to create a seamless shopping experience.

6. Nano and Micro Influencers
Collaborating with small-scale influencers with loyal audiences often gives a higher ROI than celebrity endorsements. Their recommendations feel more authentic and trustworthy.

7. Email Marketing with Segmentation
Email is still one of the most effective marketing channels. Send personalized emails based on customer behavior, purchase history, and preferences for maximum results.

The key to success in 2026 is adapting quickly to trends and consistently providing value to your customers.');
        }

        // Article 2
        $article2 = Article::find(2);
        if ($article2) {
            $article2->setTranslation('title', $locale, 'How to Build a Professional Website on a Small Budget');
            $article2->setTranslation('excerpt', $locale, 'A step-by-step guide to building a quality professional website at an affordable cost for SMEs. Perfect for beginners ready to go digital.');
            $article2->setTranslation('seo_title', $locale, 'How to Build an Affordable Professional Website for SMEs');
            $article2->setTranslation('seo_description', $locale, 'Complete guide on building a quality professional website on a small budget for SMEs. Tips on choosing platforms, templates, hosting, and SEO optimization.');
            $article2->setTranslation('content', $locale, 'Many SME owners think that building a professional website requires a huge budget. In fact, with the right approach, you can have a quality website without breaking the bank.

1. Define Your Website Goals
An e-commerce website is different from a portfolio or company profile website. Determine your main goal before starting.

2. Choose the Right Platform
For beginners, use a CMS like WordPress or Laravel (for more advanced users). Make sure the platform supports your needs and can be scaled up later.

3. Use Professional Templates
Save time and money by using premium templates. Many affordable templates come with responsive designs that look professional.

4. Optimize Images and Files
Large files slow down your website. Compress images without sacrificing quality using tools like TinyPNG or WebP format.

5. Choose the Right Hosting
Start with shared hosting or cloud hosting that suits your traffic needs. Don\'t overpay for high-end hosting if you\'re just starting.

6. DIY SEO
Learn basic SEO to rank on Google without hiring an expert. Focus on keyword research, meta descriptions, and quality content.

7. Do It Yourself or Hire a Professional?
If you have time to learn, a DIY approach saves money. But if you want a polished result quickly, hiring a professional service is a worthy investment.

Your website is a long-term investment. Build it with the right strategy from the start.');
        }

        // Article 3
        $article3 = Article::find(3);
        if ($article3) {
            $article3->setTranslation('title', $locale, 'Complete SEO Guide for Beginner Business Websites');
            $article3->setTranslation('excerpt', $locale, 'A complete SEO guide for beginners who want their business website to appear on Google\'s first page. Learn keyword research, on-page SEO, technical SEO, and content strategy.');
            $article3->setTranslation('seo_title', $locale, 'Complete SEO Guide for Beginner Websites 2026');
            $article3->setTranslation('seo_description', $locale, 'Learn SEO from basics to advanced. Complete guide to keyword research, on-page SEO, technical SEO, off-page SEO, and content strategy for beginners.');
            $article3->setTranslation('content', $locale, 'SEO (Search Engine Optimization) is the key to getting your business website found on Google. Here is a complete SEO guide for beginners.

What is SEO?
SEO is a set of techniques to improve a website\'s ranking in search engine results like Google. The higher the ranking, the more organic traffic you get.

1. Keyword Research
Use Google Keyword Planner or Ubersuggest to find relevant keywords for your business. Focus on long-tail keywords that have lower competition and higher conversion potential.

2. On-Page SEO
Optimize title tags, meta descriptions, header tags (H1-H6), and content structure. Include keywords naturally, not forced.

3. Technical SEO
Make sure your website loads fast, is mobile-friendly, uses HTTPS, and has a clear sitemap. Technical SEO helps search engines crawl and index your site effectively.

4. Quality Content
Create useful, original, and relevant content. Google favors content that answers user questions and provides added value.

5. Link Building
Get backlinks from high-quality websites. Guest posts, business partnerships, and great content are the best ways to earn backlinks naturally.

6. Monitor and Improve
Use Google Analytics and Google Search Console to monitor your SEO performance. Regularly update old content and fix any issues found.

SEO is a long-term investment. Be patient and consistent, and the results will come.');
        }

        // Article 4
        $article4 = Article::find(4);
        if ($article4) {
            $article4->setTranslation('title', $locale, 'Why Your Business Needs a Website in the Digital Era?');
            $article4->setTranslation('excerpt', $locale, 'Find out 8 reasons why a website is essential for your business in the digital era. From credibility and market reach to marketing cost efficiency.');
            $article4->setTranslation('seo_title', $locale, '8 Reasons Why Businesses Must Have a Website');
            $article4->setTranslation('seo_description', $locale, 'A website is no longer optional but a necessity. Discover 8 important reasons why your business must have a professional website in the digital era.');
            $article4->setTranslation('content', $locale, 'In today\'s digital era, having a website is no longer a luxury but a necessity. Here are the reasons why your business must have a website.

1. Credibility and Professionalism
A website gives a professional and trustworthy impression. Customers are more likely to trust a business with a website than one with only social media accounts.

2. 24/7 Accessibility
A website can be accessed anytime, anywhere. Customers can get information about your products or services even when your office is closed.

3. Wider Market Reach
With a website, your business can be accessed by anyone, anywhere, not just limited to your local area. Expand your market without opening physical branches.

4. Cost-Effective Marketing
Compared to print ads or billboards, online marketing through a website is much more cost-effective and measurable.

5. Better Customer Service
FAQs, chatbots, and contact forms on your website make it easier for customers to get help without calling or visiting in person.

6. Competitive Advantage
Many competitors still don\'t have professional websites. Having one gives you a significant edge.

7. Analytics and Data
Track visitor behavior, popular products, and conversion rates. This data is invaluable for making business decisions.

8. 24/7 Sales
An e-commerce website can accept orders even while you sleep. That means passive income potential.

A website is a long-term investment that pays for itself many times over.');
        }

        // Article 5
        $article5 = Article::find(5);
        if ($article5) {
            $article5->setTranslation('title', $locale, 'Tips for Choosing the Right Website Development Service');
            $article5->setTranslation('excerpt', $locale, 'A guide to choosing the right website development service for your business. Check out 10 important tips before deciding to work with a service provider.');
            $article5->setTranslation('seo_title', $locale, '10 Tips for Choosing a Professional Website Development Service');
            $article5->setTranslation('seo_description', $locale, 'Looking for website development services? Check out 10 important tips for choosing a professional, trustworthy service provider that fits your business needs.');
            $article5->setTranslation('content', $locale, 'Choosing the right website development service is an important decision for your business. Here are tips to consider.

1. Check the Portfolio
Look at the service provider\'s portfolio. Do their websites have modern, responsive designs that meet industry standards?

2. Read Testimonials and Reviews
Find out about previous clients\' experiences through testimonials on their website or platforms like Google Reviews.

3. Ask About the Technology Used
Make sure they use modern, reliable technologies. Outdated technology can cause problems in the future.

4. Understand the Offered Services
Do they only provide design and development, or do they also offer SEO, maintenance, and content creation? Choose a provider that meets all your needs.

5. Communication and Work Flow
Choose a provider with clear, transparent communication. A good work flow includes regular progress updates and milestone reviews.

6. Request a Timeline
Ask about the estimated completion time. Professional providers give realistic time estimates.

7. Budget and Price Transparency
Make sure there are no hidden fees. Ask for a detailed quote including all services.

8. After-Sales Support
Does the provider offer maintenance or support after the website launches? Essential for long-term success.

9. Ownership and Copyright
Make sure the website, domain, and all content fully belong to you after payment.

10. Trust Your Instincts
If a price seems too good to be true, it probably is. Choose a provider that gives you confidence.

Take your time choosing. The right partner makes all the difference.');
        }

        // Article 10
        $article10 = Article::find(10);
        if ($article10) {
            $article10->setTranslation('title', $locale, '5 Technology Trends in 2024 Every Business Owner Should Know');
            $article10->setTranslation('excerpt', $locale, 'Check out the latest technology trends in 2024 that can help your business become more competitive in the digital era.');
            $article10->setTranslation('seo_title', $locale, '2024 Technology Trends for Business in Indonesia');
            $article10->setTranslation('seo_description', $locale, 'Learn 5 latest technology trends in 2024 that can be applied to grow your business in Indonesia.');
            $article10->setTranslation('content', $locale, 'In the ever-evolving digital era, technology is the key to winning business competition. 2024 brings various technological innovations that business owners can leverage to improve efficiency and service. Here are 5 technology trends in 2024 you must know.

AI and Machine Learning Are Becoming More Advanced
Artificial intelligence and machine learning continue to grow rapidly. In 2024, this technology will be increasingly integrated into various business aspects, from customer service chatbots to predictive analytics for data-driven decision making.

Internet of Things (IoT)
IoT enables devices to connect and communicate with each other. In the business context, IoT is used for inventory monitoring, energy management, and improving operational efficiency.

Cloud Computing
Cloud technology continues to dominate. More businesses are migrating to the cloud for flexibility, security, and lower operational costs.

Cyber Security
With increasing cyber threats, security is a top priority. Technologies like multi-factor authentication and AI-based threat detection are becoming standard.

5G Technology
5G brings faster internet speeds and lower latency, enabling technologies like AR, VR, and real-time streaming applications.

Businesses that adopt these technologies early will have a significant competitive advantage. Start evaluating which trends are most relevant to your business and prepare for adoption.');
        }
    }
}

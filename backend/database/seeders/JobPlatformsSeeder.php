<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\JobPlatform;
use Illuminate\Database\Seeder;

class JobPlatformsSeeder extends Seeder
{
    public function run(): void
    {
        $platforms = [
            'GB' => [
                ['name' => 'Indeed UK', 'website_url' => 'https://uk.indeed.com', 'category' => 'General', 'tips' => 'Most popular job board in the UK. Set up email alerts for new posts matching your keywords.'],
                ['name' => 'Reed', 'website_url' => 'https://www.reed.co.uk', 'category' => 'General', 'tips' => 'UK-specific platform covering all sectors. Great for contract and permanent roles.'],
                ['name' => 'Totaljobs', 'website_url' => 'https://www.totaljobs.com', 'category' => 'General', 'tips' => 'Strong in finance, HR, and marketing sectors. Good for midlevel roles.'],
                ['name' => 'LinkedIn UK', 'website_url' => 'https://www.linkedin.com/jobs/', 'category' => 'Professional', 'tips' => 'Ensure your profile is optimised with keywords. UK recruiters heavily use LinkedIn.'],
                ['name' => 'NHS Jobs', 'website_url' => 'https://www.jobs.nhs.uk', 'category' => 'Healthcare', 'tips' => 'Official portal for all NHS and healthcare positions across England.'],
                ['name' => 'CWJobs', 'website_url' => 'https://www.cwjobs.co.uk', 'category' => 'Tech', 'tips' => 'The UK\'s leading tech job board for developers, engineers, and IT professionals.'],
            ],
            'CA' => [
                ['name' => 'Job Bank Canada', 'website_url' => 'https://www.jobbank.gc.ca', 'category' => 'General', 'tips' => 'Government-run job board with real-time postings. Useful for NOC code matching for PR purposes.'],
                ['name' => 'Indeed Canada', 'website_url' => 'https://ca.indeed.com', 'category' => 'General', 'tips' => 'Most popular job site in Canada. Set location to specific provinces for targeted searches.'],
                ['name' => 'Workopolis', 'website_url' => 'https://www.workopolis.com', 'category' => 'General', 'tips' => 'Canada-focused, great for professional and business roles.'],
                ['name' => 'LinkedIn Canada', 'website_url' => 'https://www.linkedin.com/jobs/', 'category' => 'Professional', 'tips' => 'Critical for tech, finance, and management roles. Canadian recruiters rely heavily on LinkedIn.'],
                ['name' => 'Charity Village', 'website_url' => 'https://charityvillage.com', 'category' => 'Non-Profit', 'tips' => 'Leading site for non-profit and charitable sector jobs across Canada.'],
                ['name' => 'Glassdoor Canada', 'website_url' => 'https://www.glassdoor.ca', 'category' => 'General', 'tips' => 'Use for salary benchmarks and company culture research alongside job applications.'],
            ],
            'DE' => [
                ['name' => 'StepStone', 'website_url' => 'https://www.stepstone.de', 'category' => 'General', 'tips' => 'Germany\'s largest job portal. Many listings available in English for international professionals.'],
                ['name' => 'XING', 'website_url' => 'https://www.xing.com', 'category' => 'Professional', 'tips' => 'Germany\'s LinkedIn equivalent. Essential for professional networking in the DACH region.'],
                ['name' => 'LinkedIn DE', 'website_url' => 'https://www.linkedin.com/jobs/', 'category' => 'Professional', 'tips' => 'Growing fast in Germany. Multinationals and tech companies heavily recruit here.'],
                ['name' => 'Indeed Germany', 'website_url' => 'https://www.indeed.de', 'category' => 'General', 'tips' => 'Great for finding entry-level and blue collar jobs as well as professional roles.'],
                ['name' => 'Bundesagentur für Arbeit', 'website_url' => 'https://www.arbeitsagentur.de', 'category' => 'Government', 'tips' => 'Official German Federal Employment Agency. Required registration if receiving unemployment support.'],
                ['name' => 'Jobware', 'website_url' => 'https://www.jobware.de', 'category' => 'Professional', 'tips' => 'Focuses on specialist and management-level jobs, especially in engineering and IT.'],
            ],
            'NL' => [
                ['name' => 'Nationale Vacaturebank', 'website_url' => 'https://www.nationalevacaturebank.nl', 'category' => 'General', 'tips' => 'One of the biggest Dutch job boards. Filter by English-language jobs for expats.'],
                ['name' => 'Indeed Netherlands', 'website_url' => 'https://nl.indeed.com', 'category' => 'General', 'tips' => 'Excellent for all job types across the Netherlands.'],
                ['name' => 'LinkedIn NL', 'website_url' => 'https://www.linkedin.com/jobs/', 'category' => 'Professional', 'tips' => 'Dutch companies widely use LinkedIn. Highly English-friendly environment.'],
                ['name' => 'Intermediair', 'website_url' => 'https://www.intermediair.nl', 'category' => 'Professional', 'tips' => 'Focused on university graduate and professional-level positions.'],
                ['name' => 'Undutchables', 'website_url' => 'https://www.undutchables.nl', 'category' => 'Expat', 'tips' => 'Specifically for international professionals seeking English-language jobs in Netherlands.'],
            ],
            'AU' => [
                ['name' => 'Seek', 'website_url' => 'https://www.seek.com.au', 'category' => 'General', 'tips' => 'Australia\'s #1 job board. The most important platform for any job search in Australia.'],
                ['name' => 'LinkedIn AU', 'website_url' => 'https://www.linkedin.com/jobs/', 'category' => 'Professional', 'tips' => 'Essential for white-collar and tech professionals. Widely used by Australian recruiters.'],
                ['name' => 'Jora', 'website_url' => 'https://au.jora.com', 'category' => 'General', 'tips' => 'Aggregates jobs from across the web, including government and niche boards.'],
                ['name' => 'CareerOne', 'website_url' => 'https://www.careerone.com.au', 'category' => 'General', 'tips' => 'Popular for trades, engineering, and business roles.'],
                ['name' => 'APS Jobs (Government)', 'website_url' => 'https://www.apsjobs.gov.au', 'category' => 'Government', 'tips' => 'All Australian Federal Public Service jobs are posted here.'],
            ],
            'NZ' => [
                ['name' => 'Seek NZ', 'website_url' => 'https://www.seek.co.nz', 'category' => 'General', 'tips' => 'New Zealand\'s #1 job board. Essential starting point for all job seekers.'],
                ['name' => 'Trade Me Jobs', 'website_url' => 'https://www.trademe.co.nz/a/jobs', 'category' => 'General', 'tips' => 'NZ-specific and very popular, particularly for local and regional jobs.'],
                ['name' => 'LinkedIn NZ', 'website_url' => 'https://www.linkedin.com/jobs/', 'category' => 'Professional', 'tips' => 'Good for professional and corporate roles in Auckland, Wellington, and Christchurch.'],
                ['name' => 'MyJobSpace', 'website_url' => 'https://www.myjobspace.co.nz', 'category' => 'General', 'tips' => 'NZ-only job board with listings updated daily.'],
            ],
            'IE' => [
                ['name' => 'IrishJobs', 'website_url' => 'https://www.irishjobs.ie', 'category' => 'General', 'tips' => 'Ireland\'s leading dedicated job board. Broad coverage across all sectors.'],
                ['name' => 'Jobs.ie', 'website_url' => 'https://www.jobs.ie', 'category' => 'General', 'tips' => 'Strong for admin, retail, hospitality, and trade jobs in Ireland.'],
                ['name' => 'LinkedIn IE', 'website_url' => 'https://www.linkedin.com/jobs/', 'category' => 'Professional', 'tips' => 'Essential for tech and multinational companies based in Dublin.'],
                ['name' => 'Tech Ireland', 'website_url' => 'https://jobs.tech.ie', 'category' => 'Tech', 'tips' => 'Dedicated to tech jobs from Irish startups and multinationals in Ireland.'],
                ['name' => 'Nixers.net', 'website_url' => 'https://nixers.net', 'category' => 'Freelance', 'tips' => 'Ireland-specific platform for freelance and contract tech work.'],
            ],
            'ES' => [
                ['name' => 'InfoJobs', 'website_url' => 'https://www.infojobs.net', 'category' => 'General', 'tips' => 'Spain\'s most popular job board. Essential for any job search. Upload your CV in Spanish.'],
                ['name' => 'Indeed ES', 'website_url' => 'https://es.indeed.com', 'category' => 'General', 'tips' => 'Good for both Spanish and English-language roles in Spain.'],
                ['name' => 'LinkedIn ES', 'website_url' => 'https://www.linkedin.com/jobs/', 'category' => 'Professional', 'tips' => 'Used by multinationals with offices in Madrid and Barcelona. English-friendly.'],
                ['name' => 'Tecnoempleo', 'website_url' => 'https://www.tecnoempleo.com', 'category' => 'Tech', 'tips' => 'Spain\'s leading tech job board. Great for IT, software, and engineering roles.'],
                ['name' => 'Turijobs', 'website_url' => 'https://www.turijobs.com', 'category' => 'Hospitality', 'tips' => 'Spain\'s #1 hospitality and tourism job board across all regions.'],
            ],
            'PT' => [
                ['name' => 'Net-Empregos', 'website_url' => 'https://www.net-empregos.com', 'category' => 'General', 'tips' => 'One of Portugal\'s largest job portals with listings across all sectors.'],
                ['name' => 'Sapo Emprego', 'website_url' => 'https://emprego.sapo.pt', 'category' => 'General', 'tips' => 'Widely used in Portugal across all industries.'],
                ['name' => 'LinkedIn PT', 'website_url' => 'https://www.linkedin.com/jobs/', 'category' => 'Professional', 'tips' => 'Multinationals in Lisbon and Porto use LinkedIn extensively.'],
                ['name' => 'Indeed PT', 'website_url' => 'https://pt.indeed.com', 'category' => 'General', 'tips' => 'Growing fast in Portugal. Good for English remote opportunities posted by Portuguese companies.'],
                ['name' => 'IEFP (Government)', 'website_url' => 'https://www.iefp.pt', 'category' => 'Government', 'tips' => 'Official Portuguese Employment Institute. Register here for job support and benefits.'],
            ],
            'FR' => [
                ['name' => 'Pôle Emploi', 'website_url' => 'https://www.francetravail.fr', 'category' => 'Government', 'tips' => 'France\'s official public employment service (now France Travail). Essential for job seekers.'],
                ['name' => 'LinkedIn FR', 'website_url' => 'https://www.linkedin.com/jobs/', 'category' => 'Professional', 'tips' => 'Key for corporate, tech, and executive roles in France.'],
                ['name' => 'Indeed FR', 'website_url' => 'https://fr.indeed.com', 'category' => 'General', 'tips' => 'Great breadth of listings across all sectors. Popular with both companies and candidates.'],
                ['name' => 'Cadremploi', 'website_url' => 'https://www.cadremploi.fr', 'category' => 'Professional', 'tips' => 'Specialises in cadre (executive/manager) level jobs for professionals in France.'],
                ['name' => 'APEC', 'website_url' => 'https://www.apec.fr', 'category' => 'Professional', 'tips' => 'France\'s top resource for executive professionals, with career management tools.'],
            ],
            'IT' => [
                ['name' => 'InfoJobs IT', 'website_url' => 'https://www.infojobs.it', 'category' => 'General', 'tips' => 'Italy\'s most used job board. Listings across all regions and industries.'],
                ['name' => 'Indeed IT', 'website_url' => 'https://it.indeed.com', 'category' => 'General', 'tips' => 'Good for tech and English-language roles at multinational firms in Italy.'],
                ['name' => 'LinkedIn IT', 'website_url' => 'https://www.linkedin.com/jobs/', 'category' => 'Professional', 'tips' => 'Essential for professional and managerial roles. Milan recruiting scene uses LinkedIn heavily.'],
                ['name' => 'Trovolavoro', 'website_url' => 'https://www.trovolavoro.it', 'category' => 'General', 'tips' => 'Broad Italian job board with strong coverage of manufacturing and logistics.'],
                ['name' => 'Lavoronascosto', 'website_url' => 'https://www.lavoronascosto.it', 'category' => 'Hidden Market', 'tips' => 'Focuses on jobs not publicly advertised — contacts professionals through network referrals.'],
            ],
            'SE' => [
                ['name' => 'Arbetsförmedlingen', 'website_url' => 'https://www.arbetsformedlingen.se', 'category' => 'Government', 'tips' => 'Sweden\'s public employment agency. Register here for job search support and benefits.'],
                ['name' => 'LinkedIn SE', 'website_url' => 'https://www.linkedin.com/jobs/', 'category' => 'Professional', 'tips' => 'Widely used across all Swedish industries. Swedish companies love LinkedIn.'],
                ['name' => 'Blocket Jobb', 'website_url' => 'https://jobb.blocket.se', 'category' => 'General', 'tips' => 'Sweden\'s popular classifieds site with strong job board. Good for local and blue-collar jobs.'],
                ['name' => 'Stepstone SE', 'website_url' => 'https://www.stepstone.se', 'category' => 'Professional', 'tips' => 'Great for senior and specialist professional roles in Sweden.'],
                ['name' => 'TechJobs Sweden', 'website_url' => 'https://www.jobtech.se', 'category' => 'Tech', 'tips' => 'Dedicated to tech and digital roles in Sweden with English support.'],
            ],
            'FI' => [
                ['name' => 'TE-Services', 'website_url' => 'https://www.te-palvelut.fi', 'category' => 'Government', 'tips' => 'Finnish Employment and Economic Development Services — official job marketplace.'],
                ['name' => 'LinkedIn FI', 'website_url' => 'https://www.linkedin.com/jobs/', 'category' => 'Professional', 'tips' => 'Tech companies in Helsinki actively recruit via LinkedIn. Profile in English is fine.'],
                ['name' => 'Duunitori', 'website_url' => 'https://duunitori.fi', 'category' => 'General', 'tips' => 'Finland\'s top job board aggregator with thousands of listings.'],
                ['name' => 'Monster FI', 'website_url' => 'https://www.monster.fi', 'category' => 'General', 'tips' => 'Well used in Finland for professional and executive roles.'],
            ],
            'NO' => [
                ['name' => 'NAV (Government)', 'website_url' => 'https://www.nav.no', 'category' => 'Government', 'tips' => 'Norwegian Labour and Welfare Administration. Register for job search and benefits support.'],
                ['name' => 'Finn.no Jobb', 'website_url' => 'https://www.finn.no/job/', 'category' => 'General', 'tips' => 'Norway\'s #1 classified ads site with the biggest job board.'],
                ['name' => 'LinkedIn NO', 'website_url' => 'https://www.linkedin.com/jobs/', 'category' => 'Professional', 'tips' => 'Widely used across all professional sectors in Norway.'],
                ['name' => 'Jobbn.no', 'website_url' => 'https://www.jobbn.no', 'category' => 'General', 'tips' => 'Norwegian job board aggregating listings from across the web.'],
            ],
            'AT' => [
                ['name' => 'AMS (Government)', 'website_url' => 'https://www.ams.at', 'category' => 'Government', 'tips' => 'Austria\'s Public Employment Service. Registration is required for unemployment support and benefits.'],
                ['name' => 'StepStone AT', 'website_url' => 'https://www.stepstone.at', 'category' => 'General', 'tips' => 'Leading professional job board in Austria across all industries.'],
                ['name' => 'LinkedIn AT', 'website_url' => 'https://www.linkedin.com/jobs/', 'category' => 'Professional', 'tips' => 'Used by multinationals and tech companies in Vienna.'],
                ['name' => 'Karriere.at', 'website_url' => 'https://www.karriere.at', 'category' => 'General', 'tips' => 'Austria\'s homegrown job portal for all career levels.'],
            ],
            'NG' => [
                ['name' => 'Jobberman', 'website_url' => 'https://www.jobberman.com', 'category' => 'General', 'tips' => 'Nigeria\'s largest online recruitment platform. Most active board for formal sector jobs.'],
                ['name' => 'LinkedIn NG', 'website_url' => 'https://www.linkedin.com/jobs/', 'category' => 'Professional', 'tips' => 'Key for banking, tech, and corporate roles. NGM companies and multinationals use LinkedIn.'],
                ['name' => 'NGCareers', 'website_url' => 'https://ngcareers.com', 'category' => 'General', 'tips' => 'Major aggregator of Nigeria\'s formal job market with many government vacancies.'],
                ['name' => 'MyJobMag', 'website_url' => 'https://www.myjobmag.com', 'category' => 'General', 'tips' => 'Strong for entry- to mid-level roles in Nigeria. Good for recent graduates.'],
                ['name' => 'CareerPoint', 'website_url' => 'https://www.careerpointng.com', 'category' => 'Professional', 'tips' => 'Nigeria-specific board with strong coverage in banking, finance, and management.'],
            ],
        ];

        foreach ($platforms as $code => $jobBoards) {
            $country = Country::where('code', $code)->first();
            if (!$country)
                continue;

            foreach ($jobBoards as $platform) {
                JobPlatform::updateOrCreate(
                ['country_id' => $country->id, 'name' => $platform['name']],
                    array_merge($platform, ['country_id' => $country->id])
                );
            }

            $this->command->info("Seeded " . count($jobBoards) . " job platforms for: {$country->name}");
        }
    }
}
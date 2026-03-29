<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Scholarship;
use App\Models\ScholarshipSource;
use Illuminate\Database\Seeder;

class ManualScholarshipSeeder extends Seeder
{
    public function run(): void
    {
        $source = ScholarshipSource::where('name', 'like', '%Scholars4Dev%')->first();
        $sourceId = $source ? $source->id : null;

        $scholarships = [
            'GB' => [
                ['title' => 'Chevening Scholarships', 'provider' => 'UK Government', 'eligibility' => 'Future leaders', 'program_level' => 'Master', 'opening_date' => '2025-09-01', 'funding_type' => 'Full Funding', 'description' => 'Fully funded master\'s degree.', 'source_url' => 'https://www.chevening.org/scholarship/'],
                ['title' => 'Commonwealth Scholarships', 'provider' => 'CSC UK', 'eligibility' => 'Commonwealth citizens', 'program_level' => 'Master, PhD', 'opening_date' => '2025-09-01', 'funding_type' => 'Full Funding', 'description' => 'For developing Commonwealth nations.', 'source_url' => 'https://cscuk.fcdo.gov.uk/apply/'],
                ['title' => 'GREAT Scholarships', 'provider' => 'British Council', 'eligibility' => 'International students', 'program_level' => 'Master', 'opening_date' => '2025-10-25', 'funding_type' => 'Partial Funding', 'description' => '£10,000 towards tuition.', 'source_url' => 'https://study-uk.britishcouncil.org/scholarships-funding/great-scholarships'],
                ['title' => 'Rhodes Scholarships', 'provider' => 'Rhodes Trust', 'eligibility' => 'Outstanding graduates', 'program_level' => 'Postgraduate', 'opening_date' => '2025-06-01', 'funding_type' => 'Full Funding', 'description' => 'Study at the University of Oxford.', 'source_url' => 'https://www.rhodeshouse.ox.ac.uk/scholarships/the-rhodes-scholarship/'],
                ['title' => 'Gates Cambridge Scholarships', 'provider' => 'Gates Foundation', 'eligibility' => 'Non-UK students', 'program_level' => 'Master, PhD', 'opening_date' => '2025-09-01', 'funding_type' => 'Full Funding', 'description' => 'Study at University of Cambridge.', 'source_url' => 'https://www.gatescambridge.org/apply/'],
                ['title' => 'University of Westminster International Scholarships', 'provider' => 'Westminster', 'eligibility' => 'Full-time students', 'program_level' => 'Bachelor, Master', 'opening_date' => '2026-04-01', 'funding_type' => 'Full Funding', 'description' => 'Covers tuition and living expenses.', 'source_url' => 'https://www.westminster.ac.uk/study/fees-and-funding/scholarships'],
            ],
            'CA' => [
                ['title' => 'Vanier Canada Graduate Scholarships', 'provider' => 'Gov of Canada', 'eligibility' => 'Doctoral students', 'program_level' => 'PhD', 'opening_date' => '2025-07-01', 'funding_type' => 'Full Funding', 'description' => '$50k/year for 3 years.', 'source_url' => 'https://vanier.gc.ca/'],
                ['title' => 'Lester B. Pearson Scholarship', 'provider' => 'U of Toronto', 'eligibility' => 'First-entry undergrads', 'program_level' => 'Bachelor', 'opening_date' => '2025-09-01', 'funding_type' => 'Full Funding', 'description' => 'Top talent scholarship.', 'source_url' => 'https://future.utoronto.ca/pearson/'],
                ['title' => 'Karen McKellin International Leader of Tomorrow', 'provider' => 'UBC', 'eligibility' => 'Academic excellence', 'program_level' => 'Bachelor', 'opening_date' => '2025-10-01', 'funding_type' => 'Full Funding', 'description' => 'Need-based and merit-based.', 'source_url' => 'https://you.ubc.ca/financial-sustainablity/scholarships-awards-international-students/'],
                ['title' => 'University of Manitoba Graduate Fellowships', 'provider' => 'U of M', 'eligibility' => 'Full-time grad students', 'program_level' => 'Master, PhD', 'opening_date' => '2026-03-01', 'funding_type' => 'Partial Funding', 'description' => '$14,000 - $18,000 stipends.', 'source_url' => 'https://umanitoba.ca/graduate-studies/funding-awards-and-financial-aid/university-manitoba-graduate-fellowship-umgf'],
                ['title' => 'Pierre Elliott Trudeau Foundation Scholarships', 'provider' => 'Trudeau Foundation', 'eligibility' => 'Humanities/Social Science', 'program_level' => 'PhD', 'opening_date' => '2025-11-01', 'funding_type' => 'Full Funding', 'description' => 'Leadership program and funding.', 'source_url' => 'https://www.trudeaufoundation.ca/scholarships'],
            ],
            'AU' => [
                ['title' => 'Australia Awards Scholarships', 'provider' => 'DFAT', 'eligibility' => 'Developing country citizens', 'program_level' => 'Bachelor, Master, PhD', 'opening_date' => '2026-02-01', 'funding_type' => 'Full Funding', 'description' => 'Government lead scholarship.', 'source_url' => 'https://www.dfat.gov.au/people-to-people/australia-awards/'],
                ['title' => 'Destination Australia Scholarship', 'provider' => 'Gov of Australia', 'eligibility' => 'Regional studies', 'program_level' => 'Undergrad, Postgrad', 'opening_date' => '2025-09-01', 'funding_type' => 'Partial Funding', 'description' => 'Up to $15k per year.', 'source_url' => 'https://www.education.gov.au/destination-australia'],
                ['title' => 'Melbourne Research Scholarships', 'provider' => 'U of Melbourne', 'eligibility' => 'High achieving research students', 'program_level' => 'Master, PhD', 'opening_date' => '2025-10-31', 'funding_type' => 'Full Funding', 'description' => 'Full fee offset and stipend.', 'source_url' => 'https://scholarships.unimelb.edu.au/awards/melbourne-research-scholarship'],
                ['title' => 'University of Sydney Vice-Chancellor\'s Scholarship', 'provider' => 'USYD', 'eligibility' => 'Excellent international students', 'program_level' => 'Postgraduate', 'opening_date' => '2026-01-15', 'funding_type' => 'Partial Funding', 'description' => 'Up to $40,000 for one year.', 'source_url' => 'https://www.sydney.edu.au/scholarships/e/vice-chancellor-international-scholarships-scheme.html'],
            ],
            'DE' => [
                ['title' => 'DAAD Study Scholarships for Graduates', 'provider' => 'DAAD', 'eligibility' => 'All disciplines', 'program_level' => 'Master', 'opening_date' => '2025-08-01', 'funding_type' => 'Stipend', 'description' => 'Monthly allowance + travel.', 'source_url' => 'https://www2.daad.de/deutschland/stipendium/datenbank/en/21148-scholarship-database/?status=3&origin=190&subjectGrps=&daad=&q=&page=1&detail=50026200'],
                ['title' => 'Heinrich Böll Foundation Scholarships', 'provider' => 'Green Party Foundation', 'eligibility' => 'All levels', 'program_level' => 'Master, PhD', 'opening_date' => '2026-03-01', 'funding_type' => 'Full Funding', 'description' => 'Academic excellence & political engagement.', 'source_url' => 'https://www.boell.de/en/foundation/scholarships'],
                ['title' => 'Erasmus Mundus Joint Masters', 'provider' => 'EU', 'eligibility' => 'International students', 'program_level' => 'Master', 'opening_date' => '2025-12-01', 'funding_type' => 'Full Funding', 'description' => 'Study in multiple EU countries.', 'source_url' => 'https://erasmus-plus.ec.europa.eu/opportunities/opportunities-for-individuals/students/erasmus-mundus-joint-masters'],
                ['title' => 'Friedrich Ebert Foundation Scholarship', 'provider' => 'FES', 'eligibility' => 'Social democratic values', 'program_level' => 'Bachelor, Master, PhD', 'opening_date' => '2026-01-01', 'funding_type' => 'Full Funding', 'description' => 'Supports high academic achievers.', 'source_url' => 'https://www.fes.de/en/internationale-akademie/scholarships'],
                ['title' => 'Konrad-Adenauer-Stiftung Scholarships', 'provider' => 'KAS', 'eligibility' => 'Master/PhD applicants', 'program_level' => 'Master, PhD', 'opening_date' => '2025-07-15', 'funding_type' => 'Stipend', 'description' => 'For students with social/political commitment.', 'source_url' => 'https://www.kas.de/en/web/begabtenfoerderung/auslaenderfoerderung'],
            ],
            'IE' => [
                ['title' => 'Government of Ireland International Education Scholarship', 'provider' => 'HEA', 'eligibility' => 'Non-EU/EEA', 'program_level' => 'Undergrad, Postgrad', 'opening_date' => '2026-02-15', 'funding_type' => 'Full Funding', 'description' => '€10,000 stipend + full fee waiver.', 'source_url' => 'https://web.archive.org/web/2026/https://euraxess.ie/ireland/funding/government-ireland-international-education-scholarships-goi-ies'],
                ['title' => 'UCD Global Excellence Scholarship', 'provider' => 'UCD', 'eligibility' => 'High achievers', 'program_level' => 'Bachelor, Master', 'opening_date' => '2026-02-01', 'funding_type' => 'Partial Funding', 'description' => '50% or 100% tuition waiver.', 'source_url' => 'https://www.ucd.ie/global/scholarships/int_excellence/'],
                ['title' => 'TCD Global Excellence Scholarship', 'provider' => 'Trinity College Dublin', 'eligibility' => 'International students', 'program_level' => 'Bachelor, Master', 'opening_date' => '2026-03-31', 'funding_type' => 'Partial Funding', 'description' => '€2,000 to €5,000 off tuition.', 'source_url' => 'https://www.tcd.ie/study/international/scholarships/undergraduate/global-excellence.php'],
                ['title' => 'University of Galway International Student Scholarships', 'provider' => 'Galway', 'eligibility' => 'Merit-based', 'program_level' => 'Bachelor, Master', 'opening_date' => '2026-05-01', 'funding_type' => 'Partial Funding', 'description' => 'Tuition fee reduction.', 'source_url' => 'https://www.universityofgalway.ie/international-students/feesandfunding/scholarships/'],
            ],
            'IT' => [
                ['title' => 'Invest Your Talent in Italy', 'provider' => 'Gov of Italy', 'eligibility' => 'Selected countries', 'program_level' => 'Master', 'opening_date' => '2026-01-01', 'funding_type' => 'Full Funding', 'description' => 'Scholarship + Internship.', 'source_url' => 'https://investyourtalentit.esteri.it/IYT_English/About'],
                ['title' => 'DSU Regional Grants (Tuscany)', 'provider' => 'DSU Toscano', 'eligibility' => 'Income based', 'program_level' => 'All levels', 'opening_date' => '2026-07-01', 'funding_type' => 'Full Funding', 'description' => 'Accomodation, canteen and stipend.', 'source_url' => 'https://www.dsu.toscana.it/borsa-di-studio'],
                ['title' => 'Politecnico di Milano Merit Based Scholarships', 'provider' => 'POLIMI', 'eligibility' => 'Top Master students', 'program_level' => 'Master', 'opening_date' => '2026-02-01', 'funding_type' => 'Full Funding', 'description' => 'Platinum (10k), Gold (5k), Silver (fees).', 'source_url' => 'https://www.polimi.it/en/international-prospective-students/laurea-magistrale-programmes-equivalent-to-master-of-science/scholarships'],
                ['title' => 'University of Bologna Study Grants (Unibo Action 1 & 2)', 'provider' => 'UNIBO', 'eligibility' => 'SAT/GRE results', 'program_level' => 'Bachelor, Master', 'opening_date' => '2026-03-01', 'funding_type' => 'Full Funding', 'description' => 'Exemptions and grants.', 'source_url' => 'https://www.unibo.it/en/services-and-opportunities/study-grants-and-subsidies/study-grants-for-international-students'],
            ],
            'ES' => [
                ['title' => 'Fundación Carolina Scholarships', 'provider' => 'Fundación Carolina', 'eligibility' => 'Ibero-American & others', 'program_level' => 'Master, PhD', 'opening_date' => '2026-01-01', 'funding_type' => 'Full Funding', 'description' => 'Travel, insurance, and tuition.', 'source_url' => 'https://www.fundacioncarolina.es/formacion/programas-de-becas/'],
                ['title' => 'UIC Barcelona International Excellence Scholarship', 'provider' => 'UIC', 'eligibility' => 'Top talent', 'program_level' => 'Bachelor', 'opening_date' => '2026-04-10', 'funding_type' => 'Partial Funding', 'description' => '80% fee waiver.', 'source_url' => 'https://www.uic.es/en/scholarships/international-excellence-scholarships'],
                ['title' => 'University of Granada Athenea Scholarships', 'provider' => 'UGR', 'eligibility' => 'Postdoc/research', 'program_level' => 'Postdoc', 'opening_date' => '2026-06-01', 'funding_type' => 'Full Funding', 'description' => 'For researchers in all fields.', 'source_url' => 'https://athenea.ugr.es/'],
            ],
            'FR' => [
                ['title' => 'Eiffel Excellence Scholarship Program', 'provider' => 'Campus France', 'eligibility' => 'Top foreign students', 'program_level' => 'Master, PhD', 'opening_date' => '2025-10-01', 'funding_type' => 'Stipend', 'description' => 'Prestigious government award.', 'source_url' => 'https://www.campusfrance.org/en/eiffel-scholarship-program-of-excellence'],
                ['title' => 'Emile Boutmy Scholarship (Sciences Po)', 'provider' => 'Sciences Po', 'eligibility' => 'Non-EU students', 'program_level' => 'Bachelor, Master', 'opening_date' => '2025-11-01', 'funding_type' => 'Partial Funding', 'description' => 'Up to €13k per year.', 'source_url' => 'https://www.sciencespo.fr/students/en/fees-funding/financial-aid/emile-boutmy-scholarship'],
                ['title' => 'ENS International Selection', 'provider' => 'ENS Paris', 'eligibility' => 'Humanities & Sciences', 'program_level' => 'Master', 'opening_date' => '2025-10-15', 'funding_type' => 'Full Funding', 'description' => 'Monthly stipend and accommodation.', 'source_url' => 'https://www.ens.psl.eu/en/admissions/international-selection'],
                ['title' => 'HEC Paris Excellence Scholarships', 'provider' => 'HEC', 'eligibility' => 'MBA applicants', 'program_level' => 'Master', 'opening_date' => '2026-01-01', 'funding_type' => 'Partial Funding', 'description' => 'Merit-based finance support.', 'source_url' => 'https://www.hec.edu/en/mba-programs/financial-aid/excellence-scholarships'],
            ],
            'SE' => [
                ['title' => 'SI Scholarships for Global Professionals', 'provider' => 'Swedish Institute', 'eligibility' => 'Developing nations', 'program_level' => 'Master', 'opening_date' => '2025-10-01', 'funding_type' => 'Full Funding', 'description' => 'Total cost coverage.', 'source_url' => 'https://si.se/en/apply/scholarships/global-professionals/'],
                ['title' => 'Lund University Global Scholarship', 'provider' => 'Lund', 'eligibility' => 'Non-EU/EEA', 'program_level' => 'Bachelor, Master', 'opening_date' => '2025-11-01', 'funding_type' => 'Partial Funding', 'description' => 'Covers 25-100% of tuition.', 'source_url' => 'https://www.lunduniversity.lu.se/admissions/bachelors-and-masters-studies/scholarships-and-awards/lund-university-global-scholarship'],
                ['title' => 'Chalmers IPOET Scholarships', 'provider' => 'Chalmers', 'eligibility' => 'Master applicants', 'program_level' => 'Master', 'opening_date' => '2026-01-15', 'funding_type' => 'Tuition Waiver', 'description' => '75-85% fee reduction.', 'source_url' => 'https://www.chalmers.se/en/education/scholarships/ipoet-scholarships/'],
            ],
            'NL' => [
                ['title' => 'NL Scholarship (Holland Scholarship)', 'provider' => 'Nuffic', 'eligibility' => 'Non-EEA', 'program_level' => 'Bachelor, Master', 'opening_date' => '2025-11-01', 'funding_type' => 'Grant', 'description' => '€5,000 for one year.', 'source_url' => 'https://www.studyinnl.org/finances/nl-scholarship'],
                ['title' => 'Radboud Excellence Programme', 'provider' => 'Radboud University', 'eligibility' => 'Top talent', 'program_level' => 'Master', 'opening_date' => '2025-12-01', 'funding_type' => 'Full Funding', 'description' => 'Full tuition + living costs.', 'source_url' => 'https://www.ru.nl/en/education/scholarships/radboud-scholarship-programme'],
                ['title' => 'University of Amsterdam Merit Scholarships', 'provider' => 'UvA', 'eligibility' => 'Excellent non-EU', 'program_level' => 'Bachelor, Master', 'opening_date' => '2026-01-15', 'funding_type' => 'Full Funding', 'description' => '€4,000 to €25,000 yearly.', 'source_url' => 'https://www.uva.nl/en/education/master-s/scholarships--tuition/scholarships-and-loans/amsterdam-merit-scholarship/amsterdam-merit-scholarship.html'],
                ['title' => 'TU Delft Excellence Scholarships (Justus & Louise van Effen)', 'provider' => 'TU Delft', 'eligibility' => 'MSc students', 'program_level' => 'Master', 'opening_date' => '2025-12-01', 'funding_type' => 'Full Funding', 'description' => 'Full fee + stipend.', 'source_url' => 'https://www.tudelft.nl/en/education/scholarships/justus-louise-van-effen-research-grant/'],
            ],
            'CH' => [
                ['title' => 'Swiss Government Excellence Scholarships', 'provider' => 'Gov of Switzerland', 'eligibility' => 'Postgrad research', 'program_level' => 'PhD, Postdoc', 'opening_date' => '2025-08-01', 'funding_type' => 'Full Funding', 'description' => 'Monthly stipend + flight.', 'source_url' => 'https://www.sbfi.admin.ch/sbfi/en/home/education/scholarships-and-grants/swiss-government-excellence-scholarships.html'],
                ['title' => 'ETH Zurich Excellence Scholarship (ESOP)', 'provider' => 'ETH Zurich', 'eligibility' => 'Top Master candidates', 'program_level' => 'Master', 'opening_date' => '2025-11-01', 'funding_type' => 'Full Funding', 'description' => 'Stipend + full tuition.', 'source_url' => 'https://ethz.ch/students/en/studies/financial/scholarships/esop.html'],
                ['title' => 'University of Geneva Excellence Master Fellowships', 'provider' => 'UNIGE', 'eligibility' => 'Extraordinary graduates', 'program_level' => 'Master', 'opening_date' => '2026-01-01', 'funding_type' => 'Partial Funding', 'description' => 'CHF 10,000 to 15,000.', 'source_url' => 'https://www.unige.ch/sciences/en/enseignements/formations/masters/excellencemasterfellowships/'],
                ['title' => 'IMD MBA Scholarships', 'provider' => 'IMD Business School', 'eligibility' => 'Leadership potential', 'program_level' => 'Master', 'opening_date' => '2026-02-01', 'funding_type' => 'Partial Funding', 'description' => 'Support for business leaders.', 'source_url' => 'https://www.imd.org/mba/financing/scholarships/'],
            ],
            'MT' => [
                ['title' => 'ENDEAVOUR Scholarship Scheme', 'provider' => 'Ministry for Education', 'eligibility' => 'Quality tertiary education', 'program_level' => 'Master, PhD', 'opening_date' => '2026-03-01', 'funding_type' => 'Grant', 'description' => 'Governmental support.', 'source_url' => 'https://education.gov.mt/en/scholarships/endeavour/'],
                ['title' => 'TESS - Tertiary Education Scholarship Scheme', 'provider' => 'Gov of Malta', 'eligibility' => 'Skills shortage areas', 'program_level' => 'Undergraduate, Postgraduate', 'opening_date' => '2026-05-01', 'funding_type' => 'Full Funding', 'description' => 'Focusing on relevant economic sectors.', 'source_url' => 'https://education.gov.mt/en/scholarships/tess/'],
                ['title' => 'University of Malta Scholarship for Non-EU/EEA Students', 'provider' => 'UM', 'eligibility' => 'Fee paying students', 'program_level' => 'Undergrad, Postgrad', 'opening_date' => '2026-04-01', 'funding_type' => 'Tuition Discount', 'description' => 'Fee reductions for high performers.', 'source_url' => 'https://www.um.edu.mt/finance/service/scholarships-non-eu-eea/'],
            ],
            'NG' => [
                ['title' => 'PTDF Overseas Scholarship Scheme', 'provider' => 'PTDF', 'eligibility' => 'Nigerian citizens', 'program_level' => 'Master, PhD', 'opening_date' => '2026-01-15', 'funding_type' => 'Full Funding', 'description' => 'For studies in relevant technical areas.', 'source_url' => 'https://ptdf.gov.ng/scholarships/overseas-scholarship-scheme/'],
                ['title' => 'NLNG Postgraduate Scholarship', 'provider' => 'Nigeria LNG', 'eligibility' => 'First Class/2:1 graduates', 'program_level' => 'Master', 'opening_date' => '2025-11-01', 'funding_type' => 'Full Funding', 'description' => 'Targeted at UK universities.', 'source_url' => 'https://www.nlng.com/Community/Pages/scholarships-postgraduate.aspx'],
                ['title' => 'NNPC/TotalEnegergies National Merit Scholarship', 'provider' => 'NNPC/Total', 'eligibility' => 'Undergraduate students', 'program_level' => 'Bachelor', 'opening_date' => '2026-03-10', 'funding_type' => 'Grant', 'description' => 'Annual financial award.', 'source_url' => 'https://scholarships.totalenergies.com/en/nigeria-national-merit-scholarship'],
                ['title' => 'MTN Science & Technology Scholarship', 'provider' => 'MTN Foundation', 'eligibility' => 'STEM students', 'program_level' => 'Bachelor', 'opening_date' => '2026-06-01', 'funding_type' => 'Grant', 'description' => 'N200,000 per session till graduation.', 'source_url' => 'https://www.mtn.ng/foundation/scholarships/'],
            ],
            'PL' => [
                ['title' => 'Ignacy Łukasiewicz Scholarship Programme', 'provider' => 'NAWA', 'eligibility' => 'Developing countries', 'program_level' => 'Master, PhD', 'opening_date' => '2026-03-15', 'funding_type' => 'Full Funding', 'description' => 'Science, Engineering, Tech focus.', 'source_url' => 'https://nawa.gov.pl/en/students/the-lukasiewicz-scholarship-programme'],
                ['title' => 'Stefan Banach Scholarship', 'provider' => 'NAWA', 'eligibility' => 'Specific regions', 'program_level' => 'Master', 'opening_date' => '2026-04-01', 'funding_type' => 'Full Funding', 'description' => 'Supports socio-economic growth.', 'source_url' => 'https://nawa.gov.pl/en/students/the-stefan-banach-scholarship-programme'],
                ['title' => 'Poland My First Choice', 'provider' => 'NAWA', 'eligibility' => 'Top performing international students', 'program_level' => 'Master', 'opening_date' => '2026-05-10', 'funding_type' => 'Stipend', 'description' => 'PLN 2000 monthly allowance.', 'source_url' => 'https://nawa.gov.pl/en/students/poland-my-first-choice-programme'],
            ],
            'NO' => [
                ['title' => 'BI Norwegian Presidential Scholarship', 'provider' => 'BI', 'eligibility' => 'Top academic results', 'program_level' => 'Master', 'opening_date' => '2026-03-01', 'funding_type' => 'Full Funding', 'description' => 'Tuition + stipend.', 'source_url' => 'https://www.bi.edu/scholarships/presidential/'],
                ['title' => 'Norwegian-Russian Scholarship', 'provider' => 'HK-dir', 'eligibility' => 'Specific exchange program', 'program_level' => 'Bachelor, Master', 'opening_date' => '2026-03-01', 'funding_type' => 'Stipend', 'description' => 'Cooperation between Russia and Norway.', 'source_url' => 'https://hkdir.no/en/programmes/norwegian-russian-scholarships'],
                ['title' => 'Erasmus+ Grants for Students in Norway', 'provider' => 'EU', 'eligibility' => 'Exchange students', 'program_level' => 'All levels', 'opening_date' => '2025-10-01', 'funding_type' => 'Grant', 'description' => 'Monthly supporting grant.', 'source_url' => 'https://hkdir.no/en/programmes/erasmus-plus-higher-education'],
            ],
            'FI' => [
                ['title' => 'Finland Scholarships', 'provider' => 'Finnish Gov', 'eligibility' => 'Non-EU Master students', 'program_level' => 'Master', 'opening_date' => '2026-01-01', 'funding_type' => 'Full Funding', 'description' => 'Includes €5000 relocation unit.', 'source_url' => 'https://www.studyinfinland.fi/scholarships/finland-scholarships/'],
                ['title' => 'University of Helsinki Excellence Grant', 'provider' => 'Helsinki', 'eligibility' => 'Top applicants', 'program_level' => 'Master', 'opening_date' => '2025-12-01', 'funding_type' => 'Full Funding', 'description' => 'Awarded to most qualified students.', 'source_url' => 'https://www.helsinki.fi/en/admissions/scholarships-and-funding/'],
                ['title' => 'Aalto University Scholarship Programme', 'provider' => 'Aalto', 'eligibility' => 'International students', 'program_level' => 'Bachelor, Master', 'opening_date' => '2026-01-05', 'funding_type' => 'Partial Funding', 'description' => '50% or 100% tuition waiver.', 'source_url' => 'https://www.aalto.fi/en/admission-services/scholarships-and-tuition-fees'],
            ],
            'PT' => [
                ['title' => 'ULisboa Merit Scholarships', 'provider' => 'University of Lisbon', 'eligibility' => 'Top performing students', 'program_level' => 'Bachelor, Master', 'opening_date' => '2026-05-01', 'funding_type' => 'Tuition Waiver', 'description' => 'Reduction in academic fees.', 'source_url' => 'https://www.ulisboa.pt/en/info/scholarships-and-prizes'],
                ['title' => 'UC International Excellence Scholarships', 'provider' => 'U of Coimbra', 'eligibility' => 'SAT/ACT performance', 'program_level' => 'Bachelor', 'opening_date' => '2026-04-01', 'funding_type' => 'Partial Funding', 'description' => 'Up to €2,000 reduction.', 'source_url' => 'https://www.uc.pt/en/overseas/academic-life/scholarships/'],
            ],
            'AT' => [
                ['title' => 'Ernst Mach Grant', 'provider' => 'Austrian Gov', 'eligibility' => 'Students from all countries', 'program_level' => 'Undergrad, Postgrad', 'opening_date' => '2026-01-01', 'funding_type' => 'Stipend', 'description' => '€1,050 to €1,150 monthly.', 'source_url' => 'https://grants.at/en/?=NDA3MjZfNDI4ODdfMQ=='],
                ['title' => 'ÖAD Richard Plaschka Grant', 'provider' => 'OeAD', 'eligibility' => 'Humanities/Linguistics', 'program_level' => 'PhD', 'opening_date' => '2026-02-01', 'funding_type' => 'Grant', 'description' => 'Promoting historical research.', 'source_url' => 'https://oead.at/en/to-austria/grants-and-scholarships/'],
            ],
            'NZ' => [
                ['title' => 'Manaaki New Zealand Scholarships', 'provider' => 'Gov of NZ', 'eligibility' => 'Partner nations', 'program_level' => 'Bachelor, Master, PhD', 'opening_date' => '2026-02-01', 'funding_type' => 'Full Funding', 'description' => 'Long term development fokus.', 'source_url' => 'https://www.nzscholarships.govt.nz/about/'],
                ['title' => 'University of Canterbury International Excellence', 'provider' => 'UC', 'eligibility' => 'High academic acheivement', 'program_level' => 'Bachelor', 'opening_date' => '2026-01-15', 'funding_type' => 'Partial Funding', 'description' => 'Up to $20,000 award.', 'source_url' => 'https://www.canterbury.ac.nz/get-started/scholarships/directory/university-of-canterbury-international-first-year-scholarship.html'],
                ['title' => 'Victoria University of Wellington International Excellence', 'provider' => 'VUW', 'eligibility' => 'Top talent students', 'program_level' => 'Bachelor, Master', 'opening_date' => '2026-03-01', 'funding_type' => 'Partial Funding', 'description' => '$10,000 to $20,000.', 'source_url' => 'https://www.wgtn.ac.nz/scholarships/types-of-scholarships/international/international-excellence-scholarships'],
            ],
        ];

        foreach ($scholarships as $countryCode => $items) {
            $country = Country::where('code', $countryCode)->first();
            if (!$country) continue;

            foreach ($items as $item) {
                // Ensure unique source_url by appending fragment if needed
                $uniqueUrl = $item['source_url'];
                
                Scholarship::updateOrCreate(
                    ['source_url' => $uniqueUrl],
                    array_merge($item, [
                        'country_id' => $country->id,
                        'scholarship_source_id' => $sourceId,
                        'application_link' => $item['source_url'],
                        'status' => 'approved',
                    ])
                );
            }
        }
    }
}

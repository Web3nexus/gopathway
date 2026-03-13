<?php
namespace Database\Seeders;
use App\Models\Country;
use App\Models\School;
use App\Models\SchoolProgram;
use Illuminate\Database\Seeder;

class FullSchoolDataSeeder extends Seeder
{
    // Helper: compact program entry
    private function p($name, $type, $field, $years, $fee, $currency, $intakes) {
        return ['name' => $name, 'degree_type' => $type, 'field_of_study' => $field, 'duration_years' => $years, 'tuition_per_year' => $fee, 'currency' => $currency, 'intake_periods' => $intakes];
    }

    public function run(): void
    {
        $data = [
            'GB' => [
                ['name'=>'University of Oxford','type'=>'public','website'=>'https://ox.ac.uk','application_portal'=>'https://www.ox.ac.uk/admissions','programs'=>[
                    $this->p('BSc Computer Science','bachelor','Computer Science',3,39050,'GBP',['Oct']),
                    $this->p('BSc Philosophy Politics Economics','bachelor','PPE',3,29700,'GBP',['Oct']),
                    $this->p('MSc Artificial Intelligence','master','AI',1,37510,'GBP',['Oct']),
                    $this->p('DPhil Engineering Science','phd','Engineering',4,29700,'GBP',['Oct']),
                ]],
                ['name'=>'University of Cambridge','type'=>'public','website'=>'https://cam.ac.uk','application_portal'=>'https://www.undergraduate.study.cam.ac.uk/applying','programs'=>[
                    $this->p('BA Natural Sciences','bachelor','Natural Sciences',3,24507,'GBP',['Oct']),
                    $this->p('BSc Engineering','bachelor','Engineering',3,36834,'GBP',['Oct']),
                    $this->p('MSc Data Science','master','Data Science',1,37200,'GBP',['Oct']),
                    $this->p('PhD Biotechnology','phd','Biotechnology',4,24507,'GBP',['Oct']),
                ]],
                ['name'=>'Imperial College London','type'=>'public','website'=>'https://imperial.ac.uk','application_portal'=>'https://www.imperial.ac.uk/study/apply/','programs'=>[
                    $this->p('BEng Electrical Engineering','bachelor','Engineering',3,35100,'GBP',['Oct']),
                    $this->p('MSc Computing','master','Computer Science',1,36500,'GBP',['Oct']),
                    $this->p('MBA Executive','master','Business',2,50000,'GBP',['Oct']),
                ]],
                ['name'=>'University of Edinburgh','type'=>'public','website'=>'https://ed.ac.uk','application_portal'=>'https://www.ed.ac.uk/studying-here/applying','programs'=>[
                    $this->p('BSc Informatics','bachelor','Computer Science',4,27000,'GBP',['Sept']),
                    $this->p('MA International Relations','bachelor','International Relations',4,27000,'GBP',['Sept']),
                    $this->p('MSc Data Science','master','Data Science',1,34000,'GBP',['Sept']),
                    $this->p('MSc Finance','master','Finance',1,35200,'GBP',['Sept']),
                ]],
                ['name'=>"King's College London",'type'=>'public','website'=>'https://kcl.ac.uk','application_portal'=>'https://www.kcl.ac.uk/study','programs'=>[
                    $this->p('LLB Law','bachelor','Law',3,24600,'GBP',['Sept']),
                    $this->p('BSc Biomedical Science','bachelor','Biomedicine',3,27500,'GBP',['Sept']),
                    $this->p('MSc Cybersecurity','master','Cybersecurity',1,29500,'GBP',['Sept']),
                ]],
                ['name'=>'Manchester Metropolitan University','type'=>'public','website'=>'https://mmu.ac.uk','application_portal'=>'https://www.mmu.ac.uk/study','programs'=>[
                    $this->p('BSc Nursing','bachelor','Nursing',3,15000,'GBP',['Sept','Jan']),
                    $this->p('BSc Sport Science','bachelor','Sport Science',3,15500,'GBP',['Sept']),
                    $this->p('MSc Data Analytics','master','Data Science',1,16000,'GBP',['Sept','Jan']),
                ]],
                ['name'=>'Northumbria University','type'=>'public','website'=>'https://northumbria.ac.uk','application_portal'=>'https://www.northumbria.ac.uk/study-at-northumbria/','programs'=>[
                    $this->p('BSc Business Management','bachelor','Business',3,17500,'GBP',['Sept','Jan']),
                    $this->p('BSc Computer Science','bachelor','Computer Science',3,17500,'GBP',['Sept','Jan']),
                    $this->p('MSc Project Management','master','Project Management',1,17000,'GBP',['Sept','Jan']),
                    $this->p('MBA','master','Business',1,19500,'GBP',['Sept','Jan']),
                ]],
                ['name'=>'University of Salford','type'=>'public','website'=>'https://salford.ac.uk','application_portal'=>'https://www.salford.ac.uk/undergraduate/apply','programs'=>[
                    $this->p('BSc Nursing','bachelor','Nursing',3,16200,'GBP',['Sept','Jan']),
                    $this->p('BSc Physiotherapy','bachelor','Physiotherapy',3,17000,'GBP',['Sept']),
                    $this->p('MSc Advanced Computer Science','master','Computer Science',1,16500,'GBP',['Sept','Jan']),
                    $this->p('MBA','master','Business',1,18000,'GBP',['Sept','Jan']),
                ]],
                ['name'=>'Middlesex University','type'=>'public','website'=>'https://mdx.ac.uk','application_portal'=>'https://www.mdx.ac.uk/courses/','programs'=>[
                    $this->p('BSc Accounting and Finance','bachelor','Accounting',3,15100,'GBP',['Sept','Jan','Mar']),
                    $this->p('BSc Business Administration','bachelor','Business',3,15100,'GBP',['Sept','Jan','Mar']),
                    $this->p('BSc Computer Science','bachelor','Computer Science',3,15100,'GBP',['Sept','Jan','Mar']),
                    $this->p('MSc International Business','master','Business',1,15800,'GBP',['Sept','Jan','Mar']),
                    $this->p('MBA','master','Business',1,17500,'GBP',['Sept','Jan']),
                ]],
                ['name'=>'Anglia Ruskin University','type'=>'public','website'=>'https://aru.ac.uk','application_portal'=>'https://www.aru.ac.uk/applying','programs'=>[
                    $this->p('BSc Nursing Adult','bachelor','Nursing',3,15000,'GBP',['Sept','Jan','Mar']),
                    $this->p('BSc Optometry','bachelor','Optometry',3,18500,'GBP',['Sept']),
                    $this->p('MSc Cyber Security','master','Cybersecurity',1,16000,'GBP',['Sept','Jan']),
                    $this->p('MBA','master','Business',1,16500,'GBP',['Sept','Jan']),
                ]],
                ['name'=>'London Metropolitan University','type'=>'public','website'=>'https://londonmet.ac.uk','application_portal'=>'https://www.londonmet.ac.uk/courses/','programs'=>[
                    $this->p('BSc Computing','bachelor','Computer Science',3,14500,'GBP',['Sept','Jan']),
                    $this->p('BSc Business Management','bachelor','Business',3,14500,'GBP',['Sept','Jan']),
                    $this->p('MSc Data Science','master','Data Science',1,14500,'GBP',['Sept','Jan']),
                    $this->p('MBA International','master','Business',1,15000,'GBP',['Sept','Jan']),
                ]],
                ['name'=>'University of Huddersfield','type'=>'public','website'=>'https://hud.ac.uk','application_portal'=>'https://www.hud.ac.uk/study/','programs'=>[
                    $this->p('BSc Pharmacy','bachelor','Pharmacy',4,18000,'GBP',['Sept']),
                    $this->p('BSc Computer Science','bachelor','Computer Science',3,16500,'GBP',['Sept','Jan']),
                    $this->p('MSc Pharmaceutical Science','master','Pharmacy',1,16500,'GBP',['Sept','Jan']),
                    $this->p('MSc Project Management','master','Project Management',1,16500,'GBP',['Sept','Jan']),
                    $this->p('MBA','master','Business',1,17500,'GBP',['Sept','Jan']),
                ]],
                ['name'=>'University of Wolverhampton','type'=>'public','website'=>'https://wlv.ac.uk','application_portal'=>'https://www.wlv.ac.uk/apply/','programs'=>[
                    $this->p('BSc Biomedical Science','bachelor','Biomedicine',3,13450,'GBP',['Sept','Jan']),
                    $this->p('BSc Business','bachelor','Business',3,13450,'GBP',['Sept','Jan']),
                    $this->p('MSc Public Health','master','Public Health',1,14000,'GBP',['Sept','Jan']),
                    $this->p('MBA','master','Business',1,14500,'GBP',['Sept','Jan']),
                ]],
                ['name'=>'Oxford Brookes University','type'=>'public','website'=>'https://brookes.ac.uk','application_portal'=>'https://www.brookes.ac.uk/applying/','programs'=>[
                    $this->p('BSc Nursing','bachelor','Nursing',3,15750,'GBP',['Sept','Jan']),
                    $this->p('BSc Business Management','bachelor','Business',3,15750,'GBP',['Sept','Jan']),
                    $this->p('MSc International Business','master','Business',1,16750,'GBP',['Sept','Jan']),
                    $this->p('MSc Public Health','master','Public Health',1,16100,'GBP',['Sept']),
                    $this->p('MBA','master','Business',1,19500,'GBP',['Sept','Jan']),
                ]],
                ['name'=>'Cardiff Metropolitan University','type'=>'public','website'=>'https://cardiffmet.ac.uk','application_portal'=>'https://www.cardiffmet.ac.uk/registry/Pages/Apply.aspx','programs'=>[
                    $this->p('BSc Business','bachelor','Business',3,13500,'GBP',['Sept','Jan']),
                    $this->p('BSc Sport and Exercise Science','bachelor','Sport Science',3,13500,'GBP',['Sept']),
                    $this->p('MSc International Business','master','Business',1,13500,'GBP',['Sept','Jan']),
                    $this->p('MBA','master','Business',1,14500,'GBP',['Sept','Jan']),
                ]],
                ['name'=>'De Montfort University','type'=>'public','website'=>'https://dmu.ac.uk','application_portal'=>'https://www.dmu.ac.uk/study/undergraduate/apply.aspx','programs'=>[
                    $this->p('LLB Law','bachelor','Law',3,15250,'GBP',['Sept','Jan']),
                    $this->p('BSc Computer Science','bachelor','Computer Science',3,15250,'GBP',['Sept','Jan']),
                    $this->p('MSc Cyber Security','master','Cybersecurity',1,15250,'GBP',['Sept','Jan']),
                    $this->p('MSc International Business','master','Business',1,15250,'GBP',['Sept','Jan']),
                    $this->p('MBA','master','Business',1,15750,'GBP',['Sept','Jan']),
                ]],
            ],
            'CA' => [
                ['name'=>'University of Toronto','type'=>'public','website'=>'https://utoronto.ca','application_portal'=>'https://future.utoronto.ca/apply/','programs'=>[
                    $this->p('BSc Computer Science','bachelor','Computer Science',4,57020,'CAD',['Sept']),
                    $this->p('BASc Electrical Engineering','bachelor','Engineering',4,60070,'CAD',['Sept']),
                    $this->p('MSc Artificial Intelligence','master','AI',1,26390,'CAD',['Sept']),
                    $this->p('MBA Rotman','master','Business',2,44000,'CAD',['Sept']),
                ]],
                ['name'=>'University of British Columbia','type'=>'public','website'=>'https://ubc.ca','application_portal'=>'https://you.ubc.ca/applying-ubc/','programs'=>[
                    $this->p('BSc Computer Science','bachelor','Computer Science',4,44428,'CAD',['Sept']),
                    $this->p('BSc Nursing','bachelor','Nursing',4,43000,'CAD',['Sept']),
                    $this->p('MBA Sauder School','master','Business',2,40000,'CAD',['Sept']),
                ]],
                ['name'=>'McGill University','type'=>'public','website'=>'https://mcgill.ca','application_portal'=>'https://www.mcgill.ca/applying/','programs'=>[
                    $this->p('BSc Computer Science','bachelor','Computer Science',3,31000,'CAD',['Sept']),
                    $this->p('MDCM Medicine','bachelor','Medicine',4,25000,'CAD',['Sept']),
                    $this->p('LLB Law','bachelor','Law',3,28000,'CAD',['Sept']),
                ]],
                ['name'=>'George Brown College','type'=>'college','website'=>'https://georgebrown.ca','application_portal'=>'https://www.georgebrown.ca/programs','programs'=>[
                    $this->p('Diploma Culinary Arts','diploma','Culinary Arts',2,15000,'CAD',['Sept','Jan']),
                    $this->p('Diploma Architecture Technology','diploma','Architecture',2,16200,'CAD',['Sept','Jan']),
                    $this->p('Postgraduate Health Information Management','certificate','Health',1,17500,'CAD',['Sept','Jan']),
                ]],
                ['name'=>'Seneca Polytechnic','type'=>'college','website'=>'https://senecapolytechnic.ca','application_portal'=>'https://www.ontariocolleges.ca','programs'=>[
                    $this->p('Diploma Business Administration','diploma','Business',2,16000,'CAD',['Sept','Jan','May']),
                    $this->p('Diploma Information Technology','diploma','IT',2,16500,'CAD',['Sept','Jan','May']),
                    $this->p('Graduate Certificate Project Management','certificate','Project Management',1,18000,'CAD',['Sept','Jan']),
                ]],
                ['name'=>'University of Alberta','type'=>'public','website'=>'https://ualberta.ca','application_portal'=>'https://www.ualberta.ca/admissions/','programs'=>[
                    $this->p('BSc Engineering','bachelor','Engineering',4,33000,'CAD',['Sept']),
                    $this->p('MSc Petroleum Engineering','master','Engineering',2,28000,'CAD',['Sept']),
                    $this->p('MBA','master','Business',2,40000,'CAD',['Sept']),
                ]],
                ['name'=>'Carleton University','type'=>'public','website'=>'https://carleton.ca','application_portal'=>'https://admissions.carleton.ca','programs'=>[
                    $this->p('BSc Computer Science','bachelor','Computer Science',4,30000,'CAD',['Sept','Jan']),
                    $this->p('BA Political Science','bachelor','Political Science',4,28000,'CAD',['Sept','Jan']),
                    $this->p('MSc IT Security','master','Cybersecurity',2,25000,'CAD',['Sept','Jan']),
                    $this->p('MBA Sprott School','master','Business',2,38000,'CAD',['Sept']),
                ]],
                ['name'=>'Toronto Metropolitan University','type'=>'public','website'=>'https://torontomu.ca','application_portal'=>'https://www.torontomu.ca/admissions/','programs'=>[
                    $this->p('BSc Nursing','bachelor','Nursing',4,30700,'CAD',['Sept','Jan']),
                    $this->p('BSc Business Management','bachelor','Business',4,32000,'CAD',['Sept','Jan']),
                    $this->p('MSc Data Science and Analytics','master','Data Science',2,27000,'CAD',['Sept','Jan']),
                    $this->p('MBA','master','Business',2,37500,'CAD',['Sept']),
                    $this->p('Graduate Certificate Project Management','certificate','Project Management',1,18000,'CAD',['Sept','Jan']),
                ]],
                ['name'=>'Ontario Tech University','type'=>'public','website'=>'https://ontariotechu.ca','application_portal'=>'https://ontariotechu.ca/future-students/undergraduate/','programs'=>[
                    $this->p('BSc Software Engineering','bachelor','Engineering',4,28000,'CAD',['Sept','Jan']),
                    $this->p('BSc Business and IT','bachelor','Business',4,28000,'CAD',['Sept','Jan']),
                    $this->p('MSc Computer Science','master','Computer Science',2,25000,'CAD',['Sept']),
                ]],
                ['name'=>'Algoma University','type'=>'public','website'=>'https://algomau.ca','application_portal'=>'https://www.algomau.ca/future-students/apply/','programs'=>[
                    $this->p('BSc Computer Science','bachelor','Computer Science',4,22000,'CAD',['Sept','Jan','May']),
                    $this->p('BA Business Administration','bachelor','Business',4,22000,'CAD',['Sept','Jan','May']),
                ]],
                ['name'=>'Lakehead University','type'=>'public','website'=>'https://lakeheadu.ca','application_portal'=>'https://www.lakeheadu.ca/programs/departments/','programs'=>[
                    $this->p('BSc Nursing','bachelor','Nursing',4,26000,'CAD',['Sept','Jan']),
                    $this->p('BSc Engineering','bachelor','Engineering',4,28000,'CAD',['Sept','Jan']),
                    $this->p('MBA','master','Business',2,22000,'CAD',['Sept','Jan']),
                ]],
                ['name'=>'Cape Breton University','type'=>'public','website'=>'https://cbu.ca','application_portal'=>'https://www.cbu.ca/admissions/','programs'=>[
                    $this->p('BSc Business Administration','bachelor','Business',4,18500,'CAD',['Sept','Jan','May']),
                    $this->p('BSc Information Technology','bachelor','IT',4,18500,'CAD',['Sept','Jan','May']),
                    $this->p('MBA','master','Business',2,18500,'CAD',['Sept','Jan']),
                ]],
                ['name'=>'Humber College','type'=>'college','website'=>'https://humber.ca','application_portal'=>'https://humber.ca/future-international-students.html','programs'=>[
                    $this->p('Diploma Business Administration','diploma','Business',2,16800,'CAD',['Sept','Jan']),
                    $this->p('Diploma Information Technology','diploma','IT',2,16800,'CAD',['Sept','Jan']),
                    $this->p('Graduate Certificate Cybersecurity','certificate','Cybersecurity',1,18000,'CAD',['Sept','Jan']),
                ]],
                ['name'=>'Centennial College','type'=>'college','website'=>'https://centennialcollege.ca','application_portal'=>'https://www.centennialcollege.ca/admissions/','programs'=>[
                    $this->p('Diploma Computer Systems Technology','diploma','IT',2,16000,'CAD',['Jan','May','Sept']),
                    $this->p('Diploma Nursing RPN','diploma','Nursing',2,16000,'CAD',['Sept','Jan']),
                    $this->p('Graduate Certificate Business Management','certificate','Business',1,17000,'CAD',['Sept','Jan']),
                    $this->p('Diploma Supply Chain Management','diploma','Logistics',2,14500,'CAD',['Sept','Jan']),
                ]],
            ],
            'DE' => [
                ['name'=>'Technical University of Munich','type'=>'public','website'=>'https://tum.de','application_portal'=>'https://portal.mytum.de','programs'=>[
                    $this->p('BSc Mechanical Engineering','bachelor','Engineering',3,130,'EUR',['Oct','Apr']),
                    $this->p('BSc Informatics','bachelor','Computer Science',3,130,'EUR',['Oct','Apr']),
                    $this->p('MSc Robotics Cognition Intelligence','master','AI',2,130,'EUR',['Oct','Apr']),
                    $this->p('MSc Management and Technology','master','Business',2,12000,'EUR',['Oct']),
                ]],
                ['name'=>'LMU Munich','type'=>'public','website'=>'https://lmu.de','application_portal'=>'https://www.lmu.de/en/study/students/enrollment/','programs'=>[
                    $this->p('BSc Physics','bachelor','Physics',3,130,'EUR',['Oct','Apr']),
                    $this->p('BSc Psychology','bachelor','Psychology',3,130,'EUR',['Oct','Apr']),
                    $this->p('MSc Data Science','master','Data Science',2,130,'EUR',['Oct','Apr']),
                    $this->p('MSc Neuroscience','master','Neuroscience',2,130,'EUR',['Oct','Apr']),
                ]],
                ['name'=>'Heidelberg University','type'=>'public','website'=>'https://uni-heidelberg.de','application_portal'=>'https://www.uni-heidelberg.de/en/study','programs'=>[
                    $this->p('BSc Molecular Biotechnology','bachelor','Biotechnology',3,130,'EUR',['Oct','Apr']),
                    $this->p('BSc Computer Science','bachelor','Computer Science',3,130,'EUR',['Oct','Apr']),
                    $this->p('MSc Molecular Biosciences','master','Life Sciences',2,130,'EUR',['Oct','Apr']),
                ]],
                ['name'=>'RWTH Aachen University','type'=>'public','website'=>'https://rwth-aachen.de','application_portal'=>'https://www.rwth-aachen.de/cms/root/studium/vor-dem-studium/bewerbung/','programs'=>[
                    $this->p('BSc Mechanical Engineering','bachelor','Engineering',3,130,'EUR',['Oct','Apr']),
                    $this->p('BSc Electrical Engineering','bachelor','Engineering',3,130,'EUR',['Oct','Apr']),
                    $this->p('MSc Computational Engineering','master','Engineering',2,130,'EUR',['Oct','Apr']),
                ]],
                ['name'=>'Goethe University Frankfurt','type'=>'public','website'=>'https://goethe-university-frankfurt.de','application_portal'=>'https://www.goethe-university-frankfurt.de/en/studies/','programs'=>[
                    $this->p('BSc Economics','bachelor','Economics',3,130,'EUR',['Oct','Apr']),
                    $this->p('MSc Finance','master','Finance',2,130,'EUR',['Oct','Apr']),
                    $this->p('LLM International Business Law','master','Law',2,130,'EUR',['Oct','Apr']),
                ]],
                ['name'=>'Berlin International University of Applied Sciences','type'=>'private','website'=>'https://berlin-international.de','application_portal'=>'https://berlin-international.de/apply','programs'=>[
                    $this->p('BSc Business Administration','bachelor','Business',3,7200,'EUR',['Mar','Sept']),
                    $this->p('BSc Digital Business','bachelor','Business',3,7200,'EUR',['Mar','Sept']),
                    $this->p('MSc International Business Management','master','Business',2,9500,'EUR',['Mar','Sept']),
                ]],
                ['name'=>'Berlin School of Business and Innovation','type'=>'private','website'=>'https://bsbi.de','application_portal'=>'https://bsbi.de/how-to-apply/','programs'=>[
                    $this->p('BSc Business Administration','bachelor','Business',3,8400,'EUR',['Jan','Apr','Jul','Oct']),
                    $this->p('BSc Computer Science','bachelor','Computer Science',3,8400,'EUR',['Jan','Apr','Jul','Oct']),
                    $this->p('MSc International Business','master','Business',2,10200,'EUR',['Jan','Apr','Jul','Oct']),
                    $this->p('MBA','master','Business',2,12600,'EUR',['Jan','Apr','Jul','Oct']),
                ]],
                ['name'=>'EU Business School Munich','type'=>'private','website'=>'https://euruni.edu','application_portal'=>'https://euruni.edu/admissions/','programs'=>[
                    $this->p('BSc Business','bachelor','Business',3,13500,'EUR',['Sept','Jan']),
                    $this->p('MSc International Business','master','Business',2,17500,'EUR',['Sept','Jan']),
                    $this->p('MBA','master','Business',1,22000,'EUR',['Sept','Jan']),
                ]],
                ['name'=>'International School of Management ISM','type'=>'private','website'=>'https://ism.de','application_portal'=>'https://www.ism.de/en/admission/','programs'=>[
                    $this->p('BSc International Management','bachelor','Business',3,12000,'EUR',['Mar','Sept']),
                    $this->p('MSc Digital Business Management','master','Business',2,14000,'EUR',['Mar','Sept']),
                    $this->p('MBA','master','Business',1,19000,'EUR',['Mar','Sept']),
                ]],
            ],
            'NL' => [
                ['name'=>'Eindhoven University of Technology','type'=>'public','website'=>'https://tue.nl','application_portal'=>'https://www.tue.nl/en/education/student/','programs'=>[
                    $this->p('BSc Industrial Engineering','bachelor','Engineering',3,9000,'EUR',['Sept']),
                    $this->p('BSc Electrical Engineering','bachelor','Engineering',3,9000,'EUR',['Sept']),
                    $this->p('MSc Data Science and AI','master','AI',2,18000,'EUR',['Sept']),
                    $this->p('MSc Biomedical Engineering','master','Engineering',2,18000,'EUR',['Sept']),
                ]],
                ['name'=>'The Hague University of Applied Sciences','type'=>'public','website'=>'https://thehagueuniversity.com','application_portal'=>'https://www.thehagueuniversity.com/study/bachelor/applying','programs'=>[
                    $this->p('BSc International Business','bachelor','Business',4,9000,'EUR',['Sept']),
                    $this->p('BSc Security Management','bachelor','Security',4,9000,'EUR',['Sept']),
                    $this->p('BSc Law','bachelor','Law',4,9000,'EUR',['Sept']),
                ]],
                ['name'=>'HAN University of Applied Sciences','type'=>'public','website'=>'https://hanuniversity.com','application_portal'=>'https://hanuniversity.com/en/education/bachelor/','programs'=>[
                    $this->p('BSc International Business','bachelor','Business',4,8900,'EUR',['Sept']),
                    $this->p('BSc Engineering','bachelor','Engineering',4,8900,'EUR',['Sept']),
                ]],
                ['name'=>'Rotterdam University of Applied Sciences','type'=>'public','website'=>'https://rotterdamuas.com','application_portal'=>'https://rotterdamuas.com/education/bachelor/','programs'=>[
                    $this->p('BSc Business Administration','bachelor','Business',4,9500,'EUR',['Sept']),
                    $this->p('BSc Logistics and Supply Chain','bachelor','Logistics',4,9500,'EUR',['Sept']),
                ]],
                ['name'=>'Wittenborg University of Applied Sciences','type'=>'private','website'=>'https://wittenborg.eu','application_portal'=>'https://wittenborg.eu/apply/','programs'=>[
                    $this->p('BSc International Business','bachelor','Business',4,9200,'EUR',['Feb','Apr','Jun','Aug','Oct','Dec']),
                    $this->p('BSc Hospitality Management','bachelor','Hospitality',4,9200,'EUR',['Feb','Apr','Jun','Aug','Oct','Dec']),
                    $this->p('MSc International Business Management','master','Business',2,11000,'EUR',['Feb','Apr','Jun','Aug','Oct','Dec']),
                    $this->p('MBA','master','Business',1,13500,'EUR',['Feb','Apr','Jun','Aug','Oct','Dec']),
                ]],
            ],
            'IE' => [
                ['name'=>'University College Cork','type'=>'public','website'=>'https://ucc.ie','application_portal'=>'https://www.ucc.ie/en/study/undergraduate/how-to-apply/','programs'=>[
                    $this->p('BSc Biomedical Science','bachelor','Biomedicine',4,16000,'EUR',['Sept']),
                    $this->p('MSc Food Science','master','Food Science',1,17000,'EUR',['Sept']),
                    $this->p('MSc Nursing','master','Nursing',2,16500,'EUR',['Sept']),
                ]],
                ['name'=>'Maynooth University','type'=>'public','website'=>'https://mu.ie','application_portal'=>'https://www.maynoothuniversity.ie/study/','programs'=>[
                    $this->p('BSc Computer Science and Software Engineering','bachelor','Computer Science',4,13500,'EUR',['Sept']),
                    $this->p('BSc Psychology','bachelor','Psychology',4,13500,'EUR',['Sept']),
                    $this->p('MSc Robotics','master','Engineering',1,14000,'EUR',['Sept']),
                ]],
                ['name'=>'Munster Technological University','type'=>'public','website'=>'https://mtu.ie','application_portal'=>'https://www.mtu.ie/courses/','programs'=>[
                    $this->p('BSc IT Management','bachelor','IT',4,12000,'EUR',['Sept']),
                    $this->p('BSc Business Information Systems','bachelor','Business',4,12000,'EUR',['Sept']),
                    $this->p('MSc Data Analytics','master','Data Science',1,14000,'EUR',['Sept']),
                ]],
                ['name'=>'Technological University Dublin','type'=>'public','website'=>'https://tudublin.ie','application_portal'=>'https://www.tudublin.ie/study/','programs'=>[
                    $this->p('BSc Computing','bachelor','Computer Science',4,12000,'EUR',['Sept']),
                    $this->p('BSc Business','bachelor','Business',4,10500,'EUR',['Sept']),
                    $this->p('MSc Cybersecurity','master','Cybersecurity',1,12500,'EUR',['Sept']),
                    $this->p('MSc Project Management','master','Project Management',1,11500,'EUR',['Sept']),
                ]],
                ['name'=>'National College of Ireland','type'=>'private','website'=>'https://ncirl.ie','application_portal'=>'https://www.ncirl.ie/How-to-Apply','programs'=>[
                    $this->p('BSc Computing','bachelor','Computer Science',4,12250,'EUR',['Sept','Jan']),
                    $this->p('BSc Business','bachelor','Business',4,11750,'EUR',['Sept','Jan']),
                    $this->p('MSc Data Analytics','master','Data Science',1,12500,'EUR',['Sept','Jan']),
                    $this->p('MSc Fintech','master','Finance',1,12500,'EUR',['Sept','Jan']),
                    $this->p('MBA','master','Business',1,18000,'EUR',['Sept','Jan']),
                ]],
                ['name'=>'South East Technological University','type'=>'public','website'=>'https://setu.ie','application_portal'=>'https://setu.ie/admissions/','programs'=>[
                    $this->p('BSc Software Systems Development','bachelor','Computer Science',4,10000,'EUR',['Sept']),
                    $this->p('BSc Nursing','bachelor','Nursing',4,11500,'EUR',['Sept']),
                    $this->p('MSc AI and Machine Learning','master','AI',1,11500,'EUR',['Sept']),
                ]],
            ],
            'AU' => [
                ['name'=>'Australian National University','type'=>'public','website'=>'https://anu.edu.au','application_portal'=>'https://study.anu.edu.au/apply','programs'=>[
                    $this->p('BSc Cybersecurity','bachelor','Cybersecurity',3,41440,'AUD',['Feb','Jul']),
                    $this->p('BA International Relations','bachelor','International Relations',3,38784,'AUD',['Feb','Jul']),
                    $this->p('MSc Astrophysics','master','Physics',2,45024,'AUD',['Feb','Jul']),
                ]],
                ['name'=>'University of Queensland','type'=>'public','website'=>'https://uq.edu.au','application_portal'=>'https://study.uq.edu.au/applying','programs'=>[
                    $this->p('BSc Chemistry','bachelor','Chemistry',3,41872,'AUD',['Feb','Jul']),
                    $this->p('BEng Biomedical Engineering','bachelor','Engineering',4,50480,'AUD',['Feb','Jul']),
                    $this->p('MSc Bioinformatics','master','Life Sciences',2,42520,'AUD',['Feb','Jul']),
                    $this->p('MBA','master','Business',2,50000,'AUD',['Feb','Jul']),
                ]],
                ['name'=>'RMIT University','type'=>'public','website'=>'https://rmit.edu.au','application_portal'=>'https://www.rmit.edu.au/study-with-us/international-students/apply','programs'=>[
                    $this->p('BSc Information Technology','bachelor','IT',3,35750,'AUD',['Feb','Jul']),
                    $this->p('BSc Fashion Design','bachelor','Design',3,37000,'AUD',['Feb','Jul']),
                    $this->p('MSc Data Science','master','Data Science',2,38750,'AUD',['Feb','Jul']),
                ]],
                ['name'=>'Western Sydney University','type'=>'public','website'=>'https://westernsydney.edu.au','application_portal'=>'https://www.westernsydney.edu.au/internationalstudy/','programs'=>[
                    $this->p('BSc Nursing','bachelor','Nursing',3,32000,'AUD',['Feb','Jul']),
                    $this->p('BSc Engineering','bachelor','Engineering',4,33000,'AUD',['Feb','Jul']),
                    $this->p('BSc Law','bachelor','Law',4,34000,'AUD',['Feb','Jul']),
                    $this->p('MSc IT','master','IT',2,30500,'AUD',['Feb','Jul']),
                    $this->p('MBA','master','Business',2,33000,'AUD',['Feb','Jul']),
                ]],
                ['name'=>'Charles Sturt University','type'=>'public','website'=>'https://csu.edu.au','application_portal'=>'https://www.csu.edu.au/courses/','programs'=>[
                    $this->p('BSc Nursing','bachelor','Nursing',3,28500,'AUD',['Feb','Jul']),
                    $this->p('BSc Teaching','bachelor','Education',4,26500,'AUD',['Feb','Jul']),
                    $this->p('BSc Cybersecurity','bachelor','Cybersecurity',3,29000,'AUD',['Feb','Jul']),
                    $this->p('MBA','master','Business',2,28000,'AUD',['Feb','Jul']),
                ]],
                ['name'=>'Victoria University Melbourne','type'=>'public','website'=>'https://vu.edu.au','application_portal'=>'https://www.vu.edu.au/study-at-vu/','programs'=>[
                    $this->p('BSc Accounting','bachelor','Accounting',3,27500,'AUD',['Feb','Jul','Nov']),
                    $this->p('BSc Information Technology','bachelor','IT',3,28000,'AUD',['Feb','Jul','Nov']),
                    $this->p('MSc Supply Chain Management','master','Logistics',2,27000,'AUD',['Feb','Jul']),
                ]],
                ['name'=>'Charles Darwin University','type'=>'public','website'=>'https://cdu.edu.au','application_portal'=>'https://www.cdu.edu.au/study/international','programs'=>[
                    $this->p('BSc Nursing','bachelor','Nursing',3,25600,'AUD',['Feb','Jul']),
                    $this->p('BSc Engineering','bachelor','Engineering',4,26000,'AUD',['Feb','Jul']),
                    $this->p('MSc Public Health','master','Public Health',2,25000,'AUD',['Feb','Jul']),
                ]],
            ],
            'NZ' => [
                ['name'=>'University of Otago','type'=>'public','website'=>'https://otago.ac.nz','application_portal'=>'https://www.otago.ac.nz/courses/study/','programs'=>[
                    $this->p('BSc Medical Science','bachelor','Medicine',3,38000,'NZD',['Feb','Jul']),
                    $this->p('BCom Business','bachelor','Business',3,29000,'NZD',['Feb','Jul']),
                    $this->p('MSc Pharmacology','master','Pharmacology',2,36000,'NZD',['Feb','Jul']),
                ]],
            ],
            'ES' => [
                ['name'=>'University of Barcelona','type'=>'public','website'=>'https://ub.edu','application_portal'=>'https://web.ub.edu/en/web/estudis/masters','programs'=>[
                    $this->p('BSc Economics','bachelor','Economics',4,3500,'EUR',['Sept']),
                    $this->p('BSc Biology','bachelor','Biology',4,3200,'EUR',['Sept']),
                    $this->p('MSc Bioinformatics','master','Life Sciences',2,5000,'EUR',['Sept']),
                ]],
                ['name'=>'IE University','type'=>'private','website'=>'https://ie.edu','application_portal'=>'https://www.ie.edu/university/apply/','programs'=>[
                    $this->p('BSc Data and Business Analytics','bachelor','Business',4,24000,'EUR',['Sept']),
                    $this->p('BSc Economics and Finance','bachelor','Finance',4,24000,'EUR',['Sept']),
                    $this->p('MSc Marketing and Digital Media','master','Marketing',1,30000,'EUR',['Sept']),
                    $this->p('MBA Full-Time','master','Business',1,65000,'EUR',['Sept']),
                ]],
                ['name'=>'Polytechnic University of Catalonia','type'=>'public','website'=>'https://upc.edu','application_portal'=>'https://www.upc.edu/en/admissions','programs'=>[
                    $this->p('BSc Civil Engineering','bachelor','Engineering',4,3200,'EUR',['Sept']),
                    $this->p('BSc Computer Engineering','bachelor','Computer Science',4,3200,'EUR',['Sept']),
                    $this->p('MSc Aerospace Science','master','Engineering',2,5500,'EUR',['Sept']),
                ]],
                ['name'=>'EAE Business School','type'=>'private','website'=>'https://eae.es','application_portal'=>'https://www.eae.es/en/apply','programs'=>[
                    $this->p('BSc Business Administration','bachelor','Business',4,9000,'EUR',['Oct','Feb','May']),
                    $this->p('MSc International Business','master','Business',1,16000,'EUR',['Oct','Feb','May']),
                    $this->p('MBA Full-Time','master','Business',1,22000,'EUR',['Oct','Feb']),
                ]],
                ['name'=>'ESADE Business School','type'=>'private','website'=>'https://esade.edu','application_portal'=>'https://www.esade.edu/en/admissions','programs'=>[
                    $this->p('BSc Business Administration','bachelor','Business',4,18000,'EUR',['Sept']),
                    $this->p('MSc Marketing Management','master','Marketing',1,26000,'EUR',['Sept']),
                    $this->p('MBA Full-Time','master','Business',1,67000,'EUR',['Sept']),
                    $this->p('LLM International Law','master','Law',1,22000,'EUR',['Sept']),
                ]],
            ],
            'PT' => [
                ['name'=>'University of Lisbon','type'=>'public','website'=>'https://ulisboa.pt','application_portal'=>'https://www.ulisboa.pt/en/candidatos','programs'=>[
                    $this->p('BSc Computer Science','bachelor','Computer Science',3,1500,'EUR',['Sept']),
                    $this->p('BSc Engineering','bachelor','Engineering',3,2000,'EUR',['Sept']),
                    $this->p('MSc Management','master','Business',2,5000,'EUR',['Sept']),
                    $this->p('MSc Biomedical Engineering','master','Engineering',2,4500,'EUR',['Sept']),
                ]],
                ['name'=>'Nova University Lisbon','type'=>'public','website'=>'https://unl.pt','application_portal'=>'https://www.unl.pt/en','programs'=>[
                    $this->p('BSc Economics','bachelor','Economics',3,7500,'EUR',['Sept']),
                    $this->p('MSc Finance','master','Finance',2,9500,'EUR',['Sept']),
                    $this->p('MSc Management','master','Business',2,8300,'EUR',['Sept']),
                    $this->p('MBA Nova SBE','master','Business',1,40000,'EUR',['Sept']),
                ]],
                ['name'=>'University of Porto','type'=>'public','website'=>'https://up.pt','application_portal'=>'https://sigarra.up.pt/up/en/','programs'=>[
                    $this->p('BSc Engineering','bachelor','Engineering',3,1600,'EUR',['Sept']),
                    $this->p('MSc Biomedical Engineering','master','Engineering',2,4500,'EUR',['Sept']),
                    $this->p('MSc Computer Science','master','Computer Science',2,4000,'EUR',['Sept']),
                ]],
            ],
            'FR' => [
                ['name'=>'Sorbonne University','type'=>'public','website'=>'https://sorbonne-universite.fr','application_portal'=>'https://www.sorbonne-universite.fr/en/education/applying-sorbonne-university','programs'=>[
                    $this->p('Licence Computer Science','bachelor','Computer Science',3,2770,'EUR',['Sept']),
                    $this->p('Licence Biology','bachelor','Biology',3,2770,'EUR',['Sept']),
                    $this->p('Master AI and Cognitive Science','master','AI',2,3770,'EUR',['Sept']),
                    $this->p('Master Physics','master','Physics',2,3770,'EUR',['Sept']),
                ]],
                ['name'=>'Sciences Po','type'=>'public','website'=>'https://sciencespo.fr','application_portal'=>'https://www.sciencespo.fr/admissions/en/','programs'=>[
                    $this->p('BA Political Science','bachelor','Political Science',3,10000,'EUR',['Sept']),
                    $this->p('MA International Affairs','master','International Relations',2,13000,'EUR',['Sept']),
                    $this->p('MBA Executive Education','master','Business',1,30000,'EUR',['Sept']),
                ]],
                ['name'=>'University of Paris-Saclay','type'=>'public','website'=>'https://universite-paris-saclay.fr','application_portal'=>'https://www.universite-paris-saclay.fr/en/admission','programs'=>[
                    $this->p('Licence Mathematics','bachelor','Mathematics',3,2770,'EUR',['Sept']),
                    $this->p('Master Data Science and AI','master','AI',2,3770,'EUR',['Sept']),
                    $this->p('Master Computer Science','master','Computer Science',2,3770,'EUR',['Sept']),
                ]],
            ],
            'IT' => [
                ['name'=>'Politecnico di Milano','type'=>'public','website'=>'https://polimi.it','application_portal'=>'https://www.polimi.it/en/prospective-students/','programs'=>[
                    $this->p('BSc Architecture','bachelor','Architecture',3,3748,'EUR',['Sept','Apr']),
                    $this->p('BSc Civil Engineering','bachelor','Engineering',3,3748,'EUR',['Sept','Apr']),
                    $this->p('MSc Computer Science and Engineering','master','Computer Science',2,3748,'EUR',['Sept','Apr']),
                    $this->p('MSc Mechanical Engineering','master','Engineering',2,3748,'EUR',['Sept','Apr']),
                ]],
                ['name'=>'University of Bologna','type'=>'public','website'=>'https://unibo.it','application_portal'=>'https://www.unibo.it/en/teaching/degree-programmes/','programs'=>[
                    $this->p('Laurea Medicine','bachelor','Medicine',6,2800,'EUR',['Sept']),
                    $this->p('Laurea Law','bachelor','Law',5,2800,'EUR',['Sept']),
                    $this->p('LM Artificial Intelligence','master','AI',2,2800,'EUR',['Sept']),
                ]],
                ['name'=>'Bocconi University','type'=>'private','website'=>'https://unibocconi.it','application_portal'=>'https://www.unibocconi.it/en/programmes/undergraduate-programmes','programs'=>[
                    $this->p('BSc Economics and Management','bachelor','Business',3,14000,'EUR',['Sept']),
                    $this->p('BSc International Economics','bachelor','Economics',3,14000,'EUR',['Sept']),
                    $this->p('MSc Finance','master','Finance',2,15000,'EUR',['Sept']),
                    $this->p('MBA Full-Time','master','Business',1,47500,'EUR',['Sept']),
                ]],
            ],
            'SE' => [
                ['name'=>'Karolinska Institute','type'=>'public','website'=>'https://ki.se','application_portal'=>'https://ki.se/en/education/study-here','programs'=>[
                    $this->p('BSc Biomedicine','bachelor','Biomedicine',3,195000,'SEK',['Sept']),
                    $this->p('MSc Biomedicine','master','Biomedicine',2,195000,'SEK',['Sept']),
                    $this->p('MSc Public Health Sciences','master','Public Health',2,175000,'SEK',['Sept']),
                ]],
                ['name'=>'Stockholm University','type'=>'public','website'=>'https://su.se','application_portal'=>'https://www.su.se/english/education/','programs'=>[
                    $this->p('BSc Cognitive Science','bachelor','Cognitive Science',3,160000,'SEK',['Sept']),
                    $this->p('MSc Sustainable Development','master','Environmental Science',2,150000,'SEK',['Sept']),
                ]],
                ['name'=>'Chalmers University of Technology','type'=>'public','website'=>'https://chalmers.se','application_portal'=>'https://www.chalmers.se/en/education/apply/','programs'=>[
                    $this->p('BSc Electrical Engineering','bachelor','Engineering',3,165000,'SEK',['Sept']),
                    $this->p('MSc Architecture and Engineering','master','Architecture',2,175000,'SEK',['Sept']),
                    $this->p('MSc Data Science and AI','master','AI',2,185000,'SEK',['Sept']),
                ]],
                ['name'=>'Malmo University','type'=>'public','website'=>'https://mau.se','application_portal'=>'https://www.mau.se/en/education/apply/','programs'=>[
                    $this->p('BSc Information Technology','bachelor','IT',3,140000,'SEK',['Sept']),
                    $this->p('MSc Computer Science','master','Computer Science',2,145000,'SEK',['Sept']),
                    $this->p('MSc Biomedical Science','master','Biomedicine',2,145000,'SEK',['Sept']),
                ]],
                ['name'=>'Mid Sweden University','type'=>'public','website'=>'https://miun.se','application_portal'=>'https://www.miun.se/en/education/apply-here/','programs'=>[
                    $this->p('BSc Computer Engineering','bachelor','Engineering',3,125000,'SEK',['Sept']),
                    $this->p('MSc Quality Management','master','Management',2,130000,'SEK',['Sept']),
                ]],
            ],
            'FI' => [
                ['name'=>'University of Vaasa','type'=>'public','website'=>'https://uwasa.fi','application_portal'=>'https://www.uwasa.fi/en/admissions','programs'=>[
                    $this->p('BSc Industrial Management','bachelor','Management',3,10000,'EUR',['Sept']),
                    $this->p('MSc Accounting and Finance','master','Finance',2,12000,'EUR',['Sept']),
                    $this->p('MSc Computer Science','master','Computer Science',2,12000,'EUR',['Sept']),
                ]],
            ],
            'NO' => [
                ['name'=>'University of South-Eastern Norway','type'=>'public','website'=>'https://usn.no','application_portal'=>'https://www.usn.no/english/','programs'=>[
                    $this->p('BSc Technology and Engineering','bachelor','Engineering',3,700,'NOK',['Aug']),
                    $this->p('MSc Process Technology','master','Engineering',2,700,'NOK',['Aug']),
                    $this->p('MSc Renewable Energy','master','Engineering',2,700,'NOK',['Aug']),
                ]],
            ],
            'AT' => [
                ['name'=>'WU Vienna University of Economics and Business','type'=>'public','website'=>'https://wu.ac.at','application_portal'=>'https://www.wu.ac.at/en/students/my-program/apply/','programs'=>[
                    $this->p('BSc Business and Economics','bachelor','Business',3,1500,'EUR',['Oct']),
                    $this->p('MSc Supply Chain Management','master','Logistics',2,9000,'EUR',['Oct']),
                    $this->p('MBA Executive MBA','master','Business',2,30000,'EUR',['Oct']),
                    $this->p('MSc Data Science','master','Data Science',2,9000,'EUR',['Oct']),
                ]],
            ],
            'NG' => [
                ['name'=>'University of Lagos','type'=>'public','website'=>'https://unilag.edu.ng','application_portal'=>'https://portal.unilag.edu.ng','programs'=>[
                    $this->p('BSc Computer Science','bachelor','Computer Science',4,200000,'NGN',['Sept']),
                    $this->p('MBBS Medicine and Surgery','bachelor','Medicine',6,500000,'NGN',['Sept']),
                    $this->p('MSc Information Technology','master','IT',2,400000,'NGN',['Sept']),
                ]],
                ['name'=>'Covenant University','type'=>'private','website'=>'https://covenantuniversity.edu.ng','application_portal'=>'https://covenantuniversity.edu.ng/Admissions','programs'=>[
                    $this->p('BSc Petroleum Engineering','bachelor','Engineering',5,1500000,'NGN',['Sept']),
                    $this->p('BSc Computer Science','bachelor','Computer Science',4,1200000,'NGN',['Sept']),
                    $this->p('BSc Architecture','bachelor','Architecture',5,1200000,'NGN',['Sept']),
                    $this->p('MSc Business Administration','master','Business',2,1000000,'NGN',['Sept']),
                ]],
                ['name'=>'Obafemi Awolowo University','type'=>'public','website'=>'https://obafemiuniversity.edu.ng','application_portal'=>'https://portal.oauife.edu.ng','programs'=>[
                    $this->p('BSc Electrical Electronics Engineering','bachelor','Engineering',5,300000,'NGN',['Sept']),
                    $this->p('MBBS Medicine','bachelor','Medicine',6,400000,'NGN',['Sept']),
                    $this->p('MSc Computer Science','master','Computer Science',2,250000,'NGN',['Sept']),
                ]],
                ['name'=>'Lagos Business School','type'=>'private','website'=>'https://lbs.edu.ng','application_portal'=>'https://lbs.edu.ng/programmes/','programs'=>[
                    $this->p('MBA Full-Time','master','Business',2,7200000,'NGN',['Sept']),
                    $this->p('MBA Executive','master','Business',2,5800000,'NGN',['Jan','Sept']),
                ]],
            ],
        ];

        foreach ($data as $countryCode => $schools) {
            $country = Country::where('code', $countryCode)->first();
            if (!$country) continue;

            foreach ($schools as $schoolData) {
                $programs = $schoolData['programs'] ?? [];
                unset($schoolData['programs']);

                $school = School::updateOrCreate(
                    ['country_id' => $country->id, 'name' => $schoolData['name']],
                    array_merge($schoolData, ['country_id' => $country->id])
                );

                foreach ($programs as $programData) {
                    SchoolProgram::updateOrCreate(
                        ['school_id' => $school->id, 'name' => $programData['name']],
                        array_merge($programData, ['school_id' => $school->id])
                    );
                }
            }
            $this->command->info("Seeded schools for: {$country->name}");
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\SettlementStep;
use App\Models\GovernmentOffice;

class SettlementDataSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\SettlementStep::truncate();
        \App\Models\GovernmentOffice::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $countries = Country::all();

        foreach ($countries as $country) {
            $this->seedForCountry($country);
        }
    }

    private function seedForCountry(Country $country)
    {
        switch ($country->name) {
            case 'Germany':
                $this->seedGermany($country);
                break;
            case 'France':
                $this->seedFrance($country);
                break;
            case 'United Kingdom':
                $this->seedUK($country);
                break;
            case 'Netherlands':
                $this->seedNetherlands($country);
                break;
            case 'Canada':
                $this->seedCanada($country);
                break;
            case 'Spain':
                $this->seedSpain($country);
                break;
            case 'Portugal':
                $this->seedPortugal($country);
                break;
            case 'Ireland':
                $this->seedIreland($country);
                break;
            case 'Australia':
                $this->seedAustralia($country);
                break;
            case 'New Zealand':
                $this->seedNewZealand($country);
                break;
            case 'Italy':
                $this->seedItaly($country);
                break;
            case 'Sweden':
                $this->seedSweden($country);
                break;
            case 'Finland':
                $this->seedFinland($country);
                break;
            case 'Norway':
                $this->seedNorway($country);
                break;
            case 'Austria':
                $this->seedAustria($country);
                break;
            default:
                $this->seedGeneric($country);
                break;
        }
    }

    private function seedGermany($country)
    {
        $steps = [
            ['phase' => 'week1', 'title' => 'Address Registration (Anmeldung)', 'description' => 'Register your address at the local registration office (Bürgeramt).', 'required_documents' => ['Passport', 'Rental contract', 'Landlord confirmation form'], 'estimated_time' => '14 days', 'mandatory' => true],
            ['phase' => 'week1', 'title' => 'Residence Permit Appointment', 'description' => 'Book an appointment at the immigration office (Ausländerbehörde).', 'required_documents' => ['Passport', 'Visa', 'Biometric photo', 'Address certificate'], 'estimated_time' => '2-4 months', 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Open Bank Account', 'description' => 'Required for salary and rent payments.', 'required_documents' => ['Passport', 'Anmeldung certificate'], 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Health Insurance Registration', 'description' => 'Mandatory in Germany (Public or Private).', 'required_documents' => ['Passport', 'Job contract'], 'mandatory' => true],
            ['phase' => 'long_term', 'title' => 'Tax ID (Steuer-ID)', 'description' => 'Automatically issued after address registration.', 'mandatory' => true],
            ['phase' => 'long_term', 'title' => 'Social Security Registration', 'description' => 'Handled by employer for pension and healthcare.', 'mandatory' => true],
        ];

        foreach ($steps as $idx => $step) {
            SettlementStep::create(array_merge($step, ['country_id' => $country->id, 'order' => $idx]));
        }

        GovernmentOffice::create(['country_id' => $country->id, 'name' => 'Bürgeramt', 'service_type' => 'Registration']);
        GovernmentOffice::create(['country_id' => $country->id, 'name' => 'Ausländerbehörde', 'service_type' => 'Immigration']);
    }

    private function seedFrance($country)
    {
        $steps = [
            ['phase' => 'week1', 'title' => 'Validate Visa Online', 'description' => 'Validate your long-stay visa on the OFII portal.', 'required_documents' => ['Passport', 'Visa', 'French address'], 'mandatory' => true, 'official_link' => 'https://administration-etrangers-en-france.interieur.gouv.fr/'],
            ['phase' => 'month1', 'title' => 'Open Bank Account', 'description' => 'Required for rent and salary.', 'required_documents' => ['Passport', 'Proof of address'], 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Health Insurance (Assurance Maladie)', 'description' => 'Register for the French health system.', 'required_documents' => ['Passport', 'Birth certificate', 'Bank details'], 'mandatory' => true],
            ['phase' => 'long_term', 'title' => 'Residence Card Renewal', 'description' => 'Apply for renewal before your current visa/card expires.', 'required_documents' => ['Passport', 'Income proof', 'Tax declarations'], 'mandatory' => true],
        ];

        foreach ($steps as $idx => $step) {
            SettlementStep::create(array_merge($step, ['country_id' => $country->id, 'order' => $idx]));
        }
    }

    private function seedUK($country)
    {
        $steps = [
            ['phase' => 'week1', 'title' => 'BRP Collection', 'description' => 'Collect your Biometric Residence Permit from the designated Post Office.', 'required_documents' => ['Passport', 'Decision letter'], 'mandatory' => true],
            ['phase' => 'week1', 'title' => 'GP Registration', 'description' => 'Register with a local General Practitioner for healthcare.', 'required_documents' => ['Passport', 'Proof of address'], 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'National Insurance Number (NI)', 'description' => 'Apply for your NI number for work and taxes.', 'mandatory' => true, 'official_link' => 'https://www.gov.uk/apply-national-insurance-number'],
            ['phase' => 'month1', 'title' => 'Open Bank Account', 'description' => 'High-street or digital banks (Monzo/Revolut).', 'required_documents' => ['Passport', 'BRP', 'Proof of address'], 'mandatory' => true],
            ['phase' => 'long_term', 'title' => 'Council Tax Registration', 'description' => 'Register with your local council for tax and voting.', 'mandatory' => true],
        ];

        foreach ($steps as $idx => $step) {
            SettlementStep::create(array_merge($step, ['country_id' => $country->id, 'order' => $idx]));
        }
    }

    private function seedNetherlands($country)
    {
        $steps = [
            ['phase' => 'week1', 'title' => 'BSN Number (Burgerservicenummer)', 'description' => 'Register at the municipality to get your citizen service number.', 'required_documents' => ['Passport', 'Birth certificate', 'Rental contract'], 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Health Insurance', 'description' => 'Apply for mandatory health insurance within 4 months.', 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Open Bank Account', 'description' => 'Common banks: ING, ABN AMRO, Rabobank.', 'required_documents' => ['BSN', 'Passport'], 'mandatory' => true],
            ['phase' => 'long_term', 'title' => 'DigiD Setup', 'description' => 'Digital identity for government services.', 'mandatory' => true],
        ];

        foreach ($steps as $idx => $step) {
            SettlementStep::create(array_merge($step, ['country_id' => $country->id, 'order' => $idx]));
        }
    }

    private function seedCanada($country)
    {
        $steps = [
            ['phase' => 'week1', 'title' => 'SIN Number', 'description' => 'Apply for Social Insurance Number for work and benefits.', 'required_documents' => ['Passport', 'Work/Study permit'], 'mandatory' => true],
            ['phase' => 'week1', 'title' => 'Provincial Health Card', 'description' => 'Register for health coverage (OHIP, MSP, etc.).', 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Open Bank Account', 'description' => 'Newcomer packages at RBC, TD, Scotiabank.', 'mandatory' => true],
            ['phase' => 'long_term', 'title' => 'Driver\'s License Exchange', 'description' => 'Exchange your foreign license for a provincial one.', 'mandatory' => false],
        ];

        foreach ($steps as $idx => $step) {
            SettlementStep::create(array_merge($step, ['country_id' => $country->id, 'order' => $idx]));
        }
    }

    private function seedSpain($country)
    {
        $steps = [
            ['phase' => 'week1', 'title' => 'Register Padrón (Address)', 'description' => 'Register at your local Town Hall (Ayuntamiento). Mandatory for residency and healthcare.', 'required_documents' => ['Passport', 'Rental contract'], 'estimated_time' => '3 days', 'mandatory' => true],
            ['phase' => 'week1', 'title' => 'TIE Residency Card Appointment', 'description' => 'Book your fingerprint appointment for the Foreigner Identity Card.', 'required_documents' => ['Passport', 'Visa', 'Padrón certificate'], 'estimated_time' => '30 days', 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Open Spanish Bank Account', 'description' => 'Necessary for utilities and salary.', 'required_documents' => ['Passport/TIE', 'Proof of address'], 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Healthcare Registration (CAP)', 'description' => 'Register at your local health center for public healthcare access.', 'required_documents' => ['TIE', 'Padrón'], 'mandatory' => true],
            ['phase' => 'long_term', 'title' => 'Tax Identification (NIF)', 'description' => 'Ensure your tax status is updated if staying over 183 days.', 'mandatory' => true],
        ];

        foreach ($steps as $idx => $step) {
            SettlementStep::create(array_merge($step, ['country_id' => $country->id, 'order' => $idx]));
        }
    }

    private function seedPortugal($country)
    {
        $steps = [
            ['phase' => 'week1', 'title' => 'Update Tax Address (Finanças)', 'description' => 'Register your Portuguese address and become a tax resident.', 'required_documents' => ['Passport', 'NIF'], 'mandatory' => true],
            ['phase' => 'week1', 'title' => 'Chave Móvel Digital', 'description' => 'Set up your digital identity for government portals.', 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'NISS (Social Security)', 'description' => 'Obtain your social security number for work and health.', 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'SNS Healthcare Registration', 'description' => 'Register at your local Centro de Saúde.', 'required_documents' => ['Passport', 'NISS'], 'mandatory' => true],
            ['phase' => 'long_term', 'title' => 'Driving License Exchange', 'description' => 'Verify if your license needs to be exchanged at IMT.', 'mandatory' => false],
        ];

        foreach ($steps as $idx => $step) {
            SettlementStep::create(array_merge($step, ['country_id' => $country->id, 'order' => $idx]));
        }
    }

    private function seedIreland($country)
    {
        $steps = [
            ['phase' => 'week1', 'title' => 'IRP Registration', 'description' => 'Register with Immigration Service Delivery to get your Irish Residence Permit.', 'required_documents' => ['Passport', 'Visa'], 'mandatory' => true],
            ['phase' => 'week1', 'title' => 'PPSN Application', 'description' => 'Apply for your Personal Public Service Number.', 'required_documents' => ['Passport', 'Proof of address'], 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Open Irish Bank Account', 'description' => 'Choose between high-street banks (AIB, BOI) or digital options.', 'required_documents' => ['Passport', 'IRP'], 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'GP Registration', 'description' => 'Find and register with a local doctor.', 'mandatory' => true],
            ['phase' => 'long_term', 'title' => 'Tax Credit Certificate', 'description' => 'Register with Revenue to ensure correct tax deductions.', 'mandatory' => true],
        ];

        foreach ($steps as $idx => $step) {
            SettlementStep::create(array_merge($step, ['country_id' => $country->id, 'order' => $idx]));
        }
    }

    private function seedAustralia($country)
    {
        $steps = [
            ['phase' => 'week1', 'title' => 'TFN Application', 'description' => 'Apply for a Tax File Number via the ATO website.', 'required_documents' => ['Passport', 'Visa'], 'mandatory' => true],
            ['phase' => 'week1', 'title' => 'Medicare Enrollment', 'description' => 'Register for public healthcare (if eligible).', 'required_documents' => ['Passport', 'Visa'], 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Open Australian Bank Account', 'description' => 'Major banks: CBA, Westpac, ANZ, NAB.', 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'MyGov Account Setup', 'description' => 'Link ATO and Medicare to your central government portal.', 'mandatory' => true],
            ['phase' => 'long_term', 'title' => 'Convert Driving License', 'description' => 'Apply for a local state driver\'s license.', 'mandatory' => false],
        ];

        foreach ($steps as $idx => $step) {
            SettlementStep::create(array_merge($step, ['country_id' => $country->id, 'order' => $idx]));
        }
    }

    private function seedNewZealand($country)
    {
        $steps = [
            ['phase' => 'week1', 'title' => 'IRD Number Application', 'description' => 'Essential for working and tax in New Zealand.', 'required_documents' => ['Passport', 'Visa'], 'mandatory' => true],
            ['phase' => 'week1', 'title' => 'NHI Number', 'description' => 'Obtain your National Health Index number.', 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Open NZ Bank Account', 'description' => 'ASB, BNZ, Westpac, or ANZ.', 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Register with a GP', 'description' => 'Enroll with a local family doctor.', 'mandatory' => true],
            ['phase' => 'long_term', 'title' => 'RealMe Identity Setup', 'description' => 'Verified digital identity for government services.', 'mandatory' => true],
        ];

        foreach ($steps as $idx => $step) {
            SettlementStep::create(array_merge($step, ['country_id' => $country->id, 'order' => $idx]));
        }
    }

    private function seedItaly($country)
    {
        $steps = [
            ['phase' => 'week1', 'title' => 'Codice Fiscale', 'description' => 'Obtain your unique tax code from the Agenzia delle Entrate.', 'required_documents' => ['Passport', 'Visa'], 'mandatory' => true],
            ['phase' => 'week1', 'title' => 'Permesso di Soggiorno', 'description' => 'Apply for your residency permit at a Post Office within 8 days.', 'required_documents' => ['Passport', 'Visa', 'Insurance'], 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Iscrizione Anagrafica', 'description' => 'Register your address with the local Comune.', 'required_documents' => ['Passport', 'Lease'], 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'SSN Healthcare Enrollment', 'description' => 'Register for the National Health System.', 'required_documents' => ['Codice Fiscale', 'Permesso receipt'], 'mandatory' => true],
            ['phase' => 'long_term', 'title' => 'SPID Digital ID', 'description' => 'Public System for Digital Identity.', 'mandatory' => true],
        ];

        foreach ($steps as $idx => $step) {
            SettlementStep::create(array_merge($step, ['country_id' => $country->id, 'order' => $idx]));
        }
    }

    private function seedSweden($country)
    {
        $steps = [
            ['phase' => 'week1', 'title' => 'Personnummer (Registration)', 'description' => 'Register as a resident at Skatteverket.', 'required_documents' => ['Passport', 'Visa/Permit'], 'mandatory' => true],
            ['phase' => 'week1', 'title' => 'Apply for SWEDISH ID Card', 'description' => 'Issued by Skatteverket after receiving Personnummer.', 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Open Swedish Bank Account', 'description' => 'Necessary for BankID and Swish.', 'required_documents' => ['Swedish ID'], 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Social Insurance (Försäkringskassan)', 'description' => 'Register for healthcare and benefits.', 'mandatory' => true],
            ['phase' => 'long_term', 'title' => 'SFI Language Classes', 'description' => 'Enroll in free Swedish For Immigrants courses.', 'mandatory' => false],
        ];

        foreach ($steps as $idx => $step) {
            SettlementStep::create(array_merge($step, ['country_id' => $country->id, 'order' => $idx]));
        }
    }

    private function seedFinland($country)
    {
        $steps = [
            ['phase' => 'week1', 'title' => 'Finnish Identity Code', 'description' => 'Visit DVV (Local Register Office) for your identity code.', 'required_documents' => ['Passport', 'Permit'], 'mandatory' => true],
            ['phase' => 'week1', 'title' => 'Address Registration', 'description' => 'Official notification of your residence address.', 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Open Finnish Bank Account', 'description' => 'Common banks: Nordea, OP, Danske Bank.', 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'KELA Registration', 'description' => 'Register for the Finnish social security system.', 'mandatory' => true],
            ['phase' => 'long_term', 'title' => 'HSL/Local Transport Card', 'description' => 'Setup your discounted resident travel card.', 'mandatory' => false],
        ];

        foreach ($steps as $idx => $step) {
            SettlementStep::create(array_merge($step, ['country_id' => $country->id, 'order' => $idx]));
        }
    }

    private function seedNorway($country)
    {
        $steps = [
            ['phase' => 'week1', 'title' => 'Police Registration', 'description' => 'Meet with the police to register your arrival.', 'required_documents' => ['Passport', 'Permit'], 'mandatory' => true],
            ['phase' => 'week1', 'title' => 'Norwegian ID Number (D/P)', 'description' => 'Obtain your identity number from the Tax Office.', 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Tax Deduction Card', 'description' => 'Apply via Skatteetaten to ensure correct salary tax.', 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Open Norwegian Bank Account', 'description' => 'Required for BankID and Vipps.', 'mandatory' => true],
            ['phase' => 'long_term', 'title' => 'GP Choice (Fastlegen)', 'description' => 'Choose your primary doctor on the Helsenorge portal.', 'mandatory' => true],
        ];

        foreach ($steps as $idx => $step) {
            SettlementStep::create(array_merge($step, ['country_id' => $country->id, 'order' => $idx]));
        }
    }

    private function seedAustria($country)
    {
        $steps = [
            ['phase' => 'week1', 'title' => 'Meldezettel (Registration)', 'description' => 'Register your address at the Meldeservice within 3 days.', 'required_documents' => ['Passport', 'Meldezettel form'], 'mandatory' => true],
            ['phase' => 'week1', 'title' => 'Collect Residence Permit', 'description' => 'Pick up your physical permit card from the MA35 or BH.', 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'ÖGK Health Insurance', 'description' => 'Register for the Austrian public health system.', 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Open Austrian Bank Account', 'description' => 'Erste Bank, Raiffeisen, or N26.', 'mandatory' => true],
            ['phase' => 'long_term', 'title' => 'ID Austria Setup', 'description' => 'Mobile signatures for government portal access.', 'mandatory' => true],
        ];

        foreach ($steps as $idx => $step) {
            SettlementStep::create(array_merge($step, ['country_id' => $country->id, 'order' => $idx]));
        }
    }

    private function seedGeneric($country)
    {
        $steps = [
            ['phase' => 'week1', 'title' => 'Local Registration', 'description' => 'Visit the local municipality or police station for registration.', 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Open Local Bank Account', 'description' => 'Essential for managing expenses and receiving salary.', 'mandatory' => true],
            ['phase' => 'month1', 'title' => 'Health Insurance', 'description' => 'Secure local or international insurance coverage.', 'mandatory' => true],
            ['phase' => 'long_term', 'title' => 'Tax Registration', 'description' => 'Obtain a local tax identification number.', 'mandatory' => true],
        ];

        foreach ($steps as $idx => $step) {
            SettlementStep::create(array_merge($step, ['country_id' => $country->id, 'order' => $idx]));
        }
    }
}
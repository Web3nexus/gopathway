<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\VisaType;
use App\Models\CostTemplate;
use App\Models\CostItem;
use App\Models\Pathway;
use App\Models\UserTimelineStep;
use Illuminate\Support\Facades\Schema;

class CleanupSeeder extends Seeder {
    public function run() {
        Schema::disableForeignKeyConstraints();
        
        CostItem::truncate();
        CostTemplate::truncate();
        Pathway::truncate();
        UserTimelineStep::truncate();
        VisaType::truncate();
        
        Schema::enableForeignKeyConstraints();
    }
}
import { Link } from 'react-router-dom';
import { ArrowRight } from 'lucide-react';
import { useCountries } from '@/hooks/useCountries';

export default function Countries() {
    const { data: countries, isLoading } = useCountries();

    return (
        <div className="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="mb-10 text-center">
                <h1 className="text-4xl font-bold text-[#1A1A1A] mb-3">Explore Destinations</h1>
                <p className="text-[#6B7280] max-w-xl mx-auto">
                    Browse our curated list of top relocation destinations. Click any country to explore available visa types, requirements, and timelines.
                </p>
            </div>

            {isLoading ? (
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    {[...Array(6)].map((_, i) => (
                        <div key={i} className="h-64 rounded-2xl bg-gray-200 animate-pulse" />
                    ))}
                </div>
            ) : (
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    {Array.isArray(countries) ? countries.map((country: any, idx: number) => (
                        <Link key={country.id} to={`/countries/${country.id}`}>
                            <div className="relative h-64 rounded-2xl overflow-hidden group shadow-sm hover:shadow-lg transition-all duration-300 border border-[#E5E7EB]">
                                <img
                                    src={country.image_url}
                                    alt={country.name}
                                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                />
                                <div className="absolute inset-0 bg-gradient-to-t from-black/75 via-black/20 to-transparent" />
                                {/*
**Status: Phase 33 COMPLETED**

---

# Walkthrough: Phase 34 - Global Ranking & Competitiveness Scores

I have implemented a dynamic ranking and scoring system for all 15 countries, ensuring that the best relocation destinations are automatically surfaced to the user.

## 🚀 Key Features

### 1. Automated Global Ranking
- **Competitiveness Score**: Every country now has a pre-calculated `competitiveness_score` (0-100) based on visa difficulty, costs, processing speed, PR ease, and job market.
- **Dynamic Sorting**: The Landing Page and Countries screen now automatically sort destinations by this score, placing top-ranked countries (like UK, Netherlands, Canada, and Australia) at the top.

### 2. Rank-Aware UI
- **Rank Badges**: Added visual "Rank #1", "Rank #2", etc. badges to country cards to clearly communicate their standing.
- **Score Indicators**: Integrated "Score" tags using the `TrendingUp` icon to show the data-driven reason for a country's rank.
- **Premium Aesthetics**: Enhanced the country cards on the Home page with richer gradients, blur effects, and smooth hover scales.

## 🛠️ Verification Results

### Data Accuracy
- **Seeder Expansion**: Confirmed all 15 countries have realistic scores populated (verified via `php artisan tinker`).
- **Model Sync**: Verified that saving a `CountryScore` automatically updates the `competitiveness_score` column on the related `Country` for high-performance sorting.

### Visual Consistency
- **Home Page**: Verified that the "Top Destinations" section shows exactly 6 countries in descending order of their global rank.
- **Countries Page**: Confirmed the full list is properly ranked and badges render correctly without layout shifts.

---
**Status: Phase 34 COMPLETED**
*/}
                                <div className="absolute bottom-0 left-0 right-0 p-5 text-white">
                                    <div className="flex items-center justify-between">
                                        <div>
                                            <div className="flex gap-2 mb-2">
                                                <span className="text-[10px] font-black bg-[#00C2FF] text-[#0B3C91] rounded-full px-2.5 py-1 uppercase tracking-wider border border-white/20 shadow-sm">
                                                    Rank #{idx + 1}
                                                </span>
                                                <span className="text-[10px] font-black bg-white/20 text-white rounded-full px-2.5 py-1 backdrop-blur-md border border-white/10 uppercase tracking-widest">
                                                    {country.competitiveness_score || 50} Score
                                                </span>
                                            </div>
                                            <h3 className="text-xl font-bold">{country.name}</h3>
                                            <p className="text-xs text-gray-300 mt-1 line-clamp-1">{country.description}</p>
                                        </div>
                                        <div className="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center ml-3 flex-shrink-0 group-hover:bg-[#00C2FF] transition-colors">
                                            <ArrowRight className="h-4 w-4 text-white" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    )) : null}
                </div>
            )}
        </div>
    );
}

import { useNavigate, Link } from 'react-router-dom';
import { CheckCircle2, Circle, ArrowRight, User, Compass, Map, FileText, Home } from 'lucide-react';
import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/button';

interface RelocationRoadmapProps {
    profileCompleteness: number;
    hasPathway: boolean;
    documentsCount: number;
}

export default function RelocationRoadmapWidget({ profileCompleteness, hasPathway, documentsCount }: RelocationRoadmapProps) {
    const navigate = useNavigate();

    const stages = [
        {
            id: 'profile',
            title: 'Elite Profile',
            desc: 'Tell us about your background',
            icon: User,
            isCompleted: profileCompleteness === 100,
            isActive: profileCompleteness < 100,
            link: '/profile/setup'
        },
        {
            id: 'discovery',
            title: 'Destiny Discovery',
            desc: 'Find your ideal match',
            icon: Compass,
            isCompleted: hasPathway || (profileCompleteness === 100 && documentsCount > 0), // Heuristic
            isActive: profileCompleteness === 100 && !hasPathway,
            link: '/recommendations'
        },
        {
            id: 'pathway',
            title: 'Active Roadmap',
            desc: 'Activate your journey',
            icon: Map,
            isCompleted: hasPathway,
            isActive: hasPathway,
            link: '/pathway'
        },
        {
            id: 'evidence',
            title: 'Evidence Vault',
            desc: 'Secure your application',
            icon: FileText,
            isCompleted: documentsCount > 0,
            isActive: hasPathway && documentsCount === 0,
            link: '/documents'
        },
        {
            id: 'settlement',
            title: 'Settlement Hub',
            desc: 'Prepare for landing',
            icon: Home,
            isCompleted: false, // User can manually check or just always available
            isActive: hasPathway && documentsCount > 0,
            link: '/relocation-hub'
        }
    ];

    // Determine current stage for the main highlight
    const currentStageIndex = stages.findIndex(s => s.isActive) === -1 
        ? (hasPathway ? 4 : 0) 
        : stages.findIndex(s => s.isActive);

    return (
        <div className="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-in fade-in slide-in-from-top-4 duration-700">
            <div className="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div>
                    <h3 className="text-sm font-black text-slate-900 uppercase tracking-widest">Your Relocation Journey</h3>
                    <p className="text-xs text-slate-500 mt-1">Complete these core steps to ensure a successful move.</p>
                </div>
                <div className="flex items-center gap-1.5 px-2.5 py-1 bg-white border rounded-full shadow-sm text-[10px] font-bold text-slate-600">
                    <span className="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse" />
                    Live Progress
                </div>
            </div>

            <div className="p-6">
                {/* Visual Connector Line */}
                <div className="relative flex justify-between">
                    <div className="absolute top-5 left-8 right-8 h-0.5 bg-slate-100 -z-0" />
                    <div 
                        className="absolute top-5 left-8 h-0.5 bg-blue-500 transition-all duration-1000 -z-0" 
                        style={{ width: `${Math.max(0, (currentStageIndex) * 25)}%` }}
                    />

                    {stages.map((stage, idx) => {
                        const Icon = stage.icon;
                        const isPast = idx < currentStageIndex;
                        const isCurrent = idx === currentStageIndex;
                        const isFuture = idx > currentStageIndex;

                        return (
                            <div key={stage.id} className="relative z-10 flex flex-col items-center group">
                                <button 
                                    onClick={() => navigate(stage.link)}
                                    className={cn(
                                        "w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300 border-2",
                                        stage.isCompleted || isPast
                                            ? "bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-200" 
                                            : isCurrent 
                                                ? "bg-white border-blue-500 text-blue-600 ring-4 ring-blue-50 shadow-md"
                                                : "bg-white border-slate-200 text-slate-400"
                                    )}
                                >
                                    {stage.isCompleted || isPast ? (
                                        <CheckCircle2 className="w-5 h-5" />
                                    ) : (
                                        <Icon className="w-5 h-5" />
                                    )}
                                </button>
                                
                                <div className="mt-3 text-center">
                                    <p className={cn(
                                        "text-[10px] font-black uppercase tracking-tight",
                                        isCurrent ? "text-blue-600" : "text-slate-500"
                                    )}>
                                        {stage.title}
                                    </p>
                                    <div className={cn(
                                        "hidden sm:block text-[9px] text-slate-400 mt-0.5 max-w-[80px] leading-tight opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap overflow-hidden text-ellipsis",
                                        isCurrent && "opacity-100"
                                    )}>
                                        {stage.desc}
                                    </div>
                                </div>
                            </div>
                        );
                    })}
                </div>

                <div className="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 p-4 bg-blue-50/50 rounded-2xl border border-blue-100/50">
                    <div className="flex items-center gap-4">
                        <div className="h-10 w-10 bg-white rounded-xl shadow-sm flex items-center justify-center shrink-0">
                            {(() => {
                                const CurrentIcon = stages[currentStageIndex].icon;
                                return stages[currentStageIndex].isCompleted ? (
                                    <CheckCircle2 className="h-6 w-6 text-green-500" />
                                ) : (
                                    <CurrentIcon className="h-6 w-6 text-blue-600" />
                                );
                            })()}
                        </div>
                        <div>
                            <p className="text-[10px] font-black text-blue-600 uppercase tracking-widest">Recommended Action</p>
                            <h4 className="text-sm font-bold text-slate-900">
                                {stages[currentStageIndex].isCompleted 
                                    ? "Step Complete! Proceed to the next stage." 
                                    : stages[currentStageIndex].title}
                            </h4>
                            <p className="text-xs text-slate-500 truncate max-w-[300px]">{stages[currentStageIndex].desc}</p>
                        </div>
                    </div>
                    
                    <Link to={stages[currentStageIndex].link} className="w-full sm:w-auto">
                        <Button className="w-full sm:w-auto bg-[#0B3C91] hover:bg-[#0B3C91]/90 text-white rounded-xl font-bold h-10 px-6 shadow-sm shadow-blue-100 gap-2">
                            Take Action <ArrowRight className="h-4 w-4" />
                        </Button>
                    </Link>
                </div>
            </div>
        </div>
    );
}

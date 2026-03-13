import { motion } from 'framer-motion';

const dots = [
    { x: 200, y: 150, label: "Canada" },
    { x: 350, y: 130, label: "UK" },
    { x: 420, y: 180, label: "Germany" },
    { x: 650, y: 350, label: "Australia" },
    { x: 180, y: 280, label: "USA" },
    { x: 500, y: 220, label: "UAE" },
    { x: 550, y: 120, label: "Nordics" },
];

const connections = [
    { from: 1, to: 0 },
    { from: 1, to: 3 },
    { from: 3, to: 5 },
    { from: 5, to: 6 },
    { from: 6, to: 1 },
    { from: 4, to: 1 },
];

export const WorldConnectionMap = () => {
    return (
        <div className="relative w-full h-full overflow-hidden bg-white">
            <div className="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(37,99,235,0.08),transparent_70%)]" />

            {/* Grid Pattern */}
            <div className="absolute inset-0 opacity-10" style={{
                backgroundImage: `radial-gradient(#334155 1px, transparent 1px)`,
                backgroundSize: '40px 40px'
            }} />

            <svg viewBox="0 0 800 450" className="relative w-full h-full">
                {/* Animated Connections */}
                <defs>
                    <linearGradient id="lineGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stopColor="transparent" />
                        <stop offset="50%" stopColor="#3b82f6" />
                        <stop offset="100%" stopColor="transparent" />
                    </linearGradient>
                </defs>

                {connections.map((conn, i) => {
                    const from = dots[conn.from];
                    const to = dots[conn.to];
                    return (
                        <g key={i}>
                            <motion.path
                                d={`M ${from.x} ${from.y} Q ${(from.x + to.x) / 2} ${(from.y + to.y) / 2 - 50} ${to.x} ${to.y}`}
                                stroke="rgba(59, 130, 246, 0.2)"
                                strokeWidth="1"
                                fill="none"
                            />
                            <motion.path
                                d={`M ${from.x} ${from.y} Q ${(from.x + to.x) / 2} ${(from.y + to.y) / 2 - 50} ${to.x} ${to.y}`}
                                stroke="url(#lineGrad)"
                                strokeWidth="2"
                                fill="none"
                                initial={{ pathLength: 0, opacity: 0 }}
                                animate={{
                                    pathLength: [0, 1],
                                    opacity: [0, 1, 0],
                                    pathOffset: [0, 1]
                                }}
                                transition={{
                                    duration: 4,
                                    repeat: Infinity,
                                    delay: i * 0.8,
                                    ease: "easeInOut"
                                }}
                            />
                        </g>
                    );
                })}

                {/* Pulsing Dots */}
                {dots.map((dot, i) => (
                    <g key={i}>
                        <motion.circle
                            cx={dot.x}
                            cy={dot.y}
                            r="8"
                            fill="rgba(37, 99, 235, 0.1)"
                            animate={{ scale: [1, 2], opacity: [0.5, 0] }}
                            transition={{ duration: 2, repeat: Infinity }}
                        />
                        <circle
                            cx={dot.x}
                            cy={dot.y}
                            r="3"
                            fill="#2563eb"
                        />
                        <motion.text
                            x={dot.x}
                            y={dot.y + 20}
                            textAnchor="middle"
                            fill="rgba(15, 23, 42, 0.4)"
                            fontSize="10"
                            className="font-bold pointer-events-none uppercase tracking-tighter"
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            transition={{ delay: 1 }}
                        >
                            {dot.label}
                        </motion.text>
                    </g>
                ))}
            </svg>

            {/* Glossy Overlay */}
            <div className="absolute inset-0 pointer-events-none bg-gradient-to-tr from-transparent via-blue-50/10 to-transparent" />

            {/* Dynamic Data Overlay removed for background use or kept subtle */}
            <div className="absolute bottom-12 left-12 opacity-20 hidden lg:block">
                <div className="flex items-center gap-2">
                    <div className="w-2 h-2 rounded-full bg-blue-500 animate-pulse" />
                    <span className="text-[10px] font-black text-slate-900 uppercase tracking-widest">Global Pathways Active</span>
                </div>
            </div>
        </div>
    );
};

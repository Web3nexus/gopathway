import { motion } from 'framer-motion';
import { AlertCircle, RefreshCcw, Home, LifeBuoy } from 'lucide-react';
import { Link } from 'react-router-dom';
import { Button } from '@/components/ui/button';

interface ErrorPageProps {
  error?: Error;
  resetErrorBoundary?: () => void;
}

export default function ErrorPage({ error, resetErrorBoundary }: ErrorPageProps) {
  return (
    <div className="min-h-screen bg-[#F5F7FA] flex flex-col items-center justify-center p-4 overflow-hidden relative">
      {/* Background Animated Elements */}
      <motion.div
        animate={{ rotate: 360 }}
        transition={{ duration: 40, repeat: Infinity, ease: "linear" }}
        className="absolute -top-20 -right-20 w-80 h-80 bg-red-100/50 rounded-full blur-3xl"
      />
      <motion.div
        animate={{ rotate: -360 }}
        transition={{ duration: 50, repeat: Infinity, ease: "linear" }}
        className="absolute -bottom-40 -left-20 w-[500px] h-[500px] bg-blue-100/30 rounded-full blur-3xl"
      />

      <div className="max-w-xl w-full text-center relative z-10">
        {/* Animated Error Illustration */}
        <div className="relative mb-12 flex justify-center">
          <motion.div
            initial={{ scale: 0.8, opacity: 0 }}
            animate={{ scale: 1, opacity: 1 }}
            transition={{ type: "spring", stiffness: 200, damping: 15 }}
            className="relative"
          >
            <div className="bg-white p-10 rounded-[40px] shadow-2xl shadow-red-900/10 border border-red-50 flex flex-col items-center">
                <motion.div
                    animate={{ 
                        rotate: [0, -10, 10, -5, 5, 0],
                        scale: [1, 1.05, 1]
                    }}
                    transition={{ duration: 5, repeat: Infinity, repeatDelay: 1 }}
                >
                    <AlertCircle className="w-20 h-20 text-red-500" />
                </motion.div>
                <div className="mt-6 flex gap-2">
                    <motion.div 
                        animate={{ opacity: [0, 1, 0] }}
                        transition={{ duration: 2, repeat: Infinity, times: [0, 0.5, 1] }}
                        className="w-2 h-2 rounded-full bg-red-500" 
                    />
                    <motion.div 
                        animate={{ opacity: [0, 1, 0] }}
                        transition={{ duration: 2, repeat: Infinity, delay: 0.4, times: [0, 0.5, 1] }}
                        className="w-2 h-2 rounded-full bg-red-500" 
                    />
                    <motion.div 
                        animate={{ opacity: [0, 1, 0] }}
                        transition={{ duration: 2, repeat: Infinity, delay: 0.8, times: [0, 0.5, 1] }}
                        className="w-2 h-2 rounded-full bg-red-500" 
                    />
                </div>
            </div>

            {/* Floaties */}
            <motion.div
                animate={{ y: [-10, 10, -10] }}
                transition={{ duration: 3, repeat: Infinity, ease: "easeInOut" }}
                className="absolute -top-6 -left-6 bg-white p-3 rounded-2xl shadow-lg border border-red-50"
            >
                <RefreshCcw className="w-6 h-6 text-[#0B3C91] animate-spin-slow" />
            </motion.div>
          </motion.div>
        </div>

        <motion.div
          initial={{ y: 20, opacity: 0 }}
          animate={{ y: 0, opacity: 1 }}
          transition={{ delay: 0.4, duration: 0.8 }}
        >
          <h1 className="text-4xl font-black text-[#1A1A1A] mb-3">System Turbulence</h1>
          <p className="text-lg font-bold text-red-500 mb-6 uppercase tracking-widest text-sm">Error 500: Flight Path Blocked</p>
          <p className="text-[#6B7280] mb-10 leading-relaxed mx-auto max-w-md">
            The GoPathway control tower encountered an unexpected glitch. Our engineers are currently on the runway fixing the issue.
          </p>

          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Button 
                onClick={() => resetErrorBoundary ? resetErrorBoundary() : window.location.reload()}
                className="bg-[#0B3C91] hover:bg-[#0B3C91]/90 rounded-2xl px-10 h-14 text-base font-bold shadow-xl shadow-blue-900/20"
            >
                <RefreshCcw className="mr-2 h-5 w-5" />
                Retry Boarding
            </Button>
            <Button 
                variant="outline"
                asChild
                className="rounded-2xl px-10 h-14 text-base font-bold border-[#E5E7EB] bg-white hover:border-[#0B3C91] hover:text-[#0B3C91] shadow-sm"
            >
                <Link to="/">
                    <Home className="mr-2 h-5 w-5" />
                    Back to Terminal
                </Link>
            </Button>
          </div>

          <div className="mt-12 flex items-center justify-center gap-6">
            <Link to="/support" className="flex items-center gap-2 text-sm font-semibold text-[#0B3C91] hover:underline">
                <LifeBuoy className="w-4 h-4" />
                Contact Tower (Support)
            </Link>
          </div>
        </motion.div>
      </div>

      {/* Technical Detail for Admins (Optional debugging) */}
      {error && (
        <motion.div 
            initial={{ opacity: 0 }}
            animate={{ opacity: 0.5 }}
            className="mt-12 max-w-2xl mx-auto p-4 bg-gray-100 rounded-xl border border-gray-200"
        >
            <p className="text-[10px] font-mono text-gray-500 break-all uppercase tracking-tighter">
                Debug Info: {error.message}
            </p>
        </motion.div>
      )}

      <style>{`
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin-slow {
            animation: spin-slow 8s linear infinite;
        }
      `}</style>
    </div>
  );
}

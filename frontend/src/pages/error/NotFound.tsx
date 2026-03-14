import { motion } from 'framer-motion';
import { Plane, Map, Home, ArrowLeft } from 'lucide-react';
import { Link } from 'react-router-dom';
import { Button } from '@/components/ui/button';

export default function NotFound() {
  return (
    <div className="min-h-screen bg-[#F5F7FA] flex flex-col items-center justify-center p-4 overflow-hidden relative">
      {/* Animated Clouds */}
      <motion.div
        animate={{ x: [0, 100, 0] }}
        transition={{ duration: 20, repeat: Infinity, ease: "linear" }}
        className="absolute top-20 left-10 opacity-20"
      >
        <div className="w-40 h-10 bg-white rounded-full blur-xl" />
      </motion.div>
      <motion.div
        animate={{ x: [0, -150, 0] }}
        transition={{ duration: 25, repeat: Infinity, ease: "linear" }}
        className="absolute bottom-40 right-10 opacity-20"
      >
        <div className="w-60 h-12 bg-white rounded-full blur-xl" />
      </motion.div>

      <div className="max-w-md w-full text-center relative z-10">
        {/* Animated Plane & Icon Container */}
        <div className="relative mb-8 h-48 flex items-center justify-center">
          <motion.div
            initial={{ x: -200, y: 50, opacity: 0 }}
            animate={{ x: 0, y: 0, opacity: 1 }}
            transition={{ duration: 1.5, ease: "easeOut" }}
            className="relative"
          >
            <div className="bg-white p-8 rounded-full shadow-xl shadow-blue-900/10 border border-blue-50">
              <Map className="w-16 h-16 text-[#0B3C91]" />
            </div>
            
            {/* The Plane */}
            <motion.div
              animate={{ 
                y: [0, -10, 0],
                rotate: [0, 2, 0, -2, 0]
              }}
              transition={{ duration: 4, repeat: Infinity, ease: "easeInOut" }}
              className="absolute -top-4 -right-10 bg-[#0B3C91] p-3 rounded-2xl shadow-lg text-white"
            >
              <Plane className="w-8 h-8" />
            </motion.div>
          </motion.div>
        </div>

        <motion.div
          initial={{ y: 20, opacity: 0 }}
          animate={{ y: 0, opacity: 1 }}
          transition={{ delay: 0.5, duration: 0.8 }}
        >
          <h1 className="text-8xl font-black text-[#0B3C91] mb-2 tracking-tighter">404</h1>
          <h2 className="text-2xl font-bold text-[#1A1A1A] mb-4">Lost in Transit?</h2>
          <p className="text-[#6B7280] mb-8 leading-relaxed">
            Oops! It looks like your destination isn't on our map yet, or the flight path was redirected. Let's get you back on track.
          </p>

          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Button 
                asChild
                className="bg-[#0B3C91] hover:bg-[#0B3C91]/90 rounded-2xl px-8 h-12 text-base font-bold shadow-lg shadow-blue-900/20"
            >
                <Link to="/">
                    <Home className="mr-2 h-5 w-5" />
                    Back to Home
                </Link>
            </Button>
            <Button 
                variant="outline"
                onClick={() => window.history.back()}
                className="rounded-2xl px-8 h-12 text-base font-bold border-[#E5E7EB] hover:bg-white hover:border-[#0B3C91] hover:text-[#0B3C91]"
            >
                <ArrowLeft className="mr-2 h-5 w-5" />
                Previous Page
            </Button>
          </div>
        </motion.div>
      </div>

      {/* Footer text */}
      <motion.p 
        initial={{ opacity: 0 }}
        animate={{ opacity: 1 }}
        transition={{ delay: 1.2 }}
        className="absolute bottom-8 text-xs text-[#9CA3AF] font-medium tracking-widest uppercase"
      >
        GoPathway Navigation System
      </motion.p>
    </div>
  );
}

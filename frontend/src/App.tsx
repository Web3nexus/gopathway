import { Routes, Route } from 'react-router-dom';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { DashboardLayout } from '@/layouts/DashboardLayout';
import { AdminLayout } from '@/layouts/AdminLayout';
import { PublicLayout } from '@/layouts/PublicLayout';
import { ProtectedRoute } from '@/components/auth/ProtectedRoute';
import { AdminRoute } from '@/components/auth/AdminRoute';
import { Toaster } from '@/components/ui/toaster';
import Login from '@/pages/auth/Login';
import Register from '@/pages/auth/Register';
import Home from '@/pages/public/Home';
import BlogList from '@/pages/public/BlogList';
import BlogPostView from '@/pages/public/BlogPostView';
import Countries from '@/pages/public/Countries';
import CountryDetail from '@/pages/public/CountryDetail';
import PublicPricing from '@/pages/public/Pricing';
import Dashboard from '@/pages/dashboard/Dashboard';
import ProfileSetup from '@/pages/dashboard/ProfileSetup';
import Pathway from '@/pages/dashboard/Pathway';
import CostPlanner from '@/pages/dashboard/CostPlanner';
import Recommendations from '@/pages/dashboard/Recommendations';
import Pricing from '@/pages/dashboard/Pricing';
import Billing from '@/pages/dashboard/Billing';
import Settings from '@/pages/dashboard/Settings';
import Support from '@/pages/dashboard/Support';
import Inbox from '@/pages/dashboard/Inbox';
import DocumentVault from '@/pages/dashboard/DocumentVault';
import SopBuilder from '@/pages/dashboard/SopBuilder';
import CountryComparison from '@/pages/dashboard/CountryComparison';
import StrategyComparison from '@/pages/dashboard/StrategyComparison';
import RelocationHub from '@/pages/dashboard/RelocationHub';
import JobSearchKit from '@/pages/dashboard/JobSearchKit';
import CvBuilder from '@/pages/dashboard/CvBuilder';
import ResidencyTracker from '@/pages/dashboard/ResidencyTracker';
import AdminDashboard from '@/pages/admin/AdminDashboard';
import CountryManagement from '@/pages/admin/CountryManagement';
import VisaManagement from '@/pages/admin/VisaManagement';
import CostManagement from '@/pages/admin/CostManagement';
import TimelineManagement from '@/pages/admin/TimelineManagement';
import FeatureManagement from '@/pages/admin/FeatureManagement';
import DocumentManagement from '@/pages/admin/DocumentManagement';
import ProfessionalVerification from '@/pages/admin/ProfessionalVerification';
import ProfessionalOnboarding from '@/pages/professional/Onboarding';
import ProfessionalDashboard from '@/pages/professional/Dashboard';
import ProfessionalEarnings from '@/pages/professional/EarningsDashboard';
import ExpertWithdrawals from '@/pages/admin/ExpertWithdrawals';
import UserManagement from '@/pages/admin/UserManagement';
import PlanManagement from '@/pages/admin/PlanManagement';
import GeneralSettings from '@/pages/admin/GeneralSettings';
import Referrals from '@/pages/dashboard/Referrals';
import ReferralManagement from '@/pages/admin/ReferralManagement';
import BookingManagement from '@/pages/admin/BookingManagement';
import BlogManagement from '@/pages/admin/BlogManagement';
import RelocationManagement from '@/pages/admin/RelocationManagement';
import SettlementManagement from '@/pages/admin/SettlementManagement';
import SchoolManagement from '@/pages/admin/SchoolManagement';
import CareerManagement from '@/pages/admin/CareerManagement';
import SeoSettings from '@/pages/admin/SeoSettings';
import SupportManagement from '@/pages/admin/SupportManagement';
import SchoolExplorer from '@/pages/dashboard/SchoolExplorer';
import ExpertMarketplace from '@/pages/marketplace/ExpertMarketplace';
import ErrorBoundary from './components/ErrorBoundary';

import { CurrencyProvider } from '@/contexts/CurrencyContext';
import { GlobalSeo } from '@/components/GlobalSeo';
import { GoogleAnalytics } from '@/components/GoogleAnalytics';
import { CookieConsent } from '@/components/CookieConsent';

const queryClient = new QueryClient();

function App() {
  return (
    <ErrorBoundary>
      <QueryClientProvider client={queryClient}>
        <GlobalSeo />
        <GoogleAnalytics />
        <CookieConsent />
        <CurrencyProvider>
          <Routes>
              {/* Auth Pages */}
              <Route path="/login" element={<Login />} />
              <Route path="/register" element={<Register />} />

              {/* Public Marketing Pages */}
              <Route element={<PublicLayout />}>
                <Route path="/" element={<Home />} />
                <Route path="/countries" element={<Countries />} />
                <Route path="/countries/:id" element={<CountryDetail />} />
                <Route path="/blog" element={<BlogList />} />
                <Route path="/blog/:slug" element={<BlogPostView />} />
                <Route path="/pricing" element={<PublicPricing />} />
              </Route>

              {/* Admin Routes */}
              <Route element={<AdminRoute />}>
                <Route element={<AdminLayout />}>
                  <Route path="/securegate" element={<AdminDashboard />} />
                  <Route path="/admin/countries" element={<CountryManagement />} />
                  <Route path="/admin/visas" element={<VisaManagement />} />
                  <Route path="/admin/costs" element={<CostManagement />} />
                  <Route path="/admin/roadmaps" element={<TimelineManagement />} />
                  <Route path="/admin/documents" element={<DocumentManagement />} />
                  <Route path="/admin/features" element={<FeatureManagement />} />
                  <Route path="/admin/verifications" element={<ProfessionalVerification />} />
                  <Route path="/admin/users" element={<UserManagement />} />
                  <Route path="/admin/expert-withdrawals" element={<ExpertWithdrawals />} />
                  <Route path="/admin/plans" element={<PlanManagement />} />
                  <Route path="/admin/referrals" element={<ReferralManagement />} />
                  <Route path="/admin/bookings" element={<BookingManagement />} />
                  <Route path="/admin/blog" element={<BlogManagement />} />
                  <Route path="/admin/relocation" element={<RelocationManagement />} />
                  <Route path="/admin/settlement" element={<SettlementManagement />} />
                  <Route path="/admin/schools" element={<SchoolManagement />} />
                  <Route path="/admin/career" element={<CareerManagement />} />
                  <Route path="/admin/support" element={<SupportManagement />} />
                  <Route path="/admin/seo-settings" element={<SeoSettings />} />
                  <Route path="/admin/settings" element={<GeneralSettings />} />
                </Route>
              </Route>

              {/* Protected User Routes */}
              <Route element={<ProtectedRoute />}>
                <Route element={<DashboardLayout />}>
                  <Route path="/dashboard" element={<Dashboard />} />
                  <Route path="/dashboard/referrals" element={<Referrals />} />
                  <Route path="/profile/setup" element={<ProfileSetup />} />
                  <Route path="/pathway" element={<Pathway />} />
                  <Route path="/recommendations" element={<Recommendations />} />
                  <Route path="/cost" element={<CostPlanner />} />
                  <Route path="/documents" element={<DocumentVault />} />
                  <Route path="/pricing" element={<Pricing />} />
                  <Route path="/billing" element={<Billing />} />
                  <Route path="/experts" element={<ExpertMarketplace />} />
                  <Route path="/dashboard/settings" element={<Settings />} />
                  <Route path="/support" element={<Support />} />
                  <Route path="/dashboard/messages" element={<Inbox />} />
                  <Route path="/sop-builder" element={<SopBuilder />} />
                  <Route path="/relocation-hub" element={<RelocationHub />} />
                  <Route path="/school-explorer" element={<SchoolExplorer />} />
                  <Route path="/compare" element={<CountryComparison />} />
                  <Route path="/strategy-comparison" element={<StrategyComparison />} />
                  <Route path="/job-search" element={<JobSearchKit />} />
                  <Route path="/cv-builder" element={<CvBuilder />} />
                  <Route path="/residency" element={<ResidencyTracker />} />

                  {/* Professional Specific Routes */}
                  <Route path="/professional/onboarding" element={<ProfessionalOnboarding />} />
                  <Route path="/professional/dashboard" element={<ProfessionalDashboard />} />
                  <Route path="/professional/earnings" element={<ProfessionalEarnings />} />
                </Route>
              </Route>
          </Routes>
        </CurrencyProvider>
        <Toaster />
      </QueryClientProvider>
    </ErrorBoundary>
  );
}

export default App;

import { createContext, useContext, useEffect, useState } from 'react';
import type { ReactNode } from 'react';
import api from '@/lib/api';
import { useAuth } from '@/hooks/useAuth';

interface CurrencyContextType {
    currency: string;
    rates: Record<string, number>;
    supported: Record<string, { label: string; symbol: string }>;
    isLoading: boolean;
    setCurrency: (currencyCode: string) => Promise<void>;
    convertAmount: (amountInUsd: number) => number;
    formatCurrency: (amountInUsd: number, hideDecimals?: boolean) => string;
}

const CurrencyContext = createContext<CurrencyContextType>({
    currency: 'USD',
    rates: {},
    supported: {},
    isLoading: true,
    setCurrency: async () => {},
    convertAmount: (amt) => amt,
    formatCurrency: (amt) => `$${amt}`,
});

export function CurrencyProvider({ children }: { children: ReactNode }) {
    const { user } = useAuth();
    
    // Default fallback locally to USD
    const [currency, setLocalCurrency] = useState<string>(user?.currency || 'USD');
    const [rates, setRates] = useState<Record<string, number>>({});
    const [supported, setSupported] = useState<Record<string, { label: string; symbol: string }>>({});
    const [isLoading, setIsLoading] = useState(true);

    // Sync currency state if user object updates
    useEffect(() => {
        if (user?.currency && user.currency !== currency) {
            setLocalCurrency(user.currency);
        }
    }, [user?.currency]);

    useEffect(() => {
        // Fetch exchange rates on mount
        api.get('/api/v1/currency/rates')
            .then(res => {
                setRates(res.data.rates);
                setSupported(res.data.supported);
                setIsLoading(false);
            })
            .catch(() => {
                setIsLoading(false);
            });
    }, []);

    const setCurrency = async (newCurrency: string) => {
        if (newCurrency === currency) return;
        
        // Optimistic update
        setLocalCurrency(newCurrency);
        
        try {
            await api.put('/api/v1/currency/preference', { currency: newCurrency });
            // Optionally, we could trigger a refetch of the user profile here
            // if we use a global react-query user hook. For now, local state covers it.
        } catch (error) {
            // Revert on failure
            setLocalCurrency(currency);
            console.error('Failed to update currency preference', error);
            throw error;
        }
    };

    /**
     * Converts a base USD amount to the user's currently selected currency.
     */
    const convertAmount = (amountInUsd: number): number => {
        const rate = rates[currency] || 1.0;
        return amountInUsd * rate;
    };

    /**
     * Converts and visually formats the amount with correct localized symbols.
     */
    const formatCurrency = (amountInUsd: number, hideDecimals: boolean = false): string => {
        const converted = convertAmount(amountInUsd);
        const symbol = supported[currency]?.symbol || '$';
        
        // Use Intl.NumberFormat for robust comma separations
        const formatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency, // e.g. 'NGN'
            minimumFractionDigits: hideDecimals ? 0 : 2,
            maximumFractionDigits: hideDecimals ? 0 : 2,
        });

        // Some currencies (like NGN) might not be fully supported by all browsers' native Intl,
        // so we manually prepend the custom symbol if Intl falls back to the code.
        const formattedStr = formatter.format(converted);
        
        // Quick fallback replace if native formatter just returned "NGN 5,000" instead of "₦5,000"
        if (formattedStr.includes(currency)) {
             return formattedStr.replace(currency, symbol + ' ').trim();
        }
        
        // For standard currencies, the Intl formatter is usually perfect
        return formattedStr;
    };

    return (
        <CurrencyContext.Provider value={{
            currency,
            rates,
            supported,
            isLoading,
            setCurrency,
            convertAmount,
            formatCurrency
        }}>
            {children}
        </CurrencyContext.Provider>
    );
}

export function useCurrency() {
    return useContext(CurrencyContext);
}

import React, { useState } from 'react';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Eye, EyeOff, Copy, Check } from 'lucide-react';
import { VerifyPasswordModal } from './VerifyPasswordModal';
import { adminService } from '@/services/api/adminService';
import { useToast } from '@/hooks/use-toast';

interface SensitiveInputProps {
    settingKey: string;
    value: string; // The masked value (e.g. ********)
    onChange: (newValue: string) => void;
    placeholder?: string;
    className?: string;
}

export function SensitiveInput({ settingKey, value, onChange, placeholder, className }: SensitiveInputProps) {
    const [isRevealed, setIsRevealed] = useState(false);
    const [rawValue, setRawValue] = useState('');
    const [isVerifying, setIsVerifying] = useState(false);
    const [copied, setCopied] = useState(false);
    const { toast } = useToast();

    const handleRevealClick = () => {
        if (isRevealed) {
            setIsRevealed(false);
            setRawValue('');
            return;
        }
        setIsVerifying(true);
    };

    const handleVerified = async (password: string) => {
        const response = await adminService.revealSetting(settingKey, password);
        setRawValue(response.value);
        setIsRevealed(true);
    };

    const copyToClipboard = () => {
        navigator.clipboard.writeText(rawValue || value);
        setCopied(true);
        setTimeout(() => setCopied(false), 2000);
        toast({ title: 'Copied to clipboard' });
    };

    // If it's revealed, we show the rawValue. If it's masked AND rawValue is empty, we show the value sent from prop (which should be ********)
    const displayValue = isRevealed ? rawValue : value;

    return (
        <div className="relative flex items-center gap-2 w-full">
            <div className="relative flex-grow">
                <Input
                    type={isRevealed ? 'text' : 'password'}
                    value={displayValue}
                    onChange={(e) => {
                        if (isRevealed) {
                            setRawValue(e.target.value);
                        }
                        onChange(e.target.value);
                    }}
                    placeholder={placeholder}
                    className={`pr-20 ${className}`}
                />
                <div className="absolute inset-y-0 right-0 flex items-center pr-2 gap-1">
                    <Button
                        type="button"
                        variant="ghost"
                        size="sm"
                        className="h-8 w-8 p-0"
                        onClick={handleRevealClick}
                    >
                        {isRevealed ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                    </Button>
                    {isRevealed && (
                        <Button
                            type="button"
                            variant="ghost"
                            size="sm"
                            className="h-8 w-8 p-0"
                            onClick={copyToClipboard}
                        >
                            {copied ? <Check className="h-4 w-4 text-green-500" /> : <Copy className="h-4 w-4" />}
                        </Button>
                    )}
                </div>
            </div>

            <VerifyPasswordModal
                open={isVerifying}
                onOpenChange={setIsVerifying}
                onVerified={handleVerified}
            />
        </div>
    );
}

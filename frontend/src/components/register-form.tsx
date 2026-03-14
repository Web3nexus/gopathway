import React from 'react'
import { cn } from "@/lib/utils"
import { useQueryClient } from "@tanstack/react-query"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { useForm } from "react-hook-form"
import { zodResolver } from "@hookform/resolvers/zod"
import * as z from "zod"
import { useState } from "react"
import { authService } from "@/lib/auth"
import { useToast } from "@/hooks/use-toast"
import { useNavigate, Link, useSearchParams } from "react-router-dom"
import { EyeIcon, EyeOffIcon } from "lucide-react"
import { Turnstile } from "@marsidev/react-turnstile"
import { publicService } from "@/services/api/publicService"
import { useQuery } from "@tanstack/react-query"
import api from "@/lib/api"
import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormMessage,
} from "@/components/ui/form"

const formSchema = z.object({
    name: z.string().min(2, 'Name must be at least 2 characters'),
    email: z.string().email('Invalid email address'),
    password: z.string().min(8, 'Password must be at least 8 characters'),
    ref: z.string().optional(),
    cf_turnstile_response: z.string().optional(),
});

export function RegisterForm({
    className,
    ...props
}: React.ComponentPropsWithoutRef<"div">) {
    const navigate = useNavigate();
    const [searchParams] = useSearchParams();
    const queryClient = useQueryClient();
    const { toast } = useToast();
    const [isLoading, setIsLoading] = useState(false);
    const [showPassword, setShowPassword] = useState(false);

    React.useEffect(() => {
        const refCode = searchParams.get('ref');
        if (refCode) {
            // Silently ping the tracking endpoint
            api.post(`/api/v1/referral/track/${refCode}`).catch(() => {
                // Ignore errors to not interrupt the user's flow
            });
        }
    }, [searchParams]);

    const form = useForm<z.infer<typeof formSchema>>({
        resolver: zodResolver(formSchema),
        defaultValues: {
            name: '',
            email: '',
            password: '',
            ref: searchParams.get('ref') || '',
            cf_turnstile_response: '',
        },
    });

    const { data: settingsData } = useQuery({
        queryKey: ['public-settings'],
        queryFn: publicService.getSettings,
        staleTime: 1000 * 60 * 60,
    });

    const turnstileSiteKey = settingsData?.data?.turnstile_site_key;

    async function onSubmit(values: z.infer<typeof formSchema>) {
        setIsLoading(true);
        try {
            const response = await authService.register(values);
            const user = response.data.user;

            // Update the user cache immediately with the new structure
            queryClient.setQueryData(['user'], {
                user: user,
                isImpersonating: false
            });

            toast({ title: 'Account created', description: 'Welcome to GoPathway!' });

            // Check for admin role
            const isAdmin = user?.roles?.some((role: any) => role.name === 'admin');
            if (isAdmin) {
                navigate('/securegate');
            } else {
                navigate('/dashboard');
            }
        } catch (error: any) {
            toast({
                variant: 'destructive',
                title: 'Error',
                description: error.response?.data?.message || 'Failed to create account.',
            });
        } finally {
            setIsLoading(false);
        }
    }

    const handleGoogleLogin = () => {
        toast({ title: 'Info', description: 'Google signup coming soon' });
    };

    const handleAppleLogin = () => {
        toast({ title: 'Info', description: 'Apple signup coming soon' });
    };

    return (
        <div className={cn("flex flex-col gap-6 bg-card text-card-foreground p-6 md:p-8 rounded-xl shadow-sm border", className)} {...props}>
            <div className="flex flex-col items-center gap-2 text-center">
                <h1 className="text-2xl font-bold">Create an account</h1>
                <p className="text-balance text-sm text-muted-foreground">
                    Enter your details below to create an account
                </p>
            </div>
            <Form {...form}>
                <form onSubmit={form.handleSubmit(onSubmit)} className="grid gap-6">
                    <FormField
                        control={form.control}
                        name="name"
                        render={({ field }) => (
                            <FormItem className="grid gap-2">
                                <Label htmlFor="name">Full Name</Label>
                                <FormControl>
                                    <Input id="name" placeholder="John Doe" {...field} />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        )}
                    />
                    <FormField
                        control={form.control}
                        name="email"
                        render={({ field }) => (
                            <FormItem className="grid gap-2">
                                <Label htmlFor="email">Email</Label>
                                <FormControl>
                                    <Input id="email" type="email" placeholder="m@example.com" {...field} />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        )}
                    />
                    <FormField
                        control={form.control}
                        name="password"
                        render={({ field }) => (
                            <FormItem className="grid gap-2">
                                <Label htmlFor="password">Password</Label>
                                <FormControl>
                                    <div className="relative">
                                        <Input
                                            id="password"
                                            type={showPassword ? "text" : "password"}
                                            {...field}
                                        />
                                        <button
                                            type="button"
                                            onClick={() => setShowPassword(!showPassword)}
                                            className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground"
                                        >
                                            {showPassword ? (
                                                <EyeOffIcon className="h-4 w-4" />
                                            ) : (
                                                <EyeIcon className="h-4 w-4" />
                                            )}
                                        </button>
                                    </div>
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        )}
                    />

                    {turnstileSiteKey && (
                        <div className="flex justify-center flex-col gap-2">
                            <Turnstile 
                                siteKey={turnstileSiteKey} 
                                onSuccess={(token: string) => form.setValue('cf_turnstile_response', token)}
                            />
                            <FormMessage>{form.formState.errors.cf_turnstile_response?.message}</FormMessage>
                        </div>
                    )}

                    <Button type="submit" className="w-full" disabled={isLoading}>
                        {isLoading ? 'Creating account...' : 'Sign Up'}
                    </Button>

                    <div className="relative text-center text-sm after:absolute after:inset-0 after:top-1/2 after:z-0 after:flex after:items-center after:border-t after:border-border">
                        <span className="relative z-10 bg-card px-2 text-muted-foreground">
                            Or continue with
                        </span>
                    </div>

                    <div className="grid grid-cols-2 gap-4">
                        <Button variant="outline" type="button" onClick={handleGoogleLogin} className="w-full">
                            <svg className="mr-2 h-4 w-4" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="google" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 488 512">
                                <path fill="currentColor" d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"></path>
                            </svg>
                            Google
                        </Button>
                        <Button variant="outline" type="button" onClick={handleAppleLogin} className="w-full">
                            <svg className="mr-2 h-4 w-4" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="apple" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                <path fill="currentColor" d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z"></path>
                            </svg>
                            Apple
                        </Button>
                    </div>
                </form>
            </Form>
            <div className="text-center text-sm">
                Already have an account?{" "}
                <Link to="/login" className="underline underline-offset-4">
                    Login
                </Link>
            </div>
        </div>
    )
}

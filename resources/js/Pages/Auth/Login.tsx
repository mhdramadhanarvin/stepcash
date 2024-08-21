import GuestLayout from "@/Layouts/GuestLayout";
import { Head } from "@inertiajs/react";
import SignInGoogle from "@/Components/Buttons/SignInGoogle";

export default function Login({ status }: { status: string }) {
    return (
        <GuestLayout>
            <Head title="Log in" />
            {status && (
                <div className="mb-4 font-medium text-center text-sm text-red-600 dark:text-red-400">
                    {status}
                </div>
            )}
            <SignInGoogle route={route("auth.google")} />
        </GuestLayout>
    );
}

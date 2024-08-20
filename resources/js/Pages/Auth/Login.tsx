import GuestLayout from "@/Layouts/GuestLayout";
import { Head } from "@inertiajs/react";
import SignInGoogle from "@/Components/Buttons/SignInGoogle";

export default function Login() {
    return (
        <GuestLayout>
            <Head title="Log in" />
            <SignInGoogle route={route("auth.google")} />
        </GuestLayout>
    );
}

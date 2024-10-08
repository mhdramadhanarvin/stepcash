import GuestLayout from "@/Layouts/GuestLayout";
import { Head } from "@inertiajs/react";
import SignInGoogle from "@/Components/Buttons/SignInGoogle";
import useLocalStorageState from "use-local-storage-state";

export default function Login({ status }: { status: string }) {
    const [splashScreen, setSplashScreen] = useLocalStorageState<boolean>(
        "splashScreen",
        {
            defaultValue: true,
        },
    );

    const handleSplashScreen = () => {
        setSplashScreen(!splashScreen);
    };

    return (
        <>
            {splashScreen ? (
                <div className="md:bg-contain bg-cover bg-center bg-no-repeat bg-[url('@images/splash-screen.png')] min-w-screen min-h-screen">
                    <div className="fixed top-20 left-auto md:left-0 text-white px-8 w-full grid flex justify-items-center">
                        <div className="w-96">
                            <h1 className="font-bold text-3xl">StepCash</h1>
                            <h3 className="text-xl">
                                Tukarkan langkahmu setiap hari dengan produk
                                menarik
                            </h3>
                        </div>
                    </div>
                    <div className="flex justify-center items-center text-center">
                        <div className="fixed bottom-16">
                            <div className="text-white py-3">
                                Pastikan telah memasang aplikasi Google Fit
                            </div>
                            <button
                                className="sticky buttom-5 bg-white rounded-full w-60 py-4 px-14 text-xl font-semibold text-commons"
                                onClick={handleSplashScreen}
                            >
                                Get Started
                            </button>
                        </div>
                    </div>
                </div>
            ) : (
                <GuestLayout>
                    <Head title="Log in" />
                    {status && (
                        <div className="mb-4 font-medium text-center text-sm text-red-600 dark:text-red-400">
                            {status}
                        </div>
                    )}
                    <SignInGoogle route={route("auth.google")} />
                </GuestLayout>
            )}
        </>
    );
}

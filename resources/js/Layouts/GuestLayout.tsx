import ApplicationLogo from "@/Components/ApplicationLogo";
import { Link } from "@inertiajs/react";
import { PropsWithChildren } from "react";
import useLocalStorageState from "use-local-storage-state";

export default function Guest({ children }: PropsWithChildren) {
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
        <div className="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <Link href="#" onClick={handleSplashScreen}>
                    <ApplicationLogo className="w-40 h-40 fill-current text-gray-500" />
                </Link>
            </div>

            <div className="w-full sm:max-w-md mt-6 px-6 py-4 overflow-hidden sm:rounded-lg">
                {children}
            </div>
        </div>
    );
}

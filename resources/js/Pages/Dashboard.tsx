import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { PageProps, Tracker } from "@/types";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
    faClock,
    faFire,
    faLocationDot,
    faWalking,
} from "@fortawesome/free-solid-svg-icons";
import { CircularProgress, createTheme, ThemeProvider } from "@mui/material";
import { Head } from "@inertiajs/react";
import { useQuery } from "react-query";
import { useState } from "react";

const theme = createTheme({
    palette: {
        primary: {
            main: "#EE7214",
        },
    },
});

export default function Dashboard({ auth }: PageProps) {
    const [loading, setLoading] = useState<boolean>(false);

    const fetchData = async () => {
        const response = await fetch(route("dashboard.sync"));
        return await response.json();
    };

    const { data, refetch } = useQuery(["stepData"], fetchData);

    const step: Tracker = data ?? 0;

    const calculate = Math.round((step.step / 7000) * 100);
    const progress = calculate >= 100 ? 100 : calculate;

    const refetchData = () => {
        setLoading(true);
        refetch();
        setTimeout(() => {
            setLoading(false);
        }, 15000);
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Dashboard" />
            <div>
                <h1 className="text-xl">Welcome</h1>
                <h2 className="text-2xl font-semibold">{auth.user.name}</h2>
            </div>
            <div className="w-full flex justify-center items-center text-center">
                <div
                    className={`w-40 h-40 bg-yellow-400 text-white grid justify-items-center content-center rounded-full mt-8 relative ${loading ? "animate-ping" : ""}`}
                    onClick={refetchData}
                >
                    <FontAwesomeIcon
                        icon={faWalking}
                        className="text-6xl mt-1"
                    />
                    <span className="text-2xl font-semibold">{progress}%</span>
                    <ThemeProvider theme={theme}>
                        <CircularProgress
                            variant="determinate"
                            value={progress ?? 0}
                            size={160}
                            thickness={2}
                            sx={{
                                position: "absolute",
                                top: 0,
                            }}
                        ></CircularProgress>
                    </ThemeProvider>
                </div>
            </div>
            <h2 className="text-center text-3xl pt-5 font-semibold">
                {step.step ?? 0}
            </h2>
            <h2 className="text-center text-lg py-2 font-semibold">
                Goal: 7000 Langkah
            </h2>
            <div className="w-full flex justify-center items-center text-center mt-5 pb-96">
                <div>
                    <div className="w-12 h-12 bg-gray-200 rounded-full mx-5 grid justify-items-center content-center">
                        <FontAwesomeIcon
                            icon={faClock}
                            className="text-lg text-green-500"
                        />
                    </div>
                    <div className="py-2">
                        <span>{step.time_spent} min</span>
                    </div>
                </div>
                <div>
                    <div className="w-12 h-12 bg-gray-200 rounded-full mx-6 grid justify-items-center content-center">
                        <FontAwesomeIcon
                            icon={faLocationDot}
                            className="text-lg text-blue-600"
                        />
                    </div>
                    <div className="py-2">
                        <span>{step.distance} KM</span>
                    </div>
                </div>
                <div>
                    <div className="w-12 h-12 bg-gray-200 rounded-full mx-5 grid justify-items-center content-center">
                        <FontAwesomeIcon
                            icon={faFire}
                            className="text-lg text-red-600"
                        />
                    </div>
                    <div className="py-2">
                        <span>{step.calory} Cal</span>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

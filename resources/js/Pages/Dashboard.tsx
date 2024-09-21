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
import { Head, Link } from "@inertiajs/react";
import { useQuery } from "react-query";
import { useState } from "react";
//import Joyride, { CallBackProps, STATUS, Step } from "react-joyride";
//import { useSetState } from "react-use";

const theme = createTheme({
    palette: {
        primary: {
            main: "#EE7214",
        },
    },
});

//interface State {
//    run: boolean;
//    steps: Step[];
//}

export default function Dashboard({ auth }: PageProps) {
    const [loading, setLoading] = useState<boolean>(false);

    // START ONBOARD
    //const [{ run, steps: stepsOnBoard }, setState] = useSetState<State>({
    //    run: true,
    //    steps: [
    //        {
    //            content: <h2>Panduan Penggunaan StepCash!</h2>,
    //            placement: "center",
    //            target: "body",
    //            styles: {
    //                options: {
    //                    width: 200,
    //                },
    //            },
    //        },
    //        {
    //            title: <h1 className="font-semibold">Progress Harian</h1>,
    //            content: "Progress pencapaian target langkah harian",
    //            placement: "top",
    //            styles: {
    //                options: {
    //                    width: 300,
    //                },
    //            },
    //            target: ".progress span",
    //        },
    //        {
    //            title: <h1 className="font-semibold">Sinkrinisasi Manual</h1>,
    //            content: "Klik ini untuk sinkronisasi manual data langkah",
    //            placement: "top",
    //            styles: {
    //                options: {
    //                    width: 300,
    //                },
    //            },
    //            target: ".progress div",
    //        },
    //        {
    //            title: <h1 className="font-semibold">Total Langkah</h1>,
    //            content: "Jumlah langkah dalam sehari",
    //            placement: "top",
    //            styles: {
    //                options: {
    //                    width: 300,
    //                },
    //            },
    //            target: ".totalStep",
    //        },
    //        {
    //            title: <h1 className="font-semibold">Waktu</h1>,
    //            content: "Waktu yang dihabiskan berjalan kaki dalam sehari",
    //            placement: "right",
    //            styles: {
    //                options: {
    //                    width: 300,
    //                },
    //            },
    //            target: ".timeSpent",
    //        },
    //        {
    //            title: <h1 className="font-semibold">Jarak Tempuh</h1>,
    //            content:
    //                "Jumlah jarak tempuh selama aktivitas fisik dalam sehari",
    //            placement: "top",
    //            styles: {
    //                options: {
    //                    width: 300,
    //                },
    //            },
    //            target: ".totalDistance",
    //        },
    //        {
    //            title: <h1 className="font-semibold">Jumlah Kalori</h1>,
    //            content:
    //                "Jumlah kalori yang dibakar selama aktivitas fisik seharian",
    //            placement: "top",
    //            styles: {
    //                options: {
    //                    width: 300,
    //                },
    //            },
    //            target: ".totalCalory",
    //        },
    //    ],
    //});
    //
    //const handleClickStart = (event: React.MouseEvent<HTMLElement>) => {
    //    event.preventDefault();
    //
    //    setState({
    //        run: true,
    //    });
    //};
    //
    //const handleJoyrideCallback = (data: CallBackProps) => {
    //    const { status, type } = data;
    //    const finishedStatuses: string[] = [STATUS.FINISHED, STATUS.SKIPPED];
    //
    //    if (finishedStatuses.includes(status)) {
    //        setState({ run: false });
    //    }
    //};
    // END ONBOARD

    const fetchData = async () => {
        const response = await fetch(route("dashboard.sync"));
        return await response.json();
    };

    const { data, refetch } = useQuery(["stepData"], fetchData);

    const step: Tracker = data ?? 0;

    const calculate =
        step?.step > 0 ? Math.round((step?.step / 7000) * 100) : 0;
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
            {/*
            <Joyride
                callback={handleJoyrideCallback}
                continuous
                run={run}
                scrollToFirstStep
                showProgress
                showSkipButton
                steps={stepsOnBoard}
                styles={{
                    options: {
                        zIndex: 10000,
                    },
                }}
            />
            */}
            <Head title="Dashboard" />
            <div>
                <h1 className="text-xl">Welcome</h1>
                <Link
                    href="https://forms.gle/Rbskp4trHk2TipoD8"
                    target="_blank"
                >
                    <h2 className="text-2xl font-semibold">{auth.user.name}</h2>
                </Link>
            </div>
            <div className="w-full flex justify-center items-center text-center progress">
                <div
                    className={`w-40 h-40 bg-yellow-400 text-white grid justify-items-center content-center rounded-full mt-8 relative  ${loading ? "animate-ping" : ""}`}
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
            <h2 className="text-center text-3xl pt-5 font-semibold totalStep">
                {step.step ?? 0}
            </h2>
            <h2 className="text-center text-lg py-2 font-semibold targetStep">
                Target: 7000 Langkah
            </h2>
            <div className="w-full flex justify-center items-center text-center mt-5">
                <div className="timeSpent">
                    <div className="w-12 h-12 bg-gray-200 rounded-full mx-5 grid justify-items-center content-center">
                        <FontAwesomeIcon
                            icon={faClock}
                            className="text-lg text-green-500"
                        />
                    </div>
                    <div className="py-2">
                        <span>{step.time_spent ?? 0} min</span>
                    </div>
                </div>
                <div className="totalDistance">
                    <div className="w-12 h-12 bg-gray-200 rounded-full mx-6 grid justify-items-center content-center">
                        <FontAwesomeIcon
                            icon={faLocationDot}
                            className="text-lg text-blue-600"
                        />
                    </div>
                    <div className="py-2">
                        <span>{step.distance ?? 0} KM</span>
                    </div>
                </div>
                <div className="totalCalory">
                    <div className="w-12 h-12 bg-gray-200 rounded-full mx-5 grid justify-items-center content-center">
                        <FontAwesomeIcon
                            icon={faFire}
                            className="text-lg text-red-600"
                        />
                    </div>
                    <div className="py-2">
                        <span>{step.calory ?? 0} Cal</span>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

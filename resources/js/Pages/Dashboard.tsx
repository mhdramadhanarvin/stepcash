import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { PageProps, Tracker } from "@/types";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
    faClock,
    faFire,
    faLocationDot,
    faPencil,
    faWalking,
} from "@fortawesome/free-solid-svg-icons";
import { CircularProgress, createTheme, ThemeProvider } from "@mui/material";
import { Head, Link } from "@inertiajs/react";
import { useQuery } from "react-query";
import { useState } from "react";
import Modal from "@/Components/Modal";
import SecondaryButton from "@/Components/SecondaryButton";
import TextInput from "@/Components/TextInput";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import { EditTargetStepForm } from "./Profile/Partials/EditTargetStep";

const theme = createTheme({
    palette: {
        primary: {
            main: "#EE7214",
        },
    },
});

export default function Dashboard({ auth }: PageProps) {
    const [loading, setLoading] = useState<boolean>(false);
    const [edit, setEdit] = useState<boolean>(false);

    const fetchData = async () => {
        const response = await fetch(route("dashboard.sync"));
        return await response.json();
    };

    const { data, refetch } = useQuery(["stepData"], fetchData);

    const step: Tracker = data ?? 0;

    const calculate =
        step?.step > 0
            ? Math.round((step?.step / auth.user.step_target) * 100)
            : 0;
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
                Target: {auth.user.step_target} Langkah
            </h2>
            <div
                className="text-sm text-center border-2-b"
                onClick={() => setEdit(true)}
            >
                <FontAwesomeIcon
                    icon={faPencil}
                    className="mx-2 text-blue-600"
                />
                Ubah Target
            </div>
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
            <Modal show={edit} onClose={() => setEdit(false)} closeable={true}>
                <div className="p-5 mx-4">
                    <h2 className="text-xl font-semibold mb-2">
                        Ubah Target Langkah Harian
                    </h2>
                    <EditTargetStepForm handleClose={() => setEdit(false)} />
                </div>
            </Modal>
        </AuthenticatedLayout>
    );
}

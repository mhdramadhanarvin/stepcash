import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { PageProps } from "@/types";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
    faClock,
    faFire,
    faLocationDot,
    faWalking,
} from "@fortawesome/free-solid-svg-icons";
import {
    CircularProgress,
    createTheme,
    Icon,
    ThemeProvider,
} from "@mui/material";

const theme = createTheme({
    palette: {
        primary: {
            main: "#EE7214",
        },
    },
});

export default function Dashboard({ auth }: PageProps) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <div>
                <h1 className="text-xl">Welcome</h1>
                <h2 className="text-2xl font-semibold">{auth.user.name}</h2>
            </div>
            <div className="w-full flex justify-center items-center text-center">
                <div className="w-40 h-40 bg-yellow-400 text-white grid justify-items-center content-center rounded-full mt-8 relative">
                    <FontAwesomeIcon
                        icon={faWalking}
                        className="text-6xl mt-3"
                    />
                    <span className="text-2xl font-semibold">
                        {Math.round((6029 / 7000) * 100)}%
                    </span>
                    <ThemeProvider theme={theme}>
                        <CircularProgress
                            variant="determinate"
                            value={5}
                            size={165}
                            thickness={2}
                            sx={{
                                position: "absolute",
                                top: 0,
                            }}
                        ></CircularProgress>
                    </ThemeProvider>
                </div>
            </div>
            <h2 className="text-center text-3xl pt-5 font-semibold">6029</h2>
            <h2 className="text-center text-lg py-2 font-semibold">
                Goal: 7000 Langkah
            </h2>
            <div className="w-full flex justify-center items-center text-center mt-5 pb-40">
                <div>
                    <div className="w-12 h-12 bg-gray-200 rounded-full mx-5 grid justify-items-center content-center">
                        <FontAwesomeIcon
                            icon={faClock}
                            className="text-lg text-green-500"
                        />
                    </div>
                    <div className="py-2">
                        <span>3h 12m</span>
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
                        <span>3.5km</span>
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
                        <span>150cal</span>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

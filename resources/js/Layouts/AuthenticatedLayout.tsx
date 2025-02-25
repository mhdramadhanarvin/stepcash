import { useState, PropsWithChildren, ReactNode } from "react";
import Dropdown from "@/Components/Dropdown";
import NavLink from "@/Components/NavLink";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink";
import { Link } from "@inertiajs/react";
import { User } from "@/types";
import {
    faAward,
    faBell,
    faHome,
    faUserCircle,
} from "@fortawesome/free-solid-svg-icons";
import NavIcon from "@/Components/NavIcon";
import CoinIcon from "@/Components/CoinIcon";
import { useApi } from "@/utils/useApi";
import { formatedBalance } from "@/utils/manipulation";
//import Joyride, { CallBackProps, STATUS, Step } from "react-joyride";
//import useLocalStorageState from "use-local-storage-state";

//interface State {
//    run: boolean;
//    steps: Step[];
//}

export default function Authenticated({
    user,
    header,
    children,
}: PropsWithChildren<{ user: User; header?: ReactNode }>) {
    const [showingNavigationDropdown] = useState(false);

    const { data } = useApi({
        key: "profile",
        route: route("profile.get"),
    });

    const { data: notifData } = useApi({
        key: "notifications.unread",
        route: route("notifications.getUnread"),
    });

    const profile: User = data ?? {};
    const notificationsGet: Notification[] = notifData ?? [];
    const unReadNotif = notificationsGet.length;

    // START ONBOARD
    //const [onBoard, setOnBoard] = useState<boolean>(true);
    //const [onBoard, setOnBoard] = useLocalStorageState<boolean>("onBoard", {
    //    defaultValue: true,
    //});
    //const [{ steps: stepsOnBoard }] = useState<State>({
    //    run: true,
    //    steps: [
    //        {
    //            content: <span>Selamat Datang di StepCash!</span>,
    //            placement: "center",
    //            target: "body",
    //            styles: {
    //                options: {
    //                    width: 200,
    //                },
    //            },
    //        },
    //        {
    //            title: <span className="font-semibold">Progress Harian</span>,
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
    //            title: <span className="font-semibold">Total Koin</span>,
    //            content:
    //                "Jumlah langkah harianmu akan dikonversikan ke koin dan ditampilkan disini",
    //            placement: "top",
    //            styles: {
    //                options: {
    //                    width: 300,
    //                },
    //            },
    //            target: ".totalCoin button",
    //        },
    //        {
    //            title: (
    //                <span className="font-semibold">Sinkronisasi Manual</span>
    //            ),
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
    //            title: <span className="font-semibold">Total Langkah</span>,
    //            content: "Jumlah langkah yang kamu lakukan hari ini",
    //            placement: "top",
    //            styles: {
    //                options: {
    //                    width: 300,
    //                },
    //            },
    //            target: ".totalStep",
    //        },
    //        {
    //            title: <span className="font-semibold">Waktu</span>,
    //            content: "Waktu yang dihabiskan berjalan kaki hari ini",
    //            placement: "right",
    //            styles: {
    //                options: {
    //                    width: 300,
    //                },
    //            },
    //            target: ".timeSpent",
    //        },
    //        {
    //            title: <span className="font-semibold">Jarak Tempuh</span>,
    //            content: "Jumlah jarak tempuh selama berjalan kaki hari ini",
    //            placement: "top",
    //            styles: {
    //                options: {
    //                    width: 300,
    //                },
    //            },
    //            target: ".totalDistance",
    //        },
    //        {
    //            title: <span className="font-semibold">Jumlah Kalori</span>,
    //            content: "Jumlah kalori yang dibakar selama aktivitas hari ini",
    //            placement: "top",
    //            styles: {
    //                options: {
    //                    width: 300,
    //                },
    //            },
    //            target: ".totalCalory",
    //        },
    //        {
    //            title: <span className="font-semibold">Halaman Hadiah</span>,
    //            content:
    //                "Tukarkan koin yang kamu dapatkan dengan produk pada halaman ini",
    //            placement: "top",
    //            styles: {
    //                options: {
    //                    width: 300,
    //                },
    //            },
    //            target: ".rewards",
    //        },
    //        {
    //            title: (
    //                <span className="font-semibold">Halaman Notifikasi</span>
    //            ),
    //            content:
    //                "Perkembangan terkait penukaran hadiah kamu akan diinformasikan disini",
    //            placement: "top",
    //            styles: {
    //                options: {
    //                    width: 300,
    //                },
    //            },
    //            target: ".notifications",
    //        },
    //        {
    //            title: <span className="font-semibold">Halaman Profil</span>,
    //            content: "Kamu juga bisa mengubah profil kamu pada halaman ini",
    //            placement: "top",
    //            styles: {
    //                options: {
    //                    width: 300,
    //                },
    //            },
    //            target: ".profile",
    //        },
    //    ],
    //});

    //const handleJoyrideCallback = (data: CallBackProps) => {
    //    const { status } = data;
    //    const finishedStatuses: string[] = [STATUS.FINISHED, STATUS.SKIPPED];
    //
    //    if (finishedStatuses.includes(status)) {
    //        setOnBoard(false);
    //    }
    //};

    // END ONBOARD
    return (
        <div className="min-h-screen bg-gray-100">
            {/*
            <Joyride
                callback={handleJoyrideCallback}
                continuous
                run={onBoard}
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
            <nav className="bg-white border-b border-gray-100">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-16">
                        <div className="flex">
                            <div className="shrink-0 flex items-center">
                                <Link href="/">
                                    <h1 className="text-2xl font-semibold">
                                        StepCash
                                    </h1>
                                </Link>
                            </div>

                            <div className="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink
                                    href={route("dashboard")}
                                    active={route().current("dashboard")}
                                >
                                    Dashboard
                                </NavLink>
                            </div>
                        </div>

                        <div className="hidden sm:flex sm:items-center sm:ms-6">
                            <div className="ms-3 relative">
                                <Dropdown>
                                    <Dropdown.Trigger>
                                        <span className="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                className="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {user.name}

                                                <svg
                                                    className="ms-2 -me-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fillRule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clipRule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </Dropdown.Trigger>

                                    <Dropdown.Content>
                                        <Dropdown.Link
                                            href={route("profile.edit")}
                                        >
                                            Profile
                                        </Dropdown.Link>
                                        <Dropdown.Link
                                            href={route("logout")}
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </Dropdown.Link>
                                    </Dropdown.Content>
                                </Dropdown>
                            </div>
                        </div>

                        <div className="-me-2 flex items-center sm:hidden totalCoin">
                            <button className="inline-flex items-center justify-center p-2 rounded-md text-gray-700 text-3xl hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <CoinIcon />
                                <span className="text-xl font-semibold">
                                    {formatedBalance(profile?.coin ?? 0)}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    className={
                        (showingNavigationDropdown ? "block" : "hidden") +
                        " sm:hidden"
                    }
                >
                    <div className="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink
                            href={route("dashboard")}
                            active={route().current("dashboard")}
                        >
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <div className="pt-4 pb-1 border-t border-gray-200">
                        <div className="px-4">
                            <div className="font-medium text-base text-gray-800">
                                {user.name}
                            </div>
                            <div className="font-medium text-sm text-gray-500">
                                {user.email}
                            </div>
                        </div>

                        <div className="mt-3 space-y-1">
                            <ResponsiveNavLink href={route("profile.edit")}>
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                method="post"
                                href={route("logout")}
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            {header && (
                <header className="bg-white shadow">
                    <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {header}
                    </div>
                </header>
            )}

            <main className="p-4 h-screen">{children}</main>

            <footer className="bg-commons shadow rounded-full mx-7 sticky bottom-5 z-99">
                <div className="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8 grid justify-items-center items-center grid-cols-4 gap-4 text-xl text-white">
                    <NavIcon
                        path={route("dashboard")}
                        icon={faHome}
                        active={route().current("dashboard")}
                        className="dashboard"
                    />
                    <NavIcon
                        path={route("rewards.index")}
                        icon={faAward}
                        active={route().current("rewards.*")}
                        className="rewards"
                    />
                    <div className="">
                        <NavIcon
                            path={route("notifications.index")}
                            icon={faBell}
                            active={route().current("notifications.*")}
                            notif={unReadNotif}
                            className="notifications"
                        />
                    </div>
                    <NavIcon
                        path={route("profile.edit")}
                        icon={faUserCircle}
                        active={route().current("profile.edit")}
                        className="profile"
                    />
                </div>
            </footer>
        </div>
    );
}

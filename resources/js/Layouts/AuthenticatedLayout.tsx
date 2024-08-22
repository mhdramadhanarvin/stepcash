import { useState, PropsWithChildren, ReactNode } from "react";
import ApplicationLogo from "@/Components/ApplicationLogo";
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

export default function Authenticated({
    user,
    header,
    children,
}: PropsWithChildren<{ user: User; header?: ReactNode }>) {
    const [showingNavigationDropdown, setShowingNavigationDropdown] =
        useState(false);

    return (
        <div className="min-h-screen bg-gray-100">
            <nav className="bg-white border-b border-gray-100">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-16">
                        <div className="flex">
                            <div className="shrink-0 flex items-center">
                                <Link href="/">
                                    <ApplicationLogo className="block h-9 w-auto fill-current text-gray-800" />
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

                        <div className="-me-2 flex items-center sm:hidden">
                            <button
                                //onClick={() =>
                                //    setShowingNavigationDropdown(
                                //        (previousState) => !previousState,
                                //    )
                                //}
                                className="inline-flex items-center justify-center p-2 rounded-md text-gray-700 text-3xl hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    height="30px"
                                    width="30px"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    className="mx-2"
                                >
                                    <g
                                        id="SVGRepo_bgCarrier"
                                        stroke-width="0"
                                    ></g>
                                    <g
                                        id="SVGRepo_tracerCarrier"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    ></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <ellipse
                                            rx="8.5"
                                            ry="9"
                                            transform="matrix(-1 0 0 1 10.5 12)"
                                            stroke="#4b5563"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        ></ellipse>
                                        <path
                                            d="M13 8.8C12.3732 8.29767 11.5941 8 10.7498 8C8.67883 8 7 9.79086 7 12C7 14.2091 8.67883 16 10.7498 16C11.5941 16 12.3732 15.7023 13 15.2"
                                            stroke="#4b5563"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        ></path>
                                        <path
                                            d="M11 3C14.6667 3 22 3.9 22 12C22 20.1 14.6667 21 11 21"
                                            stroke="#4b5563"
                                            stroke-width="2"
                                        ></path>
                                    </g>
                                </svg>
                                <span className="text-xl font-semibold">
                                    123
                                </span>
                                {/*
                                <svg
                                    className="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        className={
                                            !showingNavigationDropdown
                                                ? "inline-flex"
                                                : "hidden"
                                        }
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        className={
                                            showingNavigationDropdown
                                                ? "inline-flex"
                                                : "hidden"
                                        }
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                                */}
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

            <main className="p-6">{children}</main>

            <footer className="bg-indigo-800 shadow rounded-full mx-7 sticky bottom-5">
                <div className="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8 grid justify-items-center items-center grid-cols-4 gap-4 text-xl text-white">
                    <NavIcon
                        path={route("dashboard")}
                        icon={faHome}
                        active={route().current("dashboard")}
                    />
                    <NavIcon
                        path={route("rewards")}
                        icon={faAward}
                        active={route().current("rewards")}
                    />
                    <NavIcon
                        path={route("profile.edit")}
                        icon={faBell}
                        active={route().current("profile.edit")}
                    />
                    <NavIcon
                        path={route("profile.edit")}
                        icon={faUserCircle}
                        active={route().current("profile.edit")}
                    />
                </div>
            </footer>
        </div>
    );
}

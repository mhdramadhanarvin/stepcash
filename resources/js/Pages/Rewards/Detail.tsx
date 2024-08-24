import CoinIcon from "@/Components/CoinIcon";
import Modal from "@/Components/Modal";
import SecondaryButton from "@/Components/SecondaryButton";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { PageProps } from "@/types";
import {
    faCheck,
    faChevronLeft,
    faCircleCheck,
    faTicket,
    faTimesCircle,
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { Link } from "@inertiajs/react";
import { useState } from "react";

export default function Detail({ auth }: PageProps) {
    const [success, setSuccess] = useState(false);
    const handleSuccess = () => {
        setSuccess(!success);
        setFail(!fail);
    };
    const [fail, setFail] = useState(true);
    const handleFail = () => {
        setFail(!fail);
    };
    return (
        <>
            <Modal show={success} onClose={handleSuccess} closeable={true}>
                <div className="p-5 text-center">
                    <FontAwesomeIcon
                        icon={faCircleCheck}
                        className="text-7xl py-4 text-commons"
                    />
                    <h2 className="text-xl font-semibold mb-2">
                        Berhasil Menukarkan Hadiah
                    </h2>
                    <p>Tunggu konfirmasi 1x24 jam dari mitra dulu yaa..</p>
                    <div className="flex justify-center pt-5">
                        <SecondaryButton onClick={handleSuccess}>
                            TUTUP
                        </SecondaryButton>
                    </div>
                </div>
            </Modal>
            <Modal show={fail} onClose={handleFail} closeable={true}>
                <div className="p-5 text-center">
                    <FontAwesomeIcon
                        icon={faTimesCircle}
                        className="text-7xl py-4 text-red-500"
                    />
                    <h2 className="text-xl font-semibold mb-2">
                        Koin Belum Mencukupi
                    </h2>
                    <p>Belum cukup nih, kumpulkan lebih banyak lagi yaa. </p>
                    <div className="flex justify-center pt-5">
                        <SecondaryButton onClick={handleFail}>
                            TUTUP
                        </SecondaryButton>
                    </div>
                </div>
            </Modal>
            <AuthenticatedLayout user={auth.user}>
                <Link href={route("rewards.index")}>
                    <button className="p-3">
                        <FontAwesomeIcon icon={faChevronLeft} />
                    </button>
                </Link>
                <div className="max-w-lg rounded-2xl hover:shadow-xl hover:shadow-indigo-50 flex flex-col mb-64">
                    <img
                        src="https://loremflickr.com/800/600/girl"
                        className="shadow rounded-lg overflow-hidden border object-cover h-48 w-96"
                    />
                    <div className="mt-8 px-1">
                        <div className="grid grid-cols-4">
                            <div className="col-span-3">
                                <h3 className="font-semibold text-xl">
                                    Reward A
                                </h3>
                                <h3 className="text-sm">
                                    Mitra - 5x ditukarkan
                                </h3>
                            </div>
                            <div className="inline-flex">
                                <CoinIcon />
                                <span className="text-xl font-semibold">
                                    123
                                </span>
                            </div>
                        </div>
                        <p className="mt-2 text-gray-600">
                            Create Exercises for any subject with the topics you
                            and your students care about.
                        </p>
                        <div className="mt-5 flex justify-center">
                            <button
                                type="button"
                                className="inline-flex items-center rounded-md border border-transparent bg-white hover:bg-commons px-5 py-3 text-lg font-medium leading-4 shadow-lg text-commons hover:text-white border-commons"
                                onClick={() => setSuccess(true)}
                            >
                                <FontAwesomeIcon
                                    icon={faTicket}
                                    className="mx-3"
                                />
                                Tukarkan Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </AuthenticatedLayout>
        </>
    );
}

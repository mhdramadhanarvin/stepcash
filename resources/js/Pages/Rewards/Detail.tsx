import CoinIcon from "@/Components/CoinIcon";
import Modal from "@/Components/Modal";
import SecondaryButton from "@/Components/SecondaryButton";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { PageProps, ResponseGlobal, Rewards } from "@/types";
import { useApi } from "@/utils/useApi";
import {
    faChevronLeft,
    faCircleCheck,
    faTicket,
    faTimesCircle,
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { Head, Link } from "@inertiajs/react";
import axios from "axios";
import { useEffect, useState } from "react";
import { useMutation } from "react-query";

export default function Detail({ auth, id }: PageProps<{ id: number }>) {
    const [success, setSuccess] = useState(false);
    const [message, setMessage] = useState<ResponseGlobal>({
        message: "",
        reason: "",
    });

    const handleSuccess = () => {
        setSuccess(!success);
    };
    const [fail, setFail] = useState(false);
    const handleFail = () => {
        setFail(!fail);
    };

    const { data, refetch } = useApi({
        key: "rewards.show.get",
        route: route("rewards.show.get", id),
    });

    const reward: Rewards = data ?? {};

    const { mutate } = useMutation(async () => {
        return axios.post(route("rewards.exchange", reward.id));
    });

    const { refetch: refetchProfile } = useApi({
        key: "profile",
        route: route("profile.get"),
    });

    const handleSubmit = () => {
        mutate(undefined, {
            onError: (error: any) => {
                if (axios.isAxiosError(error)) {
                    setFail(true);
                    setMessage(error.response?.data);
                    refetch();
                    refetchProfile();
                }
            },
            onSuccess(data) {
                setSuccess(true);
                setMessage(data.data);
                refetch();
                refetchProfile();
            },
        });
    };

    useEffect(() => {
        refetch();
    }, [refetch]);

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
                        {message.message}
                    </h2>
                    <p>{message.reason}</p>
                    <div className="flex justify-center pt-5">
                        <SecondaryButton onClick={handleFail}>
                            TUTUP
                        </SecondaryButton>
                    </div>
                </div>
            </Modal>
            <AuthenticatedLayout user={auth.user}>
                <Head title="Detail Reward" />
                <Link href={route("rewards.index")}>
                    <button className="p-3">
                        <FontAwesomeIcon icon={faChevronLeft} />
                    </button>
                </Link>
                <div className="max-w-lg rounded-2xl hover:shadow-xl hover:shadow-indigo-50 flex flex-col mb-64">
                    <img
                        src={reward?.thumbnail}
                        className="shadow rounded-lg overflow-hidden border object-cover h-48"
                    />
                    <div className="mt-8 px-1">
                        <div className="grid grid-cols-4">
                            <div className="col-span-3">
                                <h3 className="font-semibold text-xl">
                                    {reward?.title}
                                </h3>
                                <h3 className="text-sm">
                                    {reward?.partner?.name} - Stok{" "}
                                    {reward?.quantity}
                                </h3>
                            </div>
                            <div className="inline-flex">
                                <CoinIcon />
                                <span className="text-xl font-semibold">
                                    {reward?.price}
                                </span>
                            </div>
                        </div>
                        <p className="mt-2 text-gray-600">
                            {reward?.description}
                        </p>
                        <div className="mt-5 flex justify-center">
                            {reward?.quantity == 0 ? (
                                <button
                                    type="button"
                                    className="inline-flex items-center rounded-md border border-gray-200 bg-gray-200 hover:bg-black px-5 py-3 text-lg font-medium leading-4 shadow-lg text-black hover:text-white border-black"
                                >
                                    <FontAwesomeIcon
                                        icon={faTicket}
                                        className="mx-3"
                                    />
                                    Stok Habis
                                </button>
                            ) : (
                                <button
                                    type="button"
                                    className="inline-flex items-center rounded-md border border-common bg-white hover:bg-commons px-5 py-3 text-lg font-medium leading-4 shadow-lg text-commons hover:text-white border-commons"
                                    onClick={handleSubmit}
                                >
                                    <FontAwesomeIcon
                                        icon={faTicket}
                                        className="mx-3"
                                    />
                                    Tukarkan Sekarang
                                </button>
                            )}
                        </div>
                    </div>
                </div>
            </AuthenticatedLayout>
        </>
    );
}

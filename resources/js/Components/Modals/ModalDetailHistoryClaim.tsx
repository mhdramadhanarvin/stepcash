import { RewardClaims } from "@/types";
import Modal from "../Modal";
import { Typography } from "@mui/joy";
import { Badge } from "../Badge";
import CoinIcon from "../CoinIcon";
import SecondaryButton from "../SecondaryButton";
import {
    formatedBalance,
    limitCharacter,
    RewardClaimIntruction,
    RewardClaimStatusLabel,
    RewardClaimStatusLabelColor,
} from "@/utils/manipulation";

interface ModalDetailHistoryClaimProps {
    open: boolean;
    handleClose: () => void;
    data: RewardClaims | undefined;
}
export const ModalDetailHistoryClaim = ({
    open,
    handleClose,
    data,
}: ModalDetailHistoryClaimProps) => {
    const handleCloseModal = () => {
        handleClose();
    };
    return (
        <Modal show={open} onClose={handleClose}>
            <div className="px-7 py-5">
                <div className="pb-2">
                    <h2 className="text-2xl font-bold text-gray-900">
                        Detail Penukaran
                    </h2>
                </div>
                {data?.status == "ready_to_pickup" && (
                    <div>
                        <h3 className="text-lg font-bold text-gray-900">
                            Kode Penukaran
                        </h3>
                        <span className="block mt-3 px-1">
                            <Typography
                                variant="solid"
                                color="primary"
                                paddingX={2}
                                sx={{ maxWidth: 130 }}
                            >
                                #{data?.code}
                            </Typography>
                        </span>
                    </div>
                )}
                <div className="grid grid-cols-7 pt-2">
                    <div className="col-span-4">
                        <h3 className="text-lg font-bold text-gray-900">
                            Status
                        </h3>
                        <span className="block mt-3">
                            <Badge
                                variant={
                                    RewardClaimStatusLabelColor[
                                        data?.status ?? "done"
                                    ]
                                }
                                label={
                                    RewardClaimStatusLabel[
                                        data?.status ?? "done"
                                    ]
                                }
                            />
                        </span>
                    </div>
                    <div className="col-span-3">
                        <h3 className="text-lg font-bold text-gray-900">
                            Tgl Penukaran
                        </h3>
                        <p className="text-sm">
                            <span className="block mt-3">
                                {new Date(
                                    data?.created_at ?? "",
                                ).toLocaleString("id-ID", {
                                    day: "numeric",
                                    month: "numeric",
                                    year: "numeric",
                                    hour: "numeric",
                                    minute: "numeric",
                                })}
                            </span>
                        </p>
                    </div>
                </div>
                <div className="mt-5">
                    <h3 className="text-lg font-bold text-gray-900">
                        {data?.reward.partner.name}
                    </h3>
                    <span className="text-sm">
                        {data?.reward.partner.address}
                    </span>
                    <div className="py-2 grid grid-cols-10">
                        <div className="row-span-3 col-span-2">
                            <img
                                className="w-16 h-16 rounded-xl"
                                src={data?.reward.thumbnail}
                                alt={data?.reward.title}
                            />
                        </div>
                        <div className="col-span-8 text-lg px-5">
                            {limitCharacter(data?.reward.title ?? "", 20)}
                        </div>
                        <div className="col-span-8 text-lg inline-flex px-3">
                            <CoinIcon />
                            {formatedBalance(data?.price ?? 0)}
                        </div>
                    </div>
                </div>
                <div className="mt-3">
                    <Badge
                        variant="info"
                        label={RewardClaimIntruction({
                            status: data?.status ?? "done",
                            data: {
                                address: data?.reward.partner.address,
                                code: data?.code,
                                reason_rejection: data?.reason_rejection,
                            },
                        })}
                    />
                </div>
                <div className="flex justify-center pt-5">
                    <SecondaryButton onClick={handleCloseModal}>
                        TUTUP
                    </SecondaryButton>
                </div>
            </div>
        </Modal>
    );
};

import { BadgeVariant } from "@/Components/Badge";
import { RewardClaimStatus } from "@/types";

export const limitCharacter = (character: string, limit?: number): string => {
    const limitChar: number = limit ?? 20;
    return character.length > limitChar
        ? character.slice(0, limitChar) + "..."
        : character;
};

export const RewardClaimStatusLabel: { [key in RewardClaimStatus]: string } = {
    waiting_confirmation: "Menunggu Konfirmasi",
    on_progress: "Sedang Diproses",
    ready_to_pickup: "Siap Diambil",
    done: "Selesai",
    rejected: "Ditolak",
};

export const RewardClaimStatusLabelColor: {
    [key in RewardClaimStatus]: BadgeVariant;
} = {
    waiting_confirmation: "warning",
    on_progress: "info",
    ready_to_pickup: "success",
    done: "success",
    rejected: "danger",
};

interface RewardClaimIntructionType {
    status: RewardClaimStatus;
    data?: {
        code?: string;
        address?: string;
        reason_rejection?: string;
    };
}

export const RewardClaimIntruction = ({
    status,
    data = {},
}: RewardClaimIntructionType): string => {
    switch (status) {
        case "waiting_confirmation":
            return "Sedang menunggu konfirmasi dari mitra";
        case "on_progress":
            return "Permintaan sudah di konfirmasi dan sedang disiapkan oleh mitra";
        case "ready_to_pickup":
            return `Hadiah selesai disiapkan, silahkan ambil hadiah ke alamat ${data.address} dengan menunjukkan kode penukaran`;
        case "done":
            return "Pemintaan penukaran diselesaikan";
        case "rejected":
            return `Permintaan dibatalkan oleh mitra karena alasan ${data.reason_rejection}`;
        default:
            return "";
    }
};

export const formatedBalance = (balance: number): string => {
    if (balance >= 1000) {
        return (
            new Intl.NumberFormat("en-US", { maximumFractionDigits: 2 })
                .format(balance / 1000)
                .toString() + "K"
        );
    }
    return new Intl.NumberFormat("en-US", { maximumFractionDigits: 2 })
        .format(balance)
        .toString();
};

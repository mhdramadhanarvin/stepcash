export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
    coin: number;
    step_target: number;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
};

export interface ResponseGlobal {
    message: string;
    reason: string;
}

export interface Tracker {
    step: number;
    target: number;
    calory: number;
    distance: number;
    time_spent: number;
}

export enum PartnerSector {
    FOOD_AND_BEVARAGE = "food_and_bevarage",
    SPORTS = "sports",
    OTHER = "other",
}

export type RewardClaimStatus =
    | "waiting_confirmation"
    | "on_progress"
    | "ready_to_pickup"
    | "done"
    | "rejected";

export interface Partner {
    id: number;
    user: User;
    name: string;
    address: string;
    sector: PartnerSector;
}

export interface Rewards {
    id: number;
    partner: Partner;
    title: string;
    description: string;
    quantity: number;
    price: number;
    thumbnail: string;
}

export interface RewardClaims {
    id: number;
    reward: Rewards;
    user: User;
    code: string;
    price: number;
    status: RewardClaimStatus;
    reason_rejection: string;
    created_at: Date;
}

export interface Notification {
    id: number;
    notifiable_id: User;
    data: {
        title: string;
        body: string;
        actions?: [
            {
                url?: string;
            },
        ];
    };
    read_at: Date;
    created_at: Date;
}

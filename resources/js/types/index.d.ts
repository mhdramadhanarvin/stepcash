export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
    coin: number;
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

export enum RewardClaimStatus {
    WAITING_CONFIRMATION = "waiting_confirmation",
    ON_PROGRESS = "on_progress",
    READY_TO_PICKUP = "ready_to_pickup",
    DONE = "done",
    REJECTED = "rejected",
}

export interface Partner {
    id: number;
    user: User;
    name: string;
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
    price: number;
    status: RewardClaimStatus;
    reason_rejection: string;
}

export interface Notification {
    id: number;
    user: User;
    title: string;
    message: string;
    is_read: boolean;
    created_at: Date;
}

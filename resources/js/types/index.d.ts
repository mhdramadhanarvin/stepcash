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

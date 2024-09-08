import { Typography } from "@mui/joy";

export interface BadgeProps {
    variant?: BadgeVariant;
    label: string;
}

export type BadgeVariant = "success" | "warning" | "info" | "danger" | "gray";

export const Badge = ({ variant, label }: BadgeProps) => {
    let badgeStyle = "";
    switch (variant) {
        case "success":
            badgeStyle = "bg-green-50 text-green-700 ring-green-600/20";
            break;
        case "warning":
            badgeStyle = "bg-yellow-50 text-yellow-800 ring-yellow-600/20";
            break;
        case "info":
            badgeStyle = "bg-blue-50 text-blue-700 ring-blue-700/10";
            break;
        case "danger":
            badgeStyle = "bg-red-50 text-red-700 ring-red-600/10";
            break;
        case "gray":
            badgeStyle = "bg-red-50 text-red-700 ring-red-600/10";
            break;
    }
    return (
        <Typography level="body-sm">
            <span
                className={
                    `inline-flex font-bold items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset ` +
                    badgeStyle
                }
            >
                {label}
            </span>
        </Typography>
    );
};

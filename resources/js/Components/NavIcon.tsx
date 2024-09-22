import { IconProp } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { Link } from "@inertiajs/react";
import { Badge } from "@mui/material";
import { useEffect } from "react";

interface NavIconProps {
    path: string;
    active: boolean;
    icon: IconProp;
    notif?: number;
    className?: string;
}

export default function NavIcon({
    path,
    active,
    icon,
    notif,
    className,
}: NavIconProps) {
    useEffect(() => {}, [active]);

    return (
        <Link href={path}>
            <div className={`p-0 m-0 ${className}`}>
                {active ? (
                    <div className="bg-white w-10 h-10 rounded-full grid justify-items-center items-center">
                        <Badge
                            badgeContent={notif}
                            color="error"
                            showZero={false}
                        >
                            <FontAwesomeIcon
                                icon={icon}
                                className="text-commons"
                            />
                        </Badge>
                    </div>
                ) : (
                    <Badge badgeContent={notif} color="error" showZero={false}>
                        <FontAwesomeIcon icon={icon} />
                    </Badge>
                )}
            </div>
        </Link>
    );
}

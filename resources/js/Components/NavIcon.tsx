import { IconProp } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { Link } from "@inertiajs/react";
import { ReactElement, useEffect } from "react";

interface NavIconProps {
    path: string;
    active: boolean;
    icon: IconProp;
}

export default function NavIcon({
    path,
    active,
    icon,
}: NavIconProps): ReactElement {
    useEffect(() => {
        console.log(active);
    }, [active]);

    return (
        <Link href={path}>
            {active ? (
                <div className="bg-white w-10 h-10 rounded-full grid justify-items-center items-center">
                    <FontAwesomeIcon icon={icon} className="text-commons" />
                </div>
            ) : (
                <FontAwesomeIcon icon={icon} />
            )}
        </Link>
    );
}

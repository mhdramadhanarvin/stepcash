import { SVGAttributes } from "react";

export default function CoinIcon(props: SVGAttributes<SVGElement>) {
    return (
        <svg
            {...props}
            viewBox="0 0 24 24"
            height="30px"
            width="30px"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            className="mx-2"
        >
            <g id="SVGRepo_bgCarrier"></g>
            <g id="SVGRepo_tracerCarrier"></g>
            <g id="SVGRepo_iconCarrier">
                <ellipse
                    rx="8.5"
                    ry="9"
                    transform="matrix(-1 0 0 1 10.5 12)"
                    stroke="#4b5563"
                ></ellipse>
                <path
                    d="M13 8.8C12.3732 8.29767 11.5941 8 10.7498 8C8.67883 8 7 9.79086 7 12C7 14.2091 8.67883 16 10.7498 16C11.5941 16 12.3732 15.7023 13 15.2"
                    stroke="#4b5563"
                ></path>
                <path
                    d="M11 3C14.6667 3 22 3.9 22 12C22 20.1 14.6667 21 11 21"
                    stroke="#4b5563"
                ></path>
            </g>
        </svg>
    );
}

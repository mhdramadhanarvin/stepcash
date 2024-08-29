import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { PageProps, Rewards } from "@/types";
import { useApi } from "@/utils/useApi";
import { faClockRotateLeft } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { Head, Link } from "@inertiajs/react";
import AspectRatio from "@mui/joy/AspectRatio";
import Card from "@mui/joy/Card";
import CardContent from "@mui/joy/CardContent";
import CardOverflow from "@mui/joy/CardOverflow";
import Typography from "@mui/joy/Typography";
import { Box, Pagination } from "@mui/material";
import { ChangeEvent, useEffect, useState } from "react";

export default function List({ auth }: PageProps) {
    const [page, setPage] = useState<number>(1);
    const { data, pagination, refetch } = useApi({
        key: "rewards",
        route: route("rewards.get"),
        page,
    });

    const handleChange = (e: ChangeEvent<unknown>, p: number) => {
        setPage(p);
    };

    const limitCharacter = (character: string): string => {
        return character.length > 20
            ? character.slice(0, 20) + "..."
            : character;
    };

    useEffect(() => {
        refetch();
    }, [page, refetch]);

    const rewards: Rewards[] = data ?? [];
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="List Reward" />
            <div className="w-full">
                <div className="mb-5 grid grid-cols-7">
                    <h1 className="text-2xl col-span-6">Rewards</h1>
                    <Link
                        href={route("rewards.claims.index")}
                        className="grid justify-items-end content-center text-center"
                    >
                        <FontAwesomeIcon
                            icon={faClockRotateLeft}
                            className="text-xl"
                        />
                    </Link>
                </div>
                {rewards.length == 0 && (
                    <div className="flex justify-center py-5">
                        Belum Ada Data
                    </div>
                )}
                {rewards.map((data: Rewards, key: number) => (
                    <Link href={route("rewards.show", data.id)} key={key}>
                        <Card
                            orientation="horizontal"
                            variant="outlined"
                            sx={{ width: "100%", marginY: 0.8 }}
                        >
                            <CardOverflow>
                                <AspectRatio ratio="1" sx={{ width: 90 }}>
                                    <img
                                        src={data.thumbnail}
                                        srcSet={data.thumbnail}
                                        loading="lazy"
                                        alt=""
                                        className="object-cover"
                                    />
                                </AspectRatio>
                            </CardOverflow>
                            <CardContent>
                                <Typography
                                    fontWeight="md"
                                    textColor="success.plainColor"
                                >
                                    {limitCharacter(data.title)}
                                </Typography>
                                <Typography level="body-sm">
                                    {data.partner.name}
                                </Typography>
                            </CardContent>
                            <CardOverflow
                                variant="soft"
                                color="neutral"
                                sx={{
                                    px: 1,
                                    writingMode: "vertical-rl",
                                    justifyContent: "center",
                                    fontSize: "xs",
                                    fontWeight: "xl",
                                    letterSpacing: "1px",
                                    textTransform: "uppercase",
                                    borderLeft: "1px solid",
                                    borderColor: "divider",
                                }}
                            >
                                {data.price} Coin
                            </CardOverflow>
                        </Card>
                    </Link>
                ))}
                <Box
                    width="100%"
                    display="flex"
                    alignItems="center"
                    justifyContent="center"
                    pt={2}
                    pb={5}
                >
                    <Pagination
                        count={pagination.count}
                        color="primary"
                        size="medium"
                        onChange={handleChange}
                    />
                </Box>
            </div>
        </AuthenticatedLayout>
    );
}

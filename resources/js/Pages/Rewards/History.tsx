import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { PageProps, RewardClaims } from "@/types";
import { useApi } from "@/utils/useApi";
import { faChevronLeft } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { Head, Link } from "@inertiajs/react";
import AspectRatio from "@mui/joy/AspectRatio";
import Card from "@mui/joy/Card";
import CardContent from "@mui/joy/CardContent";
import CardOverflow from "@mui/joy/CardOverflow";
import Typography from "@mui/joy/Typography";
import { Box, Pagination } from "@mui/material";
import { ChangeEvent, useEffect, useState } from "react";

export default function History({ auth }: PageProps) {
    const [page, setPage] = useState<number>(1);
    const { data, pagination, refetch } = useApi({
        key: "rewards.claims",
        route: route("rewards.claims.all"),
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

    const rewardClaims: RewardClaims[] = data ?? [];
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="History Claim Reward" />
            <Link href={route("rewards.index")}>
                <button className="p-3">
                    <FontAwesomeIcon icon={faChevronLeft} />
                </button>
            </Link>
            <div className="w-full">
                <div className="mb-5">
                    <h1 className="text-2xl col-span-6">Riwayat Penukaran</h1>
                </div>
                {rewardClaims.length == 0 && (
                    <div className="flex justify-center py-5">
                        Belum Ada Data
                    </div>
                )}
                {rewardClaims.map((data: RewardClaims, key: number) => (
                    <Card
                        orientation="horizontal"
                        variant="outlined"
                        key={key}
                        sx={{ width: "100%", marginY: 0.8, padding: 1 }}
                    >
                        <CardOverflow>
                            <AspectRatio ratio="1" sx={{ width: 90 }}>
                                <img
                                    src={data.reward.thumbnail}
                                    srcSet={data.reward.thumbnail}
                                    loading="lazy"
                                    alt=""
                                    className="object-cover"
                                />
                            </AspectRatio>
                        </CardOverflow>
                        <CardContent
                            sx={{
                                padding: 0,
                            }}
                        >
                            <Typography fontWeight="md">
                                {limitCharacter(data.reward.title)}
                            </Typography>
                            <Typography level="body-xs">
                                {new Date(data.created_at).toLocaleString(
                                    "id-ID",
                                )}
                            </Typography>
                            <Typography level="body-sm">
                                <span className="inline-flex font-bold items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">
                                    Menunggu Konfirmasi
                                </span>
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

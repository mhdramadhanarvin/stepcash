import { Badge } from "@/Components/Badge";
import { ModalDetailHistoryClaim } from "@/Components/Modals/ModalDetailHistoryClaim";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { PageProps, RewardClaims } from "@/types";
import {
    formatedBalance,
    RewardClaimStatusLabel,
    RewardClaimStatusLabelColor,
} from "@/utils/manipulation";
import { useApi } from "@/utils/useApi";
import { faChevronLeft } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { Head, Link, router } from "@inertiajs/react";
import AspectRatio from "@mui/joy/AspectRatio";
import Card from "@mui/joy/Card";
import CardContent from "@mui/joy/CardContent";
import CardOverflow from "@mui/joy/CardOverflow";
import Typography from "@mui/joy/Typography";
import { Box, Pagination } from "@mui/material";
import { ChangeEvent, useEffect, useState } from "react";

export default function History({
    auth,
    detail,
}: PageProps<{ detail: RewardClaims | null }>) {
    const [page, setPage] = useState<number>(1);
    const [showDetail, setShowDetail] = useState<RewardClaims>();
    const { data, pagination, refetch } = useApi({
        key: "rewards.claims",
        route: route("rewards.claims.all"),
        page,
    });

    const handleChange = (e: ChangeEvent<unknown>, p: number) => {
        setPage(p);
    };

    const limitCharacter = (character: string, limit?: number): string => {
        const limitChar: number = limit ?? 20;
        return character.length > limitChar
            ? character.slice(0, limitChar) + "..."
            : character;
    };

    const handleCloseModal = () => {
        setShowDetail(undefined);
        if (route().params.id !== undefined)
            router.visit(route("rewards.claims.index"));
    };

    useEffect(() => {
        refetch();
        if (detail) {
            setShowDetail(detail);
        }
    }, [page, refetch]);

    const rewardClaims: RewardClaims[] = data ?? [];

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="History Claim Reward" />
            <ModalDetailHistoryClaim
                open={showDetail !== undefined}
                handleClose={handleCloseModal}
                data={showDetail}
            />
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
                        onClick={() => setShowDetail(data)}
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
                            <Badge
                                variant={
                                    RewardClaimStatusLabelColor[data.status]
                                }
                                label={RewardClaimStatusLabel[data.status]}
                            />
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
                            {formatedBalance(data.price)} Coin
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

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Notification, PageProps } from "@/types";
import { useApi } from "@/utils/useApi";
import { faBell } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { Head, Link } from "@inertiajs/react";
import AspectRatio from "@mui/joy/AspectRatio";
import Card from "@mui/joy/Card";
import CardContent from "@mui/joy/CardContent";
import CardOverflow from "@mui/joy/CardOverflow";
import Typography from "@mui/joy/Typography";
import { Box, Pagination } from "@mui/material";
import { grey } from "@mui/material/colors";
import axios from "axios";
import { ChangeEvent, useEffect, useState } from "react";
import { useMutation } from "react-query";

export default function List({ auth }: PageProps) {
    const [page, setPage] = useState<number>(1);
    const { data, pagination, refetch } = useApi({
        key: "notifications",
        route: route("notifications.get"),
        page,
    });

    const handleChange = (e: ChangeEvent<unknown>, p: number) => {
        setPage(p);
    };

    const { refetch: refetchUnread } = useApi({
        key: "notifications.unread",
        route: route("notifications.getUnread"),
    });

    const { mutate: setReadOne } = useMutation(async (id: number) => {
        return axios.post(route("notifications.setRead", id));
    });

    const { mutate: setReadAll } = useMutation(async () => {
        return axios.post(route("notifications.setReadAll"));
    });

    const handleSingleRead = (id: number) => {
        setReadOne(id, {
            onError: (error: any) => {
                if (axios.isAxiosError(error)) {
                    refetch();
                    refetchUnread();
                }
            },
            onSuccess() {
                refetch();
                refetchUnread();
            },
        });
    };

    const handleAllRead = () => {
        setReadAll(undefined, {
            onError: (error: any) => {
                if (axios.isAxiosError(error)) {
                    refetch();
                    refetchUnread();
                }
            },
            onSuccess() {
                refetch();
                refetchUnread();
            },
        });
    };

    useEffect(() => {
        refetch();
    }, [page, refetch]);

    const notifications: Notification[] = data ?? [];
    const primary = grey[200];
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Notification" />
            <div className="w-full">
                <div className="mb-5 grid grid-cols-7">
                    <h1 className="text-2xl col-span-5">Notification</h1>
                    <div className="grid justify-items-end content-center text-center col-span-2">
                        <Typography onClick={handleAllRead}>
                            Tandai Baca
                        </Typography>
                    </div>
                </div>
                {notifications.length == 0 && (
                    <div className="flex justify-center py-5">
                        Belum Ada Data
                    </div>
                )}
                {notifications.map((data: Notification, key: number) => (
                    <Link
                        href={
                            data.data.actions &&
                            data.data.actions.length > 0 &&
                            data.data.actions[0]?.url
                                ? data.data.actions[0].url
                                : "#javascript"
                        }
                        preserveState
                        key={key}
                        onClick={() => handleSingleRead(data.id)}
                    >
                        <Card
                            orientation="horizontal"
                            variant="outlined"
                            sx={{
                                width: "100%",
                                marginY: 0.8,
                                bgcolor: data.read_at != null ? "" : primary,
                            }}
                        >
                            <CardOverflow>
                                <AspectRatio
                                    ratio="1"
                                    sx={{
                                        width: 35,
                                        alignContent: "center",
                                        marginLeft: "1em",
                                        bgcolor:
                                            data.read_at != null ? "" : primary,
                                    }}
                                >
                                    <div className="rounded-full m-2 bg-green-500">
                                        <FontAwesomeIcon
                                            icon={faBell}
                                            className="text-white"
                                        />
                                    </div>
                                </AspectRatio>
                            </CardOverflow>
                            <CardContent>
                                <div className="grid grid-cols-7">
                                    <div className="col-span-5">
                                        <Typography
                                            level="title-sm"
                                            fontWeight="md"
                                            textColor="success.plainColor"
                                        >
                                            {data.data.title}{" "}
                                            {data.data.from_system != null
                                                ? "SISTEM"
                                                : ""}
                                        </Typography>
                                    </div>
                                    <Typography
                                        level="body-xs"
                                        sx={{ paddingLeft: 2 }}
                                    >
                                        {new Date(
                                            data.created_at,
                                        ).toLocaleDateString("id-ID")}
                                    </Typography>
                                </div>
                                <Typography level="body-xs">
                                    {data.data.body}
                                </Typography>
                            </CardContent>
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

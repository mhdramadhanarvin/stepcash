import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Notification, PageProps } from "@/types";
import { useApi } from "@/utils/useApi";
import { faBell } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
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
                }
            },
            onSuccess(data) {
                refetch();
            },
        });
    };

    const handleAllRead = () => {
        setReadAll(undefined, {
            onError: (error: any) => {
                if (axios.isAxiosError(error)) {
                    refetch();
                }
            },
            onSuccess(data) {
                refetch();
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
                    <Card
                        key={key}
                        orientation="horizontal"
                        variant="outlined"
                        onClick={() => handleSingleRead(data.id)}
                        sx={{
                            width: "100%",
                            marginY: 0.8,
                            bgcolor: data.is_read ? "" : primary,
                        }}
                    >
                        <CardOverflow>
                            <AspectRatio
                                ratio="1"
                                sx={{
                                    width: 35,
                                    alignContent: "center",
                                    marginLeft: "1em",
                                    bgcolor: data.is_read ? "" : primary,
                                }}
                            >
                                <div className="rounded-full bg-green-500 m-2">
                                    <FontAwesomeIcon
                                        icon={faBell}
                                        className="text-white"
                                    />
                                </div>
                            </AspectRatio>
                        </CardOverflow>
                        <CardContent>
                            <Typography
                                fontWeight="md"
                                textColor="success.plainColor"
                            >
                                {data.title}
                            </Typography>
                            <Typography level="body-sm">
                                {data.message}
                            </Typography>
                        </CardContent>
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

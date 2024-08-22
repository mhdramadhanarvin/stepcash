import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { PageProps } from "@/types";
import { faClockRotateLeft } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import AspectRatio from "@mui/joy/AspectRatio";
import Card from "@mui/joy/Card";
import CardContent from "@mui/joy/CardContent";
import CardOverflow from "@mui/joy/CardOverflow";
import Typography from "@mui/joy/Typography";
import { Pagination } from "@mui/material";
import { useState } from "react";

export default function Rewards({ auth }: PageProps) {
    const [product, _] = useState<number[]>([1, 2, 3, 4, 5]);
    return (
        <AuthenticatedLayout user={auth.user}>
            <div className="">
                <div className="mb-5 grid grid-cols-7">
                    <h1 className="text-2xl col-span-6">Rewards</h1>
                    <span className="grid justify-items-end content-center text-center">
                        <FontAwesomeIcon
                            icon={faClockRotateLeft}
                            className="text-xl"
                        />
                    </span>
                </div>
                {product.map((data: number) => (
                    <Card
                        orientation="horizontal"
                        variant="outlined"
                        sx={{ width: "100%", marginY: 1 }}
                    >
                        <CardOverflow>
                            <AspectRatio ratio="1" sx={{ width: 90 }}>
                                <img
                                    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQtJek27VdEiJ1q-Y2Lo_Hl1tqTJO_WHu-alA&s"
                                    srcSet="https://images.unsplash.com/photo-1507833423370-a126b89d394b?auto=format&fit=crop&w=90&dpr=2 2x"
                                    loading="lazy"
                                    alt=""
                                />
                            </AspectRatio>
                        </CardOverflow>
                        <CardContent>
                            <Typography
                                fontWeight="md"
                                textColor="success.plainColor"
                            >
                                Reward <span>{data}</span>
                            </Typography>
                            <Typography level="body-sm">
                                California, USA
                            </Typography>
                        </CardContent>
                        <CardOverflow
                            variant="soft"
                            color="primary"
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
                            10 Coin
                        </CardOverflow>
                    </Card>
                ))}
                <div className="my-5">
                    <Pagination count={10} color="primary" />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

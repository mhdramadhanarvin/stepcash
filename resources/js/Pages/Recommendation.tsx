import { PageProps, RecommendationType } from "@/types";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";

export default function Recommendation({
    auth,
    recommendation,
}: PageProps & { recommendation: RecommendationType[] }) {
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="List Reward" />
            <div className="w-full">
                <div className="mb-5 grid grid-cols-7">
                    <h1 className="text-2xl col-span-6">Recommendation</h1>
                </div>
            </div>
            <div className="mb-2">
                Untuk tetap meningkatkan kebugaran anda, kami memiliki beberapa
                rekomendasi.
            </div>
            {recommendation.map((data: RecommendationType, key: number) => (
                <div
                    className="max-w-sm rounded overflow-hidden shadow-lg bg-grey-200 mb-3"
                    key={key}
                >
                    <img
                        className="w-full h-40"
                        src={data.thumbnail}
                        alt="Sunset in the mountains"
                    />
                    <div className="px-6 py-4">
                        <div className="font-bold text-xl mb-2">
                            {data.name}
                        </div>
                        <p className="text-gray-700 text-base">
                            {data.description}
                        </p>
                    </div>
                </div>
            ))}
        </AuthenticatedLayout>
    );
}

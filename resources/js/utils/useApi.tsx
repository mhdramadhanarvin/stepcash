import { useMutation, useQuery } from "react-query";
import axios from "axios";

interface UseApiProps {
    key: string;
    route: string;
    page?: number;
}

interface UseApiMutateProps {
    route: string;
    payload: object;
}

export const useApi = ({ key, route, page = 1 }: UseApiProps) => {
    const query = useQuery(
        [key, page],
        async () => {
            const { data } = await axios.get(`${route}?page=${page}`);
            return data;
        },
        { keepPreviousData: true },
    );

    return {
        ...query,
        data: query.data?.data || [],
        pagination: {
            page: query.data?.current_page,
            rows: query.data?.total,
            per_page: query.data?.per_page,
            count: query.data?.last_page,
        },
    };
};

export const useApiMutate = ({ route, payload }: UseApiMutateProps) => {
    return useMutation(async () => {
        const { data } = await axios.post(route, payload);
        return data;
    });
};

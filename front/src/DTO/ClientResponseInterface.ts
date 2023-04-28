export interface UseGetOneResponseInterface {
    data: object | undefined | null;
    loading?: boolean;
    error: ClientError | any;
}

export interface ClientError {
    message: string;
}

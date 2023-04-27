export interface UseGetOneResponseInterface {
    data: string[],
    loading: boolean,
    error?: ClientError,
}

export interface ClientError {
    message: string,
}

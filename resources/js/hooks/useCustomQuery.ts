import { useQuery, UseQueryOptions } from "@tanstack/react-query";
import qs from "qs";

import { httpClient } from "../httpClient/httpClient";

const DEFAULT_QUERY_RETRY = false;
const DEFAULT_QUERY_RETRY_DELAY = 3000;
const DEFAULT_QUERY_STALE_TIME_MS = 5000;
export const REFETCH_INTERVAL_MS = 1000 * 60 * 10; // 10 min

export type TParams = Record<string | number, unknown>;

type GetQueryKeyUrl = string;
type GetQueryKeyParams = TParams | string;
type GetQueryKeyOptions = qs.IStringifyOptions;

export interface IProps<TData = any, TQueryKey = any> {
  url?: string;
  params?: TParams | string;
  queryParams?: Partial<UseQueryOptions<TData, TQueryKey>>;
  refetchOnMount?: boolean;
}

const getQueryKey = (
  url?: GetQueryKeyUrl,
  params?: GetQueryKeyParams,
  options?: GetQueryKeyOptions
): string[] => {
  const result = [];

  if (url) {
    result.push(url);
  }

  if (typeof params === "object") {
    result.push(stringifyQS(params, options));
  } else if (typeof params === "string" && params.length > 0) {
    result.push(params);
  }

  return result;
};

const stringifyOptions: qs.IStringifyOptions = {
  arrayFormat: "brackets",
  encodeValuesOnly: true,
  skipNulls: true,
};

const stringifyQS = (
  object = {},
  options: qs.IStringifyOptions = {}
): string => {
  return qs.stringify(object, {
    ...stringifyOptions,
    ...options,
  });
};

export const useCustomQuery = <TData = any, TQueryKey = any>({
  url,
  params,
  queryParams,
  refetchOnMount = false,
}: IProps<TData, TQueryKey>) => {
  return useQuery<TData, TQueryKey>({
    queryKey: getQueryKey(url, params),
    queryFn: ({ queryKey }) => {
      return httpClient.get(queryKey.join("?")).then((response) => {
        return response.data;
      });
    },
    refetchOnWindowFocus: false,
    refetchOnReconnect: false,
    retryOnMount: false,
    refetchOnMount,
    retry: queryParams?.retry || DEFAULT_QUERY_RETRY,
    retryDelay: queryParams?.retryDelay || DEFAULT_QUERY_RETRY_DELAY,
    staleTime: queryParams?.staleTime || DEFAULT_QUERY_STALE_TIME_MS,
    ...queryParams,
  });
};

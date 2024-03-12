import dayjs from "dayjs";
import { ICity, IWarshipImprovement, TImprovementType } from "./types/types";

export const convertSecondsToTime = (seconds: number): string => {
  if (seconds < 0) {
    return "00:00";
  }

  const minutes: number = Math.floor(seconds / 60);
  const remainingSeconds: number = seconds % 60;
  const paddedMinutes: string = minutes.toString().padStart(2, "0");
  const paddedSeconds: string = remainingSeconds.toString().padStart(2, "0");
  return `${paddedMinutes}:${paddedSeconds}`;
};

// return time difference between two dates in seconds
export const getTimeLeft = (strDeadline: string) => {
  const dateUTCNow = dayjs().utc(false);
  let deadline = dayjs(new Date(strDeadline || "")).utc(true);

  return deadline.unix() - dateUTCNow.unix();
};

export const getResourceSlug = <T extends { id: N; slug?: string }, N>(
  resourcesDictionary: T[],
  resourceId: N
): string => {
  return (
    resourcesDictionary?.find((resource) => resource.id === resourceId)?.slug ||
    ""
  );
};

export const getCityResourceProductionCoefficient = (
  city: ICity,
  resourceId: number
): number => {
  return (
    city?.resourcesProductionCoefficient?.find(
      (production) => production.resourceId === resourceId
    )?.coefficient || 1
  );
};

export const getWarshipImprovementPercent = (
  warshipImprovements: IWarshipImprovement[],
  warshipId: number,
  improvementType: TImprovementType
) => {
  return (
    warshipImprovements?.find(
      (improvement) =>
        improvement.improvementType === improvementType &&
        improvement.warshipId === warshipId
    )?.percentImprovement || 0
  );
};

import dayjs from "dayjs";

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

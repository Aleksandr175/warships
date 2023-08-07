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

export const getTimeLeft = (strDeadline: string): number => {
  const dateUTCNow = dayjs.utc(new Date());
  const deadline = dayjs(new Date(strDeadline || ""));

  const deadlineString = deadline.format().toString().replace("T", " ");
  const dateArray = deadlineString.split("+");
  const deadlineDate = dateArray[0];

  return dayjs.utc(deadlineDate).unix() - dateUTCNow.unix();
};

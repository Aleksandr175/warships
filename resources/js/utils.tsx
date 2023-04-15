export const convertSecondsToTime = (seconds: number): string => {
    const minutes: number = Math.floor(seconds / 60);
    const remainingSeconds: number = seconds % 60;
    const paddedMinutes: string = minutes.toString().padStart(2, "0");
    const paddedSeconds: string = remainingSeconds.toString().padStart(2, "0");
    return `${paddedMinutes}:${paddedSeconds}`;
};

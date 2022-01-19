export interface ICity {
    id: number;
    title: string;
    coordX: number;
    coordY: number;
    gold: number;
    population: number;
}

export interface ICityBuilding {
    id: number;
    cityId: number;
    lvl: number;
}

export interface IDictionary {
    buildings: IBuilding[];
}

export interface IBuilding {
    id: number;
    title: string;
    description: string;
}

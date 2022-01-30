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

export interface ICityBuildingQueue {
    id: number;
    cityId: number;
    lvl: number;
    gold: number;
    population: number;
    time: number;
    deadline: string;
}

export interface IBuildingResource {
    buildingId: number;
    gold: number;
    population: number;
    lvl: number;
}

export interface IDictionary {
    buildings: IBuilding[];
    buildingResources: IBuildingResource[];
}

export interface IBuilding {
    id: number;
    title: string;
    description: string;
}

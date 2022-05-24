export interface ICity {
    id: number;
    title: string;
    coordX: number;
    coordY: number;
    gold: number;
    population: number;
}

export interface ICityResources {
    id?: number;
    gold: number;
    population: number;
    productionGold?: number;
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

export interface ICityResearchQueue {
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

export interface IResearchResource {
    researchId: number;
    gold: number;
    population: number;
    lvl: number;
}

export interface IDictionary {
    buildings: IBuilding[];
    buildingResources: IBuildingResource[];
    buildingsProduction: IBuildingsProduction[];
    researches: IResearch[];
    userResearches: IUserResearch[];
    researchResources: IResearchResource[];
}

export interface IBuilding {
    id: number;
    title: string;
    description: string;
}

export interface IResearch {
    id: number;
    title: string;
    description: string;
}

export interface IUserResearch {
    id: number;
    lvl: number;
    researchId: number;
}

export interface IBuildingsProduction {
    buildingId: number;
    lvl: number;
    qty: number;
    resource: string;
}

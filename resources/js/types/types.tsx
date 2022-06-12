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
    buildingId: number;
    cityId: number;
    lvl: number;
}

export interface ICityWarship {
    warshipId: number;
    cityId: number;
    qty: number;
}

export interface ICityBuildingQueue {
    buildingId: number;
    cityId: number;
    lvl: number;
    gold: number;
    population: number;
    time: number;
    deadline: string;
}

export interface ICityResearchQueue extends ICityBuildingQueue {
    researchId: number;
}

export interface ICityWarshipQueue {
    warshipId: number;
    cityId: number;
    qty: number;
    time: number;
    deadline: string;
}

export interface IBuildingResource {
    buildingId: number;
    gold: number;
    population: number;
    lvl: number;
}

export interface IResearchResource extends IBuildingResource {
    researchId: number;
}

export interface IWarshipResource extends IBuildingResource {
    warshipId: number;
    time: number;
}

export interface IDictionary {
    buildings: IBuilding[];
    buildingResources: IBuildingResource[];
    buildingsProduction: IBuildingsProduction[];
    researches: IResearch[];
    userResearches: IUserResearch[];
    researchResources: IResearchResource[];
    warships: IWarship[];
    warshipsResources: IWarshipResource[];
}

export interface IBuilding {
    id: number;
    title: string;
    description: string;
}

export interface IResearch extends IBuilding {}

export interface IWarship extends IBuilding {
    attack: number;
    speed: number;
    capacity: number;
    gold: number;
    population: number;
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

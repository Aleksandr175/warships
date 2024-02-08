export interface ICity {
  id: number;
  title: string;
  gold: number;
  population: number;
  cityTypeId: number;
  cityAppearanceId: number;
  archipelagoId: number;
  coordX: number;
  coordY: number;
  resources: ICityResource[];
  resourcesProductionCoefficient: ICityProductionCoefficient[];
}

export interface ICityShort {
  id: number;
  title: string;
  archipelagoId: number;
  coordX: number;
  coordY: number;
}

export interface ICityProductionCoefficient {
  cityId: number;
  resourceId: number;
  coefficient: number;
}

export interface IMapCity {
  id: number;
  title: string;
  cityTypeId: number;
  cityAppearanceId: number;
  archipelagoId: number;
  coordX: number;
  coordY: number;
  raided?: boolean;
  resources: ICityResource[];
}

export interface IResourceDictionary {
  id: number;
  title: string;
  description: string;
  slug: string;
}

export interface ICityResources {
  cityResources?: ICityResource[];
  resourcesDictionary?: IResourceDictionary[];
  productions: IProductions;
}

export interface ICityResource {
  cityId: number;
  resourceId: number;
  qty: number;
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
  resourceId: number;
  qty: number;
  lvl: number;
  timeRequired: number;
}

export interface IResearchResource extends IBuildingResource {
  researchId: number;
}

export interface IWarshipResource extends IBuildingResource {
  warshipId: number;
  time: number;
}

export interface IFleetTasksDictionary {
  id: number;
  slug: string;
  title: string;
  description: string;
}

export interface IFleetStatusesDictionary {
  id: number;
  title: string;
  description: string;
}

export interface IDictionary {
  buildings: IBuilding[];
  buildingResources: IBuildingResource[];
  buildingDependencies: IBuildingDependency[];
  buildingsProduction: IBuildingsProduction[];
  researches: IResearch[];
  userResearches: IUserResearch[];
  researchResources: IResearchResource[];
  researchDependencies: IResearchDependency[];
  warshipsDictionary: IWarship[];
  warshipsResources: IWarshipResource[];
  warshipDependencies: IWarshipDependency[];
  fleetTasksDictionary: IFleetTasksDictionary[];
  fleetStatusesDictionary: IFleetStatusesDictionary[];
  resourcesDictionary: IResourceDictionary[];
}

export interface IBuilding {
  id: number;
  title: string;
  description: string;
}

export type TRequiredEntity = "building" | "research";

export interface IBuildingDependency {
  buildingId: number;
  buildingLvl: number;
  requiredEntity: TRequiredEntity;
  requiredEntityId: number;
  requiredEntityLvl: number;
}

export interface IResearchDependency {
  researchId: number;
  researchLvl: number;
  requiredEntity: TRequiredEntity;
  requiredEntityId: number;
  requiredEntityLvl: number;
}

export interface IWarshipDependency {
  warshipId: number;
  requiredEntity: TRequiredEntity;
  requiredEntityId: number;
  requiredEntityLvl: number;
}

export interface IResearch extends IBuilding {}

export interface IWarshipRequiredResource {
  warshipId: number;
  resourceId: number;
  qty: number;
}

export interface IWarship extends IBuilding {
  attack: number;
  speed: number;
  capacity: number;
  health: number;
  time: number;
  gold: number;
  population: number;
  requiredResources: IWarshipRequiredResource[];
}

export interface IUserResearch {
  id: number;
  lvl: number;
  researchId: number;
}

export interface IBuildingsProduction {
  id: number;
  buildingId: number;
  resourceId: number;
  lvl: number;
  qty: number;
}

export interface IProductions {
  [resourceSlug: string]: IBuildingsProduction;
}

export type TTask = "attack" | "move" | "trade" | "transport" | "expedition";
// TODO: refactor naming
export type TType = "map" | "adventure";

export interface IFleetWarshipsData {
  fleetId?: number;
  warshipId: number;
  qty: number;
}

export interface IMapFleetWarshipsData {
  cityId: number;
  warshipId: number;
  qty: number;
}

export interface IFleet {
  cityId: number;
  coordX: number;
  coordY: number;
  repeating?: 1 | 0;
  taskType: TTask;
}

export interface ICityFleet {
  id: number;
  cityId: number;
  targetCityId: number;
  fleetDetails: IFleetWarshipsData[];
  repeating?: 1 | 0;
  /*taskType: TTask;*/
  fleetTaskId: number;
  fleetStatusId: number;
  speed: number;
  gold: number;
  time: number;
  deadline: string;
}

export type IFleetIncoming = Pick<
  ICityFleet,
  | "id"
  | "cityId"
  | "targetCityId"
  | "fleetTaskId"
  | "fleetStatusId"
  | "deadline"
  | "repeating"
>;

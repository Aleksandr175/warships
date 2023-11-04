import {
  IBuilding,
  IBuildingDependency,
  ICityBuilding,
  IResearch,
  IUserResearch,
  IWarshipDependency,
} from "../../types/types";
import { IResearchDependency } from "../../types/types";

interface IProps {
  dependencyDictionary:
    | IBuildingDependency[]
    | IResearchDependency[]
    | IWarshipDependency[];
  buildings?: ICityBuilding[] | undefined;
  researches?: IUserResearch[];
  buildingsDictionary?: IBuilding[];
  researchesDictionary?: IResearch[];
}

type TItemType = "building" | "research" | "warship";

export const useRequirementsLogic = ({
  dependencyDictionary,
  buildingsDictionary,
  researchesDictionary,
  buildings,
  researches,
}: IProps) => {
  const hasRequirements = (
    type: TItemType,
    itemId: number,
    itemLvl?: number
  ): boolean | undefined => {
    if (type === "building") {
      return dependencyDictionary.some(
        (dependency) =>
          // @ts-ignore
          dependency.buildingId === itemId && dependency.buildingLvl === itemLvl
      );
    }
    if (type === "research") {
      return dependencyDictionary.some(
        (dependency) =>
          // @ts-ignore
          dependency.researchId === itemId && dependency.researchLvl === itemLvl
      );
    }
    if (type === "warship") {
      return dependencyDictionary.some(
        (dependency) =>
          // @ts-ignore
          dependency.warshipId === itemId
      );
    }
  };

  const getRequirements = (
    type: TItemType,
    itemId: number,
    itemLvl?: number
  ): IBuildingDependency[] | IResearchDependency[] | undefined => {
    if (type === "building") {
      return (
        // TODO: fix types
        // @ts-ignore
        dependencyDictionary.filter(
          (dependency: { buildingId: number; buildingLvl: number }) =>
            dependency.buildingId === itemId &&
            dependency.buildingLvl === itemLvl
        ) || ([] as IBuildingDependency[])
      );
    }
    if (type === "research") {
      return (
        // TODO: fix types
        // @ts-ignore
        dependencyDictionary.filter(
          (dependency: { researchId: number; researchLvl: number }) =>
            dependency.researchId === itemId &&
            dependency.researchLvl === itemLvl
        ) || ([] as IResearchDependency[])
      );
    }
    if (type === "warship") {
      return (
        // TODO: fix types
        // @ts-ignore
        dependencyDictionary.filter(
          (dependency: { warshipId: number }) => dependency.warshipId === itemId
        ) || ([] as IWarshipDependency[])
      );
    }
  };

  const getRequiredItem = (
    dependency: IBuildingDependency | IResearchDependency
  ): IBuilding | IResearch | undefined => {
    console.log(dependency, buildingsDictionary);
    if (dependency.requiredEntity === "building") {
      return buildingsDictionary?.find(
        (item) => item.id === dependency.requiredEntityId
      );
    }
    if (dependency.requiredEntity === "research") {
      return researchesDictionary?.find(
        (item) => item.id === dependency.requiredEntityId
      );
    }
  };

  const hasRequirement = (
    dependency: IBuildingDependency | IResearchDependency
  ) => {
    if (dependency.requiredEntity === "building") {
      return buildings?.some(
        (item) =>
          item.buildingId === dependency.requiredEntityId &&
          item.lvl >= dependency.requiredEntityLvl
      );
    }
    if (dependency.requiredEntity === "research") {
      return researches?.some(
        (item) =>
          item.researchId === dependency.requiredEntityId &&
          item.lvl >= dependency.requiredEntityLvl
      );
    }
  };

  const hasAllRequirements = (
    type: TItemType,
    itemId: number,
    lvl?: number
  ) => {
    const requirements = getRequirements(type, itemId, lvl);
    let hasAllRequirements = true;

    requirements?.forEach((req) => {
      if (!hasRequirement(req)) {
        hasAllRequirements = false;
      }
    });

    return hasAllRequirements;
  };

  return {
    getRequiredItem,
    getRequirements,
    hasRequirements,
    hasAllRequirements,
  };
};

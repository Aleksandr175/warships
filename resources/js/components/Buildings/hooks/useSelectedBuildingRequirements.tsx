import {
  IBuilding,
  IBuildingDependency,
  ICityBuilding,
  IResearch,
  IUserResearch,
} from "../../../types/types";

interface IProps {
  buildingsDictionary: IBuilding[];
  buildingDependencyDictionary: IBuildingDependency[];
  buildings: ICityBuilding[] | undefined;
  researchDictionary: IResearch[];
  researches: IUserResearch[];
}
export const useSelectedBuildingRequirements = ({
  buildingDependencyDictionary,
  buildingsDictionary,
  researchDictionary,
  buildings,
  researches,
}: IProps) => {
  const hasRequirements = (
    buildingId: number,
    buildingLvl: number
  ): boolean => {
    return buildingDependencyDictionary.some(
      (dependency) =>
        dependency.buildingId === buildingId &&
        dependency.buildingLvl === buildingLvl
    );
  };

  const getRequirements = (
    buildingId: number,
    buildingLvl: number
  ): IBuildingDependency[] => {
    return (
      buildingDependencyDictionary.filter(
        (dependency) =>
          dependency.buildingId === buildingId &&
          dependency.buildingLvl === buildingLvl
      ) || ([] as IBuildingDependency[])
    );
  };

  const getRequiredItem = (
    dependency: IBuildingDependency
  ): IBuilding | IResearch | undefined => {
    if (dependency.requiredEntity === "building") {
      return buildingsDictionary.find(
        (item) => item.id === dependency.requiredEntityId
      );
    }
    if (dependency.requiredEntity === "research") {
      return researchDictionary.find(
        (item) => item.id === dependency.requiredEntityId
      );
    }
  };

  const hasRequirement = (dependency: IBuildingDependency) => {
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

  const hasAllRequirements = (buildingId: number, lvl: number) => {
    const requirements = getRequirements(buildingId, lvl);
    let hasAllRequirements = true;

    requirements.forEach((req) => {
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

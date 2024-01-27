import {
  IBuilding,
  IBuildingDependency,
  ICityBuilding,
  IResearch,
  IUserResearch,
  IWarshipDependency,
} from "../../types/types";
import { IResearchDependency } from "../../types/types";
import { useFetchDictionaries } from "../../hooks/useFetchDictionaries";

interface IProps {
  buildings?: ICityBuilding[] | undefined;
  researches?: IUserResearch[];
}

type TItemType = "building" | "research" | "warship";

export const useRequirementsLogic = ({ buildings, researches }: IProps) => {
  const queryDictionaries = useFetchDictionaries();

  const dictionaries = queryDictionaries.data;

  const hasRequirements = (
    type: TItemType,
    itemId: number,
    itemLvl?: number
  ): boolean | undefined => {
    if (type === "building") {
      return dictionaries?.buildingDependencies.some(
        (dependency) =>
          // @ts-ignore
          dependency.buildingId === itemId && dependency.buildingLvl === itemLvl
      );
    }
    if (type === "research") {
      return dictionaries?.researchDependencies.some(
        (dependency) =>
          // @ts-ignore
          dependency.researchId === itemId && dependency.researchLvl === itemLvl
      );
    }
    if (type === "warship") {
      return dictionaries?.warshipDependencies.some(
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
  ):
    | IBuildingDependency[]
    | IResearchDependency[]
    | IWarshipDependency[]
    | undefined => {
    if (type === "building") {
      return (
        // TODO: fix types
        // @ts-ignore
        dictionaries?.buildingDependencies.filter(
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
        dictionaries?.researchDependencies.filter(
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
        dictionaries?.warshipDependencies.filter(
          (dependency: { warshipId: number }) => dependency.warshipId === itemId
        ) || ([] as IWarshipDependency[])
      );
    }
  };

  const getRequiredItem = (
    dependency: IBuildingDependency | IResearchDependency | IWarshipDependency
  ): IBuilding | IResearch | undefined => {
    if (dependency.requiredEntity === "building") {
      return dictionaries?.buildings?.find(
        (item) => item.id === dependency.requiredEntityId
      );
    }
    if (dependency.requiredEntity === "research") {
      return dictionaries?.researches?.find(
        (item) => item.id === dependency.requiredEntityId
      );
    }
  };

  const hasRequirement = (
    dependency: IBuildingDependency | IResearchDependency | IWarshipDependency
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

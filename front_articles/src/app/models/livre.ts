import { Categorie } from "./categorie";

export class Livre {
    constructor(
        public titre: string,
        public dateSortie: Date,
        public langue: string,
        public photoCouverture: string,
        public categorieId: number,
        public auteurIds: number[],
    ) {}
}
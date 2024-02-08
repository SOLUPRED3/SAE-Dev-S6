import { Categorie } from "./categorie";

export class Livre {
    constructor(
        public id: number,
        public titre: string,
        public dateSortie: Date,
        public langue: string,
        public photoCouverture: string,
        public auteurs: string,
        public categorie: Categorie
    ) {}
}